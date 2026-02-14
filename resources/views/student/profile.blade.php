<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
        <div class="max-w-7xl mx-auto px-8 sm:px-6 lg:px-8">

            {{-- HEADER WITH BREADCRUMB --}}
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('student.dashboard') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                    </path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500">Hồ sơ cá nhân</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">Hồ sơ cá nhân</h1>
                        <p class="text-gray-600 text-lg">Quản lý thông tin cá nhân, hộ chiếu và visa</p>
                    </div>
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

            {{-- THÔNG BÁO SUCCESS --}}
            <x-alert-success />

            <div class="space-y-6">

                {{-- ===================== THÔNG TIN SINH VIÊN ===================== --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-2">
                                    <svg class="w-7 h-7" fill="none" stroke="#2563eb" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z
                 M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>


                                <div>
                                    <h2 class="text-2xl font-bold text-white">Thông tin sinh viên</h2>
                                    <p class="text-blue-100 text-sm">Student Information</p>
                                </div>
                            </div>


                            @if (!$editStudent)
                                <a href="{{ route('student.profile.show', ['edit_student' => 1]) }}#student-section"
                                    class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-300 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Chỉnh sửa
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if (!$editStudent)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Họ và tên</p>
                                            <p class="font-bold text-gray-900">{{ $student->full_name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Mã sinh viên</p>
                                            <p class="font-bold text-gray-900">{{ $student->student_code }}</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Email</p>
                                            <p class="font-bold text-gray-900 break-all">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Loại sinh viên</p>
                                            <p class="font-bold text-gray-900">
                                                @if ($student->student_type == 'exchange')
                                                    <span
                                                        class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Trao
                                                        đổi</span>
                                                @elseif($student->student_type == 'regular')
                                                    <span
                                                        class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Chính
                                                        quy</span>
                                                @elseif($student->student_type == 'postgraduate')
                                                    <span
                                                        class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">Sau
                                                        đại học</span>
                                                @else
                                                    <span class="text-gray-500">Chưa có</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Ngày sinh</p>
                                            <p class="font-bold text-gray-900">
                                                {{ $student->date_of_birth ? date('d/m/Y', strtotime($student->date_of_birth)) : 'Chưa có' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Giới tính</p>
                                            <p class="font-bold text-gray-900">
                                                @if ($student->gender == 'male')
                                                    Nam
                                                @elseif($student->gender == 'female')
                                                    Nữ
                                                @elseif($student->gender == 'other')
                                                    Khác
                                                @else
                                                    Chưa có
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Quốc tịch</p>
                                            <p class="font-bold text-gray-900">{{ $student->nationality ?? 'Chưa có' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Số điện thoại</p>
                                            <p class="font-bold text-gray-900">{{ $student->phone ?? 'Chưa có' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Ngành học</p>
                                            <p class="font-bold text-gray-900">{{ $student->major ?? 'Chưa có' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600 mb-1">Ngày nhập học</p>
                                            <p class="font-bold text-gray-900">
                                                {{ $student->enrollment_date ? date('d/m/Y', strtotime($student->enrollment_date)) : 'Chưa có' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="md:col-span-2 bg-gray-50 rounded-xl p-4 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-start">
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600 mb-1">Địa chỉ tại Việt Nam</p>
                                            <p class="font-bold text-gray-900">{{ $student->address ?? 'Chưa có' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="student-section" class="bg-white rounded-xl shadow-md p-6 scroll-mt-24">
                                <h2 class="text-xl font-bold mb-4">Student Information</h2>
                                {{-- FORM EDIT STUDENT --}}
                                <form method="POST" action="{{ route('student.profile.student.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Họ và tên <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="full_name" value="{{ $student->full_name }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Mã sinh viên
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="student_code"
                                                value="{{ $student->student_code }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Loại sinh
                                                viên</label>
                                            <select name="student_type"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                                <option value="">-- Chọn loại sinh viên --</option>
                                                <option value="exchange"
                                                    {{ $student->student_type == 'exchange' ? 'selected' : '' }}>Trao
                                                    đổi
                                                </option>
                                                <option value="regular"
                                                    {{ $student->student_type == 'regular' ? 'selected' : '' }}>Chính
                                                    quy
                                                </option>
                                                <option value="postgraduate"
                                                    {{ $student->student_type == 'postgraduate' ? 'selected' : '' }}>
                                                    Sau
                                                    đại học</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày sinh</label>
                                            <input type="date" name="date_of_birth"
                                                value="{{ $student->date_of_birth }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Giới tính</label>
                                            <select name="gender"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                                <option value="">-- Chọn giới tính --</option>
                                                <option value="male"
                                                    {{ $student->gender == 'male' ? 'selected' : '' }}>
                                                    Nam</option>
                                                <option value="female"
                                                    {{ $student->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                                <option value="other"
                                                    {{ $student->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Quốc tịch</label>
                                            <input type="text" name="nationality"
                                                value="{{ $student->nationality }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Ví dụ: Vietnam, China, Korea...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Số điện
                                                thoại</label>
                                            <input type="text" name="phone" value="{{ $student->phone }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="+84 xxx xxx xxx">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngành học</label>
                                            <input type="text" name="major" value="{{ $student->major }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Ví dụ: Computer Science">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày nhập
                                                học</label>
                                            <input type="date" name="enrollment_date"
                                                value="{{ $student->enrollment_date }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Địa chỉ tại Việt
                                                Nam</label>
                                            <textarea name="address" rows="3"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Nhập địa chỉ chi tiết...">{{ $student->address }}</textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                                        <a href="{{ route('student.profile.show') }}"
                                            class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Hủy bỏ
                                        </a>
                                        <button type="submit"
                                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-black font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===================== HỘ CHIẾU ===================== --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-2">
                                    <span class="text-3xl">📘</span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Hộ chiếu</h2>
                                    <p class="text-blue-100 text-sm">Passport Information</p>
                                </div>
                            </div>
                            @if (!$editPassport && $student->passport)
                                @php
                                    $passportStatusColor = match ($student->getPassportStatusColor()) {
                                        'green' => 'bg-green-500',
                                        'yellow' => 'bg-yellow-400',
                                        'red' => 'bg-red-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <div class="w-3 h-3 {{ $passportStatusColor }} rounded-full animate-pulse"></div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">

                        @if (!$editPassport)

                            @if ($student->passport)
{{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6"> --}}
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                            Số hộ chiếu
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->passport->passport_number }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Quốc gia cấp
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->passport->country_of_issue ?? 'Chưa có' }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Nơi cấp
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->passport->place_of_issue ?? 'Chưa có' }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Ngày cấp
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->passport->issue_date ? date('d/m/Y', strtotime($student->passport->issue_date)) : 'Chưa có' }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-1 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-200">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Ngày hết hạn
                                        </p>
                                        <p class="font-bold text-2xl text-green-700">
                                            {{ date('d/m/Y', strtotime($student->passport->expiry_date)) }}
                                        </p>
                                    </div>
                                </div>

                                @if ($student->passport->image)
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Ảnh hộ chiếu
                                        </p>
                                        <img src="{{ asset('storage/' . $student->passport->image) }}"
                                            class="w-full max-w-md mx-auto rounded-xl shadow-lg border-4 border-white cursor-pointer hover:scale-105 transition-transform duration-300"
                                            onclick="openImageModal(this.src)" alt="Passport">
                                    </div>
                                @endif
                                {{-- </div> --}}

                                <div class=" mt-6 ">
                                    <a href="{{ route('student.profile.show', ['edit_passport' => 1]) }}#passport-section"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Cập nhật hộ chiếu
                                    </a>
                                </div>

                            @else
                                <div class="text-center py-12">
                                    <div
                                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium mb-2">Chưa có thông tin hộ chiếu</p>
                                    <p class="text-sm text-gray-400 mb-6">Vui lòng cập nhật thông tin hộ chiếu của bạn
                                    </p>
                                    <a href="{{ route('student.profile.show', ['edit_passport' => 1]) }}"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Thêm hộ chiếu
                                    </a>
                                </div>
                            @endif

                        @else
                            <div id="passport-section" class="bg-white rounded-xl shadow-md p-6 scroll-mt-24">
                                <h2 class="text-xl font-bold mb-4">Passport Information</h2>
                                {{-- FORM EDIT PASSPORT --}}
                                <form method="POST" action="{{ route('student.profile.passport.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Số hộ chiếu <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="passport_number"
                                                value="{{ $student->passport->passport_number ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Ví dụ: A12345678">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Quốc gia
                                                cấp</label>
                                            <input type="text" name="country_of_issue"
                                                value="{{ $student->passport->country_of_issue ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Ví dụ: Vietnam, China...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Nơi cấp</label>
                                            <input type="text" name="place_of_issue"
                                                value="{{ $student->passport->place_of_issue ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                                placeholder="Ví dụ: Hà Nội, TP.HCM...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày cấp</label>
                                            <input type="date" name="issue_date"
                                                value="{{ $student->passport->issue_date ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày hết hạn
                                                <span class="text-red-500">*</span></label>
                                            <input type="date" name="expiry_date"
                                                value="{{ $student->passport->expiry_date ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                        </div>

                                        {{-- ==================== PASSPORT FORM - PHẦN UPLOAD ẢNH ==================== --}}
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ảnh hộ
                                                chiếu</label>

                                            {{-- Hidden file input --}}
                                            <input type="file" name="image" id="passport-file-input"
                                                accept="image/*" class="hidden"
                                                onchange="handleFileSelect(event, 'passport')">

                                            {{-- Upload Area --}}
                                            <div id="passport-upload-area"
                                                onclick="document.getElementById('passport-file-input').click()"
                                                ondrop="handleDrop(event, 'passport')"
                                                ondragover="handleDragOver(event)"
                                                ondragleave="handleDragLeave(event)"
                                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 relative">

                                                <div id="passport-preview-container">
                                                    @if (isset($student->passport->image) && $student->passport->image)
                                                        {{-- Có ảnh cũ - hiển thị ngay --}}
                                                        <div class="relative inline-block group">
                                                            <img id="passport-current-image"
                                                                src="{{ asset('storage/' . $student->passport->image) }}"
                                                                alt="Passport"
                                                                class="max-h-64 rounded-lg shadow-lg mx-auto">

                                                            {{-- Overlay khi hover --}}
                                                            <div
                                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 rounded-lg flex items-center justify-center">
                                                                <div
                                                                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-center">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    <p class="font-semibold">Click để thay đổi ảnh</p>
                                                                    <p class="text-sm mt-1">hoặc kéo thả ảnh vào đây
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Chưa có ảnh - hiển thị placeholder --}}
                                                        <div id="passport-placeholder">
                                                            <div
                                                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                                <svg class="w-10 h-10 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <p class="text-gray-600 font-medium mb-1">Click để chọn ảnh
                                                            </p>
                                                            <p class="text-sm text-gray-400">hoặc kéo thả ảnh vào đây
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-2">JPG, PNG, PDF (Tối đa
                                                                2MB)</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-2">Định dạng: JPG, PNG, PDF (Tối đa 2MB)
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Buttons outside grid --}}
                                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                                        <a href="{{ route('student.profile.show') }}"
                                            class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Hủy bỏ
                                        </a>
                                        <button type="submit"
                                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-black font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===================== VISA ===================== --}}
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-2">
                                    <span class="text-3xl">📗</span>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold">Visa</h2>
                                    <p class="text-purple-100 text-sm">Visa Information</p>
                                </div>
                            </div>
                            @if (!$editVisa && $student->visa)
                                @php
                                    $visaStatusColor = match ($student->getVisaStatusColor()) {
                                        'green' => 'bg-green-500',
                                        'yellow' => 'bg-yellow-400',
                                        'red' => 'bg-red-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp
                                <div class="w-3 h-3 {{ $visaStatusColor }} rounded-full animate-pulse"></div>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if (!$editVisa)
                            @if ($student->visa)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-max h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                            Loại visa
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">{{ $student->visa->visa_type }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            Quốc gia cấp
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->visa->country ?? 'Chưa có' }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Số visa
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->visa->visa_number ?? 'Chưa có' }}</p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                            Loại nhập cảnh
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            @if ($student->visa->entry_type == 'single')
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Đơn</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Nhiều
                                                    lần</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Ngày cấp
                                        </p>
                                        <p class="font-bold text-lg text-gray-900">
                                            {{ $student->visa->issue_date ? date('d/m/Y', strtotime($student->visa->issue_date)) : 'Chưa có' }}
                                        </p>
                                    </div>

                                    <div
                                        class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-200">
                                        <p class="text-sm text-gray-600 mb-1 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Ngày hết hạn
                                        </p>
                                        <p class="font-bold text-2xl text-green-700">
                                            {{ date('d/m/Y', strtotime($student->visa->expiry_date)) }}
                                        </p>
                                    </div>
                                </div>

                                @if ($student->visa->image)
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <p class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            Ảnh visa
                                        </p>
                                        <img src="{{ asset('storage/' . $student->visa->image) }}"
                                            class="w-full max-w-md mx-auto rounded-xl shadow-lg border-4 border-white cursor-pointer hover:scale-105 transition-transform duration-300"
                                            onclick="openImageModal(this.src)" alt="Visa">
                                    </div>
                                @endif

                                <div class="mt-6">
                                    <a href="{{ route('student.profile.show', ['edit_visa' => 1]) }}#visa-section"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Cập nhật visa
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div
                                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium mb-2">Chưa có thông tin visa</p>
                                    <p class="text-sm text-gray-400 mb-6">Vui lòng cập nhật thông tin visa của bạn</p>
                                    <a href="{{ route('student.profile.show', ['edit_visa' => 1]) }}"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Thêm visa
                                    </a>
                                </div>
                            @endif
                        @else
                            <div id="visa-section" class="bg-white rounded-xl shadow-md p-6 scroll-mt-24">
                                <h2 class="text-xl font-bold mb-4">Visa Information</h2>
                                {{-- FORM EDIT VISA --}}
                                <form method="POST" action="{{ route('student.profile.visa.update') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Loại visa <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="visa_type"
                                                value="{{ $student->visa->visa_type ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                                placeholder="Ví dụ: DN, DH, DL...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Quốc gia cấp
                                                visa</label>
                                            <input type="text" name="country"
                                                value="{{ $student->visa->country ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                                placeholder="Ví dụ: Vietnam">
                                        </div>

                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Số visa</label>
                                            <input type="text" name="visa_number"
                                                value="{{ $student->visa->visa_number ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all"
                                                placeholder="Nhập số visa">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Loại nhập
                                                cảnh</label>
                                            <select name="entry_type"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                                                <option value="single"
                                                    {{ ($student->visa->entry_type ?? '') == 'single' ? 'selected' : '' }}>
                                                    Đơn</option>
                                                <option value="multiple"
                                                    {{ ($student->visa->entry_type ?? '') == 'multiple' ? 'selected' : '' }}>
                                                    Nhiều lần</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày cấp</label>
                                            <input type="date" name="issue_date"
                                                value="{{ $student->visa->issue_date ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày hết hạn
                                                <span class="text-red-500">*</span></label>
                                            <input type="date" name="expiry_date"
                                                value="{{ $student->visa->expiry_date ?? '' }}"
                                                class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all">
                                        </div>

                                        {{-- ==================== VISA FORM - PHẦN UPLOAD ẢNH ==================== --}}
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Ảnh visa</label>

                                            {{-- Hidden file input --}}
                                            <input type="file" name="image" id="visa-file-input"
                                                accept="image/*" class="hidden"
                                                onchange="handleFileSelect(event, 'visa')">

                                            {{-- Upload Area --}}
                                            <div id="visa-upload-area"
                                                onclick="document.getElementById('visa-file-input').click()"
                                                ondrop="handleDrop(event, 'visa')" ondragover="handleDragOver(event)"
                                                ondragleave="handleDragLeave(event)"
                                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all duration-300 relative">

                                                <div id="visa-preview-container">
                                                    @if (isset($student->visa->image) && $student->visa->image)
                                                        {{-- Có ảnh cũ - hiển thị ngay --}}
                                                        <div class="relative inline-block group">
                                                            <img id="visa-current-image"
                                                                src="{{ asset('storage/' . $student->visa->image) }}"
                                                                alt="Visa"
                                                                class="max-h-64 rounded-lg shadow-lg mx-auto">

                                                            {{-- Overlay khi hover --}}
                                                            <div
                                                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 rounded-lg flex items-center justify-center">
                                                                <div
                                                                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-center">
                                                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    <p class="font-semibold">Click để thay đổi ảnh</p>
                                                                    <p class="text-sm mt-1">hoặc kéo thả ảnh vào đây
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Chưa có ảnh - hiển thị placeholder --}}
                                                        <div id="visa-placeholder">
                                                            <div
                                                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                                <svg class="w-10 h-10 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <p class="text-gray-600 font-medium mb-1">Click để chọn ảnh
                                                            </p>
                                                            <p class="text-sm text-gray-400">hoặc kéo thả ảnh vào đây
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-2">JPG, PNG, PDF (Tối đa
                                                                2MB)</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-2">Định dạng: JPG, PNG, PDF (Tối đa 2MB)
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Buttons outside grid --}}
                                    <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                                        <a href="{{ route('student.profile.show') }}"
                                            class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Hủy bỏ
                                        </a>
                                        <button type="submit"
                                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-black font-bold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- IMAGE MODAL (Tái sử dụng từ dashboard) --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
        <div class="relative max-w-4xl w-full mx-4">
            <div class="absolute -top-12 right-0 flex gap-2 z-40">
                <button onclick="zoomIn()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100 transition-colors">
                    ＋
                </button>
                <button onclick="zoomOut()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-xl hover:bg-gray-100 transition-colors">
                    －
                </button>
                <button onclick="closeImageModal()"
                    class="bg-white w-10 h-10 rounded-full flex items-center justify-center shadow text-red-500 text-xl font-bold hover:bg-red-50 transition-colors">
                    ✕
                </button>
            </div>

            <div id="imageContainer" class="overflow-hidden rounded-xl flex items-center justify-center">
                <img id="modalImage" src=""
                    class="max-h-[90vh] object-contain rounded-xl shadow-2xl border-4 border-white cursor-grab select-none"
                    style="will-change: transform;">
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        let scale = 1;
        let isDragging = false;
        let hasDragged = false;
        let startX = 0;
        let startY = 0;
        let translateX = 0;
        let translateY = 0;

        function applyTransform() {
            const img = document.getElementById('modalImage');
            img.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        }

        function clamp(v, min, max) {
            return Math.min(Math.max(v, min), max);
        }

        function limitTranslate() {
            const img = document.getElementById('modalImage');
            const container = document.getElementById('imageContainer');
            const cRect = container.getBoundingClientRect();
            const iRect = img.getBoundingClientRect();
            const maxX = Math.max(0, (iRect.width - cRect.width) / 2);
            const maxY = Math.max(0, (iRect.height - cRect.height) / 2);
            translateX = clamp(translateX, -maxX, maxX);
            translateY = clamp(translateY, -maxY, maxY);
        }

        function zoomIn() {
            scale += 0.2;
            applyTransform();
            limitTranslate();
        }

        function zoomOut() {
            scale -= 0.2;
            if (scale <= 1) {
                scale = 1;
                translateX = 0;
                translateY = 0;
            }
            applyTransform();
        }

        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = src;
            scale = 1;
            translateX = 0;
            translateY = 0;
            applyTransform();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const img = document.getElementById('modalImage');
            const modal = document.getElementById('imageModal');

            img.addEventListener('mousedown', (e) => {
                if (scale <= 1) return;
                e.preventDefault();
                e.stopPropagation();
                isDragging = true;
                hasDragged = false;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
                img.style.cursor = "grabbing";
            });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                hasDragged = true;
                translateX = e.clientX - startX;
                translateY = e.clientY - startY;
                limitTranslate();
                applyTransform();
            });

            window.addEventListener('mouseup', () => {
                if (!isDragging) return;
                isDragging = false;
                img.style.cursor = scale > 1 ? "grab" : "default";
            });

            img.addEventListener('click', (e) => {
                if (hasDragged) {
                    e.preventDefault();
                    e.stopPropagation();
                    hasDragged = false;
                }
            });

            img.addEventListener('wheel', (e) => {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn();
                else zoomOut();
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeImageModal();
            });
        });
    </script>
    <script>
        // Handle file selection
        function handleFileSelect(event, type) {
            const file = event.target.files[0];
            if (file) {
                // Validate file
                if (!validateFile(file)) {
                    return;
                }

                // Preview the new image immediately
                const reader = new FileReader();
                reader.onload = function(e) {
                    updateImagePreview(type, e.target.result);
                }
                reader.readAsDataURL(file);
            }
        }

        // Handle drag over
        function handleDragOver(event) {
            event.preventDefault();
            event.stopPropagation();
            event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
        }

        // Handle drag leave
        function handleDragLeave(event) {
            event.preventDefault();
            event.stopPropagation();
            event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
            event.currentTarget.classList.remove('border-purple-500', 'bg-purple-50');
        }

        // Handle drop
        function handleDrop(event, type) {
            event.preventDefault();
            event.stopPropagation();
            event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
            event.currentTarget.classList.remove('border-purple-500', 'bg-purple-50');

            const files = event.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];

                // Validate file
                if (!validateFile(file)) {
                    return;
                }

                // Set the file to input
                const input = document.getElementById(type + '-file-input');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;

                // Preview the image immediately
                const reader = new FileReader();
                reader.onload = function(e) {
                    updateImagePreview(type, e.target.result);
                }
                reader.readAsDataURL(file);
            }
        }

        // Validate file
        function validateFile(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!validTypes.includes(file.type)) {
                alert('⚠️ Vui lòng chọn file JPG, PNG hoặc PDF');
                return false;
            }

            if (file.size > maxSize) {
                alert('⚠️ Kích thước file không được vượt quá 2MB');
                return false;
            }

            return true;
        }

        // Update image preview - HIỂN THỊ NGAY LẬP TỨC
        function updateImagePreview(type, imageSrc) {
            const container = document.getElementById(type + '-preview-container');

            // Always replace the entire content to show new image immediately
            container.innerHTML = `
            <div class="relative inline-block group">
                <img id="${type}-current-image"
                     src="${imageSrc}"
                     alt="${type}"
                     class="max-h-64 rounded-lg shadow-lg mx-auto">

                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 rounded-lg flex items-center justify-center">
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-white text-center">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="font-semibold">Click để thay đổi ảnh</p>
                        <p class="text-sm mt-1">hoặc kéo thả ảnh vào đây</p>
                    </div>
                </div>

                <div class="absolute top-2 right-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg animate-pulse">
                    ✓ Ảnh mới
                </div>
            </div>
        `;

            // Show success feedback
            showSuccessMessage(type);
        }

        // Show success message
        function showSuccessMessage(type) {
            const uploadArea = document.getElementById(type + '-upload-area');

            // Temporarily change border to green to indicate success
            uploadArea.classList.remove('border-gray-300');
            uploadArea.classList.add('border-green-500', 'bg-green-50');

            setTimeout(() => {
                uploadArea.classList.remove('border-green-500', 'bg-green-50');
                uploadArea.classList.add('border-gray-300');
            }, 2000);
        }
    </script>
</x-app-layout>
