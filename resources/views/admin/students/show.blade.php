<x-app-layout>
<div class="p-6 max-w-5xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.index') }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">{{ $student->full_name }}</h1>
                <p class="text-xs text-slate-500">{{ $student->student_code }} &middot; {{ $student->nationality ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.students.edit', $student) }}"
                class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Chỉnh sửa
            </a>
            <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                onsubmit="return confirm('Xóa sinh viên này? Thao tác không thể hoàn tác.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-lg transition-colors border border-red-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Xóa
                </button>
            </form>
        </div>
    </div>

    {{-- THÔNG TIN SINH VIÊN --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-slate-800">Thông tin sinh viên</h2>
        </div>

        <div class="p-5">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($student->full_name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-lg font-bold text-slate-800">{{ $student->full_name }}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $student->student_code }}</span>
                        @if($student->student_type)
                            @php $typeMap=['exchange'=>['Trao đổi','bg-blue-100 text-blue-700'],'regular'=>['Chính quy','bg-green-100 text-green-700'],'postgraduate'=>['Sau ĐH','bg-purple-100 text-purple-700']]; [$tl,$tc]=$typeMap[$student->student_type]??['?','bg-gray-100 text-gray-600']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $tc }}">{{ $tl }}</span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 mt-1">{{ $student->user->email ?? '' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @php
                    $fields = [
                        ['Ngày sinh', $student->date_of_birth ? date('d/m/Y', strtotime($student->date_of_birth)) : null],
                        ['Giới tính', match($student->gender){ 'male'=>'Nam','female'=>'Nữ','other'=>'Khác',default=>null }],
                        ['Quốc tịch', $student->nationality],
                        ['Điện thoại', $student->phone],
                        ['Ngành học', $student->major],
                        ['Ngày nhập học', $student->enrollment_date ? date('d/m/Y', strtotime($student->enrollment_date)) : null],
                    ];
                @endphp
                @foreach($fields as [$label, $value])
                <div class="bg-slate-50 rounded-xl p-3">
                    <div class="text-xs text-slate-400 mb-1">{{ $label }}</div>
                    <div class="text-sm font-semibold text-slate-700">{{ $value ?? '—' }}</div>
                </div>
                @endforeach
                @if($student->address)
                <div class="bg-slate-50 rounded-xl p-3 col-span-2 md:col-span-3">
                    <div class="text-xs text-slate-400 mb-1">Địa chỉ tạm trú</div>
                    <div class="text-sm font-semibold text-slate-700">{{ $student->address }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- HỘ CHIẾU + VISA --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- HỘ CHIẾU --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Hộ chiếu</h2>
                </div>
                @php
                    $pColor = $student->getPassportStatusColor();
                    $pBadge = match($pColor){ 'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500' };
                @endphp
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pBadge }}">
                    {{ $student->getPassportStatusText() }}
                </span>
            </div>

            @if($student->passport)
            <div class="p-5 space-y-3">
                @php $pDays = $student->getDaysUntilPassportExpiry(); @endphp

                {{-- Countdown bar --}}
                @if($pDays !== null)
                <div class="rounded-xl p-3 {{ $pDays < 0 ? 'bg-red-50 border border-red-200' : ($pDays <= 30 ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200') }}">
                    <div class="text-xs font-semibold {{ $pDays < 0 ? 'text-red-700' : ($pDays <= 30 ? 'text-amber-700' : 'text-emerald-700') }} mb-1">
                        {{ $pDays < 0 ? 'Đã hết hạn' : ($pDays <= 30 ? 'Sắp hết hạn' : 'Còn hạn') }}
                    </div>
                    <div id="passport-countdown" class="text-lg font-bold {{ $pDays < 0 ? 'text-red-600' : ($pDays <= 30 ? 'text-amber-600' : 'text-emerald-600') }}"
                        data-expiry="{{ \Carbon\Carbon::parse($student->passport->expiry_date)->endOfDay()->timestamp * 1000 }}">
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    @php
                        $pFields = [
                            ['Số hộ chiếu', $student->passport->passport_number],
                            ['Quốc gia cấp', $student->passport->country_of_issue],
                            ['Nơi cấp', $student->passport->place_of_issue],
                            ['Ngày cấp', $student->passport->issue_date ? date('d/m/Y', strtotime($student->passport->issue_date)) : null],
                            ['Ngày hết hạn', date('d/m/Y', strtotime($student->passport->expiry_date))],
                            ['Cập nhật bởi', $student->passport->last_updated_by === 'admin' ? 'Admin' : 'Sinh viên'],
                        ];
                    @endphp
                    @foreach($pFields as [$label, $value])
                    <div class="bg-slate-50 rounded-xl p-3">
                        <div class="text-xs text-slate-400 mb-1">{{ $label }}</div>
                        <div class="text-sm font-semibold text-slate-700">{{ $value ?? '—' }}</div>
                    </div>
                    @endforeach
                </div>

                @if($student->passport->image)
                <div>
                    <p class="text-xs text-slate-400 mb-2">Ảnh hộ chiếu</p>
                    <img src="{{ asset('storage/' . $student->passport->image) }}"
                        class="w-full rounded-xl border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity"
                        onclick="openModal(this.src)" alt="Passport">
                </div>
                @endif
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium">Chưa có thông tin hộ chiếu</p>
            </div>
            @endif
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
                    <h2 class="text-sm font-semibold text-slate-800">Visa</h2>
                </div>
                @php
                    $vColor = $student->getVisaStatusColor();
                    $vBadge = match($vColor){ 'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500' };
                @endphp
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $vBadge }}">
                    {{ $student->getVisaStatusText() }}
                </span>
            </div>

            @if($student->visa)
            <div class="p-5 space-y-3">
                @php $vDays = $student->getDaysUntilVisaExpiry(); @endphp

                @if($vDays !== null)
                <div class="rounded-xl p-3 {{ $vDays < 0 ? 'bg-red-50 border border-red-200' : ($vDays <= 30 ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200') }}">
                    <div class="text-xs font-semibold {{ $vDays < 0 ? 'text-red-700' : ($vDays <= 30 ? 'text-amber-700' : 'text-emerald-700') }} mb-1">
                        {{ $vDays < 0 ? 'Đã hết hạn' : ($vDays <= 30 ? 'Sắp hết hạn' : 'Còn hạn') }}
                    </div>
                    <div id="visa-countdown" class="text-lg font-bold {{ $vDays < 0 ? 'text-red-600' : ($vDays <= 30 ? 'text-amber-600' : 'text-emerald-600') }}"
                        data-expiry="{{ \Carbon\Carbon::parse($student->visa->expiry_date)->endOfDay()->timestamp * 1000 }}">
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    @php
                        $vFields = [
                            ['Loại visa', $student->visa->visa_type],
                            ['Số visa', $student->visa->visa_number],
                            ['Quốc gia', $student->visa->country],
                            ['Loại nhập cảnh', $student->visa->entry_type === 'single' ? 'Đơn' : 'Nhiều lần'],
                            ['Ngày cấp', $student->visa->issue_date ? date('d/m/Y', strtotime($student->visa->issue_date)) : null],
                            ['Ngày hết hạn', date('d/m/Y', strtotime($student->visa->expiry_date))],
                            ['Trạng thái', match($student->visa->status){ 'valid'=>'Còn hạn','expired'=>'Hết hạn','cancelled'=>'Đã hủy',default=>$student->visa->status }],
                            ['Cập nhật bởi', $student->visa->last_updated_by === 'admin' ? 'Admin' : 'Sinh viên'],
                        ];
                    @endphp
                    @foreach($vFields as [$label, $value])
                    <div class="bg-slate-50 rounded-xl p-3">
                        <div class="text-xs text-slate-400 mb-1">{{ $label }}</div>
                        <div class="text-sm font-semibold text-slate-700">{{ $value ?? '—' }}</div>
                    </div>
                    @endforeach
                </div>

                @if($student->visa->image)
                <div>
                    <p class="text-xs text-slate-400 mb-2">Ảnh visa</p>
                    <img src="{{ asset('storage/' . $student->visa->image) }}"
                        class="w-full rounded-xl border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity"
                        onclick="openModal(this.src)" alt="Visa">
                </div>
                @endif
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p class="text-sm font-medium">Chưa có thông tin visa</p>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- IMAGE MODAL --}}
<div id="imgModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4" onclick="closeModal(event)">
    <div class="relative max-w-3xl w-full">
        <button onclick="document.getElementById('imgModal').classList.add('hidden');document.getElementById('imgModal').classList.remove('flex')"
            class="absolute -top-10 right-0 w-9 h-9 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImg" src="" class="w-full rounded-2xl shadow-2xl" alt="">
    </div>
</div>

{{-- COUNTDOWN SCRIPT --}}
<script>
function countdown(elId) {
    const el = document.getElementById(elId);
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
        el.textContent = (expired ? 'Đã hết hạn ' : 'Còn ') + d + ' ngày ' + h + ' giờ ' + m + ' phút ' + s + ' giây';
    }
    update();
    setInterval(update, 1000);
}
countdown('passport-countdown');
countdown('visa-countdown');

function openModal(src) {
    document.getElementById('modalImg').src = src;
    const m = document.getElementById('imgModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeModal(e) {
    if (e.target === document.getElementById('imgModal')) {
        document.getElementById('imgModal').classList.add('hidden');
        document.getElementById('imgModal').classList.remove('flex');
    }
}
</script>
</x-app-layout>
