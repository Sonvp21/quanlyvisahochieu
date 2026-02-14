<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                            Admin Dashboard
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Quản lý visa và hộ chiếu du học sinh
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-700">Hệ thống hoạt động</span>
                    </div>
                </div>
            </div>

            {{-- THỐNG KÊ TỔNG QUAN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                {{-- Tổng sinh viên --}}
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-blue-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">Sinh viên</p>
                            </div>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalStudents }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                Tổng số hiện tại
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Tổng tài khoản --}}
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-emerald-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">Tài khoản</p>
                            </div>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $totalUsers }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                Người dùng hệ thống
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Cập nhật gần đây --}}
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-violet-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">Thông tin sinh viên</p>
                            </div>
                            <p class="text-3xl font-bold text-gray-900 mb-1">{{ $recentlyUpdatedStudents }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-violet-500 rounded-full"></span>
                                Cập nhật 7 ngày
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Hồ sơ mới --}}
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 hover:border-amber-200 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600">Hồ sơ mới</p>
                            </div>
                            <p class="text-3xl font-bold text-gray-900 mb-1">
                                {{ $recentlyUpdatedPassports + $recentlyUpdatedVisas }}
                            </p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                Passport + Visa (7 ngày)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                {{-- THỐNG KÊ HỘ CHIẾU --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Hộ chiếu</h2>
                                <p class="text-xs text-gray-500">Tình trạng hiện tại</p>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-blue-50 rounded-lg">
                            <p class="text-xs font-semibold text-blue-600">
                                tổng: {{ $passportValid + $passportExpiringSoon + $passportExpired }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Còn hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-emerald-900">Còn hạn</p>
                                        <p class="text-xs text-emerald-600 mt-0.5">Hơn 30 ngày</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-emerald-700">{{ $passportValid }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-emerald-600">Tốt</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sắp hết hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border border-amber-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-amber-900">Sắp hết hạn</p>
                                        <p class="text-xs text-amber-600 mt-0.5">Còn < 30 ngày</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-amber-700">{{ $passportExpiringSoon }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-amber-600">Cảnh báo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Đã hết hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border border-red-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-red-900">Đã hết hạn</p>
                                        <p class="text-xs text-red-600 mt-0.5">Cần gia hạn ngay</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-red-700">{{ $passportExpired }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-red-600">Khẩn cấp</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($passportExpiringSoon > 0 || $passportExpired > 0)
                        <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-lg">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 mb-1">Cần xử lý ngay!</p>
                                    <p class="text-sm text-amber-800">
                                        Có <span class="font-bold">{{ $passportExpiringSoon + $passportExpired }}</span> hộ chiếu cần được cập nhật hoặc gia hạn.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- THỐNG KÊ VISA --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Visa</h2>
                                <p class="text-xs text-gray-500">Tình trạng hiện tại</p>
                            </div>
                        </div>
                        <div class="px-3 py-1 bg-violet-50 rounded-lg">
                            <p class="text-xs font-semibold text-violet-600">
                                tổng: {{ $visaValid + $visaExpiringSoon + $visaExpired }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        {{-- Còn hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl border border-emerald-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-emerald-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-emerald-900">Còn hạn</p>
                                        <p class="text-xs text-emerald-600 mt-0.5">Hơn 30 ngày</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-emerald-700">{{ $visaValid }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-emerald-600">Tốt</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Sắp hết hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl border border-amber-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-amber-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-amber-900">Sắp hết hạn</p>
                                        <p class="text-xs text-amber-600 mt-0.5">Còn < 30 ngày</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-amber-700">{{ $visaExpiringSoon }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-amber-600">Cảnh báo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Đã hết hạn --}}
                        <div class="group relative overflow-hidden p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border border-red-200 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-red-900">Đã hết hạn</p>
                                        <p class="text-xs text-red-600 mt-0.5">Cần gia hạn ngay</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold text-red-700">{{ $visaExpired }}</p>
                                    <div class="flex items-center justify-end gap-1 mt-1">
                                        <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-medium text-red-600">Khẩn cấp</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($visaExpiringSoon > 0 || $visaExpired > 0)
                        <div class="mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-lg">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-amber-900 mb-1">Cần xử lý ngay!</p>
                                    <p class="text-sm text-amber-800">
                                        Có <span class="font-bold">{{ $visaExpiringSoon + $visaExpired }}</span> visa cần được cập nhật hoặc gia hạn.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- QUICK ACTIONS --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Thao tác nhanh</h2>
                        <p class="text-xs text-gray-500">Truy cập nhanh các chức năng chính</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('admin.students.index') }}"
                        class="group relative overflow-hidden p-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02]">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="font-bold text-white text-lg mb-2">Quản lý sinh viên</h3>
                            <p class="text-blue-100 text-sm">Xem, chỉnh sửa và quản lý thông tin sinh viên</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.notification-reports.index') }}"
                        class="group relative overflow-hidden p-6 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02]">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                        <div class="relative">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="font-bold text-white text-lg mb-2">Quản lý email</h3>
                            <p class="text-purple-100 text-sm">Theo dõi và quản lý danh sách email thông báo</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
