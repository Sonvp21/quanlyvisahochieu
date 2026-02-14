<x-app-layout>
{{-- <div class="max-w-6xl mx-auto p-6 space-y-6"> --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

{{-- HEADER --}}
            <div class="flex justify-between items-center mb-0">
                <div class="mb-0">
                <nav class="flex mb-0" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.students.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Quản lý sinh viên
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500">Sửa hồ sơ sinh viên</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                    <div class="hidden md:block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Sửa hồ sơ sinh viên</h1>
        <a href="{{ route('admin.students.index') }}"
           class="text-blue-600 underline">
            ← Quay lại danh sách
        </a>
    </div>

    {{-- THÔNG BÁO LỖI --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <p class="font-bold">Có lỗi xảy ra:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">👤 Thông tin sinh viên</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Họ tên <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name"
                           value="{{ old('full_name', $student->full_name) }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Mã sinh viên <span class="text-red-500">*</span></label>
                    <input type="text" name="student_code"
                           value="{{ old('student_code', $student->student_code) }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $student->user->email) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Loại sinh viên</label>
                    <select name="student_type" class="w-full border rounded p-2">
                        <option value="">-- Chọn --</option>
                        <option value="exchange" {{ old('student_type', $student->student_type) == 'exchange' ? 'selected' : '' }}>Trao đổi</option>
                        <option value="regular" {{ old('student_type', $student->student_type) == 'regular' ? 'selected' : '' }}>Chính quy</option>
                        <option value="postgraduate" {{ old('student_type', $student->student_type) == 'postgraduate' ? 'selected' : '' }}>Sau đại học</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày sinh</label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth', $student->date_of_birth) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Giới tính</label>
                    <select name="gender" class="w-full border rounded p-2">
                        <option value="">-- Chọn --</option>
                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Quốc tịch</label>
                    <input type="text" name="nationality"
                           value="{{ old('nationality', $student->nationality) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Số điện thoại</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $student->phone) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngành học</label>
                    <input type="text" name="major"
                           value="{{ old('major', $student->major) }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày nhập học</label>
                    <input type="date" name="enrollment_date"
                           value="{{ old('enrollment_date', $student->enrollment_date) }}"
                           class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Địa chỉ tại Việt Nam</label>
                <textarea name="address"
                          class="w-full border rounded p-2"
                          rows="2">{{ old('address', $student->address) }}</textarea>
            </div>
        </div>

        {{-- HỘ CHIẾU --}}
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">📘 Hộ chiếu</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Số hộ chiếu</label>
                    <input type="text" name="passport_number"
                           value="{{ old('passport_number', $student->passport->passport_number ?? '') }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Quốc gia cấp</label>
                    <input type="text" name="passport_country"
                           value="{{ old('passport_country', $student->passport->country_of_issue ?? '') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: Vietnam">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Nơi cấp</label>
                    <input type="text" name="passport_place"
                           value="{{ old('passport_place', $student->passport->place_of_issue ?? '') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: Hà Nội">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày cấp</label>
                    <input type="date" name="passport_issue_date"
                           value="{{ old('passport_issue_date', $student->passport->issue_date ?? '') }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày hết hạn</label>
                    <input type="date" name="passport_expiry_date"
                           value="{{ old('passport_expiry_date', $student->passport->expiry_date ?? '') }}"
                           class="w-full border rounded p-2">
                </div>
            </div>

            {{-- Ảnh hộ chiếu hiện tại --}}
            @if($student->passport && $student->passport->image)
                <div class="mt-4">
                    <label class="block font-semibold mb-2">Ảnh hộ chiếu hiện tại:</label>
                    <div class="border rounded p-3 bg-gray-50">
                        <img src="{{ asset('storage/' . $student->passport->image) }}"
                             class="max-w-md w-full h-auto rounded shadow"
                             alt="Passport Image">
                    </div>
                </div>
            @endif

            {{-- Upload ảnh hộ chiếu mới --}}
            <div class="mt-4">
                <label class="block font-semibold mb-1">
                    {{ $student->passport && $student->passport->image ? 'Cập nhật ảnh hộ chiếu mới (nếu có)' : 'Ảnh hộ chiếu' }}
                </label>
                <input type="file" name="passport_image" accept="image/*"
                       class="w-full border rounded p-2">
                <p class="text-sm text-gray-500 mt-1">Định dạng: JPG, PNG, PDF (Tối đa 2MB)</p>
            </div>
        </div>

        {{-- VISA --}}
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">📗 Visa</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Loại visa</label>
                    <input type="text" name="visa_type"
                           value="{{ old('visa_type', $student->visa->visa_type ?? '') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: DN, DH">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Quốc gia cấp visa</label>
                    <input type="text" name="visa_country"
                           value="{{ old('visa_country', $student->visa->country ?? '') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: Vietnam">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Số visa</label>
                    <input type="text" name="visa_number"
                           value="{{ old('visa_number', $student->visa->visa_number ?? '') }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Loại nhập cảnh</label>
                    <select name="entry_type" class="w-full border rounded p-2">
                        <option value="">-- Chọn --</option>
                        <option value="single" {{ old('entry_type', $student->visa->entry_type ?? '') == 'single' ? 'selected' : '' }}>Đơn</option>
                        <option value="multiple" {{ old('entry_type', $student->visa->entry_type ?? '') == 'multiple' ? 'selected' : '' }}>Nhiều lần</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày cấp</label>
                    <input type="date" name="visa_issue_date"
                           value="{{ old('visa_issue_date', $student->visa->issue_date ?? '') }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày hết hạn</label>
                    <input type="date" name="visa_expiry_date"
                           value="{{ old('visa_expiry_date', $student->visa->expiry_date ?? '') }}"
                           class="w-full border rounded p-2">
                </div>
            </div>

            {{-- Ảnh visa hiện tại --}}
            @if($student->visa && $student->visa->image)
                <div class="mt-4">
                    <label class="block font-semibold mb-2">Ảnh visa hiện tại:</label>
                    <div class="border rounded p-3 bg-gray-50">
                        <img src="{{ asset('storage/' . $student->visa->image) }}"
                             class="max-w-md w-full h-auto rounded shadow"
                             alt="Visa Image">
                    </div>
                </div>
            @endif

            {{-- Upload ảnh visa mới --}}
            <div class="mt-4">
                <label class="block font-semibold mb-1">
                    {{ $student->visa && $student->visa->image ? 'Cập nhật ảnh visa mới (nếu có)' : 'Ảnh visa' }}
                </label>
                <input type="file" name="visa_image" accept="image/*"
                       class="w-full border rounded p-2">
                <p class="text-sm text-gray-500 mt-1">Định dạng: JPG, PNG, PDF (Tối đa 2MB)</p>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('admin.students.index') }}"
               class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                Hủy
            </a>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                💾 Lưu thay đổi
            </button>
        </div>

    </form>

</div>
</x-app-layout>
