<x-app-layout>
<div class="p-6 max-w-7xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tổng quan hệ thống</h1>
            <p class="text-sm text-slate-500 mt-0.5">Cập nhật lúc {{ now()->format('H:i, d/m/Y') }}</p>
        </div>
        <span class="hidden sm:flex items-center gap-2 text-xs font-medium text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full">
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
            Hệ thống hoạt động
        </span>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-slate-400">Sinh viên</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $totalStudents }}</div>
            <div class="text-xs text-slate-500 mt-1">Tổng số đang quản lý</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-xs text-slate-400">Tài khoản</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $totalUsers }}</div>
            <div class="text-xs text-slate-500 mt-1">Người dùng hệ thống</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <span class="text-xs text-slate-400">Cần xử lý</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $passportExpiringSoon + $passportExpired + $visaExpiringSoon + $visaExpired }}</div>
            <div class="text-xs text-slate-500 mt-1">Hộ chiếu + Visa</div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-slate-400">Cập nhật</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $recentlyUpdatedStudents }}</div>
            <div class="text-xs text-slate-500 mt-1">Trong 7 ngày qua</div>
        </div>
    </div>

    {{-- PASSPORT + VISA STATUS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- PASSPORT --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-800">Hộ chiếu</div>
                        <div class="text-xs text-slate-400">Tổng: {{ $passportValid + $passportExpiringSoon + $passportExpired }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.students.index', ['filter' => 'passport_expired']) }}"
                    class="text-xs text-blue-600 hover:underline font-medium">Xem tất cả</a>
            </div>
            <div class="p-5 space-y-3">
                @php
                    $pTotal = max(1, $passportValid + $passportExpiringSoon + $passportExpired);
                    $pValidPct = round($passportValid / $pTotal * 100);
                    $pExpirePct = round($passportExpiringSoon / $pTotal * 100);
                    $pExpiredPct = 100 - $pValidPct - $pExpirePct;
                @endphp
                <div class="flex h-2 rounded-full overflow-hidden bg-slate-100">
                    <div class="bg-emerald-500" style="width:{{ $pValidPct }}%"></div>
                    <div class="bg-amber-400" style="width:{{ $pExpirePct }}%"></div>
                    <div class="bg-red-500" style="width:{{ $pExpiredPct }}%"></div>
                </div>
                <div class="flex gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Còn hạn</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Sắp hết</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>Hết hạn</span>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-1">
                    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3 text-center">
                        <div class="text-2xl font-bold text-emerald-700">{{ $passportValid }}</div>
                        <div class="text-xs text-emerald-600 mt-0.5">Còn hạn</div>
                    </div>
                    <div class="rounded-xl bg-amber-50 border border-amber-100 p-3 text-center">
                        <div class="text-2xl font-bold text-amber-700">{{ $passportExpiringSoon }}</div>
                        <div class="text-xs text-amber-600 mt-0.5">Sắp hết</div>
                    </div>
                    <div class="rounded-xl bg-red-50 border border-red-100 p-3 text-center">
                        <div class="text-2xl font-bold text-red-700">{{ $passportExpired }}</div>
                        <div class="text-xs text-red-600 mt-0.5">Hết hạn</div>
                    </div>
                </div>
                @if($passportExpiringSoon + $passportExpired > 0)
                <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>{{ $passportExpiringSoon + $passportExpired }}</strong> hộ chiếu cần xử lý</span>
                </div>
                @endif
            </div>
        </div>

        {{-- VISA --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-violet-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-slate-800">Visa</div>
                        <div class="text-xs text-slate-400">Tổng: {{ $visaValid + $visaExpiringSoon + $visaExpired }}</div>
                    </div>
                </div>
                <a href="{{ route('admin.students.index', ['filter' => 'visa_expired']) }}"
                    class="text-xs text-violet-600 hover:underline font-medium">Xem tất cả</a>
            </div>
            <div class="p-5 space-y-3">
                @php
                    $vTotal = max(1, $visaValid + $visaExpiringSoon + $visaExpired);
                    $vValidPct = round($visaValid / $vTotal * 100);
                    $vExpirePct = round($visaExpiringSoon / $vTotal * 100);
                    $vExpiredPct = 100 - $vValidPct - $vExpirePct;
                @endphp
                <div class="flex h-2 rounded-full overflow-hidden bg-slate-100">
                    <div class="bg-emerald-500" style="width:{{ $vValidPct }}%"></div>
                    <div class="bg-amber-400" style="width:{{ $vExpirePct }}%"></div>
                    <div class="bg-red-500" style="width:{{ $vExpiredPct }}%"></div>
                </div>
                <div class="flex gap-4 text-xs text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Còn hạn</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Sắp hết</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>Hết hạn</span>
                </div>
                <div class="grid grid-cols-3 gap-3 pt-1">
                    <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-3 text-center">
                        <div class="text-2xl font-bold text-emerald-700">{{ $visaValid }}</div>
                        <div class="text-xs text-emerald-600 mt-0.5">Còn hạn</div>
                    </div>
                    <div class="rounded-xl bg-amber-50 border border-amber-100 p-3 text-center">
                        <div class="text-2xl font-bold text-amber-700">{{ $visaExpiringSoon }}</div>
                        <div class="text-xs text-amber-600 mt-0.5">Sắp hết</div>
                    </div>
                    <div class="rounded-xl bg-red-50 border border-red-100 p-3 text-center">
                        <div class="text-2xl font-bold text-red-700">{{ $visaExpired }}</div>
                        <div class="text-xs text-red-600 mt-0.5">Hết hạn</div>
                    </div>
                </div>
                @if($visaExpiringSoon + $visaExpired > 0)
                <div class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>{{ $visaExpiringSoon + $visaExpired }}</strong> visa cần xử lý</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5">
        <div class="text-sm font-semibold text-slate-800 mb-4">Thao tác nhanh</div>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <a href="{{ route('admin.students.index') }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 transition-all group">
                <div class="w-10 h-10 bg-blue-100 group-hover:bg-blue-600 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 group-hover:text-blue-700 transition-colors text-center">Danh sách sinh viên</span>
            </a>
 
            <a href="{{ route('admin.students.create') }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-200 border border-slate-200 transition-all group">
                <div class="w-10 h-10 bg-emerald-100 group-hover:bg-emerald-600 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 group-hover:text-emerald-700 transition-colors text-center">Thêm sinh viên</span>
            </a>
 
            <a href="{{ route('admin.students.index', ['filter' => 'passport_expiring']) }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-amber-50 hover:border-amber-200 border border-slate-200 transition-all group">
                <div class="w-10 h-10 bg-amber-100 group-hover:bg-amber-500 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-amber-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 group-hover:text-amber-700 transition-colors text-center">Sắp hết hạn</span>
            </a>
 
            <a href="{{ route('admin.notification-reports.index') }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-violet-50 hover:border-violet-200 border border-slate-200 transition-all group">
                <div class="w-10 h-10 bg-violet-100 group-hover:bg-violet-600 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-violet-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 group-hover:text-violet-700 transition-colors text-center">Báo cáo email</span>
            </a>
 
            <a href="{{ route('admin.students.export') }}"
                class="flex flex-col items-center gap-2 p-4 rounded-xl bg-slate-50 hover:bg-teal-50 hover:border-teal-200 border border-slate-200 transition-all group">
                <div class="w-10 h-10 bg-teal-100 group-hover:bg-teal-600 rounded-xl flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 text-teal-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-600 group-hover:text-teal-700 transition-colors text-center">Xuất Excel</span>
            </a>
        </div>
    </div>

</div>
</x-app-layout>
