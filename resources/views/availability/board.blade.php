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
        'light' => 'border-blue-100 bg-blue-50/70 text-slate-700',
        'busy' => 'border-blue-200 bg-blue-100/70 text-slate-800',
        'critical' => 'border-slate-900 bg-slate-900 text-white',
    ];
@endphp

@push('head')
    <style>
        @keyframes availabilitySurfaceIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .availability-surface {
            animation: availabilitySurfaceIn 0.28s ease-out both;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .availability-surface:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 26px -20px rgba(15, 23, 42, 0.42);
            border-color: rgba(59, 130, 246, 0.32);
        }

        .board-cell {
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .board-cell:hover {
            transform: translateY(-1px);
            box-shadow: 0 9px 20px -18px rgba(15, 23, 42, 0.48);
        }

        .board-item {
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .board-item:hover {
            transform: translateX(2px);
            box-shadow: 0 10px 22px -19px rgba(15, 23, 42, 0.4);
        }

        @media (prefers-reduced-motion: reduce) {
            .availability-surface,
            .board-cell,
            .board-item {
                animation: none;
                transition: none;
                transform: none;
            }
        }
    </style>
@endpush

@section('content')
    <div
        class="mx-auto max-w-7xl space-y-6"
        x-data="{
            schedulesByDate: @js($dailySchedulesByDate ?? []),
            scheduleModalOpen: false,
            modalDate: '',
            modalDateLabel: '',
            modalBusyEquipments: 0,
            modalReservedUnits: 0,
            modalAvailableEquipments: 0,
            modalSchedules: [],
            formatDateLabel(value) {
                if (!value) return '-';
                const parsed = new Date(`${value}T00:00:00`);
                if (Number.isNaN(parsed.getTime())) return value;
                return new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                }).format(parsed);
            },
            openScheduleModal(date, busyEquipments, reservedUnits, availableEquipments) {
                this.modalDate = date;
                this.modalDateLabel = this.formatDateLabel(date);
                this.modalBusyEquipments = busyEquipments;
                this.modalReservedUnits = reservedUnits;
                this.modalAvailableEquipments = availableEquipments;
                this.modalSchedules = Array.isArray(this.schedulesByDate[date]) ? this.schedulesByDate[date] : [];
                this.scheduleModalOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeScheduleModal() {
                this.scheduleModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            },
        }"
        x-on:keydown.escape.window="closeScheduleModal()"
    >
        <section class="availability-surface relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-white via-blue-50/70 to-slate-100 px-6 py-6 shadow-sm sm:px-7 sm:py-7">
            <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-blue-100/70 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-12 h-56 w-56 rounded-full bg-white/80 blur-2xl"></div>

            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-blue-600">Availability Board</p>
                    <h1 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Pusat Cek Ketersediaan Alat</h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-600 sm:text-base">
                        Klik tanggal di kalender untuk melihat pesanan alat yang sedang berjalan dan pantau ketersediaan dengan cepat.
                    </p>
                </div>

                <form method="GET" action="{{ route('availability.board') }}" class="availability-surface grid w-full gap-2 rounded-2xl border border-slate-200 bg-white/95 p-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] lg:max-w-2xl">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Cari nama alat..."
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                    <input
                        type="month"
                        name="month"
                        value="{{ $monthValue }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                    <input
                        type="date"
                        name="date"
                        value="{{ $selectedDateValue }}"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-200"
                    >
                    <div class="sm:col-span-3 flex flex-wrap items-center gap-2">
                        <button class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Tampilkan
                        </button>
                        @if ($search !== '')
                            <a href="{{ route('availability.board', ['month' => $monthValue, 'date' => $selectedDateValue]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                Reset Pencarian
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
            <article class="availability-surface overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
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
                                $todayClass = $day['is_today'] ? ($day['tone'] === 'critical' ? 'text-blue-100' : 'text-blue-600') : '';
                            @endphp
                            <button
                                type="button"
                                class="board-cell group w-full rounded-xl border px-2 py-2.5 text-left {{ $toneClass }} {{ $selectedClass }} {{ $day['in_month'] ? '' : 'opacity-55' }}"
                                @click="openScheduleModal('{{ $day['date'] }}', {{ (int) $day['busy_equipments'] }}, {{ (int) $day['reserved_units'] }}, {{ (int) $day['available_equipments'] }})"
                                aria-haspopup="dialog"
                                aria-label="Lihat detail tanggal {{ \Carbon\Carbon::parse($day['date'])->translatedFormat('d F Y') }}"
                            >
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-semibold {{ $todayClass }}">{{ $day['day'] }}</p>
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
                            </button>
                        @endforeach
                    </div>
                </div>
            </article>

            <div class="space-y-4">
                <article class="availability-surface rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tanggal Dipilih</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900">{{ $selectedDateLabel }}</h2>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Total Alat</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $summary['total_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-blue-100 bg-blue-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-blue-500">Terpakai / Blokir</p>
                            <p class="mt-1 text-2xl font-semibold text-blue-700">{{ $summary['busy_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-600">Masih Kosong</p>
                            <p class="mt-1 text-2xl font-semibold text-emerald-700">{{ $summary['available_equipments'] ?? 0 }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Unit Dipakai</p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $summary['reserved_units'] ?? 0 }}</p>
                        </div>
                    </div>
                </article>

                <article class="availability-surface rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-base font-semibold text-slate-900">Alat Paling Siap Dipakai</h3>
                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                            {{ $selectedFreeRows->count() }} kosong
                        </span>
                    </div>
                    <div class="mt-3 space-y-2">
                        @forelse ($selectedFreeRows->take(6) as $row)
                            <article class="board-item rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
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

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]">
            <article class="availability-surface rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Alat Terpakai di {{ $selectedDateLabel }}</h2>
                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">{{ $selectedBusyRows->count() }} alat</span>
                </div>
                <div class="mt-3 space-y-2">
                    @forelse ($selectedBusyRows->take(10) as $row)
                        <article class="board-item rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-900">{{ $row['name'] }}</p>
                                <p class="text-xs font-semibold text-blue-600">dipakai {{ $row['selected_reserved'] }} unit</p>
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

            <article class="availability-surface rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900">Jadwal Aktif Bulan Ini</h2>
                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold text-blue-700">{{ $monthlySchedules->count() }} jadwal</span>
                </div>
                <div class="mt-3 max-h-[29rem] space-y-2 overflow-y-auto pr-1">
                    @forelse ($monthlySchedules as $schedule)
                        <article class="board-item rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
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

        <div
            x-cloak
            x-show="scheduleModalOpen"
            x-transition.opacity
            class="fixed inset-0 z-[95] flex items-center justify-center p-4 sm:p-6"
            role="dialog"
            aria-modal="true"
            @click.self="closeScheduleModal()"
        >
            <div class="absolute inset-0 bg-slate-950/55 backdrop-blur-[1px]"></div>

            <div class="availability-surface relative z-10 w-full max-w-3xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50/80 px-5 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Detail Tanggal</p>
                        <h2 class="mt-1 text-xl font-semibold text-slate-900" x-text="modalDateLabel"></h2>
                    </div>
                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:text-blue-700"
                        @click="closeScheduleModal()"
                        aria-label="Tutup popup"
                    >
                        ✕
                    </button>
                </div>

                <div class="px-5 py-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Alat Terpakai</p>
                            <p class="mt-1 text-lg font-semibold text-slate-900" x-text="modalBusyEquipments"></p>
                        </div>
                        <div class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2.5">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-blue-500">Unit Dipakai</p>
                            <p class="mt-1 text-lg font-semibold text-blue-700" x-text="modalReservedUnits"></p>
                        </div>
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2.5">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-600">Masih Kosong</p>
                            <p class="mt-1 text-lg font-semibold text-emerald-700" x-text="modalAvailableEquipments"></p>
                        </div>
                    </div>

                    <div class="mt-4 max-h-[26rem] space-y-2 overflow-y-auto pr-1">
                        <template x-if="modalSchedules.length === 0">
                            <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-500">
                                Tidak ada pesanan aktif pada tanggal ini.
                            </p>
                        </template>

                        <template x-for="(item, index) in modalSchedules" :key="`${item.order_number}-${item.equipment_name}-${index}`">
                            <article class="board-item rounded-xl border border-slate-200 bg-slate-50 px-3 py-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900" x-text="item.equipment_name"></p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            <span x-text="item.order_number"></span>
                                            •
                                            <span x-text="item.status_label"></span>
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[11px] font-semibold text-blue-700" x-text="`x${item.qty}`"></span>
                                </div>
                                <p class="mt-2 text-xs text-slate-600">
                                    Periode: <span x-text="`${formatDateLabel(item.start_date)} - ${formatDateLabel(item.end_date)}`"></span>
                                </p>
                            </article>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
