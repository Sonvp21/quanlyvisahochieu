<x-app-layout>
<div class="p-6 max-w-7xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Báo cáo Email</h1>
            <p class="text-sm text-slate-500 mt-0.5">Lịch sử gửi thông báo hộ chiếu và visa</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Xóa cũ > 30 ngày --}}
            <form method="POST" action="{{ route('admin.notification-reports.delete-old') }}"
                onsubmit="return confirm('Xóa tất cả báo cáo cũ hơn 30 ngày?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Xóa cũ >30 ngày
                </button>
            </form>
            {{-- Xóa tất cả --}}
            <form method="POST" action="{{ route('admin.notification-reports.delete-all') }}"
                onsubmit="return confirm('Xóa toàn bộ báo cáo? Không thể hoàn tác.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Xóa tất cả
                </button>
            </form>
        </div>
    </div>

    <x-alert-success />

    {{-- SUMMARY CARDS --}}
    @if($reports->count() > 0)
    @php
        $totalSent = $reports->sum('total_count');
        $totalPassport = $reports->sum('passport_count');
        $totalVisa = $reports->sum('visa_count');
        $avgDuration = round($reports->avg('duration_seconds'), 1);
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="text-xs text-slate-400 mb-1">Tổng email đã gửi</div>
            <div class="text-2xl font-bold text-slate-800">{{ $totalSent }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="text-xs text-slate-400 mb-1">Email hộ chiếu</div>
            <div class="text-2xl font-bold text-blue-700">{{ $totalPassport }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="text-xs text-slate-400 mb-1">Email visa</div>
            <div class="text-2xl font-bold text-violet-700">{{ $totalVisa }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            <div class="text-xs text-slate-400 mb-1">Thời gian TB/lần</div>
            <div class="text-2xl font-bold text-slate-800">{{ $avgDuration }}s</div>
        </div>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Thời gian chạy</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Hộ chiếu</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Visa</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Tổng</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Thời gian xử lý</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $i => $report)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($report->run_at)->format('d/m/Y') }}</div>
                            <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($report->run_at)->format('H:i:s') }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                {{ $report->passport_count > 0 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-400' }}">
                                {{ $report->passport_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                {{ $report->visa_count > 0 ? 'bg-violet-100 text-violet-700' : 'bg-slate-100 text-slate-400' }}">
                                {{ $report->visa_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-bold
                                {{ $report->total_count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $report->total_count }} email
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-slate-500">
                            {{ $report->duration_seconds }}s
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.notification-reports.show', $report->id) }}"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Xem chi tiết">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.notification-reports.destroy', $report->id) }}"
                                    onsubmit="return confirm('Xóa báo cáo này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-600">Chưa có báo cáo nào</p>
                                    <p class="text-sm text-slate-400 mt-0.5">Chạy lệnh <code class="bg-slate-100 px-1 rounded text-xs">php artisan notifications:send-expiry</code> để gửi thông báo</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($reports, 'hasPages') && $reports->hasPages())
    <div class="flex justify-center">{{ $reports->links() }}</div>
    @endif

</div>
</x-app-layout>