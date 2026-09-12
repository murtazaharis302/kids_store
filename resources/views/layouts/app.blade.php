<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Al Hayat Kids' }}</title>
    @if (file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifestPath = public_path('build/manifest.json');
            $cssFile = 'assets/app-BzJL9BKc.css';
            $jsFile = 'assets/app-l0sNRNKZ.js';
            if (file_exists($manifestPath)) {
                $manifest = json_decode(@file_get_contents($manifestPath), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? $cssFile;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? $jsFile;
            }
        @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
        <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
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
