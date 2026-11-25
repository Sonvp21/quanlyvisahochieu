<x-app-layout>
    @php $sel = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp
    <div class="mx-auto max-w-6xl space-y-5" x-data="{ open: null }">
        <div>
            <h2 class="text-xl font-semibold text-slate-900">Nhật ký thao tác</h2>
            <p class="mt-0.5 text-sm text-slate-500">Ai đã tạo, sửa, xóa gì và khi nào. Bấm vào dòng “Sửa” để xem giá trị trước và sau.</p>
        </div>
        <form method="GET" class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm nội dung…" class="{{ $sel }} w-56">
            <select name="user_id" class="{{ $sel }}"><option value="">Mọi người dùng</option>@foreach ($users as $u)<option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->name }}</option>@endforeach</select>
            <select name="action" class="{{ $sel }}"><option value="">Mọi thao tác</option>@foreach (\App\Models\AuditLog::ACTIONS as $k => $v)<option value="{{ $k }}" @selected(request('action') === $k)>{{ $v }}</option>@endforeach</select>
            <input type="date" name="from" value="{{ request('from') }}" class="{{ $sel }}">
            <input type="date" name="to" value="{{ request('to') }}" class="{{ $sel }}">
            <button class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Lọc</button>
        </form>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full text-sm">
                <thead><tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-5 py-3">Thời gian</th><th class="px-5 py-3">Người dùng</th><th class="px-5 py-3">Thao tác</th><th class="px-5 py-3">Nội dung</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $l)
                        <tr class="align-top {{ $l->changes ? 'cursor-pointer hover:bg-slate-50/60' : '' }}" @if ($l->changes) @click="open = open === {{ $l->id }} ? null : {{ $l->id }}" @endif>
                            <td class="whitespace-nowrap px-5 py-3 text-slate-500">{{ $l->created_at->format('H:i d/m/Y') }}</td>
                            <td class="px-5 py-3 text-slate-700">{{ $l->user?->name ?? 'Hệ thống' }}<span class="block text-xs text-slate-400">{{ $l->ip }}</span></td>
                            <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ ['created' => 'bg-emerald-50 text-emerald-700', 'updated' => 'bg-blue-50 text-blue-700', 'deleted' => 'bg-red-50 text-red-700', 'login' => 'bg-slate-100 text-slate-600'][$l->action] ?? 'bg-slate-100 text-slate-600' }}">{{ \App\Models\AuditLog::ACTIONS[$l->action] ?? $l->action }}</span></td>
                            <td class="px-5 py-3 text-slate-800">
                                {{ $l->summary }}
                                @if ($l->trip_id && $l->trip)<a href="{{ route('trips.show', $l->trip) }}" @click.stop class="ml-1 text-xs font-medium text-blue-700 hover:text-blue-800">[{{ $l->trip->code }}]</a>@endif
                                @if ($l->changes)
                                    <div x-show="open === {{ $l->id }}" x-cloak class="mt-2 space-y-1 rounded-lg bg-slate-50 p-3 text-xs">
                                        @foreach ($l->changes as $field => $c)
                                            <p><span class="font-medium text-slate-600">{{ $field }}:</span> <span class="text-red-600 line-through">{{ is_scalar($c['old'] ?? null) ? $c['old'] : '—' }}</span> → <span class="text-emerald-700">{{ is_scalar($c['new'] ?? null) ? $c['new'] : '—' }}</span></p>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">Chưa có thao tác nào được ghi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</x-app-layout>
