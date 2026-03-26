<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <!-- <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png"> -->
    <link rel="icon" type="image/svg+xml" href="https://luxyformation.re/wp-content/uploads/2024/03/cropped-logo-luxy-dore-192x192.png">
    <link rel="apple-touch-icon" href="https://luxyformation.re/wp-content/uploads/2024/03/cropped-logo-luxy-dore-192x192.png/apple-touch-icon.png">

    <!-- SEO -->
    <title inertia>{{ config('app.name') }}</title>
    <meta name="description" content="Luxy Coaching & Formation — Centre de formation professionnelle et soutien scolaire à La Réunion.">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Centre de formation professionnelle et soutien scolaire à La Réunion.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_RE">

    <!-- Assets Vite -->
    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="antialiased">
    @inertia
</body>
</html>