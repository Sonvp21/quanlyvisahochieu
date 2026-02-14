<footer class="bg-white border-t border-gray-200 mt-12 text-gray-700">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Logo & Description --}}
            <div>
                <a href="#">
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="GIRC Logo" class="h-9 w-auto">
                    <span class="text-lg font-bold text-gray-900">GIRC</span>
                </div>
                </a>
                <p class="text-sm leading-relaxed text-gray-600">
                    Trung tâm Nghiên cứu Địa tin học –
                    Hệ thống quản lý sinh viên, visa và hộ chiếu hiện đại, an toàn và hiệu quả.
                </p>

            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Liên kết nhanh</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('student.dashboard') }}" class="hover:text-blue-600 transition">Trang chủ</a>
                    </li>
                    <li><a href="{{ route('student.profile.show') }}" class="hover:text-blue-600 transition">Hồ sơ cá nhân</a>
                    </li>
                    <li><a href="{{ route('student.profile.show', ['edit_student' => 1]) }}#student-section" class="hover:text-blue-600 transition">Chỉnh sửa thông tin cá nhân</a>
                    </li>
                    <li><a href="{{ route('student.profile.show', ['edit_passport' => 1]) }}#passport-section" class="hover:text-blue-600 transition">Chỉnh sửa hồ sơ hộ chiếu</a>
                    </li>
                    <li><a href="{{ route('student.profile.show', ['edit_visa' => 1]) }}#visa-section" class="hover:text-blue-600 transition">Chỉnh sửa hồ sơ visa</a>
                    </li>
                </ul>
            </div>

            {{-- Policies --}}
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Chính sách</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-blue-600 transition">Bảo mật thông tin</a></li>

                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="font-semibold text-gray-900 mb-3">Liên hệ</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-blue-600"></i>
                        Trường Đại học Nông Lâm - Đại học Thái Nguyên, xã Quyết Thắng, TP. Thái Nguyên
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-blue-600"></i>
                        support@girc.vn
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-blue-600"></i>
                        +84 123 456 789
                    </li>
                </ul>

                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Copyright --}}
    <div class="border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-3 text-center text-sm text-gray-500">
            © {{ date('Y') }} GIRC – Trung tâm Nghiên cứu Địa tin học. All rights reserved.
        </div>
    </div>
</footer>
