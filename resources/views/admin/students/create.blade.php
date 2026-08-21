<x-app-layout>
<div class="p-6 mx-auto space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.students.index') }}"
            class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Thêm sinh viên mới</h1>
            <p class="text-xs text-slate-500">Điền đầy đủ thông tin bên dưới</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- TÀI KHOẢN --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Tài khoản đăng nhập</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('email') border-red-400 @enderror"/>
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Mật khẩu <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-400 @enderror"/>
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- THÔNG TIN CÁ NHÂN --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Thông tin cá nhân</h2>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('full_name') border-red-400 @enderror"/>
                    @error('full_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Mã sinh viên</label>
                    <input type="text" name="student_code" value="{{ old('student_code') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại sinh viên</label>
                    <select name="student_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                        <option value="">— Chọn loại —</option>
                        <option value="exchange"     {{ old('student_type')=='exchange'     ?'selected':'' }}>Trao đổi</option>
                        <option value="regular"      {{ old('student_type')=='regular'      ?'selected':'' }}>Chính quy</option>
                        <option value="postgraduate" {{ old('student_type')=='postgraduate' ?'selected':'' }}>Sau đại học</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày sinh</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Giới tính</label>
                    <select name="gender" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                        <option value="">— Chọn —</option>
                        <option value="male"   {{ old('gender')=='male'  ?'selected':'' }}>Nam</option>
                        <option value="female" {{ old('gender')=='female'?'selected':'' }}>Nữ</option>
                        <option value="other"  {{ old('gender')=='other' ?'selected':'' }}>Khác</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc tịch</label>
                    <input type="text" name="nationality" value="{{ old('nationality') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngành học</label>
                    <input type="text" name="major" value="{{ old('major') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày nhập học</label>
                    <input type="date" name="enrollment_date" value="{{ old('enrollment_date') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Địa chỉ tạm trú</label>
                    <textarea name="address" rows="2"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition resize-none">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        {{-- HỘ CHIẾU --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Hộ chiếu</h2>
                <span class="text-xs text-slate-400">(tùy chọn)</span>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số hộ chiếu</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_number') border-red-400 @enderror"/>
                    @error('passport_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                    <input type="text" name="country_of_issue" value="{{ old('country_of_issue') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nơi cấp</label>
                    <input type="text" name="place_of_issue" value="{{ old('place_of_issue') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                    <input type="date" name="passport_issue_date" value="{{ old('passport_issue_date') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn</label>
                    <input type="date" name="passport_expiry_date" value="{{ old('passport_expiry_date') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('passport_expiry_date') border-red-400 @enderror"/>
                    @error('passport_expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh hộ chiếu</label>
                    <input type="file" name="passport_image" accept="image/*"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100"/>
                </div>
            </div>
        </div>

        {{-- VISA --}}
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="flex items-center gap-2.5 px-5 py-4 border-b border-slate-100">
                <div class="w-7 h-7 bg-violet-600 rounded-lg flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-800">Visa</h2>
                <span class="text-xs text-slate-400">(tùy chọn)</span>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại visa</label>
                    <input type="text" name="visa_type" value="{{ old('visa_type') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số visa</label>
                    <input type="text" name="visa_number" value="{{ old('visa_number') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Quốc gia cấp</label>
                    <input type="text" name="visa_country" value="{{ old('visa_country') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Loại nhập cảnh</label>
                    <select name="entry_type" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-white">
                        <option value="single"   {{ old('entry_type')=='single'  ?'selected':'' }}>Đơn lần</option>
                        <option value="multiple" {{ old('entry_type')=='multiple'?'selected':'' }}>Nhiều lần</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày cấp</label>
                    <input type="date" name="visa_issue_date" value="{{ old('visa_issue_date') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition"/>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ngày hết hạn</label>
                    <input type="date" name="visa_expiry_date" value="{{ old('visa_expiry_date') }}"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition @error('visa_expiry_date') border-red-400 @enderror"/>
                    @error('visa_expiry_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ảnh visa</label>
                    <input type="file" name="visa_image" accept="image/*"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-600 hover:file:bg-violet-100"/>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.students.index') }}"
                class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Hủy
            </a>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm">
                Lưu sinh viên
            </button>
        </div>
    </form>

</div>
</x-app-layout>