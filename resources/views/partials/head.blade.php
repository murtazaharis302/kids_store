<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@if (file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/build/assets/app-BzJL9BKc.css">
    <script type="module" src="/build/assets/app-l0sNRNKZ.js"></script>
@endif
@fluxAppearance
