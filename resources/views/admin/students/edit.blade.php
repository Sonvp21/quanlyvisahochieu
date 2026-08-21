<x-app-layout>
    <div class="p-6 mx-auto space-y-5">
        {{-- HEADER --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.students.show', $student) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Chỉnh sửa: {{ $student->full_name }}</h1>
                <p class="text-xs text-slate-500">{{ $student->student_code }}</p>
            </div>
        </div>

        <x-alert-success />

        {{-- ==================== FORM CHÍNH: TÀI KHOẢN + CÁ NHÂN + HỘ CHIẾU + VISA ==================== --}}
        <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- TÀI KHOẢN --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                    <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Tài khoản đăng nhập</h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('email') border-red-400 @enderror" />
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Mật khẩu mới <span class="text-slate-400 font-normal">(để trống nếu không đổi)</span></label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-400 @enderror" />
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- THÔNG TIN CÁ NHÂN --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Thông tin cá nhân</h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $student->full_name) }}" required
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('full_name') border-red-400 @enderror" />
                        @error('full_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Mã sinh viên</label>
                        <input type="text" name="student_code" value="{{ old('student_code', $student->student_code) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại sinh viên</label>
                        <select name="student_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                            <option value="">— Chọn loại —</option>
                            <option value="exchange" {{ old('student_type', $student->student_type)=='exchange'     ?'selected':'' }}>Trao đổi</option>
                            <option value="regular" {{ old('student_type', $student->student_type)=='regular'      ?'selected':'' }}>Chính quy</option>
                            <option value="postgraduate" {{ old('student_type', $student->student_type)=='postgraduate' ?'selected':'' }}>Sau đại học</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Giới tính</label>
                        <select name="gender" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                            <option value="">— Chọn —</option>
                            <option value="male" {{ old('gender', $student->gender)=='male'  ?'selected':'' }}>Nam</option>
                            <option value="female" {{ old('gender', $student->gender)=='female'?'selected':'' }}>Nữ</option>
                            <option value="other" {{ old('gender', $student->gender)=='other' ?'selected':'' }}>Khác</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc tịch</label>
                        <input type="text" name="nationality" value="{{ old('nationality', $student->nationality) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngành học</label>
                        <input type="text" name="major" value="{{ old('major', $student->major) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày nhập học</label>
                        <input type="date" name="enrollment_date" value="{{ old('enrollment_date', $student->enrollment_date) }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Địa chỉ tạm trú</label>
                        <textarea name="address" rows="2"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition resize-none">{{ old('address', $student->address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- HỘ CHIẾU --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                    <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Hộ chiếu</h2>
                    @if($student->passport)
                    @php $pColor = $student->getPassportStatusColor(); $pBadge = match($pColor){'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500'}; @endphp
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $pBadge }}">{{ $student->getPassportStatusText() }}</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số hộ chiếu</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number', $student->passport->passport_number ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_number') border-red-400 @enderror" />
                        @error('passport_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                        <input type="text" name="country_of_issue" value="{{ old('country_of_issue', $student->passport->country_of_issue ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nơi cấp</label>
                        <input type="text" name="place_of_issue" value="{{ old('place_of_issue', $student->passport->place_of_issue ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                        <input type="date" name="passport_issue_date" value="{{ old('passport_issue_date', $student->passport->issue_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn</label>
                        <input type="date" name="passport_expiry_date" value="{{ old('passport_expiry_date', $student->passport->expiry_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_expiry_date') border-red-400 @enderror" />
                        @error('passport_expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh hộ chiếu</label>
                        @if($student->passport && $student->passport->image)
                        <img src="{{ asset('storage/'.$student->passport->image) }}" class="w-24 h-16 object-cover rounded-lg mb-2 border border-slate-200">
                        @endif
                        <input type="file" name="passport_image" accept="image/*"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                    </div>
                </div>
            </div>

            {{-- VISA --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                    <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Visa</h2>
                    @if($student->visa)
                    @php $vColor = $student->getVisaStatusColor(); $vBadge = match($vColor){'green'=>'bg-emerald-100 text-emerald-700','yellow'=>'bg-amber-100 text-amber-700','red'=>'bg-red-100 text-red-700',default=>'bg-slate-100 text-slate-500'}; @endphp
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $vBadge }}">{{ $student->getVisaStatusText() }}</span>
                    @endif
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại visa</label>
                        <input type="text" name="visa_type" value="{{ old('visa_type', $student->visa->visa_type ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số visa</label>
                        <input type="text" name="visa_number" value="{{ old('visa_number', $student->visa->visa_number ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                        <input type="text" name="visa_country" value="{{ old('visa_country', $student->visa->country ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại nhập cảnh</label>
                        <select name="entry_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                            <option value="single" {{ old('entry_type', $student->visa->entry_type ?? 'single')=='single'  ?'selected':'' }}>Đơn lần</option>
                            <option value="multiple" {{ old('entry_type', $student->visa->entry_type ?? '')=='multiple'?'selected':'' }}>Nhiều lần</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                        <input type="date" name="visa_issue_date" value="{{ old('visa_issue_date', $student->visa->issue_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn</label>
                        <input type="date" name="visa_expiry_date" value="{{ old('visa_expiry_date', $student->visa->expiry_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('visa_expiry_date') border-red-400 @enderror" />
                        @error('visa_expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Trạng thái visa</label>
                        <select name="visa_status" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                            <option value="valid" {{ old('visa_status', $student->visa->status ?? 'valid')=='valid'    ?'selected':'' }}>Còn hạn</option>
                            <option value="expired" {{ old('visa_status', $student->visa->status ?? '')=='expired'  ?'selected':'' }}>Hết hạn</option>
                            <option value="cancelled" {{ old('visa_status', $student->visa->status ?? '')=='cancelled'?'selected':'' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh visa</label>
                        @if($student->visa && $student->visa->image)
                        <img src="{{ asset('storage/'.$student->visa->image) }}" class="w-24 h-16 object-cover rounded-lg mb-2 border border-slate-200">
                        @endif
                        <input type="file" name="visa_image" accept="image/*"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-600 hover:file:bg-violet-100" />
                    </div>
                </div>
            </div>

            {{-- ACTIONS của form chính --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.students.show', $student) }}"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Hủy
                </a>
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                    Lưu thay đổi
                </button>
            </div>
        </form>
        {{-- ==================== HẾT FORM CHÍNH ==================== --}}


        {{-- ==================== TẠM TRÚ — FORM RIÊNG, NẰM NGOÀI FORM CHÍNH ==================== --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-teal-600 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-800">Thông tin tạm trú</h2>
                </div>
                @if($student->residence)
                <span class="text-xs text-slate-400">
                    Cập nhật: {{ date('d/m/Y H:i', strtotime($student->residence->updated_at)) }}
                </span>
                @endif
            </div>

            <form method="POST" action="{{ route('admin.students.residence.update', $student) }}" class="p-5 space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tên cơ sở lưu trú</label>
                        <input type="text" name="facility_name"
                            value="{{ old('facility_name', $student->residence->facility_name ?? '') }}"
                            placeholder="VD: Nhà khách ĐHNL, KTX K3..."
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Phường, xã</label>
                        <input type="text" name="ward"
                            value="{{ old('ward', $student->residence->ward ?? '') }}"
                            placeholder="VD: Phường Quyết Thắng"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Địa chỉ (số nhà/tổ/xóm)</label>
                        <input type="text" name="address"
                            value="{{ old('address', $student->residence->address ?? '') }}"
                            placeholder="VD: 508-K3"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày đến</label>
                        <input type="date" name="arrival_date"
                            value="{{ old('arrival_date', $student->residence->arrival_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày dự kiến đi</label>
                        <input type="date" name="expected_departure_date"
                            value="{{ old('expected_departure_date', $student->residence->expected_departure_date ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số chứng nhận tạm trú</label>
                        <input type="text" name="certificate_no"
                            value="{{ old('certificate_no', $student->residence->certificate_no ?? '') }}"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ký hiệu (DH, LD, DL...)</label>
                        <input type="text" name="category"
                            value="{{ old('category', $student->residence->category ?? '') }}"
                            placeholder="VD: DH"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ghi chú</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none transition resize-none">{{ old('notes', $student->residence->notes ?? '') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl transition-colors shadow-sm">
                        Lưu thông tin tạm trú
                    </button>
                </div>
            </form>
        </div>
        {{-- ==================== HẾT TẠM TRÚ ==================== --}}


        {{-- ==================== NÚT XÓA SINH VIÊN — form riêng, ngoài cùng ==================== --}}
        <div class="flex justify-start">
            <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                onsubmit="return confirm('Xóa sinh viên {{ addslashes($student->full_name) }}? Không thể hoàn tác.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Xóa sinh viên
                </button>
            </form>
        </div>

    </div>
</x-app-layout>