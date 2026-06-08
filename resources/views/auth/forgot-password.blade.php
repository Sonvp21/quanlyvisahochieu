<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu · GIRC System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <div class="text-center mb-8">
        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl items-center justify-center shadow-xl mb-4">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">GIRC System</h1>
        <p class="text-slate-400 text-sm mt-1">Quên mật khẩu</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-bold text-white mb-2">Đặt lại mật khẩu</h2>
        <p class="text-sm text-slate-400 mb-6">Nhập email của bạn và chúng tôi sẽ gửi link đặt lại mật khẩu.</p>

        @if(session('status'))
        <div class="flex items-center gap-2 px-4 py-3 bg-emerald-900/50 border border-emerald-700 rounded-xl text-sm text-emerald-400 mb-4">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('email') border-red-500 @enderror"
                    placeholder="your@email.com"/>
                @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/30">
                Gửi link đặt lại mật khẩu
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                ← Quay lại đăng nhập
            </a>
        </div>
    </div>

    <p class="text-center text-xs text-slate-600 mt-6">
        © {{ date('Y') }} GIRC System · Trường Đại học Nông Lâm Thái Nguyên
    </p>
</div>

</body>
</html>