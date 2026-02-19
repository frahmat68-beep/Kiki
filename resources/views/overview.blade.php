@extends('layouts.user')

@section('title', __('ui.overview.title'))
@section('page_title', __('ui.overview.title'))

@section('content')
    @php
        $stats = [
            [
                'label' => __('ui.overview.stats.total_booking'),
                'value' => 12,
                'note' => __('ui.overview.stats.total_booking_note'),
                'accent' => 'text-slate-900',
            ],
            [
                'label' => __('ui.overview.stats.active_rental'),
                'value' => 2,
                'note' => __('ui.overview.stats.active_rental_note'),
                'accent' => 'text-blue-600',
            ],
            [
                'label' => __('ui.overview.stats.completed'),
                'value' => 10,
                'note' => __('ui.overview.stats.completed_note'),
                'accent' => 'text-emerald-600',
            ],
        ];

        $activeRentals = [
            [
                'id' => 1,
                'slug' => 'sony-a7-iii',
                'name' => 'Sony A7 III',
                'date' => __('ui.overview.sample_date_1'),
                'status' => __('ui.overview.status.pending_payment'),
                'badge_class' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-200',
                'price' => 1050000,
                'image' => 'https://images.unsplash.com/photo-1519183071298-a2962be96c68?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'id' => 2,
                'slug' => 'dji-ronin-rs3',
                'name' => 'DJI Ronin RS3',
                'date' => __('ui.overview.sample_date_2'),
                'status' => __('ui.overview.status.active'),
                'badge_class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-200',
                'price' => 500000,
                'image' => 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $recentBookings = [
            [
                'id' => 101,
                'name' => 'Godox SL60W',
                'date' => __('ui.overview.sample_date_3'),
                'status' => __('ui.overview.status.completed'),
                'badge_class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200',
                'price' => 300000,
            ],
            [
                'id' => 102,
                'name' => 'RODE Wireless GO II',
                'date' => __('ui.overview.sample_date_4'),
                'status' => __('ui.overview.status.canceled'),
                'badge_class' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-200',
                'price' => 240000,
            ],
            [
                'id' => 103,
                'name' => 'DJI Mini 3 Pro',
                'date' => __('ui.overview.sample_date_5'),
                'status' => __('ui.overview.status.completed'),
                'badge_class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200',
                'price' => 800000,
            ],
        ];
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 dark:text-blue-400">{{ __('ui.overview.subtitle') }}</p>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ __('ui.overview.title') }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('ui.overview.description') }}</p>
        </div>
        <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
            {{ __('ui.actions.rent_new') }}
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-semibold {{ $stat['accent'] }}">{{ $stat['value'] }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1.4fr,1fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('ui.overview.active_rental') }}</h3>
                <a href="{{ route('booking.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">{{ __('ui.actions.view_all') }}</a>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($activeRentals as $rent)
                    <div class="flex flex-col gap-4 rounded-xl border border-slate-100 p-4 transition hover:shadow-sm dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <img
                                src="{{ $rent['image'] }}"
                                alt="{{ $rent['name'] }}"
                                class="h-16 w-16 rounded-xl object-cover bg-slate-100"
                            >
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $rent['name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $rent['date'] }}</p>
                                <span class="mt-2 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $rent['badge_class'] }}">
                                    {{ $rent['status'] }}
                                </span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="text-right">
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('ui.overview.total') }}</p>
                                <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                    Rp {{ number_format($rent['price'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ route('product.show', $rent['slug']) }}"
                                    class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition dark:border-slate-700 dark:text-slate-200"
                                >
                                    {{ __('ui.actions.detail') }}
                                </a>
                                <a
                                    href="{{ route('booking.pay', $rent['id']) }}"
                                    class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition"
                                >
                                    {{ __('ui.actions.pay') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('ui.overview.recent_booking') }}</h3>
                <a href="{{ route('booking.history') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600">{{ __('ui.actions.history') }}</a>
            </div>

            <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($recentBookings as $booking)
                    <div class="flex flex-col gap-3 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $booking['name'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $booking['date'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $booking['badge_class'] }}">
                                {{ $booking['status'] }}
                            </span>
                            <span class="font-semibold text-slate-700 dark:text-slate-200">
                                Rp {{ number_format($booking['price'], 0, ',', '.') }}
                            </span>
                            <a href="{{ route('booking.show', $booking['id']) }}" class="text-blue-600 hover:text-blue-700">{{ __('ui.actions.detail') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
