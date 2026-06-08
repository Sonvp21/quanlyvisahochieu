<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Hệ thống Quản lý Visa & Hộ chiếu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50">

@if(Auth::check() && Auth::user()->role === 'admin')

    @include('layouts.admin-navigation')
    {{ $slot }}
    </div>

    <footer class="lg:ml-64 py-4 px-6 border-t border-slate-200 bg-white">
        <p class="text-xs text-slate-400 text-center">
            &copy; {{ date('Y') }} GIRC System &middot; Hệ thống Quản lý Visa & Hộ chiếu
        </p>
    </footer>

@elseif(Auth::check() && Auth::user()->role === 'student')

    @include('layouts.student-navigation')
    <main>{{ $slot }}</main>
    @include('layouts.student-footer')

@else

    @include('layouts.navigation')
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
        </header>
    @endisset
    <main>{{ $slot }}</main>
    @include('layouts.footer')

@endif

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.toggle('hidden');
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.add('-translate-x-full');
    document.getElementById('sidebarOverlay').classList.add('hidden');
}
</script>

</body>
</html>
