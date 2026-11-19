<x-app-layout>
    <div class="space-y-6 m-3">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl shadow-lg p-6 text-white flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Đoàn khách nước ngoài</h1>
                    <p class="text-indigo-100 text-sm">Quản lý các đoàn khách/chuyên gia nước ngoài đến thăm và làm việc</p>
                </div>
            </div>
            <a href="{{ route('admin.delegations.create') }}"
               class="inline-flex items-center gap-2 bg-white text-indigo-700 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-indigo-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Thêm đoàn khách
            </a>
        </div>

        <x-alert-success />

        <!-- Stat tiles -->
        @php
            $total = $delegations->total();
            $totalGuests = \App\Models\DelegationMember::whereIn('delegation_id', \App\Models\Delegation::pluck('id'))->count();
            $upcoming = \App\Models\Delegation::where('work_date_from', '>=', now()->toDateString())->count();
            $completed = \App\Models\Delegation::where('status', 'completed')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $total }}</p>
                    <p class="text-xs text-slate-500">Tổng số đoàn khách</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalGuests }}</p>
                    <p class="text-xs text-slate-500">Tổng số khách đã đón</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $upcoming }}</p>
                    <p class="text-xs text-slate-500">Đoàn sắp tới / đang diễn ra</p>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[220px]">
                    <label class="text-xs font-medium text-slate-500">Tìm kiếm</label>
                    <div class="relative mt-1">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Số công văn, đơn vị tiếp đón, nội dung..."
                               class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-500">Trạng thái</label>
                    <select name="status" class="mt-1 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tất cả</option>
                        <option value="draft" @selected(request('status')==='draft')>Dự thảo</option>
                        <option value="sent" @selected(request('status')==='sent')>Đã gửi công văn</option>
                        <option value="completed" @selected(request('status')==='completed')>Hoàn tất</option>
                    </select>
                </div>
                <button class="bg-slate-800 text-white text-sm px-4 py-2 rounded-lg hover:bg-slate-900 transition">Lọc</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.delegations.index') }}" class="text-sm text-slate-500 hover:text-slate-700 px-2">Xóa lọc</a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-semibold text-slate-600">Đoàn khách</th>
                        <th class="px-5 py-3.5 text-left font-semibold text-slate-600">Đơn vị tiếp đón</th>
                        <th class="px-5 py-3.5 text-left font-semibold text-slate-600">Thời gian làm việc</th>
                        <th class="px-5 py-3.5 text-center font-semibold text-slate-600">Số khách</th>
                        <th class="px-5 py-3.5 text-center font-semibold text-slate-600">Trạng thái</th>
                        <th class="px-5 py-3.5 text-right font-semibold text-slate-600">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($delegations as $delegation)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $delegation->document_no ?: 'Chưa có số công văn' }}</p>
                                        <p class="text-xs text-slate-400 truncate max-w-[220px]">{{ $delegation->purpose ?: '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $delegation->host_unit ?: '—' }}</td>
                            <td class="px-5 py-4 text-slate-600">
                                @if($delegation->work_date_from)
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $delegation->work_date_from->format('d/m/Y') }}
                                        @if($delegation->work_date_to && !$delegation->work_date_to->eq($delegation->work_date_from))
                                            - {{ $delegation->work_date_to->format('d/m/Y') }}
                                        @endif
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                    {{ $delegation->members_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $statusMap = [
                                        'draft' => ['Dự thảo', 'bg-slate-100 text-slate-600'],
                                        'sent' => ['Đã gửi công văn', 'bg-amber-100 text-amber-700'],
                                        'completed' => ['Hoàn tất', 'bg-emerald-100 text-emerald-700'],
                                    ];
                                    [$label, $cls] = $statusMap[$delegation->status] ?? ['—', 'bg-slate-100 text-slate-600'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.delegations.show', $delegation) }}"
                                       title="Xem"
                                       class="p-2 rounded-lg text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.delegations.edit', $delegation) }}"
                                       title="Sửa"
                                       class="p-2 rounded-lg text-slate-500 hover:bg-amber-50 hover:text-amber-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.delegations.export', $delegation) }}"
                                       title="Xuất Excel"
                                       class="p-2 rounded-lg text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                                    </a>
                                    <form action="{{ route('admin.delegations.destroy', $delegation) }}" method="POST" class="inline" onsubmit="return confirm('Xóa đoàn khách này?')">
                                        @csrf @method('DELETE')
                                        <button title="Xóa" class="p-2 rounded-lg text-slate-500 hover:bg-red-50 hover:text-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-2.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/></svg>
                                    <p class="text-sm">Chưa có đoàn khách nước ngoài nào.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $delegations->links() }}
    </div>
</x-app-layout>
