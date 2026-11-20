<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Hệ thống quản lý Visa &amp; Hộ chiếu</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Giữ lại các thư viện cũ để những trang chưa làm lại không bị hỏng --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important}</style>
</head>

@php
    $authUser = Auth::user();
    $role = $authUser?->role;

    // Tiêu đề thanh trên cùng theo route (đổi/ghi đè được bằng slot $header)
    $routeTitles = [
        'admin.dashboard'            => 'Tổng quan',
        'admin.students.*'           => 'Quản lý sinh viên',
        'admin.passports.*'          => 'Hộ chiếu',
        'admin.visas.*'              => 'Visa',
        'admin.delegations.*'        => 'Đoàn khách nước ngoài',
        'admin.notification-reports.*' => 'Báo cáo email',
        'student.dashboard'          => 'Tổng quan',
        'student.profile.*'          => 'Hồ sơ của tôi',
        'student.passport.*'         => 'Hộ chiếu',
        'profile.*'                  => 'Tài khoản',
    ];
    $pageTitle = 'GIRC System';
    foreach ($routeTitles as $pattern => $title) {
        if (request()->routeIs($pattern)) { $pageTitle = $title; break; }
    }
@endphp

<body class="h-full text-slate-800 antialiased" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
<div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">

    {{-- Sidebar theo vai trò --}}
    @if ($role === 'admin')
        @include('layouts.admin-navigation')
    @elseif ($role === 'student')
        @include('layouts.student-navigation')
    @endif

    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        {{-- Thanh trên cùng --}}
        <header class="sticky top-0 z-30 bg-white/85 backdrop-blur border-b border-slate-200">
            <div class="flex items-center justify-between gap-3 px-4 sm:px-6 lg:px-8 h-16">
                <div class="flex items-center gap-3 min-w-0">
                    @if ($role)
                        <button type="button" @click="sidebarOpen = true"
                                class="lg:hidden -ml-1 p-2 rounded-lg text-slate-500 hover:bg-slate-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    @endif
                    <div class="min-w-0">
                        <h1 class="text-base sm:text-lg font-semibold text-slate-900 truncate">{{ $pageTitle }}</h1>
                        <p class="text-xs text-slate-400 hidden sm:block">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if ($role === 'admin')
                        <a href="{{ route('admin.students.create') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 rounded-lg bg-blue-700 px-3.5 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-800 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Thêm sinh viên
                        </a>
                    @endif

                    @if ($authUser)
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-full pl-1 pr-3 py-1 hover:bg-slate-100 transition">
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(mb_substr($authUser->name, 0, 1)) }}
                            </span>
                            <span class="hidden md:block text-left leading-tight">
                                <span class="block text-sm font-medium text-slate-800">{{ $authUser->name }}</span>
                                <span class="block text-[11px] text-slate-400">{{ $role === 'admin' ? 'Quản trị viên' : 'Sinh viên quốc tế' }}</span>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </header>

        @isset($header)
            <div class="px-4 sm:px-6 lg:px-8 pt-6">
                {{ $header }}
            </div>
        @endisset

        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>

        <footer class="px-4 sm:px-6 lg:px-8 py-4 text-xs text-slate-400 border-t border-slate-200 bg-white">
            © {{ date('Y') }} Trung tâm Nghiên cứu Địa tin học (GIRC) · Trường Đại học Nông Lâm - Đại học Thái Nguyên
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
</body>
</html>
