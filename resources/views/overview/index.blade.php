@extends('layouts.app')

@section('title', __('ui.nav.my_orders'))
@section('page_title', __('ui.nav.my_orders'))

@php
    $formatIdr = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
    $canViewInvoice = static fn ($order) => ($order->status_pembayaran ?? 'pending') === 'paid' && ! $order->hasOutstandingDamageFee();

    $paymentMeta = function ($status) {
        return match ($status) {
            'paid' => ['label' => 'Lunas', 'badge' => 'bg-blue-100 text-blue-700'],
            'failed' => ['label' => 'Gagal', 'badge' => 'bg-rose-100 text-rose-700'],
            default => ['label' => 'Pending', 'badge' => 'bg-amber-100 text-amber-700'],
        };
    };
@endphp

@section('content')
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">{{ __('ui.nav.my_orders') }}</p>
            <h2 class="text-2xl font-semibold text-slate-900">{{ __('ui.nav.my_orders') }}</h2>
            <p class="text-sm text-slate-500">Semua order dan status pembayaran kamu.</p>
        </div>
        <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
            {{ __('ui.actions.explore_catalog') }}
        </a>
    </div>

    @if (session('error'))
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-500">{{ __('ui.overview.stats.total_booking') }}</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $stats['total_booking'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ __('ui.overview.stats.total_booking_note') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-500">{{ __('ui.overview.stats.active_rental') }}</p>
            <p class="mt-2 text-2xl font-semibold text-blue-600">{{ $stats['active_rental'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ __('ui.overview.stats.active_rental_note') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs text-slate-500">{{ __('ui.overview.stats.completed') }}</p>
            <p class="mt-2 text-2xl font-semibold text-blue-600">{{ $stats['completed'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">{{ __('ui.overview.stats.completed_note') }}</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1.4fr,1fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Rental Aktif</h3>
                <a href="{{ route('booking.history') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($activeRentals as $order)
                    @php
                        $meta = $paymentMeta($order->status_pembayaran ?? 'pending');
                        $canOpenInvoice = $canViewInvoice($order);
                        $orderNumber = $order->order_number ?? ('ORD-' . $order->id);
                    @endphp
                    <article class="rounded-xl border border-slate-100 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $orderNumber }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ optional($order->rental_start_date)->format('d M Y') }} - {{ optional($order->rental_end_date)->format('d M Y') }}
                                </p>
                                <p class="mt-2 text-xs font-semibold text-slate-500">Total {{ $formatIdr($order->grand_total ?? $order->total_amount) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $meta['badge'] }}">
                                    {{ $meta['label'] }}
                                </span>
                                @if ($canOpenInvoice)
                                    <button
                                        type="button"
                                        data-open-invoice-modal
                                        data-invoice-url="{{ route('account.orders.receipt', $order) }}"
                                        data-order-number="{{ $orderNumber }}"
                                        class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                                    >
                                        Invoice
                                    </button>
                                @elseif (($order->status_pembayaran ?? 'pending') !== 'paid')
                                    <a href="{{ route('booking.pay', $order) }}" class="rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
                                        Bayar
                                    </a>
                                @endif
                                @if ($canOpenInvoice)
                                    <button
                                        type="button"
                                        data-open-invoice-modal
                                        data-invoice-url="{{ route('account.orders.receipt', $order) }}"
                                        data-order-number="{{ $orderNumber }}"
                                        class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600"
                                    >
                                        Detail
                                    </button>
                                @else
                                    <a href="{{ route('account.orders.show', $order) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:border-blue-200 hover:text-blue-600">
                                        Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                        <p class="text-sm font-semibold text-slate-700">{{ __('ui.overview.empty_active_title') }}</p>
                        <p class="mt-2 text-xs text-slate-500">{{ __('ui.overview.empty_active_body') }}</p>
                        <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition">
                            {{ __('ui.overview.empty_active_cta') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Riwayat Terbaru</h3>
                <a href="{{ route('booking.history') }}" class="text-xs font-semibold text-slate-500 hover:text-blue-600">Riwayat</a>
            </div>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($recentBookings as $order)
                    @php
                        $meta = $paymentMeta($order->status_pembayaran ?? 'pending');
                        $canOpenInvoice = $canViewInvoice($order);
                        $orderNumber = $order->order_number ?? ('ORD-' . $order->id);
                    @endphp
                    <div class="py-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $orderNumber }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ optional($order->rental_start_date)->format('d M Y') }} - {{ optional($order->rental_end_date)->format('d M Y') }}
                                </p>
                            </div>
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $meta['badge'] }}">
                                {{ $meta['label'] }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-xs font-semibold text-slate-700">{{ $formatIdr($order->grand_total ?? $order->total_amount) }}</p>
                            @if ($canOpenInvoice)
                                <button
                                    type="button"
                                    data-open-invoice-modal
                                    data-invoice-url="{{ route('account.orders.receipt', $order) }}"
                                    data-order-number="{{ $orderNumber }}"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-700"
                                >
                                    Lihat
                                </button>
                            @else
                                <a href="{{ route('account.orders.show', $order) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    Lihat
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <p class="text-sm text-slate-500">{{ __('ui.overview.empty_recent_body') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if (isset($orders) && method_exists($orders, 'links'))
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

    <div
        id="order-invoice-modal"
        class="fixed inset-0 z-[90] hidden items-center justify-center p-3 sm:p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="order-invoice-modal-title"
    >
        <div class="absolute inset-0 bg-slate-950/55" data-close-invoice-modal></div>

        <div class="relative z-10 flex h-[94vh] w-full max-w-6xl flex-col overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-blue-600 px-4 py-3 text-white sm:px-5">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-blue-100">Invoice</p>
                    <h3 id="order-invoice-modal-title" class="text-base font-semibold sm:text-lg">
                        Detail Invoice
                    </h3>
                </div>
                <button
                    type="button"
                    data-close-invoice-modal
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-blue-300/80 bg-blue-500/60 text-xl leading-none text-white transition hover:bg-blue-500"
                    aria-label="Tutup modal"
                >
                    ×
                </button>
            </div>

            <div class="flex-1 bg-slate-100 p-2 sm:p-3">
                <iframe
                    id="order-invoice-modal-frame"
                    title="Invoice"
                    loading="lazy"
                    class="h-full w-full rounded-xl border border-slate-200 bg-white"
                ></iframe>
            </div>

            <div class="border-t border-slate-200 bg-white px-4 py-3">
                <div class="flex justify-end">
                    <button
                        type="button"
                        data-close-invoice-modal
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const modal = document.getElementById('order-invoice-modal');
            const frame = document.getElementById('order-invoice-modal-frame');
            const title = document.getElementById('order-invoice-modal-title');
            const openButtons = document.querySelectorAll('[data-open-invoice-modal]');
            const closeButtons = modal?.querySelectorAll('[data-close-invoice-modal]');
            const defaultTitle = 'Detail Invoice';

            if (!modal || !frame || openButtons.length === 0) {
                return;
            }

            const openModal = (invoiceUrl, orderNumber) => {
                if (!invoiceUrl) {
                    return;
                }

                frame.src = invoiceUrl;
                title.textContent = orderNumber ? `Invoice ${orderNumber}` : defaultTitle;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                title.textContent = defaultTitle;
                document.body.classList.remove('overflow-hidden');
                frame.src = 'about:blank';
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openModal(button.dataset.invoiceUrl, button.dataset.orderNumber);
                });
            });

            closeButtons?.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('flex')) {
                    closeModal();
                }
            });
        })();
    </script>
@endpush
