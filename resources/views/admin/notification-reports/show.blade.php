<x-app-layout>
<div class="p-6 max-w-4xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.notification-reports.index') }}"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Chi tiết báo cáo</h1>
            <p class="text-xs text-slate-500">
                Chạy lúc {{ \Carbon\Carbon::parse($report->run_at)->format('H:i:s, d/m/Y') }}
                &middot; Mất {{ $report->duration_seconds }}s
            </p>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center">
            <div class="text-xs text-slate-400 mb-1">Hộ chiếu</div>
            <div class="text-3xl font-bold text-blue-700">{{ $report->passport_count }}</div>
            <div class="text-xs text-slate-500 mt-1">email đã gửi</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center">
            <div class="text-xs text-slate-400 mb-1">Visa</div>
            <div class="text-3xl font-bold text-violet-700">{{ $report->visa_count }}</div>
            <div class="text-xs text-slate-500 mt-1">email đã gửi</div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-5 text-center">
            <div class="text-xs text-slate-400 mb-1">Tổng cộng</div>
            <div class="text-3xl font-bold {{ $report->total_count > 0 ? 'text-emerald-700' : 'text-slate-400' }}">
                {{ $report->total_count }}
            </div>
            <div class="text-xs text-slate-500 mt-1">email đã gửi</div>
        </div>
    </div>

    {{-- DETAILS --}}
    @php
        $details = is_string($report->details) ? json_decode($report->details, true) : $report->details;
    @endphp

    @if($details && count($details) > 0)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
            <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-slate-800">Danh sách email đã gửi</h2>
            <span class="text-xs text-slate-400">({{ count($details) }} email)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Sinh viên</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Loại</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Thời hạn</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($details as $i => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-xs text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800">{{ $item['student_name'] ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm text-slate-600">{{ $item['student_email'] ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(($item['type'] ?? '') === 'passport')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                    Hộ chiếu
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">
                                    Visa
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(($item['status'] ?? '') === 'expired')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                    Đã hết hạn
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                    Sắp hết hạn
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $item['time_detail'] ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @else
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="font-medium text-slate-600">Không có email nào được gửi trong lần chạy này</p>
        <p class="text-sm text-slate-400 mt-1">Có thể không có hộ chiếu/visa nào sắp hết hạn</p>
    </div>
    @endif

    {{-- DELETE --}}
    <div class="flex justify-end">
        <form method="POST" action="{{ route('admin.notification-reports.destroy', $report->id) }}"
            onsubmit="return confirm('Xóa báo cáo này?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Xóa báo cáo này
            </button>
        </form>
    </div>

</div>
</x-app-layout>