@props([
    'logoUrl' => null,
    'brandName' => 'Manake',
    'activePage' => '',
    'isSuperAdmin' => false,
    'adminName' => 'Admin',
    'adminRole' => 'admin',
])

@php
    $items = [
        [
            'key' => 'dashboard',
            'label' => __('ui.admin.dashboard'),
            'url' => route('admin.dashboard'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5" /><rect x="13" y="3" width="8" height="5" rx="1.5" /><rect x="13" y="10" width="8" height="11" rx="1.5" /><rect x="3" y="13" width="8" height="8" rx="1.5" /></svg>',
        ],
        [
            'key' => 'categories',
            'label' => __('ui.admin.categories'),
            'url' => route('admin.categories.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18" /><path d="M3 12h18" /><path d="M3 17h18" /></svg>',
        ],
        [
            'key' => 'equipments',
            'label' => __('ui.admin.equipments'),
            'url' => route('admin.equipments.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2" /><path d="M8 21h8" /><path d="M12 18v3" /></svg>',
        ],
        [
            'key' => 'copy',
            'label' => 'Teks Website',
            'url' => route('admin.copy.edit', 'landing'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><polyline points="14 2 14 8 20 8" /></svg>',
        ],
        [
            'key' => 'content',
            'label' => 'Konten Lama',
            'url' => route('admin.content.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" /><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" /></svg>',
        ],
        [
            'key' => 'orders',
            'label' => 'Orders',
            'url' => route('admin.orders.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1" /><circle cx="20" cy="21" r="1" /><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6" /></svg>',
        ],
        [
            'key' => 'users',
            'label' => 'Users',
            'url' => route('admin.users.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="8.5" cy="7" r="4" /><path d="M20 8v6" /><path d="M23 11h-6" /></svg>',
        ],
        [
            'key' => 'website',
            'label' => __('ui.admin.website_settings'),
            'url' => route('admin.website.edit'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10" /><line x1="2" y1="12" x2="22" y2="12" /><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z" /></svg>',
        ],
    ];

    if ($isSuperAdmin) {
        $items[] = [
            'key' => 'db',
            'label' => 'Data Database',
            'url' => route('admin.db.index'),
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3" /><path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5" /><path d="M3 12c0 1.7 4 3 9 3s9-1.3 9-3" /></svg>',
        ];
    }

    $adminInitial = strtoupper(substr($adminName ?: 'A', 0, 1));
    $compactLogoUrl = $logoUrl ?: asset('MANAKE-FAV-M.png');
    $expandedLogoUrl = asset('manake-logo-blue.png');
@endphp

<aside
    class="group/sidebar fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col overflow-visible border-r border-slate-200 bg-white px-2 py-5 shadow-sm transition-[width,transform,box-shadow] duration-200 ease-out lg:w-16 lg:translate-x-0 lg:hover:w-72 lg:focus-within:w-72 lg:hover:shadow-2xl lg:focus-within:shadow-2xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="flex h-12 items-center justify-between px-2">
        <a href="{{ route('admin.dashboard') }}" title="{{ $brandName }}" aria-label="{{ $brandName }}" class="flex min-w-0 items-center gap-3 rounded-xl px-1 py-1 text-slate-900 lg:justify-center lg:gap-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:gap-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:gap-3">
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
        <button type="button" class="rounded-lg border border-slate-200 p-1.5 text-slate-500 lg:hidden" @click="sidebarOpen = false" aria-label="Close sidebar">
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
                class="flex h-11 items-center rounded-xl px-3 transition lg:justify-center lg:px-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:px-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:px-3 {{ $activePage === $item['key'] ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
            >
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center">{!! $item['icon'] !!}</span>
                <span class="text-sm font-semibold transition-all duration-200 lg:ml-0 lg:pointer-events-none lg:max-w-0 lg:overflow-hidden lg:whitespace-nowrap lg:opacity-0 lg:-translate-x-2 lg:group-hover/sidebar:ml-3 lg:group-hover/sidebar:pointer-events-auto lg:group-hover/sidebar:max-w-[12rem] lg:group-hover/sidebar:opacity-100 lg:group-hover/sidebar:translate-x-0 lg:group-focus-within/sidebar:ml-3 lg:group-focus-within/sidebar:pointer-events-auto lg:group-focus-within/sidebar:max-w-[12rem] lg:group-focus-within/sidebar:opacity-100 lg:group-focus-within/sidebar:translate-x-0">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-6 border-t border-slate-200 pt-4 transition-all duration-200 lg:max-h-0 lg:overflow-hidden lg:opacity-0 lg:group-hover/sidebar:max-h-40 lg:group-hover/sidebar:opacity-100 lg:group-focus-within/sidebar:max-h-40 lg:group-focus-within/sidebar:opacity-100">
        <div class="rounded-2xl bg-slate-50 px-3 py-3 text-xs text-slate-600">
            <p class="font-semibold text-slate-900">{{ __('ui.admin.status_admin') }}</p>
            <p class="mt-1">{{ __('ui.admin.session_active') }}</p>
        </div>
    </div>

    <div class="mt-auto border-t border-slate-200 pt-4">
        <div class="flex items-center gap-3 rounded-xl px-2 py-2 text-slate-700 lg:justify-center lg:gap-0 lg:group-hover/sidebar:justify-start lg:group-hover/sidebar:gap-3 lg:group-focus-within/sidebar:justify-start lg:group-focus-within/sidebar:gap-3">
            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">{{ $adminInitial }}</span>
            <span class="min-w-0 transition-all duration-200 lg:ml-0 lg:pointer-events-none lg:max-w-0 lg:overflow-hidden lg:whitespace-nowrap lg:opacity-0 lg:-translate-x-2 lg:group-hover/sidebar:ml-3 lg:group-hover/sidebar:pointer-events-auto lg:group-hover/sidebar:max-w-[12rem] lg:group-hover/sidebar:opacity-100 lg:group-hover/sidebar:translate-x-0 lg:group-focus-within/sidebar:ml-3 lg:group-focus-within/sidebar:pointer-events-auto lg:group-focus-within/sidebar:max-w-[12rem] lg:group-focus-within/sidebar:opacity-100 lg:group-focus-within/sidebar:translate-x-0">
                <span class="block truncate text-sm font-semibold">{{ $adminName }}</span>
                <span class="block truncate text-[11px] uppercase tracking-wide text-slate-400">{{ $adminRole }}</span>
            </span>
        </div>
    </div>

    <span class="absolute -right-3 top-0 hidden h-full w-3 lg:block" aria-hidden="true"></span>
</aside>
