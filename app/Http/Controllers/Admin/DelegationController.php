<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delegation;
use App\Models\DelegationMember;
use Illuminate\Http\Request;

class DelegationController extends Controller
{
    public function index(Request $request)
    {
        $query = Delegation::withCount('members')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_no', 'like', "%{$search}%")
                  ->orWhere('host_unit', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $delegations = $query->paginate(15)->withQueryString();

        return view('admin.delegations.index', compact('delegations'));
    }

    public function create()
    {
        $delegation = new Delegation();
        return view('admin.delegations.create', compact('delegation'));
    }

    public function store(Request $request)
    {
        $data = $this->validateDelegation($request);

        $delegation = Delegation::create([
            ...$data,
            'created_by' => auth()->id(),
        ]);

        $this->syncMembers($delegation, $request);

        return redirect()
            ->route('admin.delegations.show', $delegation)
            ->with('success', 'Đã tạo đoàn khách nước ngoài thành công.');
    }

    public function show(Delegation $delegation)
    {
        $delegation->load('members');
        return view('admin.delegations.show', compact('delegation'));
    }

    public function edit(Delegation $delegation)
    {
        $delegation->load('members');
        return view('admin.delegations.edit', compact('delegation'));
    }

    public function update(Request $request, Delegation $delegation)
    {
        $data = $this->validateDelegation($request);
        $delegation->update($data);
        $this->syncMembers($delegation, $request);

        return redirect()
            ->route('admin.delegations.show', $delegation)
            ->with('success', 'Đã cập nhật thông tin đoàn khách.');
    }

    public function destroy(Delegation $delegation)
    {
        $delegation->delete();
        return redirect()
            ->route('admin.delegations.index')
            ->with('success', 'Đã xóa đoàn khách.');
    }

    protected function validateDelegation(Request $request): array
    {
        return $request->validate([
            'document_no'            => 'nullable|string|max:100',
            'host_unit'               => 'nullable|string|max:255',
            'purpose'                 => 'nullable|string',
            'work_date_from'          => 'nullable|date',
            'work_date_to'            => 'nullable|date|after_or_equal:work_date_from',
            'work_location'           => 'nullable|string|max:255',
            'accommodation_name'      => 'nullable|string|max:255',
            'accommodation_address'   => 'nullable|string|max:255',
            'recipient_units'         => 'nullable|string|max:255',
            'status'                  => 'nullable|in:draft,sent,completed',
            'notes'                   => 'nullable|string',
        ]);
    }

    protected function syncMembers(Delegation $delegation, Request $request): void
    {
        $members = $request->input('members', []);

        $keepIds = [];

        foreach ($members as $row) {
            if (empty($row['full_name'])) {
                continue;
            }

            $payload = [
                'full_name'       => $row['full_name'],
                'gender'          => $row['gender'] ?? null,
                'date_of_birth'   => $row['date_of_birth'] ?? null,
                'nationality'     => $row['nationality'] ?? 'Đài Loan',
                'occupation'      => $row['occupation'] ?? null,
                'passport_type'   => $row['passport_type'] ?? null,
                'passport_no'     => $row['passport_no'] ?? null,
                'passport_expiry' => $row['passport_expiry'] ?? null,
                'visa_no'         => $row['visa_no'] ?? null,
            ];

            if (!empty($row['id'])) {
                $member = DelegationMember::where('delegation_id', $delegation->id)->find($row['id']);
                if ($member) {
                    $member->update($payload);
                    $keepIds[] = $member->id;
                    continue;
                }
            }

            $new = $delegation->members()->create($payload);
            $keepIds[] = $new->id;
        }

        // Xóa các thành viên đã bị bỏ khỏi form
        $delegation->members()->whereNotIn('id', $keepIds)->delete();
    }
}
