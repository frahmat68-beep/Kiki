@extends('layouts.app')

@section('title', __('Availability Board'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <header class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-blue-700 sm:text-5xl">
                {{ __('Availability Board') }}
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-500">
                {{ __('Pantau ketersediaan unit peralatan secara real-time untuk perencanaan produksi Anda.') }}
            </p>
        </header>

        <div class="space-y-8">
            {{-- Filtering & Legend --}}
            <section class="rounded-[2.5rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <form method="GET" action="{{ route('availability.board') }}" class="flex w-full flex-col gap-4 sm:flex-row sm:items-center md:w-auto">
                        <div class="relative flex-1 sm:min-w-[200px]">
                            <select name="category" class="select w-full rounded-2xl border-slate-200 py-3 pl-4 pr-10 text-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                                <option value="">{{ __('Semua Kategori') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary rounded-2xl px-8 py-3 text-sm font-bold shadow-lg shadow-blue-200 transition-all hover:scale-105 active:scale-95">
                            {{ __('Filter') }}
                        </button>
                    </form>

                    <div class="flex flex-wrap items-center justify-center gap-6">
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></span>
                            <span class="text-sm font-medium text-slate-600">{{ __('Tersedia') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded-full bg-amber-500 shadow-sm shadow-amber-200"></span>
                            <span class="text-sm font-medium text-slate-600">{{ __('Terbatas') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></span>
                            <span class="text-sm font-medium text-slate-600">{{ __('Habis') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-4 w-4 rounded-full bg-slate-200 shadow-sm"></span>
                            <span class="text-sm font-medium text-slate-600">{{ __('Buffer/Maintenance') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Calendar Grid --}}
            <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                @foreach($boardData as $item)
                    <article class="group relative overflow-hidden rounded-[2.5rem] border border-slate-200 bg-white transition-all duration-300 hover:shadow-xl hover:shadow-blue-100">
                        <div class="p-6 sm:p-8">
                            <header class="mb-6 flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                        {{ $item['equipment']->name }}
                                    </h3>
                                    <p class="mt-1 text-sm font-medium text-blue-600">
                                        {{ $item['equipment']->category->name ?? __('Umum') }}
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500 border border-slate-100">
                                    {{ $item['equipment']->stock }} {{ __('Unit') }}
                                </div>
                            </header>

                            {{-- Calendar UI --}}
                            <div class="grid grid-cols-7 gap-2">
                                @php
                                    $days = ['S', 'S', 'R', 'K', 'J', 'S', 'M'];
                                @endphp
                                @foreach($days as $day)
                                    <div class="text-center text-[10px] font-black uppercase tracking-tighter text-slate-400">
                                        {{ $day }}
                                    </div>
                                @endforeach

                                @foreach($item['daily_availability'] as $date => $data)
                                    @php
                                        $availability = $data['percentage'];
                                        $colorClass = match(true) {
                                            $availability >= 100 => 'bg-emerald-500',
                                            $availability > 0 => 'bg-amber-500',
                                            default => 'bg-rose-500'
                                        };
                                        $isToday = $date === now()->toDateString();
                                    @endphp
                                    <div 
                                        class="aspect-square rounded-xl {{ $colorClass }} flex flex-col items-center justify-center p-1 relative group/day transition-transform hover:scale-110 {{ $isToday ? 'ring-2 ring-blue-700 ring-offset-2' : '' }}"
                                        title="{{ Carbon\Carbon::parse($date)->format('d M Y') }}: {{ $data['available'] }}/{{ $item['equipment']->stock }} unit"
                                    >
                                        <span class="text-[10px] font-bold text-white leading-none">
                                            {{ Carbon\Carbon::parse($date)->day }}
                                        </span>
                                        
                                        {{-- Tooltip for Mobile/Desktop Hover --}}
                                        <div class="absolute bottom-full left-1/2 mb-2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-2 py-1 text-[10px] text-white group-hover/day:block z-10">
                                            {{ $data['available'] }} {{ __('Tersedia') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <footer class="mt-8">
                                <a href="{{ route('product.show', $item['equipment']->slug) }}" class="btn-secondary flex w-full items-center justify-center rounded-[1.25rem] py-3 text-sm font-bold transition-all hover:bg-blue-700 hover:text-white">
                                    {{ __('Lihat Detail') }}
                                </a>
                            </footer>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($boardData instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-12">
                    {{ $boardData->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
