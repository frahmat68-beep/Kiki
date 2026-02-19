<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('ui.overview.title') . ' | Manake')</title>
    @php
        $faviconPath = site_setting('brand.favicon_path');
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('MANAKE-FAV-M.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.theme-init')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: "Plus Jakarta Sans", ui-sans-serif, system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <div class="fixed inset-0 z-40 bg-slate-900/40 transition lg:hidden" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 transform border-r border-slate-200 bg-white px-6 py-6 transition lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            @php
                $brandLogo = site_setting('brand.logo_path');
                $brandName = site_setting('brand.name', 'Manake');
                $logoUrl = $brandLogo ? asset('storage/' . $brandLogo) : asset('manake-logo-blue.png');
            @endphp
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3 text-slate-900">
                    <img src="{{ $logoUrl }}" alt="{{ $brandName }}" class="h-7">
                </a>
                <button class="lg:hidden text-slate-500" @click="sidebarOpen = false" aria-label="{{ __('ui.actions.close') }}">
                    ✕
                </button>
            </div>

            <nav class="mt-8 space-y-2 text-sm font-semibold">
                <a href="{{ route('overview') }}" class="flex items-center justify-between rounded-xl px-3 py-2 {{ request()->routeIs('overview') ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>{{ __('ui.nav.overview') }}</span>
                    <span class="text-[10px] uppercase tracking-widest">{{ __('ui.overview.tag') }}</span>
                </a>
                <a href="{{ route('booking.index') }}" class="flex items-center justify-between rounded-xl px-3 py-2 {{ request()->routeIs('booking.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>{{ __('ui.nav.booking') }}</span>
                </a>
                <a href="{{ route('cart') }}" class="flex items-center justify-between rounded-xl px-3 py-2 {{ request()->routeIs('cart') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>{{ __('ui.nav.cart') }}</span>
                    @if (($cartCount ?? 0) > 0)
                        <span class="inline-flex min-w-[22px] items-center justify-center rounded-full bg-blue-600 px-2 py-0.5 text-[10px] text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center justify-between rounded-xl px-3 py-2 {{ request()->routeIs('settings.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <span>{{ __('ui.nav.settings') }}</span>
                </a>
            </nav>

            <div class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-xs text-slate-600">
                <p class="font-semibold text-slate-900">{{ __('ui.overview.quick_help_title') }}</p>
                <p class="mt-2">{{ __('ui.overview.quick_help_body') }}</p>
                <a href="/contact" class="mt-3 inline-flex items-center justify-center rounded-xl bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:text-blue-600">
                    {{ __('ui.actions.contact') }}
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600 transition">
                    {{ __('ui.nav.logout') }}
                </button>
            </form>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-slate-200 bg-white">
                <div class="mx-auto flex w-full max-w-[1320px] flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div class="flex items-center gap-3">
                        <button class="lg:hidden rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 shadow-sm" @click="sidebarOpen = true">
                            ☰
                        </button>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">{{ __('ui.overview.kicker') }}</p>
                            <h1 class="text-lg font-semibold text-slate-900">@yield('page_title', __('ui.overview.title'))</h1>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                        <div class="relative w-full sm:max-w-xs md:max-w-sm">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                            <input
                                type="text"
                                placeholder="{{ __('ui.overview.search_placeholder') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white pl-9 pr-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                            >
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('catalog') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600 transition">
                                {{ __('ui.actions.explore_catalog') }}
                            </a>
                            <div class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-2 py-1.5">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">
                                    {{ strtoupper(substr(auth()->user()->display_name ?? auth()->user()->name ?? 'U', 0, 1)) }}
                                </span>
                                <span class="hidden text-sm font-semibold text-slate-700 sm:inline">
                                    {{ auth()->user()->display_name ?? auth()->user()->name ?? 'User' }}
                                </span>
                            </div>
                        </div>
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
