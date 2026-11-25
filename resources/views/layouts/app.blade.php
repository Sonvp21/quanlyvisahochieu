<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Hợp tác quốc tế &amp; Đối ngoại</title>

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
    $isHome = request()->routeIs('home');

    // Tiêu đề thanh trên cùng theo route
    $routeTitles = [
        'home'                       => 'Trang chủ',
        'admin.dashboard'            => 'Dashboard visa',
        'admin.students.*'           => 'Quản lý sinh viên',
        'admin.passports.*'          => 'Hộ chiếu',
        'admin.visas.*'              => 'Visa',
        'admin.delegations.*'        => 'Đoàn khách nước ngoài',
        'trips.*'                    => 'Đoàn ra & đoàn vào',
        'admin.users.*'              => 'Tài khoản',
        'overview'                   => 'Tổng quan',
        'stats.*'                    => 'Thống kê',
        'plans.*'                    => 'Kế hoạch năm',
        'admin.partners.*'           => 'Đối tác',
        'admin.agreements.*'         => 'MOU & thỏa thuận',
        'admin.exchange-*'           => 'Trao đổi sinh viên',
        'admin.mail-*'               => 'Email tự động',
        'admin.audit-logs.*'         => 'Nhật ký thao tác',
        'admin.people.*'             => 'Hồ sơ nhân sự & khách',
        'admin.units.*'              => 'Cơ cấu đơn vị',
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

    $tabs = $authUser ? \App\Support\Menu::tabs($authUser) : [];
    $createItems = [];
    if ($authUser && $tabs && $authUser->role !== 'student') {
        $plusIcon = config('menu.icons.plus');
        foreach (\App\Support\Menu::groups($authUser) as $g) {
            foreach ($g['items'] as $it) {
                if ($it['icon'] === $plusIcon) { $createItems[] = $it; }
            }
        }
    }
    // Quay lại theo trang cha cố định (không theo lịch sử, tránh vòng lặp danh sách <-> tạo mới)
    $backUrl = route('home');
    $rn = request()->route()?->getName();
    if ($rn && preg_match('/^(.+)\.([a-z\-]+)$/', $rn, $m) && $m[2] !== 'index') {
        $parent = $m[1] . '.index';
        if (\Illuminate\Support\Facades\Route::has($parent)) {
            $backUrl = route($parent);
        }
    }
@endphp

<body class="app-shell h-full text-slate-800 antialiased" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
<div class="flex min-h-screen flex-col">

    {{-- Thanh trên: điện thoại = nút quay lại + tiêu đề (trang chủ có đầu trang riêng); máy tính = logo + tab + người dùng --}}
    <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur {{ $isHome ? 'hidden lg:block' : '' }}" style="padding-top: env(safe-area-inset-top)">
        {{-- Điện thoại --}}
        <div class="flex h-14 items-center gap-3 px-3 sm:px-6 lg:hidden">
            <a href="{{ $backUrl }}" aria-label="Quay lại" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-slate-600 transition hover:bg-slate-100 active:scale-95">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="min-w-0 flex-1 truncate text-[17px] font-semibold text-slate-900">{{ $pageTitle }}</h1>
            @if ($authUser)
                <div class="relative shrink-0" x-data="{ userMenu: false }" @keydown.escape.window="userMenu = false">
                    <button type="button" @click="userMenu = !userMenu" aria-label="Tài khoản" class="flex h-9 items-center gap-0.5 rounded-full bg-blue-100 pl-3 pr-2 text-sm font-semibold text-blue-700 transition active:scale-90">{{ strtoupper(mb_substr($authUser->name, 0, 1)) }}<svg class="h-4 w-4 shrink-0 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/></svg></button>
                    <div x-show="userMenu" x-cloak x-transition.opacity @click.outside="userMenu = false"
                         class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $authUser->name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ $authUser->email }} · {{ \App\Models\User::ROLES[$role] ?? $role }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Thông tin cá nhân
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        {{-- Máy tính --}}
        <div class="mx-auto hidden h-16 max-w-6xl items-center justify-between gap-6 px-8 lg:flex">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-700 to-indigo-600 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ config('menu.icons.globe') }}"/></svg>
                </span>
                <span class="leading-tight">
                    <span class="block text-[15px] font-bold text-slate-900">GIRC System</span>
                    <span class="block text-[11px] text-slate-400">Hợp tác quốc tế &amp; Đối ngoại</span>
                </span>
            </a>

            <nav class="flex items-center gap-1">
                @foreach ($tabs as $t)
                    @if (!empty($t['primary']))
                        <a href="{{ $t['href'] }}" class="ml-2 inline-flex items-center gap-1.5 rounded-full bg-blue-700 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="{{ $t['icon'] }}"/></svg>{{ $t['label'] }}
                        </a>
                    @else
                        <a href="{{ $t['href'] }}" class="rounded-full px-4 py-2 text-sm font-medium transition {{ $t['active'] ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">{{ $t['label'] }}</a>
                    @endif
                @endforeach
            </nav>

            @if ($authUser)
                <div class="relative shrink-0" x-data="{ userMenu: false }" @keydown.escape.window="userMenu = false">
                    <button type="button" @click="userMenu = !userMenu" class="flex items-center gap-2.5 rounded-full py-1 pl-1 pr-3 transition hover:bg-slate-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-700">{{ strtoupper(mb_substr($authUser->name, 0, 1)) }}</span>
                    <span class="text-left leading-tight">
                        <span class="block max-w-[10rem] truncate text-sm font-medium text-slate-800">{{ $authUser->name }}</span>
                        <span class="block text-[11px] text-slate-400">{{ \App\Models\User::ROLES[$role] ?? $role }}</span>
                    </span>
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 9l-7 7-7-7"/></svg></button>
                    <div x-show="userMenu" x-cloak x-transition.opacity @click.outside="userMenu = false"
                         class="absolute right-0 top-full z-50 mt-2 w-64 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $authUser->name }}</p>
                            <p class="truncate text-xs text-slate-400">{{ $authUser->email }} · {{ \App\Models\User::ROLES[$role] ?? $role }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 transition hover:bg-slate-50">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Thông tin cá nhân
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </header>

    @isset($header)
        <div class="px-4 pt-6 sm:px-6 lg:px-8">{{ $header }}</div>
    @endisset

    <main class="flex-1 pb-28 lg:pb-10 {{ $isHome ? '' : 'px-4 pt-6 sm:px-6 lg:px-8' }}">
        <div class="{{ $isHome ? '' : 'mx-auto max-w-7xl' }}">
            {{ $slot }}
        </div>
    </main>
</div>

{{-- Thanh tab dưới cùng (điện thoại, máy tính bảng) --}}
@if ($authUser && $tabs)
<div x-data="{ createOpen: false }" @keydown.escape.window="createOpen = false">
    <nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur lg:hidden" style="padding-bottom: env(safe-area-inset-bottom)">
        <div class="mx-auto grid max-w-lg items-end px-2" style="grid-template-columns: repeat({{ count($tabs) }}, minmax(0, 1fr));">
            @foreach ($tabs as $t)
                @if (!empty($t['primary']))
                    @if (count($createItems) > 1)
                        <button type="button" @click="createOpen = true" class="relative -mt-5 flex flex-col items-center pb-1.5" aria-label="{{ $t['label'] }}">
                    @else
                        <a href="{{ $t['href'] }}" class="relative -mt-5 flex flex-col items-center pb-1.5" aria-label="{{ $t['label'] }}">
                    @endif
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/30 ring-4 ring-white transition active:scale-90">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="{{ $t['icon'] }}"/></svg>
                        </span>
                        <span class="mt-0.5 text-[11px] font-medium {{ $t['active'] ? 'text-blue-700' : 'text-slate-500' }}">{{ $t['label'] }}</span>
                    @if (count($createItems) > 1)</button>@else</a>@endif
                @else
                    <a href="{{ $t['href'] }}" class="flex flex-col items-center gap-0.5 py-2 transition active:scale-90 {{ $t['active'] ? 'text-blue-700' : 'text-slate-400' }}">
                        <svg class="h-6 w-6" fill="{{ $t['active'] ? 'rgba(37,99,235,.12)' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $t['active'] ? '2' : '1.7' }}" d="{{ $t['icon'] }}"/></svg>
                        <span class="text-[11px] font-medium">{{ $t['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    </nav>
    {{-- Bảng chọn "Tạo mới" --}}
    @if (count($createItems) > 1)
        <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 lg:hidden" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-slate-900/40" x-show="createOpen" x-transition.opacity @click="createOpen = false"></div>
            <div class="absolute inset-x-0 bottom-0 rounded-t-3xl bg-white px-5 pt-3 shadow-2xl" style="padding-bottom: calc(2rem + env(safe-area-inset-bottom))"
                 x-show="createOpen"
                 x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-slate-200"></div>
                <p class="mb-4 text-center text-base font-semibold text-slate-900">Tạo mới</p>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ($createItems as $ci)
                        <a href="{{ $ci['href'] }}" class="flex flex-col items-center gap-2 rounded-2xl bg-slate-50 px-2 py-4 text-center transition active:scale-95">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $ci['icon'] }}"/></svg>
                            </span>
                            <span class="text-xs font-medium leading-tight text-slate-700">{{ $ci['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endif

<script>
// Điện thoại: bảng -> thẻ. Gắn nhãn cột vào từng ô để CSS hiển thị "nhãn: giá trị".
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('main table').forEach(function (t) {
        if (t.hasAttribute('data-no-cards')) return;
        var heads = Array.from(t.querySelectorAll('thead th')).map(function (th) { return th.textContent.trim(); });
        if (!heads.length) return;
        t.classList.add('rt');
        t.querySelectorAll('tbody tr').forEach(function (tr) {
            Array.from(tr.children).forEach(function (td, i) {
                if (td.tagName === 'TD' && !td.hasAttribute('colspan')) td.setAttribute('data-label', heads[i] || '');
            });
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
</body>
</html>
