<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col font-sans antialiased bg-gray-100">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- 🔥 ROLE VIEW BADGE --}}
    @auth
        <div class="fixed bottom-16 right-5 text-xs text-gray-600 bg-white px-3 py-1 rounded-lg shadow z-50">
            Viewing as:
            <span class="font-semibold">
                {{ session('role_override') ?? auth()->user()->role }}
            </span>
        </div>
    @endauth

    {{-- Header --}}
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Content --}}
    <main class="flex-1 w-full">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('components.footer')

</body>
</html>
