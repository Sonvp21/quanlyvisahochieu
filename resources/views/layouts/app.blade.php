<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.png') }}">
    <title>{{ config('app.name', 'GIRC') }} - Hệ thống Quản lý Visa & Hộ chiếu</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Flowbite (UI components cho Tailwind) -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.css" rel="stylesheet" />
       <!-- Tailwind CSS CDN - Always loads -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        {{-- Tự động chọn navigation dựa vào role --}}
        @if(Auth::check() && Auth::user()->role === 'admin')
            @include('layouts.admin-navigation')
        @elseif(Auth::check() && Auth::user()->role === 'student')
            @include('layouts.student-navigation')
        @else
            @include('layouts.navigation')
        @endif

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

        @if(Auth::check() && Auth::user()->role === 'admin')
            @include('layouts.admin-footer')
        @elseif(Auth::check() && Auth::user()->role === 'student')
            @include('layouts.student-footer')
        @else
            @include('layouts.footer')
        @endif

    </div>

    <!-- Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
</body>

</html>
