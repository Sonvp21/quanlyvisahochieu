<x-app-layout>
    @php
        $card = 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm';
        $sel = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
        $fmt = fn ($n) => number_format((int) $n, 0, ',', '.');
        $typeLabels = \App\Models\Trip::TYPES;
        $catLabels = \App\Models\TripExpense::CATEGORIES;
        $sumOut = collect($monthly)->sum('out'); $sumIn = collect($monthly)->sum('in'); $sumPeople = collect($monthly)->sum('people');
        $planTotal = collect($expTable)->sum(fn ($r) => $r['plan'] ?? 0);
        $actualTotal = collect($expTable)->sum(fn ($r) => $r['actual'] ?? 0);
    @endphp
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Thống kê đoàn ra, đoàn vào</h2>
                <p class="mt-0.5 text-sm text-slate-500">{{ $total }} hồ sơ khớp bộ lọc{{ $f['all'] ? ' (gồm cả hồ sơ đang chờ duyệt)' : ' (chỉ hồ sơ đã duyệt)' }}.</p>
            </div>
            <a href="{{ route('stats.export', request()->query()) }}" class="rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white hover:bg-blue-800">Xuất CSV (mở bằng Excel)</a>
        </div>

        <form method="GET" class="flex flex-wrap items-center gap-3 {{ $card }}">
            <select name="year" class="{{ $sel }}">@foreach ($years as $y)<option value="{{ $y }}" @selected($y == $f['year'])>Năm {{ $y }}</option>@endforeach</select>
            <select name="direction" class="{{ $sel }}">
                <option value="">Đoàn ra &amp; vào</option>
                <option value="out" @selected($f['direction'] === 'out')>Chỉ đoàn ra</option>
                <option value="in" @selected($f['direction'] === 'in')>Chỉ đoàn vào</option>
            </select>
            <select name="unit_id" class="{{ $sel }}">
                <option value="">Toàn trường</option>
                @foreach ($units as $u)<option value="{{ $u->id }}" @selected($f['unit_id'] == $u->id)>{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>@endforeach
            </select>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="all" value="1" @checked($f['all']) class="rounded border-slate-300 text-blue-700 focus:ring-blue-600"> Gồm hồ sơ chờ duyệt</label>
            <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Xem</button>
        </form>

        <div class="grid grid-cols-3 gap-4">
            <div class="{{ $card }}"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Đoàn ra</p><p class="mt-2 text-2xl font-semibold text-blue-700">{{ $sumOut }}</p></div>
            <div class="{{ $card }}"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Đoàn vào</p><p class="mt-2 text-2xl font-semibold text-teal-700">{{ $sumIn }}</p></div>
            <div class="{{ $card }}"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Lượt người</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $sumPeople }}</p></div>
        </div>

        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-slate-900">Theo tháng</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400"><th class="py-2 pr-4">Tháng</th><th class="py-2 pr-4 text-right">Đoàn ra</th><th class="py-2 pr-4 text-right">Đoàn vào</th><th class="py-2 text-right">Lượt người</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($monthly as $m => $v)
                            <tr><td class="py-2 pr-4 text-slate-700">Tháng {{ $m }}</td><td class="py-2 pr-4 text-right">{{ $v['out'] ?: '—' }}</td><td class="py-2 pr-4 text-right">{{ $v['in'] ?: '—' }}</td><td class="py-2 text-right">{{ $v['people'] ?: '—' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach ([['Theo quốc gia', $byCountry, null], ['Theo đơn vị', $byUnit, null], ['Theo loại hình', $byType, $typeLabels]] as [$ttl, $rows, $map])
                <div class="{{ $card }}">
                    <h3 class="text-sm font-semibold text-slate-900">{{ $ttl }}</h3>
                    <table class="mt-3 min-w-full text-sm">
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($rows as $r)
                                <tr><td class="py-2 pr-3 text-slate-700">{{ $map ? ($map[$r->name] ?? $r->name) : $r->name }}</td><td class="py-2 text-right font-medium text-slate-900">{{ $r->c }}</td></tr>
                            @empty<tr><td class="py-3 text-slate-400">Chưa có dữ liệu.</td></tr>@endforelse
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <div class="{{ $card }}">
            <h3 class="text-sm font-semibold text-slate-900">Kinh phí theo khoản mục (đồng)</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead><tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400"><th class="py-2 pr-4">Khoản mục</th><th class="py-2 pr-4 text-right">Dự toán</th><th class="py-2 text-right">Thực chi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($expTable as $cat => $r)
                            <tr><td class="py-2 pr-4 text-slate-700">{{ $catLabels[$cat] ?? $cat }}</td><td class="py-2 pr-4 text-right">{{ isset($r['plan']) ? $fmt($r['plan']) : '—' }}</td><td class="py-2 text-right">{{ isset($r['actual']) ? $fmt($r['actual']) : '—' }}</td></tr>
                        @empty<tr><td colspan="3" class="py-3 text-slate-400">Chưa ghi khoản chi nào.</td></tr>@endforelse
                    </tbody>
                    @if ($expTable)
                        <tfoot><tr class="border-t border-slate-200 font-semibold text-slate-900"><td class="py-2 pr-4">Tổng</td><td class="py-2 pr-4 text-right">{{ $fmt($planTotal) }}</td><td class="py-2 text-right">{{ $fmt($actualTotal) }}</td></tr></tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
