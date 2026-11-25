<?php

namespace App\Http\Controllers;

use App\Models\AnnualPlan;
use App\Models\Partner;
use App\Models\Trip;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnualPlanController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->get('year', now()->year);
        $q = AnnualPlan::with(['unit', 'partner', 'trip'])->where('year', $year);
        if (in_array($request->get('direction'), ['in', 'out'], true)) {
            $q->where('direction', $request->direction);
        }
        $plans = $q->orderByRaw('planned_month asc nulls last')->orderBy('id')->get();
        $active = $plans->where('status', '!=', 'cancelled');

        return view('plans.index', [
            'year'    => $year,
            'plans'   => $plans,
            'summary' => [
                'total'    => $active->count(),
                'executed' => $active->filter->is_executed->count(),
                'budget'   => (int) $active->sum('budget'),
            ],
            'canManage' => $this->canManage($request),
            'units'     => Unit::flatTree(),
            'partners'  => Partner::where('is_active', true)->orderBy('name')->get(['id', 'name']),
            'years'     => range(now()->year + 2, now()->year - 3),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeManage($request);
        $d = $request->validate([
            'year'            => 'required|integer|min:2020|max:2100',
            'direction'       => ['required', Rule::in(['in', 'out'])],
            'title'           => 'required|string|max:255',
            'unit_id'         => 'nullable|exists:units,id',
            'partner_id'      => 'nullable|exists:partners,id',
            'country'         => 'nullable|string|max:100',
            'planned_month'   => 'nullable|integer|min:1|max:12',
            'planned_members' => 'nullable|integer|min:1|max:1000',
            'budget'          => 'nullable|integer|min:0|max:100000000000',
            'notes'           => 'nullable|string|max:2000',
        ]);
        AnnualPlan::create($d + ['created_by' => $request->user()->id]);

        return redirect()->route('plans.index', ['year' => $d['year']])->with('success', 'Đã thêm vào kế hoạch.');
    }

    public function update(Request $request, AnnualPlan $plan)
    {
        $this->authorizeManage($request);
        $d = $request->validate(['status' => ['required', Rule::in(array_keys(AnnualPlan::STATUSES))]]);
        $plan->update($d);

        return back()->with('success', 'Đã cập nhật.');
    }

    public function destroy(Request $request, AnnualPlan $plan)
    {
        $this->authorizeManage($request);
        $plan->delete();

        return back()->with('success', 'Đã xóa khỏi kế hoạch.');
    }

    /** Sinh hồ sơ dự thảo từ một dòng kế hoạch */
    public function toTrip(Request $request, AnnualPlan $plan)
    {
        $this->authorizeManage($request);
        if ($plan->trip) {
            return redirect()->route('trips.show', $plan->trip);
        }

        $in = $plan->direction === 'in';
        $start = Carbon::create($plan->year, $plan->planned_month ?: 1, 15, 8, 0);

        $trip = Trip::create([
            'code'            => Trip::nextCode($plan->direction),
            'direction'       => $plan->direction,
            'trip_type'       => $in ? 'khach_khac' : 'cong_tac',
            'title'           => $plan->title,
            'country'         => $plan->country ?: $plan->partner?->country,
            'city'            => $plan->partner?->city,
            'host_org'        => $plan->partner?->name,
            'partner_id'      => $plan->partner_id,
            'depart_at'       => $start,
            'return_at'       => $start->copy()->addDays(4)->setTime(17, 0),
            'unit_id'         => $plan->unit_id,
            'budget_estimate' => $plan->budget,
            'created_by'      => $request->user()->id,
            'status'          => 'draft',
            'notes'           => $plan->notes,
        ]);
        $plan->update(['trip_id' => $trip->id]);

        return redirect()->route('trips.edit', $trip)->with('success', 'Đã tạo hồ sơ dự thảo từ kế hoạch. Bổ sung thời gian, thành viên rồi gửi duyệt.');
    }

    private function canManage(Request $request): bool
    {
        return in_array($request->user()->role, ['admin', 'htqt'], true);
    }

    private function authorizeManage(Request $request): void
    {
        abort_unless($this->canManage($request), 403);
    }
}
