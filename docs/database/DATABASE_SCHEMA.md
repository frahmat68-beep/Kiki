# Dokumentasi Skema Database & ERD - Manake Media Equipment Rental

Dokumen ini berisi spesifikasi lengkap skema database production (Supabase PostgreSQL) serta petunjuk penggunaan **Diagram Relasi Entitas (ERD)** dalam bentuk file Vektor SVG untuk kebutuhan **Laporan Tugas Akhir / Sidang / Repository Kampus**.

---

## 📁 Struktur Direktori Dokumentasi Database

Semua berkas terkait database telah dirapikan di dalam direktori `docs/database/`:

```text
docs/database/
├── DATABASE_SCHEMA.md       # Dokumentasi rinci tabel, kolom, tipe data, dan constraint
└── manake_erd_diagram.svg   # Diagram ERD Vektor (SVG) resolusi tinggi untuk laporan/sidang
```

---

## 🖼️ File Diagram ERD (SVG)

File diagram visual resolusi tinggi tersimpan di:
👉 **`docs/database/manake_erd_diagram.svg`**

### Keunggulan File SVG Ini:
1. **Scalable Vector Graphics (SVG)**: Tidak pecah saat dibesarkan (*zoom in*), sangat cocok untuk dilampirkan pada naskah skripsi PDF maupun slide presentasi sidang (PowerPoint / Canva).
2. **Standard Kardinalitas 1:1 dan 1:N**: Menyajikan relasi antartabel secara jelas lengkap dengan penanda **PK (Primary Key)**, **FK (Foreign Key)**, dan **UQ (Unique Constraint)**.
3. **Desain Sinematik Modern**: Menggunakan palet warna tema Manake (`#D4A843` Emas dan `#0A0C14` Gelap).

---

## 🗄️ Rincian Tabel Utama Database (`public` / `manake_v2`)

| Nama Tabel | Fungsi Utama | Relasi Utama |
| :--- | :--- | :--- |
| `users` | Menyimpan kredensial akun pelanggan / penyewa. | `1 : 1` dengan `profiles`, `1 : N` dengan `orders` |
| `profiles` | Data identitas resmi penyewa (NIK, Alamat, Kontak Darurat). | `1 : 1` ke `users` |
| `categories` | Kategori peralatan sinematografi (Kamera, Lensa, Lighting, Audio, Dll). | `1 : N` dengan `equipments` |
| `equipments` | Katalog unit alat dan harga sewa per hari. | `N : 1` ke `categories`, `1 : N` dengan `order_items` |
| `orders` | Header transaksi rental beserta tanggal sewa & total biaya. | `N : 1` ke `users`, `1 : N` ke `order_items` & `payments` |
| `order_items` | Rincian item alat yang disewa dalam satu pesanan. | `N : 1` ke `orders`, `N : 1` ke `equipments` |
| `payments` | Catatan transaksi pembayaran Midtrans Gateway & status Duit. | `N : 1` ke `orders` |
| `admins` | Akun pengelola sistem (Super Admin / Admin Operasional). | `1 : N` dengan `audit_logs` |
| `audit_logs` | Catatan jejak riwayat aktivitas dan operasi admin. | `N : 1` ke `admins` |

---

## 🔑 Kredensial Langsung Database Production (Supabase)

* **Host**: `aws-1-ap-southeast-1.pooler.supabase.com`
* **Port**: `5432`
* **Database**: `postgres`
* **Username**: `postgres.iopqejjcutxdpeqsnayy`
* **Password**: `Sewadimanake`
* **URI String**: `postgresql://postgres.iopqejjcutxdpeqsnayy:Sewadimanake@aws-1-ap-southeast-1.pooler.supabase.com:5432/postgres`
