@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    @php
        $formatIdr = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $isCartEmpty = empty($cartItems);
        $estimatedSubtotal = (int) ($estimatedSubtotal ?? 0);
        $taxAmount = (int) ($taxAmount ?? 0);
        $estimatedTotal = (int) ($estimatedTotal ?? ($estimatedSubtotal + $taxAmount));
    @endphp

    <section class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-semibold text-slate-900">Konfirmasi Sewa</h1>
                    <p class="text-sm text-slate-600">Review jadwal tiap alat sebelum pembayaran.</p>
                </div>
                <a href="{{ route('cart') }}" class="text-sm text-slate-600 hover:text-blue-600">← Kembali ke Cart</a>
            </div>
        </div>
    </section>

    <section class="bg-slate-100">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-6 px-4 pb-14 sm:px-6 lg:grid-cols-[1fr,320px]">
            <div class="space-y-4">
                <div id="checkout-alert" class="hidden rounded-xl border px-4 py-3 text-sm"></div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Detail Sewa</h2>

                    @if ($isCartEmpty)
                        <p class="mt-3 text-sm text-slate-500">Cart kosong. Silakan tambahkan item sebelum checkout.</p>
                        <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            {{ __('app.actions.back_to_catalog') }}
                        </a>
                    @else
                        <div class="mt-4 space-y-3">
                            @foreach ($cartItems as $item)
                                @php
                                    $startDate = ! empty($item['rental_start_date']) ? \Carbon\Carbon::parse($item['rental_start_date']) : null;
                                    $endDate = ! empty($item['rental_end_date']) ? \Carbon\Carbon::parse($item['rental_end_date']) : null;
                                    $lineEstimate = (int) ($item['estimated_total'] ?? ((int) ($item['price'] ?? 0) * (int) ($item['qty'] ?? 1)));
                                @endphp
                                <div class="rounded-xl border border-slate-200 p-3 text-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $item['name'] }}</p>
                                            <p class="text-xs text-slate-500">Qty {{ $item['qty'] }} • {{ $formatIdr((int) ($item['price'] ?? 0)) }}/hari</p>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $formatIdr($lineEstimate) }}</p>
                                    </div>
                                    @if ($startDate && $endDate)
                                        <p class="mt-2 text-xs text-blue-700">
                                            Jadwal: {{ $startDate->translatedFormat('d M Y') }} - {{ $endDate->translatedFormat('d M Y') }}
                                            ({{ max((int) ($item['rental_days'] ?? 1), 1) }} hari)
                                        </p>
                                    @else
                                        <p class="mt-2 text-xs text-rose-600">Tanggal sewa item ini belum valid. Kembali ke keranjang untuk perbaiki.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <form id="checkout-form" class="mt-4 space-y-4">
                            @csrf
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                                <h3 class="text-sm font-semibold text-slate-900">Data Diri</h3>
                                <div class="mt-3 grid grid-cols-1 gap-3">
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500">Nama Lengkap</label>
                                        <input
                                            type="text"
                                            value="{{ $profile?->full_name ?? '-' }}"
                                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                            disabled
                                        >
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500">No. Telepon</label>
                                        <input
                                            type="text"
                                            value="{{ $profile?->phone ?? '-' }}"
                                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                            disabled
                                        >
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-slate-500">Alamat</label>
                                        <textarea
                                            rows="2"
                                            class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                                            disabled
                                        >{{ $profile?->address_text ?? $profile?->address ?? '-' }}</textarea>
                                    </div>
                                </div>
                                <p class="mt-3 text-xs text-slate-400">
                                    Data diambil dari profil. <a href="{{ route('profile.complete') }}" class="text-blue-600 hover:text-blue-700">Update profil</a> bila perlu.
                                </p>
                            </div>

                            <label class="flex items-start gap-3 text-sm text-slate-600">
                                <input type="checkbox" name="confirm_profile" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500" required>
                                <span>Saya memastikan data diri di atas sudah benar.</span>
                            </label>

                            <button
                                type="submit"
                                id="checkout-submit"
                                class="mt-2 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition"
                            >
                                Konfirmasi & Bayar
                            </button>
                        </form>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Metode Pembayaran</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Pembayaran akan diproses melalui Midtrans Snap (sandbox) tanpa reload halaman.
                    </p>
                </div>
            </div>

            <div class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Ringkasan</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal / hari</span>
                        <span id="summary-subtotal">{{ $formatIdr($subtotalPerDay ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Total (estimasi)</span>
                        <span>{{ $formatIdr($estimatedSubtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>PPN 11%</span>
                        <span>{{ $formatIdr($taxAmount) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-2 font-semibold text-slate-900">
                        <span>Total Bayar</span>
                        <span id="summary-total-side">{{ $formatIdr($estimatedTotal) }}</span>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($cartItems as $item)
                        @php
                            $startDate = ! empty($item['rental_start_date']) ? \Carbon\Carbon::parse($item['rental_start_date']) : null;
                            $endDate = ! empty($item['rental_end_date']) ? \Carbon\Carbon::parse($item['rental_end_date']) : null;
                        @endphp
                        <div class="rounded-xl border border-slate-200 p-3 text-sm text-slate-600">
                            <p class="font-semibold text-slate-900">{{ $item['name'] }}</p>
                            <div class="mt-1 flex justify-between text-xs">
                                <span>Qty {{ $item['qty'] }}</span>
                                <span>{{ $formatIdr((int) ($item['estimated_total'] ?? 0)) }}</span>
                            </div>
                            @if ($startDate && $endDate)
                                <p class="mt-1 text-xs text-blue-700">
                                    {{ $startDate->translatedFormat('d M Y') }} - {{ $endDate->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Tidak ada item di cart.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @php
        $snapSrc = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $snapSrc }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        (function () {
            const form = document.getElementById('checkout-form');
            if (!form) return;

            const alertBox = document.getElementById('checkout-alert');
            const submitButton = document.getElementById('checkout-submit');

            const showAlert = (message, type = 'info') => {
                if (!alertBox) return;
                if (!message) {
                    alertBox.classList.add('hidden');
                    return;
                }
                const baseClass = 'rounded-xl border px-4 py-3 text-sm';
                const styles = {
                    info: 'border-slate-200 bg-slate-50 text-slate-700',
                    success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                    error: 'border-rose-200 bg-rose-50 text-rose-700',
                };
                alertBox.className = `${baseClass} ${styles[type] || styles.info}`;
                alertBox.textContent = message;
                alertBox.classList.remove('hidden');
            };

            const refreshPaymentStatus = async (refreshUrl) => {
                if (!refreshUrl) {
                    return null;
                }

                const response = await fetchWithCsrf(refreshUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal sinkron status pembayaran.');
                }

                return data;
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                showAlert('', 'info');

                submitButton.disabled = true;
                submitButton.textContent = 'Memproses...';

                try {
                    const response = await fetch("{{ route('checkout.store') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: new FormData(form),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        const message = data.message || 'Checkout gagal. Silakan cek kembali.';
                        showAlert(message, 'error');
                        return;
                    }

                    const shouldFallbackToOrderDetail = Boolean(data.fallback_to_order_detail) || !data.snap_token;
                    if (shouldFallbackToOrderDetail) {
                        showAlert(data.message || 'Pesanan berhasil dibuat. Lanjutkan pembayaran dari detail order.', 'info');
                        window.location.href = data.redirect_url_to_order_detail;
                        return;
                    }

                    if (!window.snap) {
                        showAlert('Midtrans Snap belum siap. Kamu akan diarahkan ke detail order.', 'info');
                        window.location.href = data.redirect_url_to_order_detail;
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: async function () {
                            showAlert('Pembayaran berhasil. Menyiapkan invoice...', 'success');

                            try {
                                const status = await refreshPaymentStatus(data.refresh_status_url);
                                if (status?.is_paid && status?.invoice_url) {
                                    window.location.href = status.invoice_url;
                                    return;
                                }
                            } catch (error) {
                                // Lanjutkan fallback ke detail order jika sinkronisasi gagal.
                            }

                            window.location.href = data.redirect_url_to_order_detail;
                        },
                        onPending: async function () {
                            showAlert('Menunggu pembayaran. Silakan selesaikan pembayaran.', 'info');

                            try {
                                const status = await refreshPaymentStatus(data.refresh_status_url);
                                if (status?.is_paid && status?.invoice_url) {
                                    window.location.href = status.invoice_url;
                                    return;
                                }
                            } catch (error) {
                                // Fallback tetap ke detail order.
                            }

                            window.location.href = data.redirect_url_to_order_detail;
                        },
                        onError: function () {
                            showAlert('Pembayaran gagal. Coba lagi atau pilih metode lain.', 'error');
                        },
                        onClose: function () {
                            showAlert('Popup pembayaran ditutup.', 'info');
                        },
                    });
                } catch (error) {
                    showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                } finally {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Konfirmasi & Bayar';
                }
            });
        })();
    </script>
@endpush
