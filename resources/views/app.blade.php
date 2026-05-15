<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="color-scheme: light;">
    <head>
        <script>
            (function () {
                var root = document.documentElement;
                root.classList.remove('dark');
                root.style.colorScheme = 'light';
                try { localStorage.removeItem('appearance'); } catch (e) {}
                document.cookie = 'appearance=;path=/;max-age=0;SameSite=Lax';
            })();
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Basic SEO -->
        <meta name="description" content="CiSeTU - Digital villages, connected at the scale of your community. The platform for modern rural administration — automated, secure, and built for a transparent future.">
        <meta name="keywords" content="CiSeTU, digital village, rural administration, panchayat, gram panchayat, smart village, governance, e-gram">
        <meta name="author" content="CiSeTU">
        <meta name="robots" content="index, follow">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'CiSeTU') }}">
        <meta property="og:description" content="CiSeTU - Digital villages, connected at the scale of your community. The platform for modern rural administration.">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:site_name" content="CiSeTU">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ config('app.name', 'CiSeTU') }}">
        <meta property="twitter:description" content="CiSeTU - Digital villages, connected at the scale of your community.">
        <meta property="twitter:image" content="{{ asset('images/logo.png') }}">
        <style>
            html {
                background-color: oklch(1 0 0);
            }
        </style>

        <link rel="icon" href="/favicon.ico?v={{ filemtime(public_path('favicon.ico')) }}" sizes="any">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Google Fonts (Optimized to prevent render-blocking) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preload" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;0,9..144,900;1,9..144,300;1,9..144,700&family=DM+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;0,9..144,900;1,9..144,300;1,9..144,700&family=DM+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
        </noscript>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'Laravel') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
