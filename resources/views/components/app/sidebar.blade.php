@props([
    'logoUrl' => null,
    'brandName' => 'Manake',
    'categories' => collect(),
    'displayName' => 'User',
    'userInitial' => 'U',
    'isAuthenticated' => false,
])

@php
    $categories = collect($categories ?? []);
    $locale = app()->getLocale();
    $currentTheme = $themePreference ?? request()->attributes->get('theme_preference', 'light');
    if (! in_array($currentTheme, ['system', 'dark', 'light'], true)) {
        $currentTheme = 'light';
    }

    $isCatalogRoute = request()->routeIs('home')
        || request()->routeIs('catalog')
        || request()->routeIs('categories.*')
        || request()->routeIs('category.show')
        || request()->routeIs('product.show');
    $isAvailabilityRoute = request()->routeIs('availability.board');

    $items = [
        [
            'label' => __('ui.nav.catalog'),
            'url' => route('catalog'),
            'active' => $isCatalogRoute,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5" /><rect x="14" y="3" width="7" height="7" rx="1.5" /><rect x="14" y="14" width="7" height="7" rx="1.5" /><rect x="3" y="14" width="7" height="7" rx="1.5" /></svg>',
        ],
        [
            'label' => __('ui.nav.check_availability'),
            'url' => route('availability.board'),
            'active' => $isAvailabilityRoute,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2" /><path d="M8 2v4" /><path d="M16 2v4" /><path d="M3 10h18" /></svg>',
        ],
    ];

    if ($isAuthenticated) {
        $items[] = [
            'label' => __('ui.nav.my_orders'),
            'url' => route('booking.history'),
            'active' => request()->routeIs('booking.*') || request()->routeIs('overview') || request()->routeIs('dashboard') || request()->routeIs('account.orders.*'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3" /><circle cx="12" cy="12" r="9" /></svg>',
        ];
        $items[] = [
            'label' => __('ui.nav.settings'),
            'url' => route('settings.index'),
            'active' => request()->routeIs('settings.*'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3 1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8 1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z" /></svg>',
        ];
    } else {
        $items[] = [
            'label' => __('ui.nav.settings'),
            'url' => '#',
            'active' => false,
            'prefs' => true,
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3" /><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3 1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8 1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z" /></svg>',
        ];
    }

    $compactLogoUrl = $logoUrl ?: asset('MANAKE-FAV-M.png');
    $brandLogoPath = site_setting('brand.logo_path');
    $expandedLogoUrl = $brandLogoPath ? asset('storage/' . $brandLogoPath) : asset('manake-logo-blue.png');
@endphp

<aside
    class="group/sidebar fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-visible border-r border-slate-200 bg-white px-2 py-5 shadow-sm transition-[width,transform,box-shadow] duration-200 ease-out lg:w-16 lg:translate-x-0 lg:hover:w-72 lg:focus-within:w-72 lg:hover:shadow-2xl lg:focus-within:shadow-2xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-12 items-center justify-between px-2">
        <a
            href="{{ route('home') }}"
            title="{{ $brandName }}"
            aria-label="{{ $brandName }}"
            class="flex min-w-0 items-center gap-3 rounded-xl px-1 py-1 text-slate-900 lg:justify-center lg:gap-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:gap-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:gap-3"
        >
            <img
                src="{{ $compactLogoUrl }}"
                alt="{{ $brandName }}"
                class="hidden h-9 w-9 shrink-0 rounded-xl object-contain lg:block lg:group-hover/sidebar:hidden lg:group-focus-within/sidebar:hidden"
            >
            <img
                src="{{ $expandedLogoUrl }}"
                alt="{{ $brandName }}"
                class="h-9 w-auto shrink-0 object-contain lg:hidden lg:group-hover/sidebar:block lg:group-focus-within/sidebar:block"
            >
        </a>
        <button class="rounded-lg border border-slate-200 p-1.5 text-slate-500 lg:hidden" type="button" @click="sidebarOpen = false; guestPrefsOpen = false" aria-label="{{ __('ui.actions.close') }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <nav class="mt-6 space-y-1 px-1">
        @foreach ($items as $item)
            <a
                href="{{ $item['url'] }}"
                title="{{ $item['label'] }}"
                aria-label="{{ $item['label'] }}"
                @if (isset($item['modal']))
                    @click.prevent="openAuthModal('{{ $item['modal'] }}')"
                @endif
                @if (isset($item['prefs']) && $item['prefs'])
                    @click.prevent="guestPrefsOpen = !guestPrefsOpen"
                @endif
                class="flex h-11 items-center rounded-xl px-3 transition lg:justify-center lg:px-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:px-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:px-3 {{ $item['active'] ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center">{!! $item['icon'] !!}</span>
                <span class="text-sm font-semibold transition-all duration-200 lg:ml-0 lg:pointer-events-none lg:max-w-0 lg:overflow-hidden lg:whitespace-nowrap lg:opacity-0 lg:-translate-x-2 lg:group-hover/sidebar:ml-3 lg:group-hover/sidebar:pointer-events-auto lg:group-hover/sidebar:max-w-[12rem] lg:group-hover/sidebar:opacity-100 lg:group-hover/sidebar:translate-x-0 lg:group-focus-within/sidebar:ml-3 lg:group-focus-within/sidebar:pointer-events-auto lg:group-focus-within/sidebar:max-w-[12rem] lg:group-focus-within/sidebar:opacity-100 lg:group-focus-within/sidebar:translate-x-0">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    @if (! $isAuthenticated)
        <div
            x-cloak
            x-show="guestPrefsOpen"
            x-transition
            class="mx-1 mt-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 lg:group-hover/sidebar:mx-1 lg:group-focus-within/sidebar:mx-1"
        >
            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ __('ui.nav.language') }}</p>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <a href="{{ route('lang.switch', 'id') }}" data-locale-option="id" class="rounded-xl border px-3 py-2 text-center text-xs font-semibold transition {{ $locale === 'id' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:border-blue-200 hover:text-blue-600' }}">
                    {{ __('ui.languages.id') }}
                </a>
                <a href="{{ route('lang.switch', 'en') }}" data-locale-option="en" class="rounded-xl border px-3 py-2 text-center text-xs font-semibold transition {{ $locale === 'en' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:border-blue-200 hover:text-blue-600' }}">
                    {{ __('ui.languages.en') }}
                </a>
            </div>

            <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ __('ui.nav.theme') }}</p>
            <div class="mt-2 grid grid-cols-3 gap-2">
                @foreach (['system' => __('ui.settings.theme_system'), 'dark' => __('ui.settings.theme_dark'), 'light' => __('ui.settings.theme_light')] as $value => $label)
                    <a href="{{ route('theme.switch', $value) }}" data-theme-option="{{ $value }}" class="rounded-xl border px-2 py-2 text-center text-xs font-semibold transition {{ $currentTheme === $value ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:border-blue-200 hover:text-blue-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($categories->isNotEmpty())
        <div class="mt-6 border-t border-slate-200 pt-4 transition-all duration-200 lg:max-h-0 lg:overflow-hidden lg:opacity-0 lg:group-hover/sidebar:max-h-48 lg:group-hover/sidebar:opacity-100 lg:group-focus-within/sidebar:max-h-48 lg:group-focus-within/sidebar:opacity-100">
            <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ __('ui.nav.category') }}</p>
            <div class="mt-2 flex flex-wrap gap-2 px-2">
                @foreach ($categories as $category)
                    <a
                        href="{{ route('catalog', ['category' => $category->slug]) }}"
                        class="rounded-full border border-slate-200 px-2.5 py-1 text-[11px] font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($isAuthenticated)
        <div class="mt-auto border-t border-slate-200 pt-4">
            <a
                href="{{ route('profile.complete') }}"
                title="{{ __('ui.nav.my_profile') }}"
                aria-label="{{ __('ui.nav.my_profile') }}"
                class="flex items-center gap-3 rounded-xl px-2 py-2 text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 lg:justify-center lg:gap-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:gap-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:gap-3"
            >
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">{{ $userInitial }}</span>
                <span class="text-sm font-semibold transition-all duration-200 lg:ml-0 lg:pointer-events-none lg:max-w-0 lg:overflow-hidden lg:whitespace-nowrap lg:opacity-0 lg:-translate-x-2 lg:group-hover/sidebar:ml-3 lg:group-hover/sidebar:pointer-events-auto lg:group-hover/sidebar:max-w-[10rem] lg:group-hover/sidebar:opacity-100 lg:group-hover/sidebar:translate-x-0 lg:group-focus-within/sidebar:ml-3 lg:group-focus-within/sidebar:pointer-events-auto lg:group-focus-within/sidebar:max-w-[10rem] lg:group-focus-within/sidebar:opacity-100 lg:group-focus-within/sidebar:translate-x-0">{{ $displayName }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button
                    type="submit"
                    title="{{ __('ui.nav.logout') }}"
                    class="flex h-10 w-full items-center rounded-xl px-3 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 lg:justify-center lg:px-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:px-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:px-3"
                >
                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                    </span>
                    <span class="text-sm font-semibold transition-all duration-200 lg:ml-0 lg:pointer-events-none lg:max-w-0 lg:overflow-hidden lg:whitespace-nowrap lg:opacity-0 lg:-translate-x-2 lg:group-hover/sidebar:ml-3 lg:group-hover/sidebar:pointer-events-auto lg:group-hover/sidebar:max-w-[10rem] lg:group-hover/sidebar:opacity-100 lg:group-hover/sidebar:translate-x-0 lg:group-focus-within/sidebar:ml-3 lg:group-focus-within/sidebar:pointer-events-auto lg:group-focus-within/sidebar:max-w-[10rem] lg:group-focus-within/sidebar:opacity-100 lg:group-focus-within/sidebar:translate-x-0">{{ __('ui.nav.logout') }}</span>
                </button>
            </form>
        </div>
    @endif

    <span class="absolute -right-3 top-0 hidden h-full w-3 lg:block" aria-hidden="true"></span>
</aside>
