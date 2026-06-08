<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu · GIRC System</title>
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
        <p class="text-slate-400 text-sm mt-1">Đặt lại mật khẩu</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8 shadow-2xl">
        <h2 class="text-lg font-bold text-white mb-6">Mật khẩu mới</h2>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none transition @error('email') border-red-500 @enderror"/>
                @error('email')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Mật khẩu mới</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none transition @error('password') border-red-500 @enderror"
                    placeholder="••••••••"/>
                @error('password')<p class="text-xs text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none transition"
                    placeholder="••••••••"/>
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/30">
                Đặt lại mật khẩu
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-slate-600 mt-6">
        © {{ date('Y') }} GIRC System · Trường Đại học Nông Lâm Thái Nguyên
    </p>
</div>

</body>
</html>