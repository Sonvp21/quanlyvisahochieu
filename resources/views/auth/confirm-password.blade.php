<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Xác nhận mật khẩu · GIRC System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="inline-flex w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl items-center justify-center shadow-xl mb-4">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">GIRC System</h1>
    </div>
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-bold text-white mb-2">Xác nhận mật khẩu</h2>
        <p class="text-sm text-slate-400 mb-6">Đây là khu vực bảo mật. Vui lòng xác nhận mật khẩu trước khi tiếp tục.</p>
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Mật khẩu</label>
                <input type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                    placeholder="••••••••"/>
                @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors">
                Xác nhận
            </button>
        </form>
    </div>
</div>
</body>
</html>