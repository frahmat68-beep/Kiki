@extends('layouts.app')

@section('title', 'Detail Pesanan')

@php
    $formatIdr = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
    $paymentStatus = $order->status_pembayaran ?? 'pending';
    $orderStatus = $order->status_pesanan ?? 'menunggu_pembayaran';
    $isPaid = $paymentStatus === 'paid';
    $isPrimaryPayable = in_array($paymentStatus, ['pending', 'failed'], true);
    $additionalFee = $order->resolvePenaltyAmount();
    $damagePayment = $order->damagePayment;
    $damagePaymentStatus = (string) ($damagePayment?->status ?? '');
    $damageFeeStatuses = ['barang_kembali', 'barang_rusak', 'barang_hilang', 'overdue_denda'];
    $hasDamageFeeOutstanding = in_array($orderStatus, $damageFeeStatuses, true) && $additionalFee > 0 && $damagePaymentStatus !== 'paid';
    $canAccessInvoice = $isPaid && ! $hasDamageFeeOutstanding;
    $isDamageFeePaid = $additionalFee > 0 && $damagePaymentStatus === 'paid';
    $loadSnapPaymentScript = $isPrimaryPayable || $hasDamageFeeOutstanding;
    $baseTotal = (int) ($order->total_amount ?? 0);
    $taxAmount = (int) round($baseTotal * 0.11);
    $rentalGrandTotal = $baseTotal + $taxAmount;
    $grandTotal = $rentalGrandTotal + ($isDamageFeePaid ? $additionalFee : 0);

    $statusMeta = match ($paymentStatus) {
        'paid' => ['label' => 'LUNAS', 'badge' => 'bg-blue-100 text-blue-700'],
        'failed' => ['label' => 'GAGAL', 'badge' => 'bg-rose-100 text-rose-700'],
        'expired' => ['label' => 'EXPIRED', 'badge' => 'bg-slate-200 text-slate-700'],
        'refunded' => ['label' => 'REFUND', 'badge' => 'bg-indigo-100 text-indigo-700'],
        default => ['label' => 'PENDING', 'badge' => 'bg-amber-100 text-amber-700'],
    };

    $statusLabel = fn ($status) => match ($status) {
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'diproses' => 'Diproses Admin',
        'lunas' => 'Siap Diambil',
        'barang_diambil' => 'Barang Diambil',
        'barang_kembali' => 'Barang Dikembalikan',
        'barang_rusak' => 'Barang Rusak',
        'barang_hilang' => 'Barang Hilang',
        'overdue_denda' => 'Denda Overdue',
        'selesai' => 'Selesai',
        'expired' => 'Expired',
        'dibatalkan' => 'Dibatalkan',
        'refund' => 'Refund',
        default => strtoupper((string) $status),
    };

    $timeline = [
        [
            'title' => 'Menunggu Pembayaran',
            'done' => $paymentStatus !== 'pending',
            'active' => $paymentStatus === 'pending',
            'time' => null,
        ],
        [
            'title' => 'Pembayaran Terkonfirmasi',
            'done' => $paymentStatus === 'paid',
            'active' => $paymentStatus === 'paid' && $orderStatus === 'lunas',
            'time' => $order->paid_at,
        ],
        [
            'title' => 'Pesanan Diproses',
            'done' => in_array($orderStatus, ['diproses', 'lunas', 'barang_diambil', 'barang_kembali', 'barang_rusak', 'barang_hilang', 'overdue_denda', 'selesai'], true),
            'active' => in_array($orderStatus, ['diproses', 'lunas'], true),
            'time' => null,
        ],
        [
            'title' => 'Barang Diambil',
            'done' => in_array($orderStatus, ['barang_diambil', 'barang_kembali', 'barang_rusak', 'barang_hilang', 'overdue_denda', 'selesai'], true),
            'active' => $orderStatus === 'barang_diambil',
            'time' => $order->picked_up_at,
        ],
        [
            'title' => 'Barang Dikembalikan',
            'done' => in_array($orderStatus, ['barang_kembali', 'barang_rusak', 'barang_hilang', 'overdue_denda', 'selesai'], true),
            'active' => in_array($orderStatus, ['barang_kembali', 'barang_rusak', 'barang_hilang'], true),
            'time' => $order->returned_at,
        ],
    ];
    $canReschedule = in_array($orderStatus, ['menunggu_pembayaran', 'diproses', 'lunas'], true);
    $hasPickedUp = in_array($orderStatus, ['barang_diambil', 'barang_kembali', 'barang_rusak', 'barang_hilang', 'overdue_denda', 'selesai'], true);
