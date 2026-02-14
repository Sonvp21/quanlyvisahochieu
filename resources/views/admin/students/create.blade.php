<x-app-layout>
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Thêm sinh viên mới</h1>

    {{-- THÔNG BÁO LỖI --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <p class="font-bold">Có lỗi xảy ra:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
        @csrf

        {{-- TÀI KHOẢN ĐĂNG NHẬP --}}
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">🔐 Tài khoản đăng nhập</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Tên đăng nhập <span class="text-red-500">*</span></label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           class="w-full border rounded p-2"
                           required>
                </div>
                   <div>
                <label class="block font-semibold mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="w-full border rounded p-2"
                       required>
                <p class="text-xs text-gray-500 mt-1">Tối thiểu 6 ký tự</p>
            </div>

            </div>

            {{-- <div>
                <label class="block font-semibold mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="w-full border rounded p-2"
                       required>
                <p class="text-xs text-gray-500 mt-1">Tối thiểu 6 ký tự</p>
            </div> --}}
        </div>

        {{-- THÔNG TIN CƠ BẢN --}}
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2">👤 Thông tin cơ bản</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Họ tên <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name"
                           value="{{ old('full_name') }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Mã sinh viên <span class="text-red-500">*</span></label>
                    <input type="text" name="student_code"
                           value="{{ old('student_code') }}"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Loại sinh viên</label>
                    <select name="student_type" class="w-full border rounded p-2">
                        <option value="">-- Chọn --</option>
                        <option value="exchange" {{ old('student_type') == 'exchange' ? 'selected' : '' }}>Trao đổi</option>
                        <option value="regular" {{ old('student_type') == 'regular' ? 'selected' : '' }}>Chính quy</option>
                        <option value="postgraduate" {{ old('student_type') == 'postgraduate' ? 'selected' : '' }}>Sau đại học</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày sinh</label>
                    <input type="date" name="date_of_birth"
                           value="{{ old('date_of_birth') }}"
                           class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Giới tính</label>
                    <select name="gender" class="w-full border rounded p-2">
                        <option value="">-- Chọn --</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Quốc tịch</label>
                    <input type="text" name="nationality"
                           value="{{ old('nationality') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: Việt Nam">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Số điện thoại</label>
                    <input type="text" name="phone"
                           value="{{ old('phone') }}"
                           class="w-full border rounded p-2"
                           placeholder="0901234567">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngành học</label>
                    <input type="text" name="major"
                           value="{{ old('major') }}"
                           class="w-full border rounded p-2"
                           placeholder="Ví dụ: Công nghệ thông tin">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Ngày nhập học</label>
                    <input type="date" name="enrollment_date"
                           value="{{ old('enrollment_date') }}"
                           class="w-full border rounded p-2">
                </div>
                 <div>
                <label class="block font-semibold mb-1">Địa chỉ tại Việt Nam</label>
                <textarea name="address"
                          class="w-full border rounded p-2"
                          rows="2"
                          placeholder="Nhập địa chỉ đầy đủ">{{ old('address') }}</textarea>
            </div>
            </div>


        </div>

        {{-- BUTTONS --}}
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.students.index') }}"
               class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">
                Hủy
            </a>

            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                ➕ Thêm sinh viên
            </button>
        </div>

    </form>

</div>
</x-app-layout>
