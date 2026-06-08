<x-app-layout>
<div class="p-6 max-w-2xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Hộ chiếu của tôi</h1>
            <p class="text-xs text-slate-500">Xem và cập nhật thông tin hộ chiếu</p>
        </div>
    </div>

    <x-alert-success />

    @php
        $passport = $student->passport;
        $pColor = $student->getPassportStatusColor();
        $pBadge = match($pColor){ 'green'=>'bg-emerald-100 text-emerald-700 border-emerald-200','yellow'=>'bg-amber-100 text-amber-700 border-amber-200','red'=>'bg-red-100 text-red-700 border-red-200',default=>'bg-slate-100 text-slate-500 border-slate-200' };
        $pDays = $student->getDaysUntilPassportExpiry();
    @endphp

    {{-- CURRENT PASSPORT INFO --}}
    @if($passport)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Thông tin hiện tại</h2>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $pBadge }}">
                {{ $student->getPassportStatusText() }}
            </span>
        </div>

        <div class="p-5 space-y-4">
            {{-- Countdown --}}
            @if($pDays !== null)
            <div class="rounded-xl p-4 text-center {{ $pDays < 0 ? 'bg-red-50 border border-red-200' : ($pDays <= 30 ? 'bg-amber-50 border border-amber-200' : 'bg-emerald-50 border border-emerald-200') }}">
                <div class="text-xs font-semibold {{ $pDays < 0 ? 'text-red-600' : ($pDays <= 30 ? 'text-amber-600' : 'text-emerald-600') }} mb-1">
                    {{ $pDays < 0 ? 'Đã hết hạn' : 'Thời gian còn lại' }}
                </div>
                <div id="passport-countdown" class="text-xl font-bold {{ $pDays < 0 ? 'text-red-600' : ($pDays <= 30 ? 'text-amber-600' : 'text-emerald-600') }}"
                    data-expiry="{{ \Carbon\Carbon::parse($passport->expiry_date)->endOfDay()->timestamp * 1000 }}">
                </div>
            </div>
            @endif

            {{-- Fields --}}
            <div class="grid grid-cols-2 gap-3">
                @php
                    $fields = [
                        ['Số hộ chiếu', $passport->passport_number],
                        ['Quốc gia cấp', $passport->country_of_issue],
                        ['Nơi cấp', $passport->place_of_issue],
                        ['Ngày cấp', $passport->issue_date ? date('d/m/Y', strtotime($passport->issue_date)) : null],
                        ['Ngày hết hạn', date('d/m/Y', strtotime($passport->expiry_date))],
                        ['Cập nhật lúc', date('d/m/Y H:i', strtotime($passport->updated_at))],
                    ];
                @endphp
                @foreach($fields as [$label, $value])
                <div class="bg-slate-50 rounded-xl p-3">
                    <div class="text-xs text-slate-400 mb-1">{{ $label }}</div>
                    <div class="text-sm font-semibold text-slate-700">{{ $value ?? '—' }}</div>
                </div>
                @endforeach
            </div>

            {{-- Image --}}
            @if($passport->image)
            <div>
                <div class="text-xs text-slate-400 mb-2">Ảnh hộ chiếu</div>
                <img src="{{ asset('storage/'.$passport->image) }}"
                    class="w-full rounded-xl border border-slate-200 cursor-pointer hover:opacity-90 transition-opacity"
                    onclick="openModal(this.src)" alt="Passport">
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="font-medium text-slate-600">Chưa có thông tin hộ chiếu</p>
        <p class="text-sm text-slate-400 mt-1">Điền form bên dưới để thêm thông tin</p>
    </div>
    @endif

    {{-- UPDATE FORM --}}
    <form method="POST" action="{{ route('student.profile.passport.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">{{ $passport ? 'Cập nhật hộ chiếu' : 'Thêm hộ chiếu' }}</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số hộ chiếu <span class="text-red-500">*</span></label>
                    <input type="text" name="passport_number" value="{{ old('passport_number', $passport->passport_number ?? '') }}" required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_number') border-red-400 @enderror"/>
                    @error('passport_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                    <input type="text" name="country_of_issue" value="{{ old('country_of_issue', $passport->country_of_issue ?? '') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nơi cấp</label>
                    <input type="text" name="place_of_issue" value="{{ old('place_of_issue', $passport->place_of_issue ?? '') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                    <input type="date" name="issue_date" value="{{ old('issue_date', $passport->issue_date ?? '') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $passport->expiry_date ?? '') }}" required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('expiry_date') border-red-400 @enderror"/>
                    @error('expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh hộ chiếu</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100"/>
                </div>
            </div>
            <div class="px-5 pb-5 flex justify-end">
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                    {{ $passport ? 'Cập nhật hộ chiếu' : 'Thêm hộ chiếu' }}
                </button>
            </div>
        </div>
    </form>

</div>

{{-- IMAGE MODAL --}}
<div id="imgModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4"
    onclick="if(event.target===this)closeModal()">
    <div class="relative max-w-2xl w-full">
        <button onclick="closeModal()"
            class="absolute -top-10 right-0 w-9 h-9 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="modalImg" src="" class="w-full rounded-2xl shadow-2xl" alt="">
    </div>
</div>

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
        el.textContent = (expired?'Đã hết hạn ':'Còn ')+d+' ngày '+h+' giờ '+m+' phút '+s+' giây';
    }
    update(); setInterval(update, 1000);
}
countdown('passport-countdown');
function openModal(src){ document.getElementById('modalImg').src=src; const m=document.getElementById('imgModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(){ const m=document.getElementById('imgModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
</script>
</x-app-layout>