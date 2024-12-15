<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title inertia>{{ config('app.name', 'TRADE WISE') }}</title>
        <!-- Primary Meta Tags -->
        <meta name="title" content="TRADE WISE">
        <meta name="author" content="TRADE WISE">
        <meta name="description" content="Trade Wise is a comprehensive trading money management platform that helps users manage their funds effectively. Track, grow, and optimize your investments with our intuitive tools and expert insights. Start managing your money smarter with Trade Wise today!">
        <meta name="keywords" content="Trade Wise, trading platform, money management, investment management, fund management, personal finance, investment tracking, financial tools, smart investing, portfolio management, trading tools, wealth management, finance tracking, online trading, crypto" />
        <link rel="canonical" href="{{ url('/') }}">
        <link rel='shortlink' href='{{ url('/') }}' />

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="TRADE WISE">
        <meta property="og:description" content="Trade Wise is a comprehensive trading money management platform that helps users manage their funds effectively. Track, grow, and optimize your investments with our intuitive tools and expert insights. Start managing your money smarter with Trade Wise today!">
        <meta property="og:image" content="{{ asset('assets/img/preview.jpg') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="TRADE WISE">
        <meta property="twitter:description" content="Trade Wise is a comprehensive trading money management platform that helps users manage their funds effectively. Track, grow, and optimize your investments with our intuitive tools and expert insights. Start managing your money smarter with Trade Wise today!">
        <meta property="twitter:image" content="{{ asset('assets/img/preview.jpg') }}">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/img/favicon/android-chrome-512x512.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/favicon/android-chrome-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('assets/img/favicon/safari-pinned-tab.svg') }}" color="#ffffff">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">

        <!-- Icons -->
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/scss/volt.scss', 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
