<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Đoàn ra &amp; đoàn vào</h2>
                <p class="mt-0.5 text-sm text-slate-500">Hồ sơ xin đi công tác, tiếp đoàn khách nước ngoài, phê duyệt và báo cáo.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('trips.create', ['direction' => 'in']) }}"
                   class="inline-flex items-center gap-1.5 rounded-lg border border-blue-700 bg-white px-3.5 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tiếp đoàn vào
                </a>
                <a href="{{ route('trips.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Đi công tác (đoàn ra)
                </a>
            </div>
        </div>

        {{-- Chuyển nhanh giữa đoàn ra / đoàn vào --}}
        <div class="flex gap-1 rounded-xl bg-slate-100 p-1 text-sm font-medium sm:w-fit">
            @foreach ([['', 'Tất cả'], ['out', 'Đoàn ra'], ['in', 'Đoàn vào']] as [$d, $dl])
                <a href="{{ route('trips.index', array_filter(array_merge(request()->except('page', 'direction'), ['direction' => $d]))) }}"
                   class="rounded-lg px-4 py-1.5 transition {{ (request('direction') ?? '') === $d ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">{{ $dl }}</a>
            @endforeach
        </div>

        {{-- Thẻ thống kê (bấm để lọc) --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @php
                $cards = [
                    ['Chờ tôi duyệt', $stats['to_approve'], 'to_approve', 'text-amber-600'],
                    ['Đang trong quy trình', $stats['pending'], null, 'text-slate-900'],
                    ['Đã duyệt năm ' . now()->year, $stats['approved'], null, 'text-emerald-600'],
                    ['Chờ nộp báo cáo', $stats['need_report'], null, 'text-red-600'],
                ];
            @endphp
            @foreach ($cards as [$label, $value, $filter, $tone])
                @php $on = $filter && request('filter') === $filter; @endphp
                <a href="{{ $filter ? route('trips.index', $on ? [] : ['filter' => $filter]) : '#' }}"
                   class="rounded-2xl border bg-white p-4 shadow-sm transition {{ $filter ? 'hover:border-blue-300' : 'cursor-default' }} {{ $on ? 'border-blue-600 ring-1 ring-blue-600' : 'border-slate-200' }}">
                    <p class="text-2xl font-bold {{ $tone }}">{{ $value }}</p>
                    <p class="text-xs text-slate-500">{{ $label }}</p>
                </a>
            @endforeach
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-2 border-b border-slate-100 p-4">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã, tên chuyến đi, nước, đơn vị mời..."
                       class="min-w-[220px] max-w-md flex-1 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Mọi trạng thái</option>
                    @foreach (\App\Models\Trip::STATUSES as $k => [$label])
                        <option value="{{ $k }}" @selected(request('status') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="type" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Mọi loại hình</option>
                    @foreach (\App\Models\Trip::TYPES as $k => $label)
                        <option value="{{ $k }}" @selected(request('type') === $k)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                    <option value="">Mọi năm</option>
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected((string) request('year') === (string) $y)>{{ $y }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-slate-800 px-3.5 py-2 text-sm font-medium text-white hover:bg-slate-900">Lọc</button>
                @if (request()->hasAny(['search', 'status', 'type', 'year', 'filter', 'direction']))
                    <a href="{{ route('trips.index') }}" class="px-1 text-sm text-slate-500 hover:text-slate-700">Xóa lọc</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Chuyến đi</th>
                            <th class="px-5 py-3">Nước / đối tác</th>
                            <th class="px-5 py-3">Thời gian</th>
                            <th class="px-5 py-3">Đơn vị</th>
                            <th class="px-5 py-3 text-center">Số người</th>
                            <th class="px-5 py-3 text-center">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($trips as $t)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('trips.show', $t) }}" class="font-medium text-slate-900 hover:text-blue-700">{{ $t->title }}</a>
                                    <p class="mt-0.5 text-xs text-slate-400"><span class="font-mono">{{ $t->code }}</span> · <span class="{{ $t->isIncoming() ? 'text-emerald-600' : 'text-blue-600' }}">{{ $t->direction_label }}</span> · {{ $t->type_label }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $t->country }}@if($t->city), {{ $t->city }}@endif @if($t->isIncoming() && $t->host_org)<p class="text-xs text-slate-400">{{ $t->host_org }}</p>@endif</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">
                                    {{ $t->depart_at->format('d/m/Y') }}
                                    @unless ($t->depart_at->isSameDay($t->return_at)) <span class="text-slate-400">→</span> {{ $t->return_at->format('d/m/Y') }} @endunless
                                    <p class="text-xs text-slate-400">{{ $t->days }} ngày</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $t->unit?->name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex h-7 min-w-[28px] items-center justify-center rounded-full bg-blue-50 px-2 text-xs font-semibold text-blue-700">{{ $t->members->count() }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $t->status_class }}">{{ $t->status_label }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-16 text-center text-slate-400">Chưa có hồ sơ nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($trips->hasPages())
                <div class="border-t border-slate-100 px-5 py-3">{{ $trips->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
