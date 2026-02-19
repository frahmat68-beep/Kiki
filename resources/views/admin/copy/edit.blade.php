@extends('layouts.admin', ['activePage' => 'copy'])

@section('title', 'Editor Teks Website')
@section('page_title', 'Editor Teks Website')

@php
    $fallbacks = [
        'home.hero_title' => __('app.landing.hero_title') . ' ' . __('app.landing.hero_highlight') . ' ' . __('app.landing.hero_suffix'),
        'home.hero_subtitle' => __('app.landing.hero_desc'),
        'hero_cta_text' => __('app.actions.see_catalog'),
        'copy.landing.ready_panel_title' => 'Ready To Rent',
        'copy.landing.ready_panel_subtitle' => 'Item live dari inventory yang tersedia hari ini.',
        'copy.landing.flow_kicker' => 'Alur Rental',
        'copy.landing.flow_title' => 'Biar proses sewa tidak ribet',
        'copy.landing.flow_catalog_link' => 'Lihat semua alat',
        'copy.landing.step_1_title' => 'Pilih Alat',
        'copy.landing.step_1_desc' => 'Filter berdasarkan kategori, status ready, dan budget harian.',
        'copy.landing.step_2_title' => 'Isi Profil',
        'copy.landing.step_2_desc' => 'Data identitas dan kontak disimpan agar checkout berikutnya lebih cepat.',
        'copy.landing.step_3_title' => 'Bayar via Midtrans',
        'copy.landing.step_3_desc' => 'Pilih metode pembayaran favorit tanpa pindah halaman berulang.',
        'copy.landing.step_4_title' => 'Generate Resi',
        'copy.landing.step_4_desc' => 'Setelah lunas, resi bisa dibuka dan dicetak langsung dari detail order.',
        'copy.landing.quick_category_kicker' => 'Kategori Cepat',
        'copy.landing.quick_category_title' => 'Akses langsung ke kebutuhan produksi',
        'copy.landing.quick_category_empty' => 'Belum ada kategori tersedia.',
        'copy.catalog.kicker' => __('app.catalog.kicker'),
        'copy.catalog.title' => __('app.catalog.title'),
        'copy.catalog.subtitle' => __('app.catalog.subtitle'),
        'copy.catalog.category_label' => __('app.catalog.filter_category'),
        'copy.catalog.empty_title' => 'Belum ada alat tersedia.',
        'copy.catalog.empty_subtitle' => 'Tambahkan alat baru dari admin agar kategori ini terisi.',
        'copy.category.kicker' => __('app.category.title'),
        'copy.category.subtitle' => 'Daftar alat pada kategori ini.',
        'copy.category.total_label' => 'Total alat',
        'copy.category.empty_title' => 'Belum ada alat di kategori ini.',
        'copy.category.empty_subtitle' => 'Silakan cek kategori lain atau hubungi admin.',
        'copy.booking.title' => __('ui.nav.my_orders'),
        'copy.booking.subtitle' => 'Semua order dan status pembayaran kamu.',
        'copy.booking.active_title' => 'Rental Aktif',
        'copy.booking.recent_title' => 'Riwayat Terbaru',
        'copy.booking.cta_text' => __('ui.actions.explore_catalog'),
        'copy.order_detail.title' => 'Detail Pesanan',
        'copy.order_detail.subtitle' => 'Pantau status pembayaran dan progres rental di sini.',
        'copy.order_detail.back_label' => 'Kembali ke Riwayat',
        'copy.order_detail.order_number_label' => 'Nomor Order',
        'copy.order_detail.progress_title' => 'Progress Pesanan',
        'copy.order_detail.items_title' => 'Item Disewa',
        'copy.order_detail.payment_title' => 'Pembayaran',
        'copy.availability.title' => 'Pusat Cek Ketersediaan Alat',
        'copy.availability.subtitle' => 'Klik tanggal di kalender untuk melihat pesanan aktif, atau drag beberapa hari sekaligus untuk cek rentang sewa.',
        'copy.availability.calendar_title' => 'Kalender Pemakaian',
        'copy.availability.selected_title' => 'Tanggal Dipilih',
        'copy.availability.ready_title' => 'Alat Paling Siap Dipakai',
        'copy.availability.busy_title' => 'Alat Terpakai',
        'copy.availability.monthly_title' => 'Jadwal Aktif Bulan Ini',
        'copy.rules_page.kicker' => 'Rules Sewa',
        'copy.rules_page.title' => 'Panduan Sewa Manake Rental',
        'copy.rules_page.subtitle' => 'Halaman ini merangkum aturan utama supaya proses sewa aman, jelas, dan adil untuk semua user. Aturan ini berlaku untuk booking website, reschedule, dan pengelolaan unit.',
        'copy.rules_page.operational_title' => 'Catatan Operasional',
        'copy.rules_page.cta_primary' => 'Mulai Sewa dari Katalog',
        'copy.rules_page.cta_secondary' => 'Hubungi Tim Manake',
        'footer.about' => __('app.footer.about_body'),
        'footer.address' => __('app.footer.address_body'),
        'footer.whatsapp' => '+62 812-3456-7890',
        'contact.email' => 'hello@manakerental.id',
        'footer.instagram' => '@manakerental',
        'footer_copyright' => __('app.footer.copyright'),
        'site_tagline' => __('app.footer.tagline'),
        'footer.rules_title' => __('app.footer.rules_title'),
        'footer.rules_link' => __('app.footer.rules_link'),
        'footer.rules_note' => __('app.footer.rules_note'),
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="card rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">{{ $sectionMeta['label'] }}</h2>
                    <p class="mt-1 text-sm text-slate-600">{{ $sectionMeta['description'] }}</p>
                    <p class="mt-1 text-xs text-slate-500">Semua field di halaman ini khusus edit teks user-facing. Gambar dan aset tetap di halaman Website Settings/Content lama.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </section>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px,1fr]">
            <aside class="card h-fit rounded-2xl p-4 shadow-sm">
                <p class="px-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Halaman User</p>
                <nav class="mt-3 space-y-1">
                    @foreach ($sections as $sectionKey => $meta)
                        <a
                            href="{{ route('admin.copy.edit', $sectionKey) }}"
                            class="block rounded-xl px-3 py-2 text-sm font-semibold transition {{ $currentSection === $sectionKey ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                        >
                            {{ $meta['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>

            <section class="card rounded-2xl p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.copy.update', $currentSection) }}" class="space-y-5">
                    @csrf

                    @foreach ($sectionMeta['fields'] as $fieldName => $meta)
                        @php
                            $currentValue = old($fieldName, $settings[$meta['key']] ?? $fallbacks[$meta['key']] ?? '');
                            $usingFallback = ! isset($settings[$meta['key']]) || $settings[$meta['key']] === null;
                        @endphp

                        <div>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <label for="{{ $fieldName }}" class="text-sm font-semibold text-slate-900">{{ $meta['label'] }}</label>
                                    @if (!empty($meta['location']))
                                        <p class="text-xs text-slate-500">Lokasi tampil: {{ $meta['location'] }}</p>
                                    @endif
                                </div>
                                @if ($usingFallback)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                        Sedang pakai teks default
                                    </span>
                                @endif
                            </div>

                            @if (($meta['type'] ?? 'text') === 'textarea')
                                <textarea
                                    id="{{ $fieldName }}"
                                    name="{{ $fieldName }}"
                                    rows="4"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                                >{{ $currentValue }}</textarea>
                            @else
                                <input
                                    id="{{ $fieldName }}"
                                    name="{{ $fieldName }}"
                                    type="text"
                                    value="{{ $currentValue }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:ring-2 focus:ring-blue-500/30 focus:outline-none"
                                >
                            @endif

                            <p class="mt-1 text-[11px] text-slate-400">Kosongkan field jika mau balik ke teks default bawaan sistem.</p>
                        </div>
                    @endforeach

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">Perubahan langsung tampil di halaman user setelah disimpan.</p>
                        <button type="submit" class="btn-primary inline-flex items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold transition">
                            Simpan Teks Halaman
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
