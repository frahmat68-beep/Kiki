@extends('layouts.app')

@section('title', 'Cek Ketersediaan Alat')
@section('meta_description', 'Pantau ketersediaan alat rental per tanggal dengan kalender interaktif dan ringkasan pemakaian.')

@php
    $monthValue = $monthDate->format('Y-m');
    $selectedDateValue = $selectedDate->toDateString();
    $monthLabel = $monthDate->translatedFormat('F Y');
    $selectedDateLabel = $selectedDate->translatedFormat('d M Y');
    $prevMonth = $monthDate->copy()->subMonth()->format('Y-m');
    $nextMonth = $monthDate->copy()->addMonth()->format('Y-m');
    $weekdayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    $toneClasses = [
        'calm' => 'border-slate-200 bg-white text-slate-700',
        'light' => 'border-blue-100 bg-blue-50 text-blue-700',
        'busy' => 'border-amber-100 bg-amber-50 text-amber-700',
        'critical' => 'border-rose-100 bg-rose-50 text-rose-700',
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        <section class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-700 via-blue-600 to-slate-900 px-6 py-6 text-white shadow-xl sm:px-7 sm:py-7">
            <div class="pointer-events-none absolute -right-16 -top-20 h-44 w-44 rounded-full bg-white/15 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-16 h-52 w-52 rounded-full bg-cyan-300/20 blur-2xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">Availability Board</p>
                    <h1 class="mt-2 text-2xl font-semibold sm:text-3xl">Pusat Cek Ketersediaan Alat</h1>
                    <p class="mt-2 max-w-2xl text-sm text-blue-100 sm:text-base">
                        Cek alat kosong atau terpakai per tanggal, lihat kepadatan sewa, dan pantau jadwal aktif dalam satu halaman.
                    </p>
                </div>

                <form method="GET" action="{{ route('availability.board') }}" class="grid w-full gap-2 rounded-2xl border border-white/20 bg-white/10 p-3 backdrop-blur sm:grid-cols-[minmax(0,1fr)_auto_auto] lg:max-w-2xl">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama alat..."
                        class="w-full rounded-xl border border-white/25 bg-white/95 px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                    >
                    <input
                        type="month"
                        name="month"
                        value="{{ $monthValue }}"
                        class="rounded-xl border border-white/25 bg-white/95 px-3 py-2 text-sm text-slate-700 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                    >
                    <input
                        type="date"
                        name="date"
                        value="{{ $selectedDateValue }}"
                        class="rounded-xl border border-white/25 bg-white/95 px-3 py-2 text-sm text-slate-700 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/40"
                    >
                    <div class="sm:col-span-3 flex flex-wrap items-center gap-2">
                        <button class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                            Tampilkan
                        </button>
                        @if ($search !== '')
                            <a href="{{ route('availability.board', ['month' => $monthValue, 'date' => $selectedDateValue]) }}" class="inline-flex items-center justify-center rounded-xl border border-white/30 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/15">
                                Reset Pencarian
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-slate-50/70 px-5 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Kalender Pemakaian</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $monthLabel }}</h2>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-1.5">
                        <a
                            href="{{ route('availability.board', ['month' => $prevMonth, 'date' => $monthDate->copy()->subMonth()->startOfMonth()->toDateString(), 'q' => $search ?: null]) }}"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                            aria-label="Bulan sebelumnya"
                        >
                            ←
                        </a>
                        <span class="min-w-[9rem] text-center text-sm font-semibold text-slate-700">{{ $monthLabel }}</span>
                        <a
                            href="{{ route('availability.board', ['month' => $nextMonth, 'date' => $monthDate->copy()->addMonth()->startOfMonth()->toDateString(), 'q' => $search ?: null]) }}"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                            aria-label="Bulan berikutnya"
                        >
                            →
                        </a>
                    </div>
                </div>

                <div class="px-5 py-4">
                    <div class="grid grid-cols-7 gap-2">
                        @foreach ($weekdayLabels as $weekday)
                            <p class="text-center text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $weekday }}</p>
                        @endforeach
                    </div>
                    <div class="mt-2 grid grid-cols-7 gap-2">
                        @foreach ($calendarDays as $day)
                            @php
                                $toneClass = $toneClasses[$day['tone']] ?? $toneClasses['calm'];
                                $selectedClass = $day['is_selected'] ? 'ring-2 ring-blue-500 shadow-md shadow-blue-100' : '';
                            @endphp
                            <a
                                href="{{ route('availability.board', ['month' => $monthValue, 'date' => $day['date'], 'q' => $search ?: null]) }}"
                                class="group rounded-xl border px-2 py-2.5 transition hover:-translate-y-0.5 {{ $toneClass }} {{ $selectedClass }} {{ $day['in_month'] ? '' : 'opacity-55' }}"
                            >
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-semibold {{ $day['is_today'] ? 'text-blue-600' : '' }}">{{ $day['day'] }}</p>
                                    @if ($day['is_selected'])
                                        <span class="inline-flex h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                    @endif
                                </div>
                                <p class="mt-2 text-[11px] font-semibold">
                                    {{ $day['busy_equipments'] }} alat terpakai
                                </p>
                                <p class="mt-0.5 text-[10px]">
                                    {{ $day['reserved_units'] }} unit dipakai
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </article>

            <div class="space-y-4">
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tanggal Dipilih</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900">{{ $selectedDateLabel }}</h2>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Total Alat</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $summary['total_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-rose-100 bg-rose-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-rose-500">Terpakai / Blokir</p>
                            <p class="mt-1 text-2xl font-semibold text-rose-700">{{ $summary['busy_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-600">Masih Kosong</p>
                            <p class="mt-1 text-2xl font-semibold text-emerald-700">{{ $summary['available_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-blue-500">Unit Dipakai</p>
                            <p class="mt-1 text-2xl font-semibold text-blue-700">{{ $summary['reserved_units'] ?? 0 }}</p>
                        </div>
                    </div>
                </article>

                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-base font-semibold text-slate-900">Alat Paling Siap Dipakai</h3>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                            {{ $selectedFreeRows->count() }} kosong
                        </span>
                    </div>
                    <div class="mt-3 space-y-2">
                        @forelse ($selectedFreeRows->take(6) as $row)
                            <article class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-slate-900">{{ $row['name'] }}</p>
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                                        sisa {{ $row['selected_available'] }}
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-slate-500">{{ $row['category'] }}</p>
                            </article>
                        @empty
                            <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-3 py-3 text-xs text-slate-500">
                                Tidak ada alat yang kosong pada tanggal ini.
                            </p>
                        @endforelse
                    </div>
                </article>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Matriks Ketersediaan Alat</h2>
                    <p class="text-xs text-slate-500">Lihat kondisi setiap alat pada tiap tanggal. Warna merah berarti penuh/tidak tersedia.</p>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    {{ $equipmentRows->count() }} alat ditampilkan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[1100px] border-separate border-spacing-0 text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500">
                            <th class="sticky left-0 z-20 border-b border-r border-slate-200 bg-slate-50 px-3 py-3 text-left text-[11px] uppercase tracking-wider">Alat</th>
                            @foreach ($dateKeys as $dateKey)
                                @php
                                    $date = \Carbon\Carbon::parse($dateKey)->startOfDay();
                                @endphp
                                <th class="border-b border-slate-200 px-2 py-2 text-center font-semibold {{ $date->isSameDay($selectedDate) ? 'bg-blue-50 text-blue-700' : '' }}">
                                    <p>{{ $date->format('d') }}</p>
                                    <p class="text-[10px] font-medium text-slate-400">{{ strtoupper($date->translatedFormat('M')) }}</p>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($equipmentRows as $row)
                            <tr class="hover:bg-slate-50/60">
                                <td class="sticky left-0 z-10 border-r border-slate-200 bg-white px-3 py-2 align-top">
                                    <p class="font-semibold text-slate-900">{{ $row['name'] }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-500">{{ $row['category'] }} • stok {{ $row['stock'] }}</p>
                                    <span class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $row['status_badge_class'] }}">
                                        {{ $row['status_label'] }}
                                    </span>
                                </td>
                                @foreach ($dateKeys as $dateKey)
                                    @php
                                        $cell = data_get($row['day_cells'], $dateKey, ['available' => 0, 'reserved' => 0, 'is_blocked' => true]);
                                        $availableUnits = (int) ($cell['available'] ?? 0);
                                        $reservedUnits = (int) ($cell['reserved'] ?? 0);
                                        $isBlocked = (bool) ($cell['is_blocked'] ?? false);
                                        $cellClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                                        if ($isBlocked) {
                                            $cellClass = 'bg-rose-50 text-rose-700 border-rose-100';
                                        } elseif ($reservedUnits > 0) {
                                            $cellClass = 'bg-amber-50 text-amber-700 border-amber-100';
                                        }
                                        if ($dateKey === $selectedDateValue) {
                                            $cellClass .= ' ring-1 ring-blue-300';
                                        }
                                    @endphp
                                    <td class="border-b border-slate-100 px-1.5 py-1.5 text-center">
                                        <div class="rounded-lg border px-1 py-1 {{ $cellClass }}">
                                            <p class="text-[11px] font-semibold">{{ $availableUnits }}</p>
                                            <p class="text-[10px]">sisa</p>
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ max(count($dateKeys) + 1, 2) }}" class="px-4 py-8 text-center text-sm text-slate-500">
                                    Tidak ada alat yang cocok dengan kata kunci pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Alat Terpakai di {{ $selectedDateLabel }}</h2>
                    <span class="rounded-full bg-rose-100 px-2.5 py-1 text-[11px] font-semibold text-rose-700">{{ $selectedBusyRows->count() }} alat</span>
                </div>
                <div class="mt-3 space-y-2">
                    @forelse ($selectedBusyRows->take(10) as $row)
                        <article class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $row['name'] }}</p>
                                <p class="text-xs font-semibold text-rose-600">dipakai {{ $row['selected_reserved'] }} unit</p>
                            </div>
                            @if ($row['source_labels']->isNotEmpty())
                                <p class="mt-1 text-xs text-slate-600">{{ $row['source_labels']->implode(', ') }}</p>
                            @endif
                            @if ($row['order_numbers']->isNotEmpty())
                                <p class="mt-1 text-[11px] text-slate-500">Order: {{ $row['order_numbers']->take(3)->implode(', ') }}</p>
                            @endif
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-3 py-3 text-xs text-slate-500">
                            Tidak ada alat yang sedang dipakai pada tanggal ini.
                        </p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Jadwal Aktif Bulan Ini</h2>
                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">{{ $monthlySchedules->count() }} jadwal</span>
                </div>
                <div class="mt-3 max-h-[29rem] space-y-2 overflow-y-auto pr-1">
                    @forelse ($monthlySchedules as $schedule)
                        <article class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                            <p class="text-sm font-semibold text-slate-900">{{ $schedule['equipment_name'] }}</p>
                            <p class="mt-0.5 text-xs text-slate-600">
                                {{ \Carbon\Carbon::parse($schedule['start_date'])->translatedFormat('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($schedule['end_date'])->translatedFormat('d M Y') }}
                                • Qty {{ $schedule['qty'] }}
                            </p>
                            <p class="mt-1 text-[11px] text-slate-500">
                                {{ $schedule['order_number'] }} • {{ strtoupper($schedule['status_pesanan']) }}
                            </p>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-3 py-3 text-xs text-slate-500">
                            Belum ada jadwal aktif pada rentang bulan ini.
                        </p>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
@endsection
