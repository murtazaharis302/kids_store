<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Al Hayat Kids' }}</title>
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="stylesheet" href="/style.css">
        <link rel="stylesheet" href="/build/assets/app-BzJL9BKc.css">
        <script type="module" src="/build/assets/app-l0sNRNKZ.js"></script>
    @endif
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans antialiased">
    <x-layouts.app.sidebar>
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.sidebar>
    @livewireScripts
</body>
</html>
