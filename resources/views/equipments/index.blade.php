@extends('layouts.app')

@section('title', __('app.catalog.title'))

@section('content')
    @php
        $groups = collect($groupedEquipments ?? []);
        $activeCategorySlug = $activeCategorySlug ?? '';
        $search = trim((string) ($search ?? request('q', '')));
        $catalogKicker = setting('copy.catalog.kicker', __('app.catalog.kicker'));
        $catalogTitle = setting('copy.catalog.title', __('app.catalog.title'));
        $catalogSubtitle = setting('copy.catalog.subtitle', __('app.catalog.subtitle'));
        $categoryLabel = setting('copy.catalog.category_label', __('app.catalog.filter_category'));
        $emptyTitle = setting('copy.catalog.empty_title', 'Belum ada alat tersedia.');
        $emptySubtitle = setting('copy.catalog.empty_subtitle', 'Tambahkan alat baru dari admin agar katalog terisi.');
    @endphp

    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
            <div class="flex flex-col gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">{{ $catalogKicker }}</p>
                    <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl">{{ $catalogTitle }}</h1>
                    <p class="text-sm text-slate-600">{{ $catalogSubtitle }}</p>
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $categoryLabel }}</p>
                    @if ($search !== '')
                        <a href="{{ route('catalog', $activeCategorySlug !== '' ? ['category' => $activeCategorySlug] : []) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600">
                            Reset Pencarian
                        </a>
                    @endif
                </div>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a
                        href="{{ route('catalog', $search !== '' ? ['q' => $search] : []) }}"
                        class="rounded-full border px-3 py-1.5 text-xs font-semibold transition {{ $activeCategorySlug === '' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:border-blue-200 hover:text-blue-600' }}"
                    >
                        Semua Kategori
                    </a>
                    @foreach ($categories as $category)
                        @php
                            $categoryParams = ['category' => $category->slug];
                            if ($search !== '') {
                                $categoryParams['q'] = $search;
                            }
                        @endphp
                        <a
                            href="{{ route('catalog', $categoryParams) }}"
                            class="rounded-full border px-3 py-1.5 text-xs font-semibold transition {{ $activeCategorySlug === $category->slug ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:border-blue-200 hover:text-blue-600' }}"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($search !== '')
                <p class="mt-4 text-sm text-slate-500">
                    Hasil pencarian untuk <span class="font-semibold text-slate-700">&quot;{{ $search }}&quot;</span>.
                </p>
            @endif
        </div>
    </section>

    <section class="bg-slate-100">
        <div class="mx-auto max-w-7xl space-y-10 px-4 pb-12 sm:px-6">
            @forelse ($groups as $group)
                @php
                    $category = $group['category'];
                    $items = collect($group['items'] ?? []);
                @endphp

                <section>
                    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Kategori</p>
                            <h2 class="text-2xl font-semibold text-slate-900">{{ $category->name }}</h2>
                            @if (!empty($category->description))
                                <p class="text-sm text-slate-500">{{ $category->description }}</p>
                            @endif
                        </div>
                        <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600">
                            {{ $items->count() }} item
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($items as $item)
                            @php
                                $statusValue = $item->status ?? ($item->stock > 0 ? 'ready' : 'unavailable');
                                $statusKey = $statusValue === 'ready' ? 'ready' : 'rented';
                                $statusLabel = $statusKey === 'ready' ? __('app.status.ready') : __('app.status.rented');
                                $statusClass = $statusKey === 'ready'
                                    ? 'bg-emerald-100 text-emerald-700'
                                    : 'bg-amber-100 text-amber-700';
                                $imagePath = $item->image_path ?? $item->image;
                                $image = $imagePath
                                    ? (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . $imagePath))
                                    : 'https://images.unsplash.com/photo-1519183071298-a2962be96c68?auto=format&fit=crop&w=900&q=80';
                                $reservedUnits = (int) ($item->reserved_units ?? 0);
                                $availableUnits = (int) $item->available_units;
                                $canRent = $statusValue === 'ready' && (int) $item->stock > 0;
                            @endphp

                            <article
                                x-data="{
                                    quickOpen: false,
                                    quickQty: 1,
                                    quickStart: '',
                                    quickEnd: '',
                                    maxQty: {{ max((int) $item->stock, 1) }},
                                    calcDays() {
                                        if (!this.quickStart || !this.quickEnd) return 0;
                                        const start = new Date(this.quickStart);
                                        const end = new Date(this.quickEnd);
                                        const diff = Math.ceil((end - start) / 86400000) + 1;
                                        return Number.isNaN(diff) || diff <= 0 ? 0 : diff;
                                    },
                                    calcTotal() {
                                        const days = this.calcDays();
                                        return days > 0 ? days * {{ (int) $item->price_per_day }} * this.quickQty : 0;
                                    },
                                    formatIdr(value) {
                                        return new Intl.NumberFormat('id-ID').format(value);
                                    }
                                }"
                                class="card group flex h-full flex-col overflow-hidden rounded-2xl shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                            >
                                <div class="relative flex h-56 items-center justify-center bg-slate-50 p-4 sm:h-60">
                                    <img
                                        src="{{ $image }}"
                                        alt="{{ $item->name }}"
                                        class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                    <span class="badge-status absolute left-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold">
                                        {{ $item->category?->name ?? __('app.category.title') }}
                                    </span>
                                    <span class="absolute right-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <div class="flex flex-1 flex-col p-5">
                                    <h3 class="min-h-[3.4rem] text-lg font-semibold leading-snug text-slate-900">{{ $item->name }}</h3>
                                    <p class="mt-2 text-xs text-slate-500">{{ __('app.product.price_per_day') }}</p>
                                    <p class="text-lg font-semibold text-slate-900">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</p>

                                    <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                        <div class="rounded-lg bg-slate-50 px-2 py-2">
                                            <p class="text-[10px] uppercase tracking-wide text-slate-500">Stok</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-900">{{ $item->stock }}</p>
                                        </div>
                                        <div class="rounded-lg bg-slate-50 px-2 py-2">
                                            <p class="text-[10px] uppercase tracking-wide text-slate-500">Dipakai</p>
                                            <p class="mt-1 text-sm font-semibold text-amber-600">{{ $reservedUnits }}</p>
                                        </div>
                                        <div class="rounded-lg bg-slate-50 px-2 py-2">
                                            <p class="text-[10px] uppercase tracking-wide text-slate-500">Tersedia</p>
                                            <p class="mt-1 text-sm font-semibold {{ $availableUnits > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $availableUnits }}</p>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-[11px] text-slate-500">Ketersediaan final dicek berdasarkan tanggal sewa saat checkout.</p>

                                    <div class="mt-4 mt-auto space-y-3">
                                        <a
                                            href="{{ route('product.show', $item->slug) }}"
                                            class="btn-secondary inline-flex w-full items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition"
                                        >
                                            {{ __('app.actions.view_detail') }}
                                        </a>

                                        @auth
                                            @if ($canRent)
                                                <button
                                                    type="button"
                                                    class="btn-primary inline-flex w-full items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition"
                                                    @click="quickOpen = true; quickQty = 1; quickStart = ''; quickEnd = '';"
                                                >
                                                    Pesan Cepat
                                                </button>
                                            @else
                                                <button type="button" disabled class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                                    Stok tidak tersedia
                                                </button>
                                            @endif
                                        @endauth

                                        @guest
                                            @if ($canRent)
                                                <a
                                                    href="{{ route('login', ['reason' => 'cart']) }}"
                                                    @click.prevent="window.dispatchEvent(new CustomEvent('open-auth-modal', { detail: 'login' }))"
                                                    class="btn-primary inline-flex w-full items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold transition"
                                                >
                                                    Login untuk pesan
                                                </a>
                                            @else
                                                <button type="button" disabled class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-500">
                                                    Stok tidak tersedia
                                                </button>
                                            @endif
                                        @endguest
                                    </div>
                                </div>

                                @auth
                                    @if ($canRent)
                                        <template x-teleport="body">
                                            <div
                                                x-cloak
                                                x-show="quickOpen"
                                                x-transition.opacity
                                                class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-900/45 px-4"
                                                @click.self="quickOpen = false"
                                                @keydown.escape.window="quickOpen = false"
                                            >
                                                <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Pesan Cepat</p>
                                                            <h4 class="mt-1 text-base font-semibold text-slate-900">{{ $item->name }}</h4>
                                                            <p class="text-xs text-slate-500">Isi tanggal sewa, lalu langsung masuk keranjang.</p>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:text-slate-700"
                                                            @click="quickOpen = false"
                                                            aria-label="{{ __('ui.actions.close') }}"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <form method="POST" action="{{ route('cart.add') }}" class="mt-4 space-y-3">
                                                        @csrf
                                                        <input type="hidden" name="equipment_id" value="{{ $item->id }}">
                                                        <input type="hidden" name="name" value="{{ $item->name }}">
                                                        <input type="hidden" name="slug" value="{{ $item->slug }}">
                                                        <input type="hidden" name="category" value="{{ $item->category?->name }}">
                                                        <input type="hidden" name="image" value="{{ $image }}">
                                                        <input type="hidden" name="price" value="{{ $item->price_per_day }}">

                                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                            <div>
                                                                <label class="text-xs font-semibold text-slate-500">Tanggal Sewa</label>
                                                                <input
                                                                    type="date"
                                                                    name="rental_start_date"
                                                                    x-model="quickStart"
                                                                    min="{{ now()->toDateString() }}"
                                                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                                                                    required
                                                                >
                                                            </div>
                                                            <div>
                                                                <label class="text-xs font-semibold text-slate-500">Tanggal Kembali</label>
                                                                <input
                                                                    type="date"
                                                                    name="rental_end_date"
                                                                    x-model="quickEnd"
                                                                    :min="quickStart || '{{ now()->toDateString() }}'"
                                                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                                                                    required
                                                                >
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="text-xs font-semibold text-slate-500">Jumlah</label>
                                                            <input
                                                                type="number"
                                                                name="qty"
                                                                min="1"
                                                                :max="maxQty"
                                                                x-model="quickQty"
                                                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                                                                required
                                                            >
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600">
                                                            <div>
                                                                <p class="text-[11px] uppercase tracking-wide text-slate-500">Durasi</p>
                                                                <p class="font-semibold text-slate-900" x-text="calcDays() > 0 ? `${calcDays()} hari` : '-'"></p>
                                                            </div>
                                                            <div class="text-right">
                                                                <p class="text-[11px] uppercase tracking-wide text-slate-500">Estimasi</p>
                                                                <p class="font-semibold text-slate-900" x-text="calcTotal() > 0 ? `Rp ${formatIdr(calcTotal())}` : 'Rp -'"></p>
                                                            </div>
                                                        </div>

                                                        <div class="flex gap-2 pt-1">
                                                            <button type="button" class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600" @click="quickOpen = false">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                                                Tambah
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                @endauth
                            </article>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="card rounded-2xl p-8 text-center shadow-sm">
                    <p class="text-base font-semibold text-slate-900">{{ $emptyTitle }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $emptySubtitle }}</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
