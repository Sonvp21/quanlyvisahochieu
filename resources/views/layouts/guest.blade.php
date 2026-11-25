<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1d4ed8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GIRC') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell flex min-h-screen items-center justify-center bg-gradient-to-b from-blue-700 via-blue-600 to-indigo-700 p-4 antialiased" style="font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;">
    {{ $slot }}
</body>
</html>
