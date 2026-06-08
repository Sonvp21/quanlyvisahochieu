<x-app-layout>
<div class="p-6 max-w-3xl mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Hồ sơ của tôi</h1>
            <p class="text-xs text-slate-500">Cập nhật thông tin cá nhân, hộ chiếu và visa</p>
        </div>
        <a href="{{ route('student.dashboard') }}"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    <x-alert-success />

    @if($errors->any())
    <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- THÔNG TIN SINH VIÊN --}}
    <form method="POST" action="{{ route('student.profile.student.update') }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Thông tin cá nhân</h2>
                <span class="text-xs text-slate-400 ml-1">(chỉ đọc một số trường)</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Họ và tên</label>
                        <input type="text" value="{{ $student->full_name }}" disabled
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 text-slate-500 cursor-not-allowed"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Mã sinh viên</label>
                        <input type="text" value="{{ $student->student_code }}" disabled
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 text-slate-500 cursor-not-allowed"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc tịch</label>
                        <input type="text" value="{{ $student->nationality }}" disabled
                            class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50 text-slate-500 cursor-not-allowed"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Địa chỉ tạm trú tại Việt Nam</label>
                        <textarea name="address" rows="2"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition resize-none">{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                        Lưu thông tin
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- HỘ CHIẾU --}}
    <form method="POST" action="{{ route('student.profile.passport.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Hộ chiếu</h2>
                </div>
                @if($student->passport)
                    @php $pColor = $student->getPassportStatusColor(); $pBadge = match($pColor){'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500'}; @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $pBadge }}">{{ $student->getPassportStatusText() }}</span>
                @endif
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số hộ chiếu <span class="text-red-500">*</span></label>
                        <input type="text" name="passport_number" value="{{ old('passport_number', $student->passport->passport_number ?? '') }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_number') border-red-400 @enderror"/>
                        @error('passport_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                        <input type="text" name="country_of_issue" value="{{ old('country_of_issue', $student->passport->country_of_issue ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nơi cấp</label>
                        <input type="text" name="place_of_issue" value="{{ old('place_of_issue', $student->passport->place_of_issue ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', $student->passport->issue_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn <span class="text-red-500">*</span></label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $student->passport->expiry_date ?? '') }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('expiry_date') border-red-400 @enderror"/>
                        @error('expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh hộ chiếu</label>
                        @if($student->passport && $student->passport->image)
                            <img src="{{ asset('storage/'.$student->passport->image) }}" class="w-24 h-16 object-cover rounded-lg mb-2 border border-slate-200 cursor-pointer" onclick="openModal(this.src)">
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100"/>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                        Cập nhật hộ chiếu
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- VISA --}}
    <form method="POST" action="{{ route('student.profile.visa.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Visa</h2>
                </div>
                @if($student->visa)
                    @php $vColor = $student->getVisaStatusColor(); $vBadge = match($vColor){'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500'}; @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $vBadge }}">{{ $student->getVisaStatusText() }}</span>
                @endif
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại visa <span class="text-red-500">*</span></label>
                        <input type="text" name="visa_type" value="{{ old('visa_type', $student->visa->visa_type ?? '') }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số visa</label>
                        <input type="text" name="visa_number" value="{{ old('visa_number', $student->visa->visa_number ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                        <input type="text" name="country" value="{{ old('country', $student->visa->country ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại nhập cảnh</label>
                        <select name="entry_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                            <option value="single"   {{ old('entry_type', $student->visa->entry_type ?? 'single')=='single'  ?'selected':'' }}>Đơn lần</option>
                            <option value="multiple" {{ old('entry_type', $student->visa->entry_type ?? '')=='multiple'?'selected':'' }}>Nhiều lần</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', $student->visa->issue_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn <span class="text-red-500">*</span></label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $student->visa->expiry_date ?? '') }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('expiry_date') border-red-400 @enderror"/>
                        @error('expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh visa</label>
                        @if($student->visa && $student->visa->image)
                            <img src="{{ asset('storage/'.$student->visa->image) }}" class="w-24 h-16 object-cover rounded-lg mb-2 border border-slate-200 cursor-pointer" onclick="openModal(this.src)">
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-600 hover:file:bg-violet-100"/>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition-colors shadow-sm">
                        Cập nhật visa
                    </button>
                </div>
            </div>
        </div>
    </form>

</div>

{{-- IMAGE MODAL --}}
<div id="imgModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4" onclick="if(event.target===this){closeModal()}">
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
function openModal(src){ document.getElementById('modalImg').src=src; const m=document.getElementById('imgModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
function closeModal(){ const m=document.getElementById('imgModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
</script>
</x-app-layout>