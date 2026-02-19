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
        <style>
            [x-cloak] { display: none !important; }
            body { font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, sans-serif; }
            :root {
                --manake-heading-h1: #1d4ed8;
                --manake-heading-h2: #2563eb;
                --manake-heading-h3: #1e40af;
                --manake-heading-h4: #1d4ed8;
            }
            h1 {
                color: var(--manake-heading-h1);
                letter-spacing: -0.015em;
                font-weight: 800;
            }
            h2 {
                color: var(--manake-heading-h2);
                letter-spacing: -0.012em;
                font-weight: 700;
            }
            h3 {
                color: var(--manake-heading-h3);
                font-weight: 700;
            }
            :is(h4, h5, h6) {
                color: var(--manake-heading-h4);
                font-weight: 600;
            }
        </style>
    </head>
    <body class="antialiased">
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
