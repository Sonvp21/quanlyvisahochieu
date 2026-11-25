<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Trip;
use App\Models\TripFile;
use App\Models\Unit;
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
        $trip = new Trip(['trip_type' => 'cong_tac', 'unit_id' => $user->unit_id]);
        $selected = [];

        // Cán bộ tự làm hồ sơ cho mình: chọn sẵn chính họ
        if ($user->person) {
            $selected[] = ['person_id' => $user->person->id, 'role' => 'Trưởng đoàn'];
        }

        return view('trips.create', $this->formData($trip, $selected));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $trip = DB::transaction(function () use ($request, $data) {
            $trip = Trip::create($data + [
                'code'       => Trip::nextCode(),
                'direction'  => 'out',
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

        return redirect()->route('trips.show', $trip)->with('success', 'Đã nộp báo cáo sau chuyến.');
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
            'units'    => Unit::flatTree(),
            'people'   => Person::with('unit')->where('is_active', true)->orderBy('full_name')->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => trim(($p->academic_title ? $p->academic_title . '. ' : '') . $p->full_name), 'sub' => $p->unit?->name ?? ($p->organization ?: $p->type_label)])
                ->values(),
            'selected' => $selected,
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'trip_type'       => ['required', Rule::in(array_keys(Trip::TYPES))],
            'title'           => 'required|string|max:255',
            'purpose'         => 'nullable|string|max:3000',
            'country'         => 'required|string|max:100',
            'city'            => 'nullable|string|max:150',
            'host_org'        => 'nullable|string|max:255',
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
