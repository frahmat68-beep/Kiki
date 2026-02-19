<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @php
            $faviconPath = site_setting('brand.favicon_path');
            $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('MANAKE-FAV-M.png');
        @endphp
        <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
        @include('partials.theme-init')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased" style="font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, sans-serif;">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 sm:px-6 sm:py-10">
            <div>
                <a href="/">
                    <img src="{{ asset('manake-logo-blue.png') }}" alt="Manake" class="h-12 w-auto rounded-xl bg-white p-2">
                </a>
            </div>

            <div class="card mt-6 w-full overflow-hidden rounded-2xl px-6 py-5 shadow-md sm:max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
