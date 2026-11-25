<x-app-layout>
    @php
        $maxM = max(1, collect($monthly)->map(fn ($m) => $m['out'] + $m['in'])->max());
        $maxC = max(1, $byCountry->max('c') ?? 1);
        $maxU = max(1, $byUnit->max('c') ?? 1);
        $card = 'rounded-2xl border border-slate-200 bg-white p-5 shadow-sm';
        $fmt = fn ($n) => $n >= 1000000 ? rtrim(rtrim(number_format($n / 1000000, 1, ',', '.'), '0'), ',') . ' triệu' : number_format($n, 0, ',', '.');
    @endphp
    <div class="space-y-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Tổng quan hợp tác quốc tế &amp; đối ngoại</h2>
                <p class="mt-0.5 text-sm text-slate-500">Số liệu tính trên hồ sơ đã duyệt có thời gian đi trong năm {{ $year }}.</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                    @foreach ($years as $y)<option value="{{ $y }}" @selected($y == $year)>Năm {{ $y }}</option>@endforeach
                </select>
                <a href="{{ route('stats.index', ['year' => $year]) }}" class="rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Thống kê chi tiết</a>
            </form>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['Đoàn ra', $kpi['out'], 'text-blue-700', null],
                ['Đoàn vào', $kpi['in'], 'text-teal-700', null],
                ['Lượt người tham gia', $kpi['people'], 'text-slate-900', null],
                ['Thực chi', $fmt($kpi['spent']) . ' đ', 'text-slate-900', null],
                ['Hồ sơ đang chờ duyệt', $kpi['pending'], $kpi['pending'] ? 'text-amber-600' : 'text-slate-900', route('trips.index')],
                ['Quá hạn nộp báo cáo', $kpi['overdue'], $kpi['overdue'] ? 'text-red-600' : 'text-slate-900', route('trips.index')],
                ['MOU sắp hết hạn', $kpi['mou'], $kpi['mou'] ? 'text-amber-600' : 'text-slate-900', in_array(auth()->user()->role, ['admin', 'htqt'], true) ? route('admin.agreements.index', ['state' => 'expiring']) : null],
                ['SV đang/được chọn trao đổi', $kpi['exchange'], 'text-slate-900', null],
            ] as [$label, $val, $cls, $href])
                <div class="{{ $card }} relative {{ $href ? 'transition hover:border-blue-300' : '' }}">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $label }}</p>
                    <p class="mt-2 text-2xl font-semibold {{ $cls }}">{{ $val }}</p>
                    @if ($href)<a href="{{ $href }}" class="absolute inset-0 rounded-2xl" aria-label="{{ $label }}"></a>@endif
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="{{ $card }} lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-900">Số đoàn theo tháng</h3>
                    <div class="flex gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-blue-600"></span>Đoàn ra</span>
                        <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm bg-teal-500"></span>Đoàn vào</span>
                    </div>
                </div>
                <div class="mt-5 flex h-48 items-end gap-2">
                    @foreach ($monthly as $m => $v)
                        @php $tot = $v['out'] + $v['in']; @endphp
                        <div class="flex h-full flex-1 flex-col items-center justify-end" title="Tháng {{ $m }}: {{ $v['out'] }} ra, {{ $v['in'] }} vào">
                            <span class="mb-1 text-[11px] font-medium text-slate-500">{{ $tot ?: '' }}</span>
                            <div class="flex w-full max-w-[28px] flex-col justify-end overflow-hidden rounded-t" style="height: {{ round($tot / $maxM * 85) }}%">
                                <div class="bg-teal-500" style="height: {{ $tot ? round($v['in'] / $tot * 100) : 0 }}%"></div>
                                <div class="bg-blue-600" style="height: {{ $tot ? round($v['out'] / $tot * 100) : 0 }}%"></div>
                            </div>
                            <span class="mt-1.5 text-[11px] text-slate-400">T{{ $m }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="{{ $card }}">
                <h3 class="text-sm font-semibold text-slate-900">Thực hiện kế hoạch năm {{ $year }}</h3>
                @if ($plan['total'])
                    @php $pct = round($plan['executed'] / $plan['total'] * 100); @endphp
                    <p class="mt-4 text-3xl font-semibold text-slate-900">{{ $plan['executed'] }}<span class="text-lg font-normal text-slate-400"> / {{ $plan['total'] }}</span></p>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $pct }}%"></div></div>
                    <p class="mt-2 text-xs text-slate-500">{{ $pct }}% nội dung kế hoạch đã có hồ sơ được duyệt.</p>
                @else
                    <p class="mt-4 text-sm text-slate-400">Chưa lập kế hoạch năm.</p>
                @endif
                <a href="{{ route('plans.index', ['year' => $year]) }}" class="mt-4 inline-block text-sm font-medium text-blue-700 hover:text-blue-800">Xem kế hoạch năm →</a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="{{ $card }}">
                <h3 class="text-sm font-semibold text-slate-900">Quốc gia / vùng lãnh thổ</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($byCountry as $r)
                        <div>
                            <div class="flex justify-between text-sm"><span class="text-slate-700">{{ $r->country }}</span><span class="font-medium text-slate-900">{{ $r->c }}</span></div>
                            <div class="mt-1 h-1.5 rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-600" style="width: {{ round($r->c / $maxC * 100) }}%"></div></div>
                        </div>
                    @empty<p class="text-sm text-slate-400">Chưa có dữ liệu.</p>@endforelse
                </div>
            </div>
            <div class="{{ $card }}">
                <h3 class="text-sm font-semibold text-slate-900">Đơn vị</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($byUnit as $r)
                        <div>
                            <div class="flex justify-between text-sm"><span class="text-slate-700">{{ $r->name }}</span><span class="font-medium text-slate-900">{{ $r->c }}</span></div>
                            <div class="mt-1 h-1.5 rounded-full bg-slate-100"><div class="h-full rounded-full bg-indigo-500" style="width: {{ round($r->c / $maxU * 100) }}%"></div></div>
                        </div>
                    @empty<p class="text-sm text-slate-400">Chưa có dữ liệu.</p>@endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @foreach ([['Chờ tôi xử lý', $awaiting, 'Không có hồ sơ nào đang chờ bạn.'], ['Sắp diễn ra trong 30 ngày', $upcoming, 'Không có đoàn nào sắp diễn ra.']] as [$ttl, $list, $empty])
                <div class="{{ $card }}">
                    <h3 class="text-sm font-semibold text-slate-900">{{ $ttl }}</h3>
                    <ul class="mt-3 divide-y divide-slate-100">
                        @forelse ($list as $t)
                            <li class="py-2.5">
                                <a href="{{ route('trips.show', $t) }}" class="block hover:text-blue-700">
                                    <span class="text-sm font-medium text-slate-900">{{ $t->title }}</span>
                                    <span class="mt-0.5 block text-xs text-slate-500">{{ $t->code }} · {{ $t->direction_label }} · {{ $t->unit?->name ?? '—' }} · {{ $t->depart_at?->format('d/m/Y') }}</span>
                                </a>
                            </li>
                        @empty<li class="py-3 text-sm text-slate-400">{{ $empty }}</li>@endforelse
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
