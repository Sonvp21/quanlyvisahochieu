<x-app-layout>
<div class="p-6 max-w-5xl mx-auto space-y-5">

    {{-- WELCOME --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Xin chào, {{ $student->full_name }}!</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Mã sinh viên: <span class="font-semibold text-blue-600">{{ $student->student_code }}</span>
                &middot; {{ now()->locale('vi')->isoFormat('dddd, D/M/YYYY') }}
            </p>
        </div>
        <a href="{{ route('student.profile.show') }}"
            class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Cập nhật hồ sơ
        </a>
    </div>

    {{-- ALERT nếu có vấn đề --}}
    @php
        $hasIssue = in_array($student->getPassportStatus(), ['expired','expiring_soon']) || in_array($student->getVisaStatus(), ['expired','expiring_soon']);
    @endphp
    @if($hasIssue)
    <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span>Bạn có hộ chiếu hoặc visa cần xử lý. Vui lòng liên hệ phòng Quan hệ Quốc tế sớm nhất có thể.</span>
    </div>
    @endif

    {{-- STATUS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Hộ chiếu --}}
        @php
            $pColor = $student->getPassportStatusColor();
            $pBg = match($pColor){ 'green'=>'bg-emerald-50 border-emerald-200','yellow'=>'bg-amber-50 border-amber-200','red'=>'bg-red-50 border-red-200',default=>'bg-slate-50 border-slate-200' };
            $pIcon = match($pColor){ 'green'=>'text-emerald-600 bg-emerald-100','yellow'=>'text-amber-600 bg-amber-100','red'=>'text-red-600 bg-red-100',default=>'text-slate-500 bg-slate-100' };
            $pBadge = match($pColor){ 'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500' };
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl {{ $pIcon }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pBadge }}">{{ $student->getPassportStatusText() }}</span>
            </div>
            <div class="text-sm font-bold text-slate-800 mb-1">Hộ chiếu</div>
            <div class="text-xs text-slate-500">
                @if($student->passport)
                    {{ $student->passport->passport_number }}
                    <br>Hết hạn: {{ date('d/m/Y', strtotime($student->passport->expiry_date)) }}
                @else
                    Chưa có thông tin
                @endif
            </div>
        </div>

        {{-- Visa --}}
        @php
            $vColor = $student->getVisaStatusColor();
            $vBg = match($vColor){ 'green'=>'bg-emerald-50 border-emerald-200','yellow'=>'bg-amber-50 border-amber-200','red'=>'bg-red-50 border-red-200',default=>'bg-slate-50 border-slate-200' };
            $vIcon = match($vColor){ 'green'=>'text-emerald-600 bg-emerald-100','yellow'=>'text-amber-600 bg-amber-100','red'=>'text-red-600 bg-red-100',default=>'text-slate-500 bg-slate-100' };
            $vBadge = match($vColor){ 'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500' };
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl {{ $vIcon }} flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $vBadge }}">{{ $student->getVisaStatusText() }}</span>
            </div>
            <div class="text-sm font-bold text-slate-800 mb-1">Visa</div>
            <div class="text-xs text-slate-500">
                @if($student->visa)
                    {{ $student->visa->visa_number ?? $student->visa->visa_type }}
                    <br>Hết hạn: {{ date('d/m/Y', strtotime($student->visa->expiry_date)) }}
                @else
                    Chưa có thông tin
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700">Email</span>
            </div>
            <div class="text-sm font-bold text-slate-800 mb-1">Tài khoản</div>
            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</div>
        </div>
    </div>

    {{-- COUNTDOWN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Hộ chiếu countdown --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Hộ chiếu</h2>
            </div>
            @if($student->passport)
            <div class="p-5 space-y-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Số: <strong class="text-slate-700">{{ $student->passport->passport_number }}</strong></span>
                        <span>HH: <strong class="text-slate-700">{{ date('d/m/Y', strtotime($student->passport->expiry_date)) }}</strong></span>
                    </div>
                    @if($student->passport->issue_date)
                    <div class="text-xs text-slate-400">Ngày cấp: {{ date('d/m/Y', strtotime($student->passport->issue_date)) }}</div>
                    @endif
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <div class="text-xs text-slate-500 mb-1">Thời gian còn lại</div>
                    <div id="passport-countdown" class="text-xl font-bold text-slate-800"
                        data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}">
                    </div>
                </div>
                <div id="passport-warning"></div>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">Chưa có thông tin hộ chiếu</p>
            </div>
            @endif
        </div>

        {{-- Visa countdown --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Visa</h2>
            </div>
            @if($student->visa)
            <div class="p-5 space-y-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-xs text-slate-500">
                        <span>Số: <strong class="text-slate-700">{{ $student->visa->visa_number ?? $student->visa->visa_type }}</strong></span>
                        <span>HH: <strong class="text-slate-700">{{ date('d/m/Y', strtotime($student->visa->expiry_date)) }}</strong></span>
                    </div>
                    <div class="text-xs text-slate-400">
                        Loại: {{ $student->visa->visa_type }}
                        &middot; {{ $student->visa->entry_type === 'single' ? 'Đơn lần' : 'Nhiều lần' }}
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-center">
                    <div class="text-xs text-slate-500 mb-1">Thời gian còn lại</div>
                    <div id="visa-countdown" class="text-xl font-bold text-slate-800"
                        data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}">
                    </div>
                </div>
                <div id="visa-warning"></div>
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p class="text-sm">Chưa có thông tin visa</p>
            </div>
            @endif
        </div>
    </div>

    {{-- CTA mobile --}}
    <div class="sm:hidden">
        <a href="{{ route('student.profile.show') }}"
            class="flex items-center justify-center gap-2 w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Cập nhật hồ sơ
        </a>
    </div>

</div>

<script>
function countdown(elId, warnId, label) {
    const el = document.getElementById(elId);
    const warn = document.getElementById(warnId);
    if (!el) return;
    const expiry = Number(el.dataset.expiry);

    function update() {
        let diff = expiry - Date.now();
        const expired = diff < 0;
        diff = Math.abs(diff);
        const d = Math.floor(diff/86400000);
        const h = Math.floor((diff%86400000)/3600000);
        const m = Math.floor((diff%3600000)/60000);
        const s = Math.floor((diff%60000)/1000);

        if (expired) {
            el.textContent = `Đã hết hạn ${d} ngày ${h} giờ ${m} phút ${s} giây`;
            el.className = 'text-xl font-bold text-red-600';
            if (warn) warn.innerHTML = `<div class="flex items-center gap-2 px-3 py-2 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700"><svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span>${label} đã hết hạn. Vui lòng liên hệ phòng Quan hệ Quốc tế!</span></div>`;
        } else if (d < 30) {
            el.textContent = `Còn ${d} ngày ${h} giờ ${m} phút ${s} giây`;
            el.className = 'text-xl font-bold text-amber-600';
            if (warn) warn.innerHTML = `<div class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-700"><svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg><span>${label} sắp hết hạn. Hãy chuẩn bị gia hạn sớm!</span></div>`;
        } else {
            el.textContent = `Còn ${d} ngày ${h} giờ ${m} phút ${s} giây`;
            el.className = 'text-xl font-bold text-emerald-600';
            if (warn) warn.innerHTML = '';
        }
    }
    update();
    setInterval(update, 1000);
}
countdown('passport-countdown', 'passport-warning', 'Hộ chiếu');
countdown('visa-countdown', 'visa-warning', 'Visa');
</script>
</x-app-layout>