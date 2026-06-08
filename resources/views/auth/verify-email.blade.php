<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Xác thực email · GIRC System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl items-center justify-center shadow-xl mb-4">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">GIRC System</h1>
    </div>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-bold text-white mb-2">Xác thực email</h2>
        <p class="text-sm text-slate-400 mb-6">
            Cảm ơn bạn đã đăng ký! Vui lòng kiểm tra email và nhấn vào link xác thực.
            Nếu chưa nhận được email, hãy gửi lại.
        </p>
        @if(session('status') == 'verification-link-sent')
        <div class="flex items-center gap-2 px-4 py-3 bg-emerald-900/50 border border-emerald-700 rounded-xl text-sm text-emerald-400 mb-4">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Email xác thực đã được gửi lại!
        </div>
        @endif
        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors">
                    Gửi lại email xác thực
                </button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full py-2.5 text-sm font-medium text-slate-400 hover:text-slate-300 transition-colors">
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>