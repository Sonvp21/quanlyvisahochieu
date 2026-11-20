<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hệ thống quản lý thông tin người nước ngoài đến học tập, công tác</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Bỏ nền xanh nhạt của Chrome khi tự điền email/mật khẩu */
        input:-webkit-autofill,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #f8fafc inset !important;
            -webkit-text-fill-color: #0f172a !important;
        }
    </style>
</head>
{{-- Màn hình lớn (lg trở lên): khóa đúng 1 màn hình, không scroll. Màn nhỏ: cuộn bình thường --}}
<body class="min-h-screen lg:h-screen lg:overflow-hidden flex flex-col bg-slate-50 text-slate-800 antialiased"
      style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">

    {{-- ================= HEADER: nền xanh đặc, ảnh thật mờ dần về bên phải ================= --}}
    <header class="relative shrink-0 overflow-hidden bg-blue-900 text-white">
        <img src="{{ asset('images/banner.jpg') . '?v=' . filemtime(public_path('images/banner.jpg')) }}" alt=""
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-800/80 to-blue-900/90"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-6">
            <h1 class="text-center text-lg sm:text-xl lg:text-2xl font-bold leading-snug lg:whitespace-nowrap">
                Hệ thống quản lý thông tin người nước ngoài đến học tập, công tác
            </h1>
        </div>
    </header>

    {{-- ================= MAIN ================= --}}
    <main class="flex-1 min-h-0">
        <div class="h-full max-w-7xl mx-auto px-6 py-6 grid lg:grid-cols-12 gap-6 lg:gap-10 items-stretch">

            {{-- ---------- Trái: ảnh thật ---------- --}}
            <div class="order-2 lg:order-1 lg:col-span-8 relative min-h-[260px] lg:min-h-0 rounded-3xl overflow-hidden shadow-xl shadow-slate-300/70">
                <img src="{{ asset('images/hero1.jpg') }}" alt="Quản lý hộ chiếu, visa của người nước ngoài trên hệ thống"
                     class="absolute inset-0 w-full h-full object-cover object-[25%_center]">
                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-blue-950/80 via-blue-950/30 to-transparent"></div>

                {{-- Chữ đặt góc phải dưới để không che hộ chiếu/visa ở bên trái ảnh --}}
                <div class="absolute left-6 right-6 bottom-6 text-white sm:left-auto sm:max-w-md sm:text-right">
                    <p class="text-xl sm:text-2xl font-semibold leading-snug drop-shadow">
                        Quản lý hộ chiếu, visa và tạm trú<br class="hidden sm:block"> của người nước ngoài trong một hệ thống
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2 sm:justify-end">
                        <span class="rounded-full bg-white/15 ring-1 ring-white/30 backdrop-blur px-3 py-1 text-xs font-medium">Nhắc hạn tự động</span>
                        <span class="rounded-full bg-white/15 ring-1 ring-white/30 backdrop-blur px-3 py-1 text-xs font-medium">Xuất Mẫu số 01</span>
                        <span class="rounded-full bg-white/15 ring-1 ring-white/30 backdrop-blur px-3 py-1 text-xs font-medium">Công văn đoàn khách</span>
                    </div>
                </div>
            </div>

            {{-- ---------- Phải: đăng nhập ---------- --}}
            <div class="order-1 lg:order-2 lg:col-span-4 flex items-center">
                <div class="w-full max-w-md mx-auto lg:mx-0 rounded-3xl bg-white border border-slate-200 shadow-xl shadow-slate-300/50 p-7 sm:p-9">

                    @auth
                        <div class="text-center">
                            <div class="mx-auto w-14 h-14 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xl font-bold">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <h2 class="mt-4 text-xl font-bold text-slate-900">Xin chào, {{ Auth::user()->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500">Bạn đã đăng nhập vào hệ thống.</p>
                            <a href="{{ route('dashboard') }}"
                               class="mt-6 inline-flex w-full justify-center items-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-800 transition">
                                Vào trang quản lý
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    @else
                        <h2 class="text-2xl font-bold text-slate-900">Đăng nhập</h2>
                        <p class="mt-1.5 text-sm text-slate-500">Dành cho quản trị viên và sinh viên quốc tế.</p>

                        @if ($errors->any())
                            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                <div class="relative mt-1.5">
                                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                           placeholder="email@girc.edu.vn"
                                           class="w-full rounded-xl border border-slate-300 bg-slate-50 pl-10 pr-3 py-3 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                </div>
                            </div>

                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu</label>
                                <div class="relative mt-1.5">
                                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                           placeholder="Nhập mật khẩu"
                                           class="w-full rounded-xl border border-slate-300 bg-slate-50 pl-10 pr-16 py-3 text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 transition">
                                    <button type="button" @click="show = !show"
                                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-500 hover:text-blue-700"
                                            x-text="show ? 'Ẩn' : 'Hiện'"></button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <label class="inline-flex items-center gap-2 text-slate-600">
                                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-700 focus:ring-blue-600">
                                    Ghi nhớ đăng nhập
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="font-medium text-blue-700 hover:text-blue-900">Quên mật khẩu?</a>
                                @endif
                            </div>

                            <button type="submit"
                                    class="w-full rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition">
                                Đăng nhập
                            </button>
                        </form>

                        <p class="mt-5 text-center text-xs text-slate-400">Hệ thống chỉ dành cho người dùng được cấp tài khoản.</p>
                    @endauth
                </div>
            </div>
        </div>
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="shrink-0 bg-blue-900 text-blue-100">
        <div class="max-w-7xl mx-auto px-6 py-3 flex flex-col sm:flex-row items-center justify-between gap-0.5 text-xs">
            <span>© {{ date('Y') }} Trung tâm Nghiên cứu Địa tin học (GIRC)</span>
            <span>Trường Đại học Nông Lâm - Đại học Thái Nguyên</span>
        </div>
    </footer>

</body>
</html>
