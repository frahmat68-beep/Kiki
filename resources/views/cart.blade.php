<x-app-layout>
    @section('title', __('Keranjang Belanja'))

    <div class="mx-auto max-w-7xl px-4 py-8 pb-16 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <header class="mb-10 text-center sm:text-left">
            <h1 class="text-4xl font-extrabold tracking-tight text-blue-700 sm:text-5xl">
                {{ __('Keranjang Belanja') }}
            </h1>
            <p class="mt-3 text-lg text-slate-500">
                {{ __('Kelola pilihan alat produksi Anda sebelum melanjutkan ke pembayaran.') }}
            </p>
        </header>

        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start lg:gap-16">
            {{-- Cart Items Section --}}
            <section class="lg:col-span-12 space-y-8">
                @if (session('success'))
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm animate-fade-in-down">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700 shadow-sm animate-fade-in-down">
                        {{ session('error') }}
                    </div>
                @endif

                @if (empty($cartItems))
                    <article class="flex flex-col items-center justify-center rounded-[3rem] border-2 border-dashed border-slate-200 bg-white py-24 text-center">
                        <div class="relative mb-6">
                            <div class="absolute -inset-4 rounded-full bg-blue-50 opacity-50"></div>
                            <svg class="relative h-20 w-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">{{ __('Keranjang Kosong') }}</h2>
                        <p class="mt-2 max-w-sm text-slate-500">{{ __('Anda belum menambahkan item apapun ke keranjang belanja.') }}</p>
                        <a href="{{ route('catalog') }}" class="btn-primary mt-8 inline-flex items-center rounded-2xl px-10 py-4 font-bold shadow-xl shadow-blue-200 transition-all hover:scale-105 active:scale-95">
                            {{ __('Mulai Sewa Alat') }}
                        </a>
                    </article>
                @else
                    <div class="space-y-4">
                        @foreach ($cartItems as $item)
                        <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white p-6 transition-all duration-300 hover:shadow-xl hover:shadow-blue-100 sm:p-8">
                            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                                {{-- Thumbnail --}}
                                <div class="h-32 w-32 flex-shrink-0 overflow-hidden rounded-[1.5rem] bg-slate-50 border border-slate-100">
                                    <img src="{{ $item['image'] ?? config('placeholders.equipment') }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>

                                {{-- Details --}}
                                <div class="flex flex-1 flex-col justify-between">
                                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                        <div>
                                            <span class="mb-1 inline-block text-xs font-black uppercase tracking-widest text-blue-600">
                                                {{ $item['category'] ?? __('Umum') }}
                                            </span>
                                            <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-blue-700 transition-colors">
                                                <a href="{{ route('product.show', $item['slug'] ?? '#') }}">{{ $item['name'] }}</a>
                                            </h3>
                                            @if (!empty($item['rental_start_date']) && !empty($item['rental_end_date']))
                                                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm font-semibold text-slate-500">
                                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ Carbon\Carbon::parse($item['rental_start_date'])->translatedFormat('d M Y') }}</span>
                                                    <span class="text-slate-300">→</span>
                                                    <span>{{ Carbon\Carbon::parse($item['rental_end_date'])->translatedFormat('d M Y') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-black text-slate-900">
                                                @php
                                                    $itemPrice = (int)($item['price'] ?? 0);
                                                    $itemQty = (int)($item['qty'] ?? 1);
                                                    $days = 1;
                                                    if (!empty($item['rental_start_date']) && !empty($item['rental_end_date'])) {
                                                        $days = Carbon\Carbon::parse($item['rental_start_date'])->diffInDays(Carbon\Carbon::parse($item['rental_end_date'])) + 1;
                                                    }
                                                    $lineSubtotal = $itemPrice * $itemQty * $days;
                                                @endphp
                                                Rp{{ number_format($lineSubtotal, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs font-bold text-slate-400">
                                                Rp{{ number_format($itemPrice, 0, ',', '.') }} × {{ $itemQty }} unit × {{ $days }} hari
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex items-center justify-between gap-4">
                                        {{-- Quantity Selector --}}
                                        <div class="inline-flex items-center rounded-2xl bg-slate-50 p-1 border border-slate-100">
                                            <form method="POST" action="{{ route('cart.decrement', $item['key']) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm transition hover:text-blue-700 active:scale-90" {{ $itemQty <= 1 ? 'disabled' : '' }}>
                                                    <span class="text-lg font-bold">−</span>
                                                </button>
                                            </form>
                                            <span class="w-12 text-center text-sm font-black text-slate-900">{{ $itemQty }}</span>
                                            <form method="POST" action="{{ route('cart.increment', $item['key']) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-slate-600 shadow-sm transition hover:text-blue-700 active:scale-90" {{ $itemQty >= ($item['stock'] ?? 99) ? 'disabled' : '' }}>
                                                    <span class="text-lg font-bold">+</span>
                                                </button>
                                            </form>
                                        </div>

                                        {{-- Remove Action --}}
                                        <form method="POST" action="{{ route('cart.remove', $item['key']) }}" 
                                            class="inline"
                                            x-data
                                            @submit.prevent="if(confirm('{{ __('Hapus item ini dari keranjang?') }}')) $el.submit()">
                                            @csrf @method('DELETE')
                                            <button class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-rose-500 transition hover:text-rose-700 group/btn">
                                                <svg class="h-4 w-4 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                {{ __('Hapus') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                @endif
            </section>

            @if (!empty($cartItems))
            {{-- Summary Section --}}
            <section class="lg:col-span-12">
                <article class="rounded-[3rem] border border-slate-200 bg-slate-900 p-8 shadow-2xl sm:p-10 text-white">
                    <h2 class="text-2xl font-extrabold">{{ __('Ringkasan Biaya Estimasi') }}</h2>
                    <p class="mt-1 text-slate-400 font-medium">{{ __('Semua biaya sudah termasuk pajak dan garansi alat dasar.') }}</p>
                    
                    <div class="mt-8 space-y-4 border-t border-slate-800 pt-8">
                        @if ($cartSuggestedStartDate && $cartSuggestedEndDate)
                            <div class="flex items-center justify-between rounded-2xl bg-blue-600/10 p-4 border border-blue-500/20">
                                <span class="text-sm font-bold text-blue-300 uppercase tracking-widest">{{ __('Masa Sewa Terpusat') }}</span>
                                <span class="text-sm font-black">{{ Carbon\Carbon::parse($cartSuggestedStartDate)->format('d M') }} - {{ Carbon\Carbon::parse($cartSuggestedEndDate)->format('d M Y') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-lg font-medium text-slate-400">
                            <span>{{ __('Subtotal') }}</span>
                            <span class="font-bold text-white">Rp{{ number_format($estimatedSubtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-medium text-slate-400">
                            <span>{{ __('PPN (11%)') }}</span>
                            <span class="font-bold text-white">Rp{{ number_format($taxAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex flex-col gap-4 border-t border-slate-800 pt-8 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="text-xs font-black uppercase tracking-[0.2em] text-blue-500">{{ __('Total Pembayaran') }}</span>
                                <p class="text-4xl font-black lg:text-5xl">Rp{{ number_format($grandTotal, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col gap-3 min-w-[240px]">
                                <a href="{{ route('checkout') }}" class="btn-primary flex items-center justify-center rounded-[1.25rem] py-4 text-lg font-black shadow-2xl shadow-blue-500/20 transition-all hover:scale-105 active:scale-95">
                                    {{ __('Lanjut ke Checkout') }}
                                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                                <p class="text-center text-[10px] uppercase tracking-widest text-slate-500 font-bold">
                                    {{ __('Keamanan pembayaran dijamin oleh') }} <span class="text-white">Midtrans</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            </section>
            @endif

            {{-- Recommendations --}}
            @if ($suggestedEquipments->isNotEmpty())
            <aside class="lg:col-span-12 mt-12">
                <div class="mb-8 flex items-end justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900">{{ __('Lengkapi Produksi Anda') }}</h2>
                        <p class="text-sm text-slate-500 font-medium">{{ __('Beberapa alat yang mungkin Anda butuhkan sebagai pendukung.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($suggestedEquipments as $suggestion)
                    <article class="group relative flex flex-col rounded-[2.5rem] border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-blue-100">
                        <div class="relative mb-5 aspect-[4/3] overflow-hidden rounded-[1.5rem] bg-slate-50 border border-slate-100">
                            <img src="{{ $suggestion->image_path ? site_media_url($suggestion->image_path) : config('placeholders.equipment') }}" alt="{{ $suggestion->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 opacity-0 transition-opacity group-hover:opacity-100"></div>
                        </div>
                        <span class="mb-1 text-[10px] font-black uppercase tracking-widest text-blue-600">
                            {{ $suggestion->category->name ?? __('Umum') }}
                        </span>
                        <h3 class="line-clamp-1 text-base font-extrabold text-slate-900 transition-colors group-hover:text-blue-700">
                            <a href="{{ route('product.show', $suggestion->slug) }}">{{ $suggestion->name }}</a>
                        </h3>
                        <p class="mt-2 text-lg font-black text-slate-900">
                            Rp{{ number_format($suggestion->price_per_day, 0, ',', '.') }}<span class="text-xs text-slate-400">/hari</span>
                        </p>
                        <a href="{{ route('product.show', $suggestion->slug) }}" class="mt-4 flex items-center justify-center rounded-xl bg-slate-50 py-3 text-xs font-bold text-slate-700 transition hover:bg-blue-600 hover:text-white group-hover:shadow-lg">
                            {{ __('Tambah Cepat') }}
                        </a>
                    </article>
                    @endforeach
                </div>
            </aside>
            @endif
        </div>
    </div>
</x-app-layout>
