<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GIRC System · Quản lý Visa & Hộ chiếu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-white min-h-screen">

    {{-- NAV --}}
    <nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-6 py-4 bg-slate-950/80 backdrop-blur-md border-b border-slate-800/50">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <span class="font-bold text-white">GIRC System</span>
        </div>
        @if(Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Vào hệ thống
            </a>
            @else
            <a href="{{ route('login') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Đăng nhập
            </a>
            @endauth
        @endif
    </nav>

    {{-- HERO --}}
    <div class="min-h-screen flex flex-col items-center justify-center px-6 text-center pt-20">

        {{-- Background glow --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 left-1/3 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-3xl mx-auto space-y-6">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-xs font-semibold">
                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
                Hệ thống Quản lý Du học sinh
            </div>

            {{-- Title --}}
            <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight">
                Quản lý
                <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">Visa & Hộ chiếu</span>
                <br>thông minh
            </h1>

            <p class="text-lg text-slate-400 max-w-xl mx-auto leading-relaxed">
                Theo dõi tình trạng visa và hộ chiếu của sinh viên quốc tế.
                Tự động thông báo khi sắp hết hạn. Quản lý tập trung, hiệu quả.
            </p>

            {{-- CTA --}}
            <div class="flex items-center justify-center gap-4 pt-2">
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    Vào hệ thống
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/40">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Đăng nhập
                </a>
                @endauth
            </div>

            {{-- Features --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-12">
                @php
                    $features = [
                        ['Theo dõi tự động', 'Cảnh báo visa và hộ chiếu sắp hết hạn theo thời gian thực', 'from-blue-500 to-blue-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['Thông báo email', 'Gửi email nhắc nhở tự động hàng ngày đến sinh viên', 'from-violet-500 to-violet-600', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['Báo cáo chi tiết', 'Theo dõi lịch sử gửi email và trạng thái từng sinh viên', 'from-emerald-500 to-emerald-600', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ];
                @endphp
                @foreach($features as [$title, $desc, $gradient, $icon])
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 text-left hover:border-slate-700 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center mb-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                    </div>
                    <div class="font-bold text-white mb-1 text-sm">{{ $title }}</div>
                    <div class="text-xs text-slate-400 leading-relaxed">{{ $desc }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="py-6 text-center border-t border-slate-800">
        <p class="text-xs text-slate-600">
            © {{ date('Y') }} GIRC System · Trường Đại học Nông Lâm, Quyết Thắng, Thái Nguyên
        </p>
    </footer>

</body>
</html>