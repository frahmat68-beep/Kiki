<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('ui.admin.panel_title') . ' | Manake')</title>
    @php
        $faviconPath = site_setting('brand.favicon_path');
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('MANAKE-FAV-M.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    @include('partials.theme-init')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, sans-serif; }
        :root {
            --manake-heading-h1: #1d4ed8;
            --manake-heading-h2: #2563eb;
            --manake-heading-h3: #1e40af;
            --manake-heading-h4: #1d4ed8;
        }
        header :is(h1, h2, h3) {
            color: var(--manake-heading-h1) !important;
            letter-spacing: -0.012em;
        }
        main h1 {
            color: var(--manake-heading-h1) !important;
            letter-spacing: -0.015em;
            font-weight: 800;
        }
        main h2 {
            color: var(--manake-heading-h2) !important;
            letter-spacing: -0.012em;
            font-weight: 700;
        }
        main h3 {
            color: var(--manake-heading-h3) !important;
            font-weight: 700;
        }
        main :is(h4, h5, h6) {
            color: var(--manake-heading-h4) !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="min-h-screen">
    @php
        $activePage = $activePage ?? '';
        $brandName = site_setting('brand.name', 'Manake');
        $logoUrl = asset('MANAKE-FAV-M.png');
        $adminName = auth('admin')->user()->name ?? 'Admin';
        $adminRole = auth('admin')->user()->role ?? 'admin';
        $isSuperAdmin = auth('admin')->check() && $adminRole === 'super_admin';
    @endphp

    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden" @click="sidebarOpen = false"></div>

        <x-admin.sidebar
            :logo-url="$logoUrl"
            :brand-name="$brandName"
            :active-page="$activePage"
            :is-super-admin="$isSuperAdmin"
            :admin-name="$adminName"
            :admin-role="$adminRole"
        />

        <div class="lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white">
                <div class="mx-auto flex h-16 w-full max-w-[1320px] items-center justify-between gap-3 px-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 lg:hidden" @click="sidebarOpen = true" aria-label="Open sidebar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                <line x1="4" y1="12" x2="20" y2="12"></line>
                                <line x1="4" y1="17" x2="20" y2="17"></line>
                            </svg>
                        </button>
                        <div class="min-w-0">
                            <h1 class="truncate text-lg font-semibold text-slate-900">@yield('page_title', __('ui.admin.dashboard'))</h1>
                            <p class="text-xs text-slate-500">{{ __('ui.admin.panel_title') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">
                        <a href="/" class="hidden text-sm font-semibold text-slate-600 transition hover:text-blue-600 sm:inline">{{ __('ui.admin.view_website') }}</a>
                        <div class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">
                            {{ strtoupper(substr($adminName, 0, 1)) }}
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600">
                                {{ __('ui.nav.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="px-4 py-6 sm:px-6 sm:py-8">
                <div class="mx-auto w-full max-w-[1320px]">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('partials.ui-feedback')
    @stack('scripts')
    @include('partials.theme-toggle')
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (window.axios) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken;
        }
        window.fetchWithCsrf = (url, options = {}) => {
            const headers = new Headers(options.headers || {});
            headers.set('X-CSRF-TOKEN', window.csrfToken);
            return fetch(url, { ...options, headers });
        };
    </script>
</body>
</html>
