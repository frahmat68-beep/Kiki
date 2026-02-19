<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CopywritingController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.copy.edit', 'landing');
    }

    public function edit(string $section): View
    {
        $sections = $this->sections();
        abort_unless(isset($sections[$section]), 404);

        $sectionMeta = $sections[$section];
        $settingKeys = collect($sectionMeta['fields'] ?? [])->pluck('key')->all();
        $settings = SiteSetting::query()
            ->whereIn('key', $settingKeys)
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.copy.edit', [
            'sections' => $sections,
            'currentSection' => $section,
            'sectionMeta' => $sectionMeta,
            'settings' => $settings,
            'activePage' => 'copy',
        ]);
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        $sections = $this->sections();
        abort_unless(isset($sections[$section]), 404);

        $sectionMeta = $sections[$section];
        $rules = [];

        foreach ($sectionMeta['fields'] as $fieldName => $meta) {
            $max = (int) ($meta['max'] ?? ($meta['type'] === 'textarea' ? 4000 : 255));
            $rules[$fieldName] = ['nullable', 'string', 'max:' . $max];
        }

        $validated = $request->validate($rules);
        $adminId = auth('admin')->id();
        $changedKeys = [];

        foreach ($sectionMeta['fields'] as $fieldName => $meta) {
            $value = trim((string) ($validated[$fieldName] ?? ''));
            $storedValue = $value !== '' ? $value : null;

            SiteSetting::updateOrCreate(
                ['key' => $meta['key']],
                [
                    'value' => $storedValue,
                    'type' => $meta['type'] ?? 'text',
                    'group' => $sectionMeta['group'] ?? 'copy',
                    'updated_by_admin_id' => $adminId,
                ]
            );

            $changedKeys[] = $meta['key'];
            site_setting_forget($meta['key']);
        }

        admin_audit('copy.update', 'site_settings', null, [
            'section' => $section,
            'keys' => $changedKeys,
        ], $adminId);

        return redirect()
            ->route('admin.copy.edit', $section)
            ->with('success', 'Teks untuk halaman ini berhasil disimpan.');
    }

    private function sections(): array
    {
        return [
            'landing' => [
                'label' => 'Landing Page',
                'group' => 'landing',
                'description' => 'Teks untuk halaman utama user (/).',
                'fields' => [
                    'hero_title' => [
                        'key' => 'home.hero_title',
                        'label' => 'Judul Hero',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Landing > Hero > Judul utama',
                    ],
                    'hero_subtitle' => [
                        'key' => 'home.hero_subtitle',
                        'label' => 'Subjudul Hero',
                        'type' => 'textarea',
                        'max' => 1000,
                        'location' => 'Landing > Hero > Paragraf deskripsi',
                    ],
                    'hero_cta_text' => [
                        'key' => 'hero_cta_text',
                        'label' => 'Teks Tombol Hero',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Landing > Hero > Tombol utama',
                    ],
                    'ready_panel_title' => [
                        'key' => 'copy.landing.ready_panel_title',
                        'label' => 'Judul Panel Ready Item',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Kartu carousel kanan',
                    ],
                    'ready_panel_subtitle' => [
                        'key' => 'copy.landing.ready_panel_subtitle',
                        'label' => 'Subjudul Panel Ready Item',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Landing > Kartu carousel kanan',
                    ],
                    'flow_kicker' => [
                        'key' => 'copy.landing.flow_kicker',
                        'label' => 'Label Kecil Alur Rental',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Landing > Section alur',
                    ],
                    'flow_title' => [
                        'key' => 'copy.landing.flow_title',
                        'label' => 'Judul Alur Rental',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Landing > Section alur',
                    ],
                    'flow_catalog_link' => [
                        'key' => 'copy.landing.flow_catalog_link',
                        'label' => 'Teks Link ke Katalog',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Section alur > kanan atas',
                    ],
                    'step_1_title' => [
                        'key' => 'copy.landing.step_1_title',
                        'label' => 'Judul Step 1',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Alur rental > kartu step 1',
                    ],
                    'step_1_desc' => [
                        'key' => 'copy.landing.step_1_desc',
                        'label' => 'Deskripsi Step 1',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Landing > Alur rental > kartu step 1',
                    ],
                    'step_2_title' => [
                        'key' => 'copy.landing.step_2_title',
                        'label' => 'Judul Step 2',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Alur rental > kartu step 2',
                    ],
                    'step_2_desc' => [
                        'key' => 'copy.landing.step_2_desc',
                        'label' => 'Deskripsi Step 2',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Landing > Alur rental > kartu step 2',
                    ],
                    'step_3_title' => [
                        'key' => 'copy.landing.step_3_title',
                        'label' => 'Judul Step 3',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Alur rental > kartu step 3',
                    ],
                    'step_3_desc' => [
                        'key' => 'copy.landing.step_3_desc',
                        'label' => 'Deskripsi Step 3',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Landing > Alur rental > kartu step 3',
                    ],
                    'step_4_title' => [
                        'key' => 'copy.landing.step_4_title',
                        'label' => 'Judul Step 4',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Landing > Alur rental > kartu step 4',
                    ],
                    'step_4_desc' => [
                        'key' => 'copy.landing.step_4_desc',
                        'label' => 'Deskripsi Step 4',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Landing > Alur rental > kartu step 4',
                    ],
                    'quick_category_kicker' => [
                        'key' => 'copy.landing.quick_category_kicker',
                        'label' => 'Label Kecil Kategori Cepat',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Landing > Section kategori cepat',
                    ],
                    'quick_category_title' => [
                        'key' => 'copy.landing.quick_category_title',
                        'label' => 'Judul Kategori Cepat',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Landing > Section kategori cepat',
                    ],
                    'quick_category_empty' => [
                        'key' => 'copy.landing.quick_category_empty',
                        'label' => 'Teks Saat Kategori Kosong',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Landing > Section kategori cepat',
                    ],
                ],
            ],
            'catalog' => [
                'label' => 'Catalog',
                'group' => 'catalog',
                'description' => 'Teks halaman katalog (/catalog).',
                'fields' => [
                    'catalog_kicker' => [
                        'key' => 'copy.catalog.kicker',
                        'label' => 'Label Kecil Katalog',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Catalog > Header',
                    ],
                    'catalog_title' => [
                        'key' => 'copy.catalog.title',
                        'label' => 'Judul Katalog',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Catalog > Header',
                    ],
                    'catalog_subtitle' => [
                        'key' => 'copy.catalog.subtitle',
                        'label' => 'Subjudul Katalog',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Catalog > Header',
                    ],
                    'catalog_category_label' => [
                        'key' => 'copy.catalog.category_label',
                        'label' => 'Label Filter Kategori',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Catalog > Filter kategori',
                    ],
                    'catalog_empty_title' => [
                        'key' => 'copy.catalog.empty_title',
                        'label' => 'Judul Saat Katalog Kosong',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Catalog > Empty state',
                    ],
                    'catalog_empty_subtitle' => [
                        'key' => 'copy.catalog.empty_subtitle',
                        'label' => 'Subjudul Saat Katalog Kosong',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Catalog > Empty state',
                    ],
                ],
            ],
            'category' => [
                'label' => 'Halaman Kategori',
                'group' => 'category',
                'description' => 'Teks halaman detail kategori (/category/{slug}).',
                'fields' => [
                    'category_kicker' => [
                        'key' => 'copy.category.kicker',
                        'label' => 'Label Kecil Kategori',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Category detail > Header',
                    ],
                    'category_subtitle' => [
                        'key' => 'copy.category.subtitle',
                        'label' => 'Subjudul Kategori',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Category detail > Header',
                    ],
                    'category_total_label' => [
                        'key' => 'copy.category.total_label',
                        'label' => 'Label Total Item',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Category detail > Ringkasan',
                    ],
                    'category_empty_title' => [
                        'key' => 'copy.category.empty_title',
                        'label' => 'Judul Saat Kategori Kosong',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Category detail > Empty state',
                    ],
                    'category_empty_subtitle' => [
                        'key' => 'copy.category.empty_subtitle',
                        'label' => 'Subjudul Saat Kategori Kosong',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Category detail > Empty state',
                    ],
                ],
            ],
            'booking' => [
                'label' => 'Riwayat & Detail Pesanan',
                'group' => 'booking',
                'description' => 'Teks halaman riwayat booking dan detail pesanan user.',
                'fields' => [
                    'booking_title' => [
                        'key' => 'copy.booking.title',
                        'label' => 'Judul Halaman Riwayat',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Riwayat > Header utama',
                    ],
                    'booking_subtitle' => [
                        'key' => 'copy.booking.subtitle',
                        'label' => 'Subjudul Halaman Riwayat',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Riwayat > Header deskripsi',
                    ],
                    'booking_active_title' => [
                        'key' => 'copy.booking.active_title',
                        'label' => 'Judul Kolom Rental Aktif',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Riwayat > Kartu kiri',
                    ],
                    'booking_recent_title' => [
                        'key' => 'copy.booking.recent_title',
                        'label' => 'Judul Kolom Riwayat Terbaru',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Riwayat > Kartu kanan',
                    ],
                    'booking_cta_text' => [
                        'key' => 'copy.booking.cta_text',
                        'label' => 'Teks Tombol Header Riwayat',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Riwayat > Tombol kanan atas',
                    ],
                    'order_detail_title' => [
                        'key' => 'copy.order_detail.title',
                        'label' => 'Judul Halaman Detail Pesanan',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Header utama',
                    ],
                    'order_detail_subtitle' => [
                        'key' => 'copy.order_detail.subtitle',
                        'label' => 'Subjudul Halaman Detail Pesanan',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Detail Pesanan > Header deskripsi',
                    ],
                    'order_detail_back_label' => [
                        'key' => 'copy.order_detail.back_label',
                        'label' => 'Label Tombol Kembali Detail Pesanan',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Link kembali',
                    ],
                    'order_number_label' => [
                        'key' => 'copy.order_detail.order_number_label',
                        'label' => 'Label Nomor Order',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Kartu ringkasan',
                    ],
                    'order_progress_title' => [
                        'key' => 'copy.order_detail.progress_title',
                        'label' => 'Judul Progress Pesanan',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Kartu progress',
                    ],
                    'order_items_title' => [
                        'key' => 'copy.order_detail.items_title',
                        'label' => 'Judul Item Disewa',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Kartu item',
                    ],
                    'order_payment_title' => [
                        'key' => 'copy.order_detail.payment_title',
                        'label' => 'Judul Kartu Pembayaran',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Detail Pesanan > Sidebar kanan',
                    ],
                ],
            ],
            'availability' => [
                'label' => 'Cek Ketersediaan',
                'group' => 'availability',
                'description' => 'Teks halaman availability board (/availability-board).',
                'fields' => [
                    'availability_title' => [
                        'key' => 'copy.availability.title',
                        'label' => 'Judul Halaman Cek Ketersediaan',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Availability Board > Hero',
                    ],
                    'availability_subtitle' => [
                        'key' => 'copy.availability.subtitle',
                        'label' => 'Subjudul Halaman Cek Ketersediaan',
                        'type' => 'textarea',
                        'max' => 500,
                        'location' => 'Availability Board > Hero',
                    ],
                    'availability_calendar_title' => [
                        'key' => 'copy.availability.calendar_title',
                        'label' => 'Judul Kartu Kalender',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Availability Board > Kartu kalender',
                    ],
                    'availability_selected_title' => [
                        'key' => 'copy.availability.selected_title',
                        'label' => 'Judul Kartu Tanggal Dipilih',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Availability Board > Ringkasan kanan',
                    ],
                    'availability_ready_title' => [
                        'key' => 'copy.availability.ready_title',
                        'label' => 'Judul Kartu Alat Siap Dipakai',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Availability Board > Ringkasan kanan',
                    ],
                    'availability_busy_title' => [
                        'key' => 'copy.availability.busy_title',
                        'label' => 'Judul Kartu Alat Terpakai',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Availability Board > Bagian bawah kiri',
                    ],
                    'availability_monthly_title' => [
                        'key' => 'copy.availability.monthly_title',
                        'label' => 'Judul Kartu Jadwal Aktif Bulan Ini',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Availability Board > Bagian bawah kanan',
                    ],
                ],
            ],
            'rules' => [
                'label' => 'Rules Sewa',
                'group' => 'rules',
                'description' => 'Teks halaman rules sewa (/rental-rules).',
                'fields' => [
                    'rules_kicker' => [
                        'key' => 'copy.rules_page.kicker',
                        'label' => 'Kicker Rules Sewa',
                        'type' => 'text',
                        'max' => 80,
                        'location' => 'Rules Sewa > Header',
                    ],
                    'rules_title' => [
                        'key' => 'copy.rules_page.title',
                        'label' => 'Judul Rules Sewa',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Rules Sewa > Header',
                    ],
                    'rules_subtitle' => [
                        'key' => 'copy.rules_page.subtitle',
                        'label' => 'Subjudul Rules Sewa',
                        'type' => 'textarea',
                        'max' => 600,
                        'location' => 'Rules Sewa > Header',
                    ],
                    'rules_operational_title' => [
                        'key' => 'copy.rules_page.operational_title',
                        'label' => 'Judul Catatan Operasional',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Rules Sewa > Kartu bawah',
                    ],
                    'rules_cta_primary' => [
                        'key' => 'copy.rules_page.cta_primary',
                        'label' => 'Teks Tombol Utama Rules',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Rules Sewa > Tombol utama',
                    ],
                    'rules_cta_secondary' => [
                        'key' => 'copy.rules_page.cta_secondary',
                        'label' => 'Teks Tombol Kedua Rules',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Rules Sewa > Tombol sekunder',
                    ],
                ],
            ],
            'footer' => [
                'label' => 'Footer Website',
                'group' => 'footer',
                'description' => 'Teks footer yang tampil di semua halaman user.',
                'fields' => [
                    'footer_about' => [
                        'key' => 'footer.about',
                        'label' => 'Tentang Kami',
                        'type' => 'textarea',
                        'max' => 1500,
                        'location' => 'Footer kolom kiri',
                    ],
                    'footer_address' => [
                        'key' => 'footer.address',
                        'label' => 'Alamat',
                        'type' => 'textarea',
                        'max' => 1200,
                        'location' => 'Footer kolom kanan',
                    ],
                    'footer_whatsapp' => [
                        'key' => 'footer.whatsapp',
                        'label' => 'WhatsApp',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Footer kolom kontak',
                    ],
                    'footer_email' => [
                        'key' => 'contact.email',
                        'label' => 'Email Kontak',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Footer kolom kontak',
                    ],
                    'footer_instagram' => [
                        'key' => 'footer.instagram',
                        'label' => 'Instagram',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Footer kolom kontak',
                    ],
                    'footer_copyright' => [
                        'key' => 'footer_copyright',
                        'label' => 'Copyright',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Footer baris bawah',
                    ],
                    'footer_tagline' => [
                        'key' => 'site_tagline',
                        'label' => 'Tagline Bawah Footer',
                        'type' => 'text',
                        'max' => 255,
                        'location' => 'Footer baris bawah',
                    ],
                    'footer_rules_title' => [
                        'key' => 'footer.rules_title',
                        'label' => 'Judul Kartu Rules Footer',
                        'type' => 'text',
                        'max' => 120,
                        'location' => 'Footer > Kartu panduan sewa',
                    ],
                    'footer_rules_link' => [
                        'key' => 'footer.rules_link',
                        'label' => 'Teks Link Rules Footer',
                        'type' => 'text',
                        'max' => 160,
                        'location' => 'Footer > Kartu panduan sewa',
                    ],
                    'footer_rules_note' => [
                        'key' => 'footer.rules_note',
                        'label' => 'Catatan Rules Footer',
                        'type' => 'textarea',
                        'max' => 400,
                        'location' => 'Footer > Kartu panduan sewa',
                    ],
                ],
            ],
        ];
    }
}
