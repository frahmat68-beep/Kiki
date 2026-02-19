@extends('layouts.app')

@section('title', 'Rules Sewa Manake')
@section('meta_description', 'Panduan lengkap rules sewa Manake: booking, pembayaran, reschedule, buffer, denda keterlambatan, dan tanggung jawab penyewa.')

@section('content')
    <section class="mx-auto max-w-6xl space-y-6">
        <div class="rounded-3xl border border-blue-100 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-500">Rules Sewa</p>
            <h1 class="mt-2 text-3xl font-extrabold">Panduan Sewa Manake Rental</h1>
            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-slate-600">
                Halaman ini merangkum aturan utama supaya proses sewa aman, jelas, dan adil untuk semua user. Aturan ini berlaku untuk booking website, reschedule, dan pengelolaan unit.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">1. Booking & Pembayaran</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Booking dianggap aktif setelah checkout berhasil dan pembayaran terkonfirmasi.</li>
                    <li>Kode pembayaran berlaku terbatas (umumnya 60 menit) sebelum status berubah expired.</li>
                    <li>Invoice digital menjadi bukti transaksi resmi setelah status pembayaran lunas.</li>
                </ul>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">2. Ketersediaan, Buffer & Stok</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Sistem memakai buffer 1 hari sebelum dan sesudah masa sewa untuk keamanan operasional.</li>
                    <li>Jika tanggal bentrok dan stok tidak cukup, checkout otomatis ditolak.</li>
                    <li>Kalender ketersediaan menampilkan hari kosong, hari disewa, dan hari buffer agar keputusan sewa lebih akurat.</li>
                </ul>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">3. Reschedule Pesanan</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Reschedule hanya bisa sebelum status pengambilan barang berjalan.</li>
                    <li>Durasi hari sewa harus tetap sama dengan order awal.</li>
                    <li>Jumlah unit/item yang dipesan juga harus tetap sama.</li>
                    <li>Jika stok tidak tersedia di tanggal baru, sistem menolak reschedule dan menampilkan tanggal bentrok.</li>
                </ul>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold">4. Denda & Tanggung Jawab</h2>
                <ul class="mt-3 space-y-2 text-sm text-slate-600">
                    <li>Keterlambatan 3 jam: denda 30% dari biaya sewa harian.</li>
                    <li>Keterlambatan 6 jam: denda 50% dari biaya sewa harian.</li>
                    <li>Di atas 9 jam: denda 100% dari biaya sewa harian.</li>
                    <li>Kerusakan unit: minimal 50% sesuai assessment admin.</li>
                    <li>Kehilangan unit: penggantian 100% harga unit.</li>
                </ul>
            </article>
        </div>

        <article class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
            <h2 class="text-lg font-semibold">Catatan Operasional</h2>
            <p class="mt-2 text-sm text-slate-600">
                Tim admin berhak melakukan verifikasi data, validasi stok terakhir, dan pengecekan kondisi alat saat pickup/return. Jika ada perbedaan data atau kendala lapangan, keputusan operasional admin menjadi acuan final.
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Mulai Sewa dari Katalog
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-xl border border-blue-200 bg-white px-4 py-2 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-50">
                    Hubungi Tim Manake
                </a>
            </div>
        </article>
    </section>
@endsection
