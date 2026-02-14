<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Hệ thống Quản lý Visa & Hộ chiếu</title>

    <!-- Tailwind CSS CDN - Always loads -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <!-- Logo không có background -->
                        <img src="{{ asset('images/logo.jpg') }}" alt="GIRC Logo"
                            class="w-12 h-12 rounded-xl object-cover shadow-lg">

                        <!-- Badge cam góc phải -->
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-full border-2 border-white">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">GIRC</h1>
                        <p class="text-xs text-gray-600">Visa Management</p>
                    </div>
                </div>
                </a>

                <!-- Auth Links -->
                @if (Route::has('login'))
                    <div class="flex items-center gap-3 sm:gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 sm:px-6 py-2 sm:py-2.5 bg-sky-600 text-white rounded-xl font-semibold hover:bg-sky-700 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-xl font-semibold hover:from-sky-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base">
                                {{-- class="px-4 sm:px-6 py-2 sm:py-2.5 text-gray-700 hover:text-sky-600 font-medium transition-colors text-sm sm:text-base"> --}}
                                Đăng nhập
                            </a>
                            {{-- @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 sm:px-6 py-2 sm:py-2.5 bg-gradient-to-r from-sky-600 to-blue-700 text-white rounded-xl font-semibold hover:from-sky-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl text-sm sm:text-base">
                                    Đăng ký
                                </a>
                            @endif --}}
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen overflow-hidden pt-20">
        <!-- Background with Gradient -->
        {{-- <div class="absolute inset-0 bg-gradient-to-br from-sky-900 via-blue-800 to-sky-700"> --}}
        <div class="absolute inset-0 bg-gradient-to-br from-green-900 via-green-800 to-sky-700">

            <div class="absolute inset-0 opacity-20"
                style="background-image: radial-gradient(circle, rgba(255, 255, 255, 0.15) 1px, transparent 1px); background-size: 30px 30px;">
            </div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-sky-400/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-amber-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Left Content -->
                <div class="text-white space-y-6 sm:space-y-8">
                    <!-- Badge -->
                    <div
                        class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 sm:px-5 py-2 sm:py-2.5 rounded-full border border-white/20">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-400"></span>
                        </span>
                        <span class="text-xs sm:text-sm font-medium">Hệ thống quản lý visa & hộ chiếu</span>
                    </div>

                    <!-- Main Heading -->
                    <div class="space-y-4">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">
                            Quản lý Visa,
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight">Hộ chiếu</h1>

                            {{-- <span
                                class="block bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-300 bg-clip-text text-transparent">
                                Chuyên nghiệp
                            </span> --}}
                        </h1>
                        <p class="text-lg sm:text-xl lg:text-2xl text-blue-100 leading-relaxed font-light">
                            Hệ thống tự động hóa toàn bộ quy trình quản lý visa và hộ chiếu cho sinh viên quốc tế
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-sky-700 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 space-x-2">
                                    <span>Vào Dashboard</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-sky-700 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1 space-x-2">
                                    <span>Bắt đầu ngay</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                                <a href="#features"
                                    class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white/10 backdrop-blur-md text-white rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/30">
                                    Tìm hiểu thêm
                                </a>
                            @endauth
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6 sm:gap-8 pt-8">
                        <div class="text-center">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-2">500+</div>
                            <div class="text-xs sm:text-sm text-blue-200">Sinh viên</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-2">20+</div>
                            <div class="text-xs sm:text-sm text-blue-200">Quốc gia</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-2">100%</div>
                            <div class="text-xs sm:text-sm text-blue-200">Tự động</div>
                        </div>
                    </div>
                </div>

                <!-- Right Visual -->
                <div class="relative">
                    <!-- Main Card -->
                    <div class="relative bg-white rounded-3xl shadow-2xl p-6 sm:p-8">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1">Quản lý thông minh</h3>
                                <p class="text-sm text-gray-600">Tự động hóa hoàn toàn</p>
                            </div>
                            <div
                                class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-sky-500 to-sky-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-4 sm:space-y-5">
                            <div
                                class="flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-blue-50 rounded-2xl hover:bg-blue-100 transition-colors">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Quản lý hồ sơ tập
                                        trung</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Lưu trữ và quản lý toàn bộ hồ sơ sinh
                                        viên</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-purple-50 rounded-2xl hover:bg-purple-100 transition-colors">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Theo dõi hạn visa
                                        tự động</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Cảnh báo trước 30 ngày hết hạn</p>
                                </div>
                            </div>

                            <div
                                class="flex items-start space-x-3 sm:space-x-4 p-3 sm:p-4 bg-amber-50 rounded-2xl hover:bg-amber-100 transition-colors">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Thông báo email
                                        tự động</h4>
                                    <p class="text-xs sm:text-sm text-gray-600">Gửi thông báo nhắc nhở sinh viên</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Badge -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Đã xử lý hôm nay</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl font-bold text-sky-600">24</span>
                                    <span class="text-xs text-gray-500">visa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Decorative Elements -->
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-amber-400/40 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-sky-400/40 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 sm:mb-16">
                <span class="text-sky-600 font-semibold text-xs sm:text-sm uppercase tracking-wider">Tính năng nổi
                    bật</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mt-3 mb-4">
                    Giải pháp toàn diện cho quản lý visa, hộ chiếu
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto">
                    Tự động hóa quy trình, tiết kiệm thời gian và đảm bảo không bỏ sót bất kỳ hồ sơ nào
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-cyan-50 p-6 sm:p-8 rounded-3xl border border-blue-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Quản lý sinh viên</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Lưu trữ đầy đủ thông tin cá nhân và giấy tờ của từng sinh viên quốc tế
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-gradient-to-br from-purple-50 to-pink-50 p-6 sm:p-8 rounded-3xl border border-purple-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Cảnh báo thông minh</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Hệ thống tự động gửi thông báo trước khi visa và hộ chiếu hết hạn
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-gradient-to-br from-amber-50 to-orange-50 p-6 sm:p-8 rounded-3xl border border-amber-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Báo cáo chi tiết</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Thống kê và báo cáo tình trạng visa theo thời gian thực
                    </p>
                </div>

                <!-- Feature 4 -->
                <div
                    class="bg-gradient-to-br from-emerald-50 to-teal-50 p-6 sm:p-8 rounded-3xl border border-emerald-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Bảo mật cao</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Mã hóa dữ liệu và phân quyền truy cập đảm bảo an toàn thông tin
                    </p>
                </div>

                <!-- Feature 5 -->
                <div
                    class="bg-gradient-to-br from-rose-50 to-red-50 p-6 sm:p-8 rounded-3xl border border-rose-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Upload tài liệu</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Tải lên và lưu trữ các tài liệu scan: visa, hộ chiếu, giấy tờ liên quan
                    </p>
                </div>

                <!-- Feature 6 -->
                <div
                    class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 sm:p-8 rounded-3xl border border-indigo-100 hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Tự động gửi mail</h3>
                    <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                        Tự động gửi email nhắc nhở sinh viên khi gần đến hạn visa, hộ chiếu
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 bg-gradient-to-br from-green-600 via-green-700 to-sky-800 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10"
            style="background-image: radial-gradient(circle, rgba(255, 255, 255, 0.15) 1px, transparent 1px); background-size: 30px 30px;">
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 sm:mb-6">
                Sẵn sàng quản lý visa, hộ chiếu hiệu quả hơn?
            </h2>
            <p class="text-lg sm:text-xl text-blue-100 mb-6 sm:mb-8 leading-relaxed">
                Tham gia hệ thống ngay hôm nay và trải nghiệm sự khác biệt trong quản lý sinh viên quốc tế
            </p>
            @if (Route::has('login'))
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-sky-700 rounded-xl font-bold hover:bg-gray-100 transition-all duration-300 shadow-2xl hover:shadow-3xl hover:-translate-y-1 text-base sm:text-lg">
                    <span>Đăng nhập</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            @endif
        </div>
    </section>


    <!-- Footer -->
    <footer id="footer" class="bg-gray-900 text-white py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12 mb-8 sm:mb-12">
                <!-- Brand -->
                <div class="sm:col-span-2">
                    <a href="/">
                    <div class="flex items-center space-x-3 mb-4 sm:mb-6">
                        <div class="relative">
                            <!-- Logo không có background -->
                            <img src="{{ asset('images/logo.jpg') }}" alt="GIRC Logo"
                                class="w-12 h-12 rounded-xl object-cover shadow-lg">
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">GIRC</h3>
                            <p class="text-sm text-gray-400">Visa Management System</p>
                        </div>
                    </div>
                    </a>
                    <p class="text-sm sm:text-base text-gray-400 leading-relaxed max-w-md">
                        Hệ thống quản lý visa và hộ chiếu cho sinh viên quốc tế - Giải pháp toàn diện và
                        hiệu quả.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold text-base sm:text-lg mb-3 sm:mb-4">Liên kết nhanh</h4>
                    <ul class="space-y-2 sm:space-y-3 text-sm sm:text-base text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Tính năng</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Đăng nhập</a>
                        </li>
                        {{-- <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Đăng ký</a>
                        </li> --}}
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold text-base sm:text-lg mb-3 sm:mb-4">Liên hệ</h4>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm text-gray-400">
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-sky-400 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span>visa@girc.edu.vn</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-sky-400 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <span>(024) 3869 4242</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-sky-400 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Trung tâm Nghiên cứu Địa tin học, Thái Nguyên, Việt Nam</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-6 sm:pt-8 mt-6 sm:mt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <p class="text-gray-400 text-xs sm:text-sm text-center sm:text-left">
                        &copy; {{ date('Y') }} GIRC - Geoinformatics Research Center. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-4 sm:space-x-6 text-xs sm:text-sm text-gray-400">
                        <a href="#" class="hover:text-white transition-colors">Chính sách bảo mật</a>
                        <a href="#" class="hover:text-white transition-colors">Điều khoản sử dụng</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>
