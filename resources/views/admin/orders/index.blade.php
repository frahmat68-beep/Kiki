@extends('layouts.admin', ['activePage' => 'orders'])

@section('title', 'Admin Orders')
@section('page_title', 'Orders')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-blue-700">Daftar Order</h2>
                    <p class="text-xs text-slate-500">Monitoring transaksi, pembayaran, dan status pesanan.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-[1fr,180px,auto]">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari order / email user..."
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                >
                <select name="status" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                    <option value="">Semua Status Bayar</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ ($status ?? '') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ ($status ?? '') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Filter</button>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-[860px] w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">User</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status Bayar</th>
                            <th class="px-5 py-3">Status Pesanan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($orders as $order)
                            @php
                                $paymentBadge = match($order->status_pembayaran) {
                                    'paid' => 'status-chip-success',
                                    'failed' => 'status-chip-danger',
                                    default => 'status-chip-warning',
                                };
                                $orderStatusLabel = match($order->status_pesanan) {
                                    'menunggu_pembayaran' => 'Menunggu Bayar',
                                    'diproses' => 'Diproses',
                                    'lunas' => 'Siap Diambil',
                                    'barang_diambil' => 'Barang Diambil',
                                    'barang_kembali' => 'Barang Kembali',
                                    'barang_rusak' => 'Barang Rusak',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    'refund' => 'Refund',
                                    default => strtoupper((string) ($order->status_pesanan ?? '-')),
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ $order->order_number ?? ('ORD-' . $order->id) }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->created_at?->format('d M Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $order->user?->name ?? '-' }}</p>
                                    <p class="text-sm text-slate-600">{{ $order->user?->email ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 font-semibold text-slate-800">Rp {{ number_format((int) ($order->grand_total ?? $order->total_amount), 0, ',', '.') }}</td>
                                <td class="px-5 py-4">
                                    <span class="status-chip {{ $paymentBadge }}">
                                        {{ strtoupper($order->status_pembayaran ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $orderStatusLabel }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex rounded-xl border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:text-blue-600">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">Belum ada order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{ $orders->links() }}
    </div>
@endsection
