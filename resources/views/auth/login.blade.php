<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Đăng nhập</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        /* Background gradient phù hợp Nông Lâm & Địa tin học */
        .girc-bg {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 50%, #059669 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        /* Pattern nền */
        .girc-bg::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background-image:
                radial-gradient(circle, rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                radial-gradient(circle, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 50px 50px;
            background-position: 0 0, 25px 25px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 15s infinite ease-in-out;
        }

        .shape-1 {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape-2 {
            top: 60%;
            right: 15%;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation-delay: 2s;
        }

        .shape-3 {
            bottom: 20%;
            left: 20%;
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 20px;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* Main content wrapper - scrollable */
        .content-wrapper {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* Card shadow */
        .card-shadow {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Input focus effect */
        .input-girc {
            transition: all 0.3s ease;
        }

        .input-girc:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
            transform: translateY(-1px);
        }

        /* Button gradient */
        .btn-girc {
            background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-girc::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-girc:hover::before {
            left: 100%;
        }

        .btn-girc:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.4);
        }

        .btn-girc:active {
            transform: translateY(0);
        }

        /* Toggle password button */
        .toggle-password {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .toggle-password:hover {
            transform: scale(1.1);
        }

        .toggle-password:active {
            transform: scale(0.95);
        }

        /* Card animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Left panel background with map pattern */
        .map-pattern {
            background-image:
                linear-gradient(rgba(13, 148, 136, 0.95), rgba(5, 150, 105, 0.95)),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body>

    <!-- Fixed Background -->
    <div class="girc-bg">
        <!-- Floating shapes -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Scrollable Content -->
    <div class="content-wrapper">
        <div class="py-8 px-4 sm:px-6 lg:px-8 flex items-start md:items-center justify-center min-h-screen">
            <div class="max-w-5xl w-full">

                <div class="bg-white rounded-2xl card-shadow overflow-hidden login-card">
                    <div class="grid md:grid-cols-5">

                        <!-- Left Panel - Branding -->
                        <div class="md:col-span-2 map-pattern p-8 md:p-8 flex flex-col justify-center text-white relative overflow-hidden">

                            <!-- Decorative elements -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                            <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full -ml-20 -mb-20"></div>

                            <div class="relative z-10">
                                <!-- Logo -->
                                <div class="mb-6">
                                    <a href="/">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                                            <img src="{{ asset('images/logo.jpg') }}" alt="GIRC Logo" class="w-12 h-12 object-contain">
                                        </div>
                                        <div>
                                            <h1 class="text-xl md:text-2xl font-bold">GIRC</h1>
                                            <p class="text-xs md:text-sm text-teal-100">Geoinformatics Research Center</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                <!-- Welcome Text -->
                                <div class="space-y-3">
                                    <h2 class="text-2xl md:text-3xl font-bold leading-tight">
                                        Chào mừng trở lại!
                                    </h2>
                                    <p class="text-teal-100 text-sm leading-relaxed">
                                        Hệ thống Quản lý Visa & Hộ chiếu Sinh viên Quốc tế
                                    </p>

                                    <!-- Features List -->
                                    <div class="space-y-2 mt-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-7 h-7 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Quản lý thông tin hiệu quả</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-7 h-7 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Theo dõi visa, hộ chiếu tự động</span>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <div class="w-7 h-7 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm">Thông báo hết hạn kịp thời</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- University Info -->
                                <div class="mt-8 pt-6 border-t border-white border-opacity-20">
                                    <p class="text-xs text-teal-100">
                                        Trường Đại học Nông Lâm Thái Nguyên
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel - Login Form -->
                        <div class="md:col-span-3 p-8 md:p-12">

                            <!-- Header -->
                            <div class="mb-6">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Đăng nhập</h2>
                                <p class="text-sm text-gray-600">Vui lòng đăng nhập để tiếp tục</p>
                            </div>

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="mb-6 p-4 bg-teal-50 border-l-4 border-teal-500 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-teal-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm text-teal-800 font-medium">{{ session('status') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                @csrf

                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Địa chỉ Email
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                            autofocus
                                            autocomplete="username"
                                            class="input-girc block w-full pl-12 pr-4 py-3.5 text-gray-900 border-2 border-gray-200 rounded-xl focus:outline-none placeholder-gray-400"
                                            placeholder="email@example.com"
                                        >
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Mật khẩu
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                        </div>
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autocomplete="current-password"
                                            class="input-girc block w-full pl-12 pr-12 py-3.5 text-gray-900 border-2 border-gray-200 rounded-xl focus:outline-none placeholder-gray-400"
                                            placeholder="••••••••"
                                        >
                                        <!-- Toggle Password Button -->
                                        <button
                                            type="button"
                                            onclick="togglePassword()"
                                            class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                                        >
                                            <!-- Eye Icon (Show) -->
                                            <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <!-- Eye Slash Icon (Hide) -->
                                            <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="flex items-center justify-end">
                                    {{-- <label class="flex items-center cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            name="remember"
                                            class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 focus:ring-2 cursor-pointer"
                                        >
                                        <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 transition">Ghi nhớ</span>
                                    </label> --}}

                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-teal-600 hover:text-teal-700 transition">
                                            Quên mật khẩu?
                                        </a>
                                    @endif
                                </div>

                                <!-- Login Button -->
                                <button
                                    type="submit"
                                    class="btn-girc w-full flex items-center justify-center px-6 py-4 text-white font-semibold rounded-xl focus:outline-none focus:ring-4 focus:ring-teal-300 shadow-lg"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Đăng nhập
                                </button>

                            </form>

                            <!-- Footer Info -->
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <p class="text-xs text-gray-500 text-center">
                                    Bạn gặp vấn đề khi đăng nhập?
                                    <a href="/#footer" class="text-teal-600 hover:text-teal-700 font-medium">Liên hệ hỗ trợ</a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-white text-opacity-90 font-medium">
                        © {{ date('Y') }} GIRC - Geoinformatics Research Center
                    </p>
                    <p class="text-xs text-white text-opacity-75 mt-1">
                        Trường Đại học Nông Lâm Thái Nguyên
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>

</body>
</html>
