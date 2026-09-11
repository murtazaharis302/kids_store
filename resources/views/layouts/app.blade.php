<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Al Hayat Kids' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
