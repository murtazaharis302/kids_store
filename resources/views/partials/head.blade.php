<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

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
@fluxAppearance