@endphp

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-blue-600">Order</p>
                    <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Detail Pesanan</h1>
                    <p class="text-sm text-slate-500">Pantau status pembayaran dan progres rental di sini.</p>
                </div>
                <a href="{{ route('booking.history') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600">← Kembali ke Riwayat</a>
            </div>
        </div>
    </section>

    <section class="bg-slate-100">
        <div class="mx-auto max-w-6xl px-4 pb-12 sm:px-6">
            <div id="payment-alert" class="hidden rounded-xl border px-4 py-3 text-sm"></div>

            @if (session('error'))
                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($hasDamageFeeOutstanding)
                <div class="mt-4 rounded-2xl border-2 border-rose-300 bg-rose-50 px-5 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-700">Tagihan Tambahan Wajib</p>
                    <p class="mt-1 text-lg font-semibold text-rose-800">Barang terdeteksi bermasalah. Biaya tambahan {{ $formatIdr($additionalFee) }} wajib dibayar via Midtrans.</p>
                    <p class="mt-1 text-sm text-rose-700">Biaya tambahan ini tidak dikenakan PPN.</p>
                    @if ($order->additional_fee_note)
                        <p class="mt-2 rounded-lg border border-rose-200 bg-white px-3 py-2 text-xs text-rose-700">{{ $order->additional_fee_note }}</p>
                    @endif
                </div>
            @endif

            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr,340px]">
                <div class="space-y-6">
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-xs text-slate-500">Nomor Order</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <p id="order-number-text" class="text-lg font-semibold text-slate-900">{{ $order->order_number ?? ('ORD-' . $order->id) }}</p>
                                    <button
                                        type="button"
                                        id="copy-order-number"
                                        class="inline-flex items-center rounded-lg border border-slate-200 px-2 py-1 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600"
                                    >
                                        Copy Resi
                                    </button>
                                </div>
                            </div>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusMeta['badge'] }}">
                                {{ $statusMeta['label'] }}
                            </span>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-500">Order ID</p>
                                <p class="mt-1 font-semibold text-slate-800">#{{ $order->id }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-500">Periode Sewa</p>
                                <p class="mt-1 font-semibold text-slate-800">
                                    {{ optional($order->rental_start_date)->format('d M Y') }} - {{ optional($order->rental_end_date)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="rounded-xl bg-slate-50 px-4 py-3">
                                <p class="text-xs text-slate-500">Status Rental</p>
                                <p class="mt-1 font-semibold text-slate-800">{{ $statusLabel($orderStatus) }}</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Progress Pesanan</h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($timeline as $step)
                                @php
                                    $stepClass = $step['done']
                                        ? 'border-blue-200 bg-blue-50'
                                        : ($step['active'] ? 'border-amber-200 bg-amber-50' : 'border-slate-200 bg-slate-50');
                                    $dotClass = $step['done']
                                        ? 'bg-blue-600'
                                        : ($step['active'] ? 'bg-amber-500' : 'bg-slate-300');
                                @endphp
                                <div class="flex items-center justify-between rounded-xl border px-3 py-2 {{ $stepClass }}">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-2.5 w-2.5 rounded-full {{ $dotClass }}"></span>
                                        <p class="text-sm font-semibold text-slate-800">{{ $step['title'] }}</p>
                                    </div>
                                    @if ($step['time'])
                                        <p class="text-xs text-slate-500">{{ $step['time']->format('d M Y H:i') }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if ($orderStatus === 'barang_rusak')
                            <p class="mt-3 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                                Barang tercatat mengalami kerusakan.
                                @if ($hasDamageFeeOutstanding)
                                    Silakan lanjutkan pembayaran tagihan tambahan.
                                @else
                                    Tim admin akan memproses tindak lanjut biaya/konfirmasi.
                                @endif
                            </p>
                        @endif
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Item Disewa</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($order->items as $item)
                                <div class="rounded-xl border border-slate-100 p-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $item->equipment->name ?? 'Equipment' }}</p>
                                            <p class="text-xs text-slate-500">Qty {{ $item->qty }} x {{ $formatIdr($item->price) }} / hari</p>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $formatIdr($item->subtotal) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Belum ada item pada order ini.</p>
                            @endforelse
                        </div>
                    </article>

                    @if (\Illuminate\Support\Facades\Schema::hasTable('order_notifications') && $order->relationLoaded('notifications') && $order->notifications->isNotEmpty())
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Notifikasi Pesanan</h2>
                            <div class="mt-4 space-y-3">
                                @foreach ($order->notifications as $notification)
                                    <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">
                                        <p class="text-sm font-semibold text-slate-800">{{ $notification->title }}</p>
                                        <p class="mt-1 text-xs text-slate-600">{{ $notification->message }}</p>
                                        <p class="mt-1 text-[11px] text-slate-400">{{ $notification->created_at?->format('d M Y H:i') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endif
                </div>

                <aside class="h-fit space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Pembayaran</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Midtrans Order ID</span>
                            <span class="font-semibold text-slate-800">{{ $order->midtrans_order_id ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Status Pesanan</span>
                            <span class="font-semibold text-slate-800">{{ strtoupper($order->status_pesanan ?? 'pending') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Sewa</span>
                            <span class="font-semibold text-slate-800">{{ $formatIdr($baseTotal) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>PPN 11%</span>
                            <span class="font-semibold text-slate-800">{{ $formatIdr($taxAmount) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-2 text-slate-700">
                            <span class="font-semibold">Total Sewa</span>
                            <span class="font-semibold text-slate-900">{{ $formatIdr($rentalGrandTotal) }}</span>
                        </div>
                        @if ($additionalFee > 0)
                            <div class="mt-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Tagihan Tambahan</p>
                                    <span class="rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $isDamageFeePaid ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $isDamageFeePaid ? 'LUNAS' : 'BELUM LUNAS' }}
                                    </span>
                                </div>
                                <div class="mt-1 flex items-center justify-between text-sm text-amber-700">
                                    <span>Biaya kerusakan / penalti</span>
                                    <span class="font-semibold">{{ $formatIdr($additionalFee) }}</span>
                                </div>
                                <p class="mt-1 text-xs text-amber-700">Tanpa PPN.</p>
                            </div>
                            @if ($order->additional_fee_note)
                                <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">{{ $order->additional_fee_note }}</p>
                            @endif
                        @endif
                        @if ($isDamageFeePaid && $additionalFee > 0)
                            <div class="flex justify-between border-t border-slate-200 pt-2 text-slate-700">
                                <span class="font-semibold">Total Akhir</span>
                                <span class="font-semibold text-slate-900">{{ $formatIdr($grandTotal) }}</span>
                            </div>
                        @endif
                        @if ($order->admin_note)
                            <div class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs text-blue-700">
                                <p class="font-semibold">Catatan Admin</p>
                                <p class="mt-1">{{ $order->admin_note }}</p>
                            </div>
                        @endif
                        @if ($order->paid_at)
                            <div class="flex justify-between text-slate-600">
                                <span>Dibayar Pada</span>
                                <span class="font-semibold text-slate-800">{{ $order->paid_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>

                    @if ($canReschedule)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                            <h3 class="text-sm font-semibold text-slate-900">Butuh Reschedule?</h3>
                            <p class="mt-1 text-xs text-slate-600">Jadwal masih bisa diubah selama barang belum diambil. Durasi sewa harus tetap sama.</p>
                            <form method="POST" action="{{ route('account.orders.reschedule', $order) }}" class="mt-3 space-y-2">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Tanggal Sewa Baru</label>
                                    <input
                                        type="date"
                                        name="rental_start_date"
                                        min="{{ now()->toDateString() }}"
                                        value="{{ old('rental_start_date', optional($order->rental_start_date)->format('Y-m-d')) }}"
                                        required
                                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                    >
                                </div>
                                <div>
                                    <label class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Tanggal Kembali Baru</label>
                                    <input
                                        type="date"
                                        name="rental_end_date"
                                        min="{{ now()->toDateString() }}"
                                        value="{{ old('rental_end_date', optional($order->rental_end_date)->format('Y-m-d')) }}"
                                        required
                                        class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30"
                                    >
                                </div>
                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg border border-blue-200 bg-white px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">
                                    Simpan Reschedule
                                </button>
                            </form>
                        </div>
                    @elseif ($hasPickedUp)
                        <p class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                            Jadwal tidak dapat diubah karena barang sudah diambil.
                        </p>
                    @endif

                    @if ($isPrimaryPayable)
                        <button id="pay-now-button" class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            Bayar Sekarang
                        </button>
                        <button id="refresh-status-button" class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-blue-200 hover:text-blue-600 transition">
                            Cek Status Pembayaran
                        </button>
                        <p class="text-xs text-slate-500">Pembayaran diproses via Midtrans Snap.</p>
                    @endif

                    @if ($hasDamageFeeOutstanding)
                        <button id="pay-damage-fee-button" class="mt-3 inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                            Bayar Tagihan Tambahan
                        </button>
                        <button id="refresh-damage-status-button" class="mt-2 inline-flex w-full items-center justify-center rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                            Cek Status Tagihan Tambahan
                        </button>
                    @endif

                    @if ($canAccessInvoice)
                        <div class="space-y-2">
                            <a href="{{ route('account.orders.receipt', $order) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-blue-200 hover:text-blue-600">
                                Lihat Invoice
                            </a>
                            <a href="{{ route('account.orders.receipt.pdf', $order) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                Download PDF
                            </a>
                        </div>
                    @elseif ($isPaid && $hasDamageFeeOutstanding)
                        <p class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                            Invoice dibuka setelah tagihan tambahan lunas.
                        </p>
                    @endif
                </aside>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const copyButton = document.getElementById('copy-order-number');
            const valueNode = document.getElementById('order-number-text');

            if (!copyButton || !valueNode) return;

            copyButton.addEventListener('click', async () => {
                const orderNumber = valueNode.textContent?.trim();
                if (!orderNumber) return;

                try {
                    await navigator.clipboard.writeText(orderNumber);
                    copyButton.textContent = 'Tersalin';
                    setTimeout(() => {
                        copyButton.textContent = 'Copy Resi';
                    }, 1500);
                } catch (error) {
                    copyButton.textContent = 'Gagal';
                    setTimeout(() => {
                        copyButton.textContent = 'Copy Resi';
                    }, 1500);
                }
            });
        })();
    </script>

    @if ($loadSnapPaymentScript)
        @php
            $snapSrc = config('services.midtrans.is_production')
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js';
        @endphp
        <script src="{{ $snapSrc }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script>
            (function () {
                const payButton = document.getElementById('pay-now-button');
                const refreshButton = document.getElementById('refresh-status-button');
                const damagePayButton = document.getElementById('pay-damage-fee-button');
                const refreshDamageButton = document.getElementById('refresh-damage-status-button');
                const alertBox = document.getElementById('payment-alert');
                if (!payButton && !damagePayButton) return;

                const showAlert = (message, type = 'info') => {
                    if (!alertBox) return;
                    const styles = {
                        info: 'border-slate-200 bg-white text-slate-700',
                        success: 'border-blue-200 bg-blue-50 text-blue-700',
                        error: 'border-rose-200 bg-rose-50 text-rose-700',
                    };
                    alertBox.className = `rounded-xl border px-4 py-3 text-sm ${styles[type] || styles.info}`;
                    alertBox.textContent = message;
                    alertBox.classList.remove('hidden');
                };

                const syncRentalPaymentStatus = async (redirectWhenPaid = false) => {
                    const response = await fetchWithCsrf(@json(route('payments.refresh-status', $order)), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal sinkron status pembayaran.');
                    }

                    if (data.is_paid && data.invoice_url) {
                        showAlert('Pembayaran terkonfirmasi. Mengalihkan ke invoice...', 'success');
                        if (redirectWhenPaid) {
                            window.location.href = data.invoice_url;
                        }
                    } else if (data.is_paid && data.has_damage_fee_outstanding) {
                        showAlert('Pembayaran sewa sudah lunas. Lunasi tagihan tambahan untuk membuka invoice.', 'info');
                    }

                    return data;
                };

                const syncDamagePaymentStatus = async () => {
                    const response = await fetchWithCsrf(@json(route('payments.damage-fee.refresh-status', $order)), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                    });
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal sinkron status tagihan tambahan.');
                    }

                    if (data.is_paid) {
                        showAlert('Tagihan tambahan terkonfirmasi lunas.', 'success');
                    }

                    return data;
                };

                const processPayment = async (config) => {
                    if (!config.button) return;
                    config.button.disabled = true;
                    config.button.textContent = 'Memproses...';
                    showAlert('Membuat sesi pembayaran...', 'info');

                    try {
                        const response = await fetchWithCsrf(config.tokenEndpoint, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                            },
                        });
                        const data = await response.json();

                        if (!response.ok || !data.snap_token) {
                            throw new Error(data.message || 'Gagal membuat sesi pembayaran.');
                        }

                        if (!window.snap) {
                            throw new Error('Midtrans Snap belum siap. Coba refresh halaman.');
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: async function () {
                                showAlert('Pembayaran berhasil. Sinkron status...', 'success');
                                try {
                                    const status = await config.refreshStatus();
                                    if (!status.is_paid || config.forceReloadAfterPaid) {
                                        setTimeout(() => window.location.reload(), 900);
                                    }
                                } catch (error) {
                                    showAlert(error.message || 'Pembayaran berhasil, tapi sinkronisasi gagal.', 'info');
                                    setTimeout(() => window.location.reload(), 900);
                                }
                            },
                            onPending: async function () {
                                showAlert('Pembayaran pending. Silakan selesaikan di Midtrans.', 'info');
                                try {
                                    await config.refreshStatus();
                                } catch (error) {
                                    // fallback ke reload normal
                                }
                                setTimeout(() => window.location.reload(), 900);
                            },
                            onError: function () {
                                showAlert('Pembayaran gagal. Coba lagi.', 'error');
                            },
                            onClose: function () {
                                showAlert('Popup pembayaran ditutup.', 'info');
                            },
                        });
                    } catch (error) {
                        showAlert(error.message || 'Terjadi kesalahan saat membuka pembayaran.', 'error');
                    } finally {
                        config.button.disabled = false;
                        config.button.textContent = config.defaultLabel;
                    }
                };

                payButton?.addEventListener('click', () => processPayment({
                    button: payButton,
                    tokenEndpoint: @json(route('payments.snap-token', $order)),
                    refreshStatus: () => syncRentalPaymentStatus(true),
                    defaultLabel: 'Bayar Sekarang',
                    forceReloadAfterPaid: false,
                }));

                damagePayButton?.addEventListener('click', () => processPayment({
                    button: damagePayButton,
                    tokenEndpoint: @json(route('payments.damage-fee.snap-token', $order)),
                    refreshStatus: () => syncDamagePaymentStatus(),
                    defaultLabel: 'Bayar Tagihan Tambahan',
                    forceReloadAfterPaid: true,
                }));

                refreshButton?.addEventListener('click', async () => {
                    refreshButton.disabled = true;
                    const defaultText = refreshButton.textContent;
                    refreshButton.textContent = 'Memeriksa...';

                    try {
                        const status = await syncRentalPaymentStatus(true);
                        if (!status.is_paid) {
                            showAlert('Status terbaru: belum lunas. Silakan selesaikan pembayaran.', 'info');
                        }
                    } catch (error) {
                        showAlert(error.message || 'Gagal cek status pembayaran.', 'error');
                    } finally {
                        refreshButton.disabled = false;
                        refreshButton.textContent = defaultText;
                    }
                });

                refreshDamageButton?.addEventListener('click', async () => {
                    refreshDamageButton.disabled = true;
                    const defaultText = refreshDamageButton.textContent;
                    refreshDamageButton.textContent = 'Memeriksa...';

                    try {
                        const status = await syncDamagePaymentStatus();
                        if (!status.is_paid) {
                            showAlert('Tagihan tambahan belum lunas. Silakan lanjutkan pembayaran.', 'info');
                        } else {
                            setTimeout(() => window.location.reload(), 800);
                        }
                    } catch (error) {
                        showAlert(error.message || 'Gagal cek status tagihan tambahan.', 'error');
                    } finally {
                        refreshDamageButton.disabled = false;
                        refreshDamageButton.textContent = defaultText;
                    }
                });

                let pollAttempts = 0;
                const maxPollAttempts = 15;
                const pollStatus = async () => {
                    if (pollAttempts >= maxPollAttempts) {
                        return;
                    }

                    pollAttempts += 1;

                    try {
                        const status = await syncRentalPaymentStatus(true);
                        if (!status.is_paid) {
                            setTimeout(pollStatus, 12000);
                        }
                    } catch (error) {
                        setTimeout(pollStatus, 12000);
                    }
                };

                if (payButton) {
                    setTimeout(pollStatus, 7000);
                }

                @if (session('pay_now'))
                    payButton?.click();
                @endif
            })();
        </script>
    @endif
@endpush
