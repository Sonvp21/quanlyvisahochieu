<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập · GIRC System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- LOGO --}}
    <div class="text-center mb-8">
        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl items-center justify-center shadow-xl mb-4">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">GIRC System</h1>
        <p class="text-slate-400 text-sm mt-1">Hệ thống Quản lý Visa & Hộ chiếu</p>
    </div>

    {{-- CARD --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-bold text-white mb-6">Đăng nhập</h2>

        {{-- Session Status --}}
        <x-auth-session-status class="mb-4 text-sm text-emerald-400" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('email') border-red-500 @enderror"
                    placeholder="your@email.com"/>
                @error('email')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-400">Mật khẩu</label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>
                <input type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                    placeholder="••••••••"/>
                @error('password')
                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember_me"
                    class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500 focus:ring-offset-slate-900"/>
                <label for="remember_me" class="text-sm text-slate-400 cursor-pointer">Ghi nhớ đăng nhập</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/30 mt-2">
                Đăng nhập
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-slate-600 mt-6">
        © {{ date('Y') }} GIRC System · Trường Đại học Nông Lâm Thái Nguyên
    </p>
</div>

</body>
</html>