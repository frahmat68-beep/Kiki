@php
    $navItems = config('navigation', []);
    $isLoggedIn = auth()->check();
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 w-72 transform bg-white transition-transform duration-300 ease-in-out lg:static lg:block lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    @click.away="sidebarOpen = false"
>
    {{-- Decorative Background Element --}}
    <div class="absolute top-0 right-0 h-32 w-16 bg-gradient-to-bl from-blue-50/50 to-transparent pointer-events-none"></div>

    <div class="flex h-full flex-col p-6">
        {{-- Branding Section --}}
        <header class="mb-10 px-4">
            <a href="{{ route('home') }}" class="group block">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-200 ring-4 ring-blue-50 transition-transform group-hover:scale-110">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 4L3 8L12 12L21 8L12 4Z" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 12L12 16L21 12" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 16L12 20L21 16" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black tracking-tight text-slate-900 group-hover:text-blue-700 transition-colors">
                            {{ site_setting('brand.name', 'Manake') }}
                        </h1>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            {{ __('Platform Sewa') }}
                        </p>
                    </div>
                </div>
            </a>
        </header>

        {{-- Main Navigation --}}
        <nav class="flex-1 space-y-9 overflow-y-auto px-1 custom-scrollbar">
            {{-- Personal Section --}}
            <section>
                <h3 class="mb-4 px-3 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                    {{ __('Menu Utama') }}
                </h3>
                <div class="space-y-1.5">
                    <a
                        href="{{ route('catalog') }}"
                        class="nav-link-v2 {{ Route::is('catalog') ? 'active' : '' }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>{{ __('Katalog') }}</span>
                    </a>
                    <a
                        href="{{ route('availability.board') }}"
                        class="nav-link-v2 {{ Route::is('availability.board') ? 'active' : '' }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ __('Availability Board') }}</span>
                    </a>
                </div>
            </section>

            {{-- Account Section --}}
            @if ($isLoggedIn)
                <section>
                    <h3 class="mb-4 px-3 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                        {{ __('Aktivitas Akun') }}
                    </h3>
                    <div class="space-y-1.5">
                        <a
                            href="{{ route('cart') }}"
                            class="nav-link-v2 {{ Route::is('cart') ? 'active' : '' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <div class="flex flex-1 items-center justify-between">
                                <span>{{ __('Keranjang Belanja') }}</span>
                                @php $cartCount = resolve(\App\Services\CartService::class)->count(); @endphp
                                @if($cartCount > 0)
                                    <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-[10px] font-black text-blue-700 ring-2 ring-white">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        <a
                            href="{{ route('booking.index') }}"
                            class="nav-link-v2 {{ Route::is('booking.*') ? 'active' : '' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span>{{ __('Histori Pesanan') }}</span>
                        </a>
                        <a
                            href="{{ route('notifications') }}"
                            class="nav-link-v2 {{ Route::is('notifications') ? 'active' : '' }}"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <div class="flex flex-1 items-center justify-between">
                                <span>{{ __('Notifikasi') }}</span>
                                @php $unreadNotifs = auth()->user()->unreadNotifications->count(); @endphp
                                @if($unreadNotifs > 0)
                                    <span class="rounded-full bg-rose-100 px-2.5 py-0.5 text-[10px] font-black text-rose-700 ring-2 ring-white">
                                        {{ $unreadNotifs }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </div>
                </section>
            @endif

            {{-- Support Section --}}
            <section>
                <h3 class="mb-4 px-3 text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                    {{ __('Dukungan') }}
                </h3>
                <div class="space-y-1.5">
                    <a
                        href="{{ route('contact') }}"
                        class="nav-link-v2 {{ Route::is('contact') ? 'active' : '' }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>{{ __('Pusat Bantuan') }}</span>
                    </a>
                    <a
                        href="{{ route('rental.rules') }}"
                        class="nav-link-v2 {{ Route::is('rental.rules') ? 'active' : '' }}"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        <span>{{ __('Aturan Sewa') }}</span>
                    </a>
                </div>
            </section>
        </nav>

        {{-- User Section --}}
        <footer class="mt-8 border-t border-slate-100 pt-8">
            @if ($isLoggedIn)
                <div class="space-y-4">
                    <a href="{{ route('profile') }}" class="group flex items-center gap-3 rounded-[1.25rem] border border-slate-100 bg-slate-50 p-3 transition-all hover:bg-white hover:shadow-xl hover:shadow-blue-100">
                        <div class="h-10 w-10 overflow-hidden rounded-xl bg-blue-100 ring-2 ring-white">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=1D4ED8&background=DBEAFE" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm font-black text-slate-900">{{ auth()->user()->name }}</p>
                            <p class="truncate text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Pengaturan Profil') }}</p>
                        </div>
                    </a>

                    @if($isLoggedIn && auth()->user()->is_admin)
                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="flex w-full items-center justify-center gap-2 rounded-[1.25rem] bg-slate-900 py-3.5 text-xs font-black text-white shadow-xl shadow-slate-200 transition-all hover:bg-blue-700 active:scale-95"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            <span>{{ __('DASHBOARD ADMIN') }}</span>
                        </a>
                    @endif

                    <form method="GET" action="{{ route('logout') }}">
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-[1.25rem] border border-rose-100 bg-rose-50 py-3.5 text-xs font-black text-rose-600 transition-all hover:bg-rose-100 active:scale-95"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>{{ __('KELUAR') }}</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-1 gap-3">
                    <a
                        href="{{ route('login') }}"
                        class="flex items-center justify-center rounded-[1.25rem] bg-blue-700 py-4 text-xs font-black text-white shadow-xl shadow-blue-200 transition-all hover:bg-black active:scale-95"
                    >
                        {{ __('MASUK AKUN') }}
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="flex items-center justify-center rounded-[1.25rem] border border-slate-200 bg-white py-4 text-xs font-black text-slate-900 transition-all hover:border-blue-700 hover:text-blue-700 active:scale-95"
                    >
                        {{ __('DAFTAR BARU') }}
                    </a>
                </div>
            @endif
        </footer>
    </div>
</aside>

<style>
    .nav-link-v2 {
        @apply flex items-center gap-4 rounded-[1.25rem] px-4 py-3.5 text-sm font-bold text-slate-500 transition-all duration-200 hover:bg-blue-50 hover:text-blue-700;
    }
    .nav-link-v2.active {
        @apply bg-blue-50 text-blue-700 font-extrabold shadow-sm ring-1 ring-blue-100;
    }
    .nav-link-v2 svg {
        @apply transition-transform duration-200;
    }
    .nav-link-v2:hover svg, .nav-link-v2.active svg {
        @apply scale-110;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        @apply bg-transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        @apply rounded-full bg-slate-100 hover:bg-slate-200;
    }
</style>
