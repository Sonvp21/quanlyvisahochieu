<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\DelegationExportController;
use App\Http\Controllers\Admin\DelegationWordExportController;
use App\Models\Delegation;
use App\Models\MailLog;
use App\Models\Partner;
use App\Models\Person;
use App\Models\Trip;
use App\Models\TripFile;
use App\Models\Unit;
use App\Services\TripDocuments;
use App\Services\TripMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TripController extends Controller
{
    // ------------------------------------------------------------------ Danh sách
    public function index(Request $request)
    {
        $user = $request->user();
        $base = Trip::visibleTo($user);

        $query = (clone $base)->with(['unit', 'creator', 'members']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'ilike', "%{$s}%")
                    ->orWhere('code', 'ilike', "%{$s}%")
                    ->orWhere('country', 'ilike', "%{$s}%")
                    ->orWhere('host_org', 'ilike', "%{$s}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('trip_type', $request->type);
        }
        if (in_array($request->direction, ['in', 'out'], true)) {
            $query->where('direction', $request->direction);
        }
        if ($request->filled('year')) {
            $query->whereYear('depart_at', (int) $request->year);
        }
        if ($request->filter === 'to_approve') {
            $query->awaiting($user);
        }
        if ($request->filter === 'mine') {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('members', fn ($m) => $m->where('people.user_id', $user->id));
            });
        }

        $trips = $query->orderByDesc('depart_at')->paginate(15)->withQueryString();

        $stats = [
            'to_approve' => (clone $base)->awaiting($user)->count(),
            'pending'    => (clone $base)->whereIn('status', ['pending_unit', 'pending_htqt', 'pending_bgh'])->count(),
            'approved'   => (clone $base)->whereIn('status', ['approved', 'completed'])->whereYear('depart_at', now()->year)->count(),
            'need_report' => (clone $base)->where('status', 'approved')->whereDate('return_at', '<', now())->count(),
        ];

        $years = (clone $base)->selectRaw('extract(year from depart_at)::int as y')->distinct()->orderByDesc('y')->pluck('y');

        return view('trips.index', compact('trips', 'stats', 'years'));
    }

    // ------------------------------------------------------------------ Tạo / sửa
    public function create(Request $request)
    {
        $user = $request->user();
        $direction = $request->direction === 'in' ? 'in' : 'out';
        $trip = new Trip([
            'direction' => $direction,
            'trip_type' => $direction === 'in' ? 'giao_su' : 'cong_tac',
            'unit_id'   => $user->unit_id,
        ]);
        $selected = [];

        // Đoàn ra: cán bộ tự làm hồ sơ cho mình thì chọn sẵn chính họ
        if ($direction === 'out' && $user->person) {
            $selected[] = ['person_id' => $user->person->id, 'role' => 'Trưởng đoàn'];
        }

        return view('trips.create', $this->formData($trip, $selected));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $trip = DB::transaction(function () use ($request, $data) {
            $trip = Trip::create($data + [
                'code'       => Trip::nextCode($data['direction']),
                'status'     => 'draft',
                'created_by' => $request->user()->id,
            ]);
            $this->syncMembers($trip, $request->input('members', []));

            return $trip;
        });

        if ($request->input('action') === 'submit') {
            return $this->doSubmit($request, $trip);
        }

        return redirect()->route('trips.show', $trip)->with('success', 'Đã lưu dự thảo hồ sơ.');
    }

    public function edit(Request $request, Trip $trip)
    {
        $this->authorizeEdit($request, $trip);

        $selected = $trip->members->map(fn ($p) => ['person_id' => $p->id, 'role' => $p->pivot->role_in_trip])->all();

        return view('trips.edit', $this->formData($trip, $selected));
    }

    public function update(Request $request, Trip $trip)
    {
        $this->authorizeEdit($request, $trip);

        DB::transaction(function () use ($request, $trip) {
            $trip->update($this->validated($request));
            $this->syncMembers($trip, $request->input('members', []));
        });

        if ($request->input('action') === 'submit') {
            return $this->doSubmit($request, $trip->refresh());
        }

        return redirect()->route('trips.show', $trip)->with('success', 'Đã cập nhật hồ sơ.');
    }

    public function destroy(Request $request, Trip $trip)
    {
        $u = $request->user();
        abort_unless($trip->status === 'draft' && ($u->role === 'admin' || $u->id === $trip->created_by), 403);

        foreach ($trip->files as $f) {
            Storage::delete($f->path);
        }
        $trip->delete();

        return redirect()->route('trips.index')->with('success', 'Đã xóa dự thảo.');
    }

    // ------------------------------------------------------------------ Chi tiết
    public function show(Request $request, Trip $trip)
    {
        $this->authorizeView($request, $trip);
        $trip->load(['unit', 'creator', 'members.unit', 'approvals.approver', 'report.submitter', 'files.uploader']);
        $user = $request->user();

        return view('trips.show', [
            'trip'       => $trip,
            'canEdit'    => $trip->canEditBy($user),
            'canAct'     => $trip->canActBy($user),
            'canReport'  => in_array($trip->status, ['approved', 'completed'], true) && $trip->isMemberOrOwner($user),
            'canFiles'   => $user->role === 'admin' || $user->role === 'htqt' || $user->id === $trip->created_by,
            'flow'       => $trip->flowSteps(),
            'docs'       => TripDocuments::available($trip),
            'expenses'   => $trip->expenses()->get(),
            'mailLogs'   => in_array($user->role, ['admin', 'htqt'], true) ? MailLog::where('trip_id', $trip->id)->latest('id')->limit(8)->get() : collect(),
            'canExport'  => $trip->isIncoming() && ($user->role === 'admin' || $user->role === 'htqt'
                || ($user->id === $trip->created_by && in_array($trip->status, ['approved', 'completed'], true))),
        ]);
    }

    // ------------------------------------------------------------------ Gửi duyệt / duyệt
    public function submit(Request $request, Trip $trip)
    {
        $this->authorizeEdit($request, $trip);

        return $this->doSubmit($request, $trip);
    }

    private function doSubmit(Request $request, Trip $trip)
    {
        if ($trip->members()->count() === 0) {
            return redirect()->route('trips.edit', $trip)->with('error', 'Cần chọn ít nhất một thành viên đi trước khi gửi duyệt.');
        }

        $flow = Trip::stepsFor($trip->creator->role);

        DB::transaction(function () use ($request, $trip, $flow) {
            $trip->approvals()->create([
                'step' => null, 'approver_id' => $request->user()->id, 'action' => 'submitted',
                'comment' => $trip->status === 'returned' ? 'Gửi lại sau khi bổ sung' : null,
            ]);

            if (empty($flow)) {
                $trip->update(['status' => 'approved', 'submitted_at' => now(), 'decided_at' => now(), 'report_due_at' => $this->dueDate($trip)]);
            } else {
                $trip->update(['status' => 'pending_' . $flow[0], 'submitted_at' => now()]);
            }
        });

        app(TripMailer::class)->fire($trip->fresh()->status === 'approved' ? 'approved' : 'step_pending', $trip->fresh());

        return redirect()->route('trips.show', $trip)->with('success', 'Đã gửi hồ sơ đi phê duyệt.');
    }

    public function decide(Request $request, Trip $trip)
    {
        abort_unless($trip->canActBy($request->user()), 403);

        $data = $request->validate([
            'action'  => ['required', Rule::in(['approve', 'return', 'reject'])],
            'comment' => 'nullable|string|max:2000',
        ]);

        if ($data['action'] !== 'approve' && blank($data['comment'] ?? null)) {
            return back()->withErrors(['comment' => 'Vui lòng nhập lý do khi trả lại hoặc không duyệt.'])->withInput();
        }

        $step = $trip->pendingStep();

        DB::transaction(function () use ($request, $trip, $data, $step) {
            $trip->approvals()->create([
                'step'        => $step,
                'approver_id' => $request->user()->id,
                'action'      => match ($data['action']) {'approve' => 'approved', 'return' => 'returned', default => 'rejected'},
                'comment'     => $data['comment'] ?? null,
            ]);

            if ($data['action'] === 'approve') {
                $next = $trip->nextStepAfter($step);
                if ($next) {
                    $trip->update(['status' => 'pending_' . $next]);
                } else {
                    $trip->update(['status' => 'approved', 'decided_at' => now(), 'report_due_at' => $this->dueDate($trip)]);
                }
            } elseif ($data['action'] === 'return') {
                $trip->update(['status' => 'returned']);
            } else {
                $trip->update(['status' => 'rejected', 'decided_at' => now()]);
            }
        });

        $fresh = $trip->fresh();
        app(TripMailer::class)->fire(match ($fresh->status) {
            'approved' => 'approved', 'returned' => 'returned', 'rejected' => 'rejected', default => 'step_pending',
        }, $fresh);

        return redirect()->route('trips.show', $trip)->with('success', 'Đã ghi nhận ý kiến của bạn.');
    }

    // ------------------------------------------------------------------ Báo cáo sau chuyến
    public function reportForm(Request $request, Trip $trip)
    {
        $this->authorizeReport($request, $trip);
        $trip->load('report');

        return view('trips.report', compact('trip'));
    }

    public function reportStore(Request $request, Trip $trip)
    {
        $this->authorizeReport($request, $trip);

        $data = $request->validate([
            'summary'         => 'required|string|max:5000',
            'results'         => 'nullable|string|max:5000',
            'recommendations' => 'nullable|string|max:5000',
        ]);

        $trip->report()->updateOrCreate(
            ['trip_id' => $trip->id],
            $data + ['submitted_by' => $request->user()->id, 'submitted_at' => now()]
        );
        $trip->update(['status' => 'completed']);

        app(TripMailer::class)->fire('report_submitted', $trip->fresh());

        return redirect()->route('trips.show', $trip)->with('success', 'Đã nộp báo cáo sau chuyến.');
    }

    // ------------------------------------------------------------------ Biểu mẫu Word
    public function document(Request $request, Trip $trip, string $doc)
    {
        $this->authorizeView($request, $trip);
        abort_unless(array_key_exists($doc, TripDocuments::available($trip)), 404);

        [$name, $bin] = (new TripDocuments())->build($trip, $doc);

        return response()->streamDownload(function () use ($bin) {
            echo $bin;
        }, $name, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    // ------------------------------------------------------------------ Tệp đính kèm
    public function uploadFile(Request $request, Trip $trip)
    {
        $this->authorizeView($request, $trip);
        $u = $request->user();
        abort_unless($u->role === 'admin' || $u->role === 'htqt' || $u->id === $trip->created_by, 403);

        $request->validate([
            'kind' => ['required', Rule::in(array_keys(Trip::FILE_KINDS))],
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip',
        ]);

        $file = $request->file('file');
        $path = $file->store('trip-files/' . $trip->id);

        $trip->files()->create([
            'kind' => $request->kind, 'original_name' => $file->getClientOriginalName(),
            'path' => $path, 'uploaded_by' => $u->id,
        ]);

        return back()->with('success', 'Đã tải tệp lên.');
    }

    public function downloadFile(Request $request, TripFile $file)
    {
        $this->authorizeView($request, $file->trip);

        return Storage::download($file->path, $file->original_name);
    }

    public function deleteFile(Request $request, TripFile $file)
    {
        $u = $request->user();
        abort_unless($u->role === 'admin' || $u->role === 'htqt' || $u->id === $file->trip->created_by, 403);

        Storage::delete($file->path);
        $file->delete();

        return back()->with('success', 'Đã xóa tệp.');
    }


    // ------------------------------------------------------------------ Thêm nhanh khách (đoàn vào)
    public function quickPerson(Request $request)
    {
        $data = $request->validate([
            'full_name'      => 'required|string|max:255',
            'academic_title' => 'nullable|string|max:100',
            'gender'         => 'nullable|in:male,female,other',
            'date_of_birth'  => 'nullable|date|before:today',
            'nationality'    => 'nullable|string|max:100',
            'position'       => 'nullable|string|max:255',
            'organization'   => 'nullable|string|max:255',
        ]);

        $person = Person::create($data + ['person_type' => 'guest', 'nationality' => $data['nationality'] ?? 'Việt Nam', 'is_active' => true]);

        return response()->json([
            'id'   => $person->id,
            'name' => trim(($person->academic_title ? $person->academic_title . '. ' : '') . $person->full_name),
            'sub'  => $person->organization ?: $person->type_label,
            'type' => $person->person_type,
        ]);
    }

    // ------------------------------------------------------------------ Công văn báo đoàn (đoàn vào)
    public function exportWord(Request $request, Trip $trip)
    {
        $delegation = $this->delegationFor($request, $trip);

        return app(DelegationWordExportController::class)->export($delegation);
    }

    public function exportExcel(Request $request, Trip $trip)
    {
        $delegation = $this->delegationFor($request, $trip);

        return app(DelegationExportController::class)->export($delegation);
    }

    /** Lấy (hoặc tạo từ hồ sơ đoàn vào) bản "công văn báo đoàn" để xuất Word/Excel */
    private function delegationFor(Request $request, Trip $trip): Delegation
    {
        $u = $request->user();
        abort_unless($trip->isIncoming(), 404);
        $this->authorizeView($request, $trip);
        abort_unless($u->role === 'admin' || $u->role === 'htqt'
            || ($u->id === $trip->created_by && in_array($trip->status, ['approved', 'completed'], true)), 403);

        if ($trip->delegation) {
            return $trip->delegation;
        }

        $trip->load(['members.passports', 'members.visas', 'unit']);

        return DB::transaction(function () use ($trip) {
            $delegation = Delegation::create([
                'host_unit'      => $trip->unit?->name ?? $trip->host_org,
                'purpose'        => $trip->title . ($trip->purpose ? ' — ' . $trip->purpose : ''),
                'work_date_from' => $trip->depart_at,
                'work_date_to'   => $trip->return_at,
                'work_location'  => $trip->work_location ?: $trip->unit?->name,
                'accommodation_name' => $trip->accommodation,
                'status'         => 'draft',
                'notes'          => $trip->notes,
                'created_by'     => $trip->created_by,
            ]);

            foreach ($trip->members as $p) {
                $passport = $p->passports->sortByDesc('expiry_date')->first();
                $visa     = $p->visas->sortByDesc('expiry_date')->first();

                $delegation->members()->create([
                    'full_name'       => trim(($p->academic_title ? $p->academic_title . '. ' : '') . $p->full_name),
                    'gender'          => $p->gender === 'female' ? 'Nữ' : 'Nam',
                    'date_of_birth'   => $p->date_of_birth,
                    'nationality'     => $p->nationality ?: 'Việt Nam',
                    'occupation'      => $p->position ?: $p->organization,
                    'passport_type'   => 'Phổ thông',
                    'passport_no'     => $passport?->passport_number,
                    'passport_expiry' => $passport?->expiry_date,
                    'visa_no'         => $visa?->visa_number,
                ]);
            }

            $trip->update(['delegation_id' => $delegation->id]);

            return $delegation;
        });
    }

    // ------------------------------------------------------------------ Nội bộ
    private function authorizeView(Request $request, Trip $trip): void
    {
        abort_unless(Trip::visibleTo($request->user())->whereKey($trip->id)->exists(), 403);
    }

    private function authorizeEdit(Request $request, Trip $trip): void
    {
        abort_unless($trip->canEditBy($request->user()), 403, 'Hồ sơ đang trong quy trình duyệt hoặc bạn không có quyền sửa.');
    }

    private function authorizeReport(Request $request, Trip $trip): void
    {
        abort_unless(in_array($trip->status, ['approved', 'completed'], true) && $trip->isMemberOrOwner($request->user()), 403);
    }

    private function dueDate(Trip $trip)
    {
        return $trip->return_at->copy()->addDays((int) config('trips.report_days', 15))->toDateString();
    }

    private function formData(Trip $trip, array $selected): array
    {
        $oldMembers = old('members');
        if (is_array($oldMembers)) {
            $selected = array_values($oldMembers);
        }

        return [
            'trip'     => $trip,
            'types'    => Trip::typesFor($trip->direction ?: 'out'),
            'units'    => Unit::flatTree(),
            'partners' => Partner::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'people'   => Person::with('unit')->where('is_active', true)->orderBy('full_name')->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => trim(($p->academic_title ? $p->academic_title . '. ' : '') . $p->full_name), 'sub' => $p->unit?->name ?? ($p->organization ?: $p->type_label), 'type' => $p->person_type])
                ->values(),
            'selected' => $selected,
        ];
    }

    private function validated(Request $request): array
    {
        $direction = $request->input('direction', ($request->route('trip') instanceof Trip ? $request->route('trip')->direction : 'out'));
        $direction = $direction === 'in' ? 'in' : 'out';

        $data = $request->validate([
            'direction'       => 'nullable|in:in,out',
            'trip_type'       => ['required', Rule::in(array_keys(Trip::typesFor($direction)))],
            'work_location'   => 'nullable|string|max:255',
            'accommodation'   => 'nullable|string|max:255',
            'reception_notes' => 'nullable|string|max:3000',
            'title'           => 'required|string|max:255',
            'purpose'         => 'nullable|string|max:3000',
            'country'         => 'required|string|max:100',
            'city'            => 'nullable|string|max:150',
            'host_org'        => 'nullable|string|max:255',
            'partner_id'      => 'nullable|exists:partners,id',
            'depart_at'       => 'required|date',
            'return_at'       => 'required|date|after_or_equal:depart_at',
            'unit_id'         => 'nullable|exists:units,id',
            'budget_estimate' => 'nullable|integer|min:0',
            'funding_source'  => 'nullable|string|max:255',
            'notes'           => 'nullable|string|max:2000',
            'members'                => 'nullable|array',
            'members.*.person_id'    => 'required|exists:people,id',
            'members.*.role'         => 'nullable|string|max:100',
        ], [
            'return_at.after_or_equal' => 'Thời gian về phải sau hoặc bằng thời gian đi.',
        ]);

        unset($data['members']);
        $data['direction'] = $direction;

        return $data;
    }

    private function syncMembers(Trip $trip, array $members): void
    {
        $sync = [];
        foreach ($members as $m) {
            if (!empty($m['person_id'])) {
                $sync[(int) $m['person_id']] = ['role_in_trip' => $m['role'] ?: 'Thành viên'];
            }
        }
        $trip->members()->sync($sync);
    }
}
