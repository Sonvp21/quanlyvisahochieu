<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripExpense;
use App\Models\Unit;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $f = $this->filters($request);
        $base = fn () => $this->query($f);
        $ids = fn () => $base()->select('trips.id');

        $monthly = array_fill(1, 12, ['out' => 0, 'in' => 0, 'people' => 0]);
        $base()->selectRaw('extract(month from trips.depart_at)::int as m, trips.direction, count(*) as c')->groupBy('m', 'trips.direction')->get()
            ->each(function ($r) use (&$monthly) {
                $monthly[(int) $r->m][$r->direction] = (int) $r->c;
            });
        \Illuminate\Support\Facades\DB::table('trip_members')->join('trips', 'trips.id', '=', 'trip_members.trip_id')->whereIn('trips.id', $ids())
            ->selectRaw('extract(month from trips.depart_at)::int as m, count(*) as c')->groupBy('m')->get()
            ->each(function ($r) use (&$monthly) {
                $monthly[(int) $r->m]['people'] = (int) $r->c;
            });

        $group = fn (string $expr, string $alias) => $base()->selectRaw("{$expr} as {$alias}, count(*) as c")->groupBy($alias)->orderByDesc('c')->get();

        $expenses = TripExpense::whereIn('trip_id', $ids())->selectRaw('category, kind, sum(amount) as s')->groupBy('category', 'kind')->get();
        $expTable = [];
        foreach ($expenses as $e) {
            $expTable[$e->category][$e->kind] = (int) $e->s;
        }

        return view('stats.index', [
            'f'         => $f,
            'total'     => $base()->count(),
            'monthly'   => $monthly,
            'byCountry' => $group("coalesce(nullif(trips.country, ''), 'Chưa rõ')", 'name'),
            'byType'    => $group('trips.trip_type', 'name'),
            'byUnit'    => $this->byUnit($f),
            'expTable'  => $expTable,
            'units'     => Unit::flatTree(),
            'years'     => range(now()->year + 1, now()->year - 6),
        ]);
    }

    public function export(Request $request)
    {
        $f = $this->filters($request);
        $rows = $this->query($f)->with(['unit', 'partner'])->withCount('members')
            ->withSum(['expenses as plan_sum' => fn ($q) => $q->where('kind', 'plan')], 'amount')
            ->withSum(['expenses as actual_sum' => fn ($q) => $q->where('kind', 'actual')], 'amount')
            ->orderBy('trips.depart_at')->get();

        $name = 'Thong ke doan ra vao ' . $f['year'] . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Mã', 'Chiều', 'Loại', 'Nội dung', 'Quốc gia', 'Đối tác / đơn vị mời', 'Đơn vị', 'Đi', 'Về', 'Số người', 'Trạng thái', 'Dự toán (đ)', 'Thực chi (đ)']);
            foreach ($rows as $t) {
                fputcsv($out, [
                    $t->code, $t->direction_label, $t->type_label, $t->title, $t->country, $t->partner?->name ?? $t->host_org,
                    $t->unit?->name, $t->depart_at?->format('d/m/Y'), $t->return_at?->format('d/m/Y'), $t->members_count,
                    $t->status_label, $t->plan_sum ?: $t->budget_estimate, $t->actual_sum,
                ]);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filters(Request $request): array
    {
        return [
            'year'      => (int) $request->get('year', now()->year),
            'direction' => in_array($request->get('direction'), ['in', 'out'], true) ? $request->get('direction') : null,
            'unit_id'   => $request->filled('unit_id') ? (int) $request->unit_id : null,
            'all'       => $request->boolean('all'), // gồm cả hồ sơ chưa duyệt
        ];
    }

    private function query(array $f)
    {
        $q = Trip::query()->whereYear('trips.depart_at', $f['year']);
        $f['all'] ? $q->where('trips.status', '!=', 'draft') : $q->whereIn('trips.status', ['approved', 'completed']);
        if ($f['direction']) {
            $q->where('trips.direction', $f['direction']);
        }
        if ($f['unit_id'] && ($unit = Unit::find($f['unit_id']))) {
            $q->whereIn('trips.unit_id', $unit->selfAndDescendantIds());
        }

        return $q;
    }

    private function byUnit(array $f)
    {
        return $this->query($f)->leftJoin('units', 'units.id', '=', 'trips.unit_id')
            ->selectRaw("coalesce(units.name, 'Chưa gán đơn vị') as name, count(*) as c")->groupBy('units.name')->orderByDesc('c')->get();
    }
}
