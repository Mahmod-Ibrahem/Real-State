<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ $page['props']['metaTitle'] ?? 'Qeima | Best Property Finder In Egypt' }}</title>
    <meta inertia name="description"
          content="{{ $page['props']['metaDescription'] ?? 'Find your dream property in Egypt with Qeima – the best platform for buying, selling, and renting real estate.' }}">
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta inertia property="og:url" content="{{ url()->current() }}">
    <meta inertia property="og:title"
          content="{{ $page['props']['property']['name'] ?? 'Qeima | Best Property Finder In Egypt'}}">
    <meta inertia property="og:description"
          content="{{ $page['props']['property']['description'] ?? 'Find your dream property in Egypt with Qeima – the best platform for buying, selling, and renting real estate.'}}">
    <meta inertia property="og:image" content="{{ $page['props']['property']['image_url'] ?? ''}}">
    <meta property="og:locale" content="en_US"/>
    <meta inertia property="og:image:alt" content="{{ $page['props']['property']['name'] ?? 'Property Image'}}"/>
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta inertia name="twitter:url" content="{{ url()->current() }}">
    <meta inertia name="twitter:title"
          content="{{ $page['props']['property']['name'] ?? 'Qeima | Best Property Finder In Egypt'}}">
    <meta inertia name="twitter:description"
          content="{{ $page['props']['property']['description'] ?? 'Find your dream property in Egypt with Qeima – the best platform for buying, selling, and renting real estate.'}}">
    <meta inertia name="twitter:image" content="{{ $page['props']['property']['image_url'] ?? ''}}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @inertiaHead
</head>
<body class="antialiased bg-slate-50/50">
@inertia
</body>
</html>
