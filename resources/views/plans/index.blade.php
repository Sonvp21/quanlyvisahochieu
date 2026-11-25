<x-app-layout>
    @php
        $card = 'rounded-2xl border border-slate-200 bg-white shadow-sm';
        $in = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
        $fmt = fn ($n) => $n ? number_format($n, 0, ',', '.') . ' đ' : '—';
    @endphp
    <div class="space-y-6">
        <x-alert-success />
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Kế hoạch đối ngoại năm {{ $year }}</h2>
                <p class="mt-0.5 text-sm text-slate-500">Dự kiến đoàn ra, đoàn vào trong năm; từ mỗi dòng có thể sinh hồ sơ dự thảo.</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="{{ $in }}">@foreach ($years as $y)<option value="{{ $y }}" @selected($y == $year)>Năm {{ $y }}</option>@endforeach</select>
                <select name="direction" onchange="this.form.submit()" class="{{ $in }}">
                    <option value="">Ra &amp; vào</option>
                    <option value="out" @selected(request('direction') === 'out')>Đoàn ra</option>
                    <option value="in" @selected(request('direction') === 'in')>Đoàn vào</option>
                </select>
            </form>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="{{ $card }} p-5"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nội dung dự kiến</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $summary['total'] }}</p></div>
            <div class="{{ $card }} p-5"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Đã thực hiện</p><p class="mt-2 text-2xl font-semibold text-emerald-700">{{ $summary['executed'] }}</p></div>
            <div class="{{ $card }} p-5"><p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kinh phí dự kiến</p><p class="mt-2 text-2xl font-semibold text-slate-900">{{ $fmt($summary['budget']) }}</p></div>
        </div>

        @if ($canManage)
            <details class="{{ $card }} p-5" @if ($errors->any()) open @endif>
                <summary class="cursor-pointer text-sm font-medium text-blue-700 hover:text-blue-800">+ Thêm nội dung vào kế hoạch</summary>
                <form method="POST" action="{{ route('plans.store') }}" class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-4">
                    @csrf
                    <input type="hidden" name="year" value="{{ $year }}">
                    <div class="md:col-span-3"><input type="text" name="title" value="{{ old('title') }}" placeholder="Nội dung (vd: Đoàn công tác khảo sát tại Nhật Bản) *" required class="{{ $in }} w-full">@error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <select name="direction" class="{{ $in }}"><option value="out">Đoàn ra</option><option value="in">Đoàn vào</option></select>
                    <select name="planned_month" class="{{ $in }}"><option value="">Tháng dự kiến</option>@for ($m = 1; $m <= 12; $m++)<option value="{{ $m }}">Tháng {{ $m }}</option>@endfor</select>
                    <input type="text" name="country" placeholder="Quốc gia" class="{{ $in }}">
                    <select name="partner_id" class="{{ $in }}"><option value="">Đối tác (nếu có)</option>@foreach ($partners as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
                    <select name="unit_id" class="{{ $in }}"><option value="">Đơn vị phụ trách</option>@foreach ($units as $u)<option value="{{ $u->id }}">{{ str_repeat('— ', $u->depth) }}{{ $u->name }}</option>@endforeach</select>
                    <input type="number" min="1" name="planned_members" placeholder="Số người" class="{{ $in }}">
                    <input type="number" min="0" name="budget" placeholder="Kinh phí dự kiến (đồng)" class="{{ $in }}">
                    <input type="text" name="notes" placeholder="Ghi chú" class="{{ $in }} md:col-span-2">
                    <div><button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800">Thêm</button></div>
                </form>
            </details>
        @endif

        <div class="overflow-hidden {{ $card }}">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Tháng</th><th class="px-5 py-3">Nội dung</th><th class="px-5 py-3">Đơn vị</th><th class="px-5 py-3 text-right">Người</th><th class="px-5 py-3 text-right">Kinh phí</th><th class="px-5 py-3">Tình trạng</th>@if ($canManage)<th class="px-5 py-3"></th>@endif
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($plans as $p)
                        <tr class="align-top {{ $p->status === 'cancelled' ? 'opacity-50' : '' }}">
                            <td class="whitespace-nowrap px-5 py-3 text-slate-600">{{ $p->planned_month ? 'T' . $p->planned_month : '—' }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-900">{{ $p->title }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $p->direction === 'in' ? 'Đoàn vào' : 'Đoàn ra' }}{{ $p->country ? ' · ' . $p->country : '' }}{{ $p->partner ? ' · ' . $p->partner->name : '' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->unit?->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-right text-slate-600">{{ $p->planned_members ?? '—' }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right text-slate-600">{{ $fmt($p->budget) }}</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $p->state[1] }}">{{ $p->state[0] }}</span>
                                @if ($p->trip)<a href="{{ route('trips.show', $p->trip) }}" class="mt-1 block text-xs font-medium text-blue-700 hover:text-blue-800">{{ $p->trip->code }}</a>@endif
                            </td>
                            @if ($canManage)
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @unless ($p->trip)
                                            @if ($p->status === 'planned')
                                                <form method="POST" action="{{ route('plans.trip', $p) }}">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">Tạo hồ sơ</button></form>
                                            @endif
                                            <form method="POST" action="{{ route('plans.update', $p) }}" class="flex items-center gap-1">
                                                @csrf @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="rounded-lg border-slate-300 py-1 text-xs focus:border-blue-600 focus:ring-blue-600">
                                                    @foreach (\App\Models\AnnualPlan::STATUSES as $k => $v)<option value="{{ $k }}" @selected($p->status === $k)>{{ $v }}</option>@endforeach
                                                </select>
                                            </form>
                                            <form method="POST" action="{{ route('plans.destroy', $p) }}" onsubmit="return confirm('Xóa dòng kế hoạch này?')">@csrf @method('DELETE')<button class="text-xs text-red-600 hover:text-red-700">Xóa</button></form>
                                        @endunless
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">Chưa có kế hoạch cho năm {{ $year }}.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
