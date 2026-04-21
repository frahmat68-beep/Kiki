@extends('layouts.app')

@section('title', __('Katalog Peralatan - Manake'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Search & Branding --}}
        <header class="mb-12 text-center">
            <h1 class="text-4xl font-black tracking-tight text-slate-900 md:text-6xl">
                {{ __('Katalog') }} <span class="text-blue-700">{{ __('Produksi') }}</span>
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-500 font-medium">
                {{ __('Peralatan sinematografi dan fotografi profesional siap mendukung karya terbaik Anda.') }}
            </p>
            
            <div class="mt-10 max-w-2xl mx-auto">
                <form action="{{ route('catalog') }}" method="GET" class="relative group" x-data="{ searchQuery: '{{ request('q') }}' }">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        name="q" 
                        x-model="searchQuery"
                        placeholder="{{ __('Cari kamera, lensa, atau aksesoris...') }}"
                        class="block w-full pl-14 pr-32 py-5 text-base text-slate-900 bg-white border-2 border-slate-100 rounded-[2rem] shadow-sm transition-all focus:border-blue-600 focus:ring-0 group-hover:border-slate-200"
                    >
                    <div class="absolute inset-y-0 right-2 flex items-center">
                        <button type="submit" class="bg-blue-700 text-white font-black text-xs uppercase tracking-widest px-8 py-3.5 rounded-[1.5rem] shadow-lg shadow-blue-200 hover:bg-black transition-all active:scale-95">
                            {{ __('Temukan') }}
                        </button>
                    </div>
                </form>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-12">
            {{-- Filters Sidebar (Desktop) --}}
            <aside class="hidden lg:block w-72 flex-shrink-0 space-y-10">
                <section>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 px-2">{{ __('Kategori') }}</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('catalog') }}" class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ !request('category') ? 'bg-blue-50 text-blue-700 font-extrabold shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span>{{ __('Semua Koleksi') }}</span>
                            <span class="text-[10px] bg-white px-2 py-0.5 rounded-lg border border-slate-100">{{ $categories->sum('equipments_count') }}</span>
                        </a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('catalog', ['category' => $cat->slug] + request()->except(['category', 'page'])) }}" class="flex items-center justify-between px-4 py-3 rounded-2xl transition-all {{ request('category') == $cat->slug ? 'bg-blue-50 text-blue-700 font-extrabold shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span class="truncate">{{ $cat->name }}</span>
                                <span class="text-[10px] bg-white px-2 py-0.5 rounded-lg border border-slate-100">{{ $cat->equipments_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 px-2">{{ __('Urutkan') }}</h3>
                    <div class="flex flex-col gap-1">
                        @foreach(['latest' => __('Terbaru'), 'price_low' => __('Harga Terendah'), 'price_high' => __('Harga Tertinggi')] as $key => $label)
                            <a href="{{ route('catalog', ['sort' => $key] + request()->except(['sort', 'page'])) }}" class="flex items-center px-4 py-3 rounded-2xl transition-all {{ (request('sort', 'latest') == $key) ? 'bg-slate-900 text-white font-extrabold shadow-lg' : 'text-slate-600 hover:bg-slate-50' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                section>
            </aside>

            {{-- Product Grid --}}
            <main class="flex-1">
                @if ($equipments->isEmpty())
                    <article class="flex flex-col items-center justify-center rounded-[3rem] border-2 border-dashed border-slate-200 bg-white py-32 text-center">
                        <div class="relative mb-8">
                            <div class="absolute -inset-6 rounded-full bg-blue-50 opacity-50"></div>
                            <svg class="relative h-24 w-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900">{{ __('Alat Tidak Ditemukan') }}</h2>
                        <p class="mt-4 max-w-sm text-lg text-slate-500 font-medium">{{ __('Maaf, tidak ada alat yang cocok dengan pencarian atau filter Anda.') }}</p>
                        <a href="{{ route('catalog') }}" class="btn-primary mt-10 rounded-2xl px-12 py-4 font-bold shadow-xl shadow-blue-200 transition-all hover:scale-105 active:scale-95">
                            {{ __('Atur Ulang Semua') }}
                        </a>
                    </article>
                @else
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($equipments as $item)
                        <article class="group relative flex flex-col overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-blue-100">
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/5] overflow-hidden bg-slate-50 border-b border-slate-100">
                                <img src="{{ $item->image_path ? site_media_url($item->image_path) : config('placeholders.equipment') }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                                
                                {{-- Status Badge --}}
                                <div class="absolute top-5 left-5">
                                    @php
                                        $available = (int)($item->available_units ?? $item->stock);
                                    @endphp
                                    @if ($available > 0)
                                        <span class="rounded-2xl bg-white/95 backdrop-blur-sm px-3 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-600 shadow-sm border border-emerald-50">
                                            {{ __('Tersedia') }}
                                        </span>
                                    @else
                                        <span class="rounded-2xl bg-rose-500/95 backdrop-blur-sm px-3 py-1 text-[10px] font-black uppercase tracking-widest text-white shadow-sm">
                                            {{ __('Tersewa') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Container --}}
                            <div class="flex flex-1 flex-col p-6 sm:p-8">
                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-600 mb-1">
                                    {{ $item->category->name ?? __('Umum') }}
                                </p>
                                <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-blue-700 transition-colors line-clamp-2 min-h-[3.5rem]">
                                    <a href="{{ route('product.show', $item->slug) }}">{{ $item->name }}</a>
                                </h3>
                                
                                <div class="mt-8 flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ __('Mulai dari') }}</p>
                                        <p class="mt-1 text-2xl font-black text-slate-900">
                                            Rp{{ number_format($item->price_per_day, 0, ',', '.') }}<span class="text-xs text-slate-400 font-bold ml-1">/hari</span>
                                        </p>
                                    </div>
                                    <a href="{{ route('product.show', $item->slug) }}" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-200 transition-all duration-300 hover:bg-blue-700 hover:scale-110 active:scale-95">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-16">
                        {{ $equipments->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
@endsection
