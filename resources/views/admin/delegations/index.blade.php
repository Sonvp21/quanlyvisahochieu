<x-app-layout>
    <div class="space-y-6">
        <x-alert-success />

        @php
            $totalDelegations = \App\Models\Delegation::count();
            $totalGuests      = \App\Models\DelegationMember::count();
            $upcoming         = \App\Models\Delegation::whereDate('work_date_to', '>=', now()->toDateString())->count();
        @endphp

        {{-- Thống kê nhanh --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach ([
                ['Tổng số đoàn khách', $totalDelegations, 'bg-blue-50 text-blue-600', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['Tổng số khách đã đón', $totalGuests, 'bg-emerald-50 text-emerald-600', 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4'],
                ['Sắp tới / đang diễn ra', $upcoming, 'bg-amber-50 text-amber-600', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ] as [$label, $value, $tone, $icon])
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $tone }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                    </span>
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
                        <p class="text-xs text-slate-500">{{ $label }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Bảng danh sách --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 p-4">
                <form method="GET" class="flex flex-1 flex-wrap items-center gap-2">
                    <div class="relative min-w-[220px] flex-1 max-w-md">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm số công văn, đơn vị, nội dung..."
                               class="w-full rounded-lg border border-slate-300 bg-slate-50 py-2 pl-9 pr-3 text-sm focus:border-blue-600 focus:bg-white focus:ring-2 focus:ring-blue-600/20">
                    </div>
                    <select name="status" onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-slate-50 py-2 pl-3 pr-8 text-sm focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20">
                        <option value="">Mọi trạng thái</option>
                        <option value="draft" @selected(request('status')==='draft')>Dự thảo</option>
                        <option value="sent" @selected(request('status')==='sent')>Đã gửi công văn</option>
                        <option value="completed" @selected(request('status')==='completed')>Hoàn tất</option>
                    </select>
                    <button class="rounded-lg bg-slate-800 px-3.5 py-2 text-sm font-medium text-white hover:bg-slate-900">Lọc</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.delegations.index') }}" class="px-1 text-sm text-slate-500 hover:text-slate-700">Xóa lọc</a>
                    @endif
                </form>

                <a href="{{ route('admin.delegations.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-800 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Thêm đoàn khách
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50/70 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Đoàn khách</th>
                            <th class="px-5 py-3">Đơn vị tiếp đón</th>
                            <th class="px-5 py-3">Thời gian</th>
                            <th class="px-5 py-3 text-center">Số khách</th>
                            <th class="px-5 py-3 text-center">Trạng thái</th>
                            <th class="px-5 py-3 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($delegations as $delegation)
                            @php
                                $statusMap = [
                                    'draft'     => ['Dự thảo', 'bg-slate-100 text-slate-600'],
                                    'sent'      => ['Đã gửi công văn', 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'],
                                    'completed' => ['Hoàn tất', 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'],
                                ];
                                [$label, $cls] = $statusMap[$delegation->status] ?? ['—', 'bg-slate-100 text-slate-600'];
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.delegations.show', $delegation) }}" class="font-semibold text-slate-900 hover:text-blue-700">
                                        {{ $delegation->document_no ?: 'Chưa có số công văn' }}
                                    </a>
                                    <p class="mt-0.5 max-w-[260px] truncate text-xs text-slate-400">{{ $delegation->purpose ?: '—' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $delegation->host_unit ?: '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    @if($delegation->work_date_from)
                                        {{ $delegation->work_date_from->format('H:i d/m/Y') }}
                                        @if($delegation->work_date_to && !$delegation->work_date_to->eq($delegation->work_date_from))
                                            <span class="text-slate-400">→</span> {{ $delegation->work_date_to->format('H:i d/m/Y') }}
                                        @endif
                                    @else — @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex h-7 min-w-[28px] items-center justify-center rounded-full bg-blue-50 px-2 text-xs font-semibold text-blue-700">
                                        {{ $delegation->members_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-0.5">
                                        <a href="{{ route('admin.delegations.show', $delegation) }}" title="Xem"
                                           class="rounded-lg p-2 text-slate-400 transition hover:bg-blue-50 hover:text-blue-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.delegations.edit', $delegation) }}" title="Sửa"
                                           class="rounded-lg p-2 text-slate-400 transition hover:bg-amber-50 hover:text-amber-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.delegations.export-word', $delegation) }}" title="Xuất công văn Word"
                                           class="rounded-lg p-2 text-slate-400 transition hover:bg-sky-50 hover:text-sky-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.delegations.export', $delegation) }}" title="Xuất Excel phụ lục"
                                           class="rounded-lg p-2 text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                        </a>
                                        <form action="{{ route('admin.delegations.destroy', $delegation) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Xóa đoàn khách này?')">
                                            @csrf @method('DELETE')
                                            <button title="Xóa" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                                        <p class="text-sm">Chưa có đoàn khách nước ngoài nào.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($delegations->hasPages())
                <div class="border-t border-slate-100 px-5 py-3">{{ $delegations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
