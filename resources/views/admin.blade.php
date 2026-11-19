<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title inertia>Qeima Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>

    @routes
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @inertiaHead
</head>
<body class="bg-[#F7F7F8] font-sans text-slate-700 antialiased">
@inertia
</body>
</html>
