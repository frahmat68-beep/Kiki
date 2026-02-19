@extends('layouts.admin', ['activePage' => 'dashboard'])

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Operasional')

@section('content')
    @php
        $statusBadge = fn (?string $status) => match ($status) {
            'lunas' => ['label' => 'Siap Diambil', 'class' => 'bg-blue-100 text-blue-700'],
            'barang_diambil' => ['label' => 'Sedang Disewa', 'class' => 'bg-amber-100 text-amber-700'],
            'barang_kembali' => ['label' => 'Sudah Kembali', 'class' => 'bg-emerald-100 text-emerald-700'],
            'barang_rusak' => ['label' => 'Barang Rusak', 'class' => 'bg-rose-100 text-rose-700'],
            default => ['label' => strtoupper((string) $status), 'class' => 'bg-slate-100 text-slate-700'],
        };
        $rentalCalendar = $rentalCalendar ?? [];
        $calendarDays = collect($rentalCalendar['days'] ?? []);
        $calendarBaseQuery = request()->except(['calendar_month', 'page']);
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Kontrol Operasional</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900 sm:text-3xl">Konfirmasi Status Rental User</h2>
            <p class="mt-2 text-sm text-slate-600">
                Dashboard ini difokuskan untuk konfirmasi barang diambil, dikembalikan, atau rusak. Setiap update otomatis kirim notifikasi ke user.
            </p>
        </section>

        <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Siap Diambil</p>
                <p class="mt-2 text-3xl font-semibold text-blue-600">{{ (int) ($summary['ready_pickup'] ?? 0) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Sedang Disewa</p>
                <p class="mt-2 text-3xl font-semibold text-amber-600">{{ (int) ($summary['on_rent'] ?? 0) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Sudah Kembali</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-600">{{ (int) ($summary['returned'] ?? 0) }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-semibold text-slate-500">Kasus Rusak</p>
                <p class="mt-2 text-3xl font-semibold text-rose-600">{{ (int) ($summary['damaged'] ?? 0) }}</p>
            </article>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Kalender Total Alat Disewa</h3>
                    <p class="text-sm text-slate-500">Ringkasan unit aktif per tanggal (simple view).</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-2 py-1.5">
                    <a
                        href="{{ route('admin.dashboard', array_merge($calendarBaseQuery, ['calendar_month' => $rentalCalendar['previous_month'] ?? now()->subMonth()->format('Y-m')])) }}"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        aria-label="Bulan sebelumnya"
                    >
                        ←
                    </a>
                    <span class="min-w-[9rem] text-center text-sm font-semibold text-slate-700">{{ $rentalCalendar['month_label'] ?? now()->translatedFormat('F Y') }}</span>
                    <a
                        href="{{ route('admin.dashboard', array_merge($calendarBaseQuery, ['calendar_month' => $rentalCalendar['next_month'] ?? now()->addMonth()->format('Y-m')])) }}"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition hover:bg-slate-100 hover:text-slate-900"
                        aria-label="Bulan berikutnya"
                    >
                        →
                    </a>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <article class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Total Unit-Hari</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-900">{{ (int) ($rentalCalendar['total_unit_days'] ?? 0) }}</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Puncak Harian</p>
                    <p class="mt-1 text-2xl font-semibold text-blue-600">{{ (int) ($rentalCalendar['max_daily_units'] ?? 0) }}</p>
                </article>
                <article class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Order Aktif</p>
                    <p class="mt-1 text-2xl font-semibold text-amber-600">{{ (int) ($rentalCalendar['active_orders'] ?? 0) }}</p>
                </article>
            </div>

            <div class="mt-5 grid grid-cols-7 gap-2">
                @foreach (['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'] as $weekday)
                    <p class="text-center text-[11px] font-semibold uppercase tracking-widest text-slate-400">{{ $weekday }}</p>
                @endforeach
            </div>
            <div class="mt-2 grid grid-cols-7 gap-2">
                @foreach ($calendarDays as $day)
                    @php
                        $hasRental = (int) ($day['total_qty'] ?? 0) > 0;
                    @endphp
                    <div class="rounded-xl border px-2 py-2 {{ ($day['in_month'] ?? false) ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50 text-slate-300' }}">
                        <p class="text-xs font-semibold">{{ $day['day'] }}</p>
                        <p class="mt-1 text-xs {{ $hasRental ? 'font-semibold text-blue-600' : 'text-slate-400' }}">
                            {{ $hasRental ? ($day['total_qty'] . ' unit') : '-' }}
                        </p>
                    </div>
                @endforeach
            </div>

            @if (collect($rentalCalendar['events'] ?? [])->isNotEmpty())
                <div class="mt-4 space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Jadwal Aktif (sample)</p>
                    @foreach (collect($rentalCalendar['events'] ?? []) as $event)
                        <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg bg-white px-3 py-2 text-xs">
                            <p class="font-semibold text-slate-800">{{ $event['order_number'] }}</p>
                            <p class="text-slate-600">
                                {{ \Carbon\Carbon::parse($event['start_date'])->format('d M') }} - {{ \Carbon\Carbon::parse($event['end_date'])->format('d M') }}
                                • Qty {{ (int) ($event['qty'] ?? 0) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Overview Pesanan User</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Buka Semua Orders →</a>
            </div>

            @if ($operationalOrders->isEmpty())
                <div class="px-5 py-8 text-sm text-slate-500">
                    Tidak ada order operasional untuk ditindaklanjuti saat ini.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($operationalOrders as $order)
                        @php
                            $badge = $statusBadge($order->status_pesanan);
                            $itemsLabel = $order->items->pluck('equipment.name')->filter()->take(2)->implode(', ');
                            $rentalStart = $order->rental_start_date ? $order->rental_start_date->copy()->startOfDay() : null;
                            $pickupOpenAt = $rentalStart?->copy()->subDay();
                            $canConfirmPickupNow = $pickupOpenAt ? now()->greaterThanOrEqualTo($pickupOpenAt) : false;
                            $isReadyPickup = $order->status_pesanan === 'lunas';
                            $isOnRent = $order->status_pesanan === 'barang_diambil';
                            $isReturned = in_array($order->status_pesanan, ['barang_kembali', 'barang_rusak', 'selesai'], true);
                        @endphp
                        <article class="px-5 py-4">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-base font-semibold text-slate-900">{{ $order->order_number ?? ('ORD-' . $order->id) }}</p>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $order->user?->name ?? '-' }} •
                                        {{ optional($order->rental_start_date)->format('d M Y') }} - {{ optional($order->rental_end_date)->format('d M Y') }}
                                    </p>
                                    @if ($itemsLabel !== '')
                                        <p class="mt-1 text-xs text-slate-500">Item: {{ $itemsLabel }}@if($order->items->count() > 2) +{{ $order->items->count() - 2 }} lainnya @endif</p>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 lg:w-[480px]">
                                    @if ($isReadyPickup && $canConfirmPickupNow)
                                        <form method="POST" action="{{ route('admin.dashboard.orders.operational-status', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pesanan" value="barang_diambil">
                                            <button class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                                Konfirmasi Diambil
                                            </button>
                                        </form>
                                    @else
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-xs font-semibold text-slate-400">
                                            @if ($isOnRent)
                                                Sudah Diambil
                                            @elseif ($isReturned)
                                                Order Selesai
                                            @else
                                                Belum Bisa Konfirmasi
                                            @endif
                                        </div>
                                    @endif

                                    @if ($isOnRent)
                                        <form method="POST" action="{{ route('admin.dashboard.orders.operational-status', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pesanan" value="barang_kembali">
                                            <button class="w-full rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                                Konfirmasi Dikembalikan
                                            </button>
                                        </form>
                                    @else
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-xs font-semibold text-slate-400">
                                            @if ($order->status_pesanan === 'barang_kembali')
                                                Sudah Kembali
                                            @elseif ($order->status_pesanan === 'barang_rusak')
                                                Ditandai Rusak
                                            @else
                                                Menunggu Status Diambil
                                            @endif
                                        </div>
                                    @endif

                                    @if ($isOnRent)
                                        <form method="POST" action="{{ route('admin.dashboard.orders.operational-status', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_pesanan" value="barang_rusak">
                                            <button class="w-full rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                                Tandai Rusak
                                            </button>
                                        </form>
                                    @else
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-center text-xs font-semibold text-slate-400">
                                            @if ($order->status_pesanan === 'barang_rusak')
                                                Sudah Ditandai
                                            @else
                                                Menunggu Status Diambil
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if ($isReadyPickup && ! $canConfirmPickupNow)
                                <p class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                                    Konfirmasi barang diambil hanya bisa dilakukan H-1 sebelum tanggal sewa
                                    @if ($pickupOpenAt)
                                        (mulai {{ $pickupOpenAt->format('d M Y') }}).
                                    @endif
                                </p>
                            @endif
                            @if (! $isOnRent && ! $isReturned)
                                <p class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                    Konfirmasi pengembalian atau tandai rusak hanya aktif setelah status <span class="font-semibold">Barang Diambil</span>.
                                </p>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $operationalOrders->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
