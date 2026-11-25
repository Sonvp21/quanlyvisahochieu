<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\AnnualPlan;
use App\Models\ExchangeApplication;
use App\Models\Trip;
use App\Models\TripExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $year = (int) $request->get('year', now()->year);
        $done = ['approved', 'completed'];

        $inYear = fn () => Trip::whereIn('status', $done)->whereYear('depart_at', $year);

        $monthly = array_fill(1, 12, ['out' => 0, 'in' => 0]);
        $inYear()->selectRaw('extract(month from depart_at)::int as m, direction, count(*) as c')
            ->groupBy('m', 'direction')->get()
            ->each(function ($r) use (&$monthly) {
                $monthly[(int) $r->m][$r->direction] = (int) $r->c;
            });

        $byCountry = $inYear()->whereNotNull('country')->where('country', '!=', '')
            ->selectRaw('country, count(*) as c')->groupBy('country')->orderByDesc('c')->limit(8)->get();

        $byUnit = Trip::whereIn('trips.status', $done)->whereYear('trips.depart_at', $year)
            ->leftJoin('units', 'units.id', '=', 'trips.unit_id')
            ->selectRaw("coalesce(units.name, 'Chưa gán đơn vị') as name, count(*) as c")
            ->groupBy('units.name')->orderByDesc('c')->limit(8)->get();

        $peopleCount = DB::table('trip_members')->whereIn('trip_id', $inYear()->select('trips.id'))->count();

        $spent = TripExpense::where('kind', 'actual')->whereIn('trip_id', $inYear()->select('trips.id'))->sum('amount');

        $plans = AnnualPlan::where('year', $year)->where('status', '!=', 'cancelled')->with('trip:id,status')->get();

        return view('overview.index', [
            'year'      => $year,
            'kpi'       => [
                'out'      => $inYear()->where('direction', 'out')->count(),
                'in'       => $inYear()->where('direction', 'in')->count(),
                'people'   => $peopleCount,
                'pending'  => Trip::whereIn('status', ['pending_unit', 'pending_htqt', 'pending_bgh'])->count(),
                'overdue'  => Trip::where('status', 'approved')->whereDate('report_due_at', '<', today())->count(),
                'mou'      => Agreement::expiringSoon()->count(),
                'exchange' => ExchangeApplication::whereIn('status', ['approved', 'ongoing'])->count(),
                'spent'    => $spent,
            ],
            'monthly'   => $monthly,
            'byCountry' => $byCountry,
            'byUnit'    => $byUnit,
            'awaiting'  => Trip::awaiting($user)->with('unit')->orderBy('submitted_at')->limit(6)->get(),
            'upcoming'  => Trip::where('status', 'approved')->whereBetween('depart_at', [now(), now()->addDays(30)])->with('unit')->orderBy('depart_at')->limit(6)->get(),
            'plan'      => ['total' => $plans->count(), 'executed' => $plans->filter->is_executed->count()],
            'years'     => range(now()->year + 1, now()->year - 4),
        ]);
    }
}
