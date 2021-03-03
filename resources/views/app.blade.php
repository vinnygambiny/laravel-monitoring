<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Monitoring</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('css/app.css', 'vendor/monitoring')) }}">

    <!-- Scripts -->
    @routes
    <script src="{{ asset(mix('js/app.js', 'vendor/monitoring')) }}" defer></script>
</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
