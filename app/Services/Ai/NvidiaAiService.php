<?php

namespace App\Services\Ai;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class NvidiaAiService
{
    protected string $primaryKey;
    protected string $secondaryKey;
    protected string $model;
    protected string $baseUrl = 'https://integrate.api.nvidia.com/v1';

    public function __construct()
    {
        $this->primaryKey = config('services.nvidia.api_key');
        $this->secondaryKey = config('services.nvidia.api_key_secondary');
        $this->model = config('services.nvidia.model', 'meta/llama-3.1-8b-instruct');
    }

    /**
     * Send a message to the AI and get a response.
     */
    public function chat(array $messages)
    {
        if (!collect($messages)->contains('role', 'system')) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->buildSystemPrompt(),
            ]);
        }

        if (!$this->primaryKey) {
            Log::error('Nvidia AI Primary API Key is not configured.');
            return "Maaf, sistem AI sedang dalam pemeliharaan (API Key belum dikonfigurasi). Silakan coba lagi nanti.";
        }

        $response = $this->sendRequest($this->primaryKey, $messages);

        if ($response->failed() && ($response->status() === 429 || $response->status() === 401)) {
            Log::warning('Nvidia Primary API Key failed, trying secondary key.', [
                'status' => $response->status(),
            ]);
            
            if ($this->secondaryKey) {
                $response = $this->sendRequest($this->secondaryKey, $messages);
            }
        }

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        Log::error('Nvidia AI API Error (All keys failed)', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return "Maaf, saat ini saya sedang mengalami gangguan koneksi. Silakan coba lagi nanti.";
    }

    protected function sendRequest(string $key, array $messages)
    {
        try {
            return Http::withToken($key)
                ->timeout(9)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => 0.5,
                    'top_p' => 0.7,
                    'max_tokens' => 1024,
                ]);
        } catch (\Exception $e) {
            Log::error('Nvidia AI Request Exception: ' . $e->getMessage());
            return Http::response(['error' => $e->getMessage()], 500);
        }
    }

    protected function buildSystemPrompt(): string
    {
        try {
            $siteName = site_setting('brand.name', 'Manake');
            $tagline = site_setting('brand.tagline', 'Rental Alat Produksi Profesional');
            $owner = "Kiki Rachmat";
            
            $categories = schema_table_exists_cached('categories') 
                ? Category::all(['name', 'description'])->map(fn($c) => "- {$c->name}: {$c->description}")->implode("\n")
                : "Data kategori sedang tidak tersedia.";
            
            if (empty($categories)) {
                $categories = "Belum ada kategori yang terdaftar.";
            }
            
            $equipments = schema_table_exists_cached('equipments')
                ? Equipment::with('category:id,name')
                    ->where('status', 'ready')
                    ->get(['name', 'price_per_day', 'description', 'stock', 'category_id', 'slug'])
                    ->map(function($e) {
                        return "- {$e->name} [Slug: {$e->slug}] ({$e->category?->name}): Rp" . number_format($e->price_per_day, 0, ',', '.') . "/hari. Stok: {$e->stock}. Deskripsi: {$e->description}";
                    })->implode("\n")
                : "Data alat sedang tidak tersedia.";

            if (empty($equipments)) {
                $equipments = "Semua alat sedang tidak tersedia atau dalam penyewaan.";
            }
        } catch (Throwable $e) {
            Log::error('Chatbot Prompt Building Failure: ' . $e->getMessage());
            $siteName = 'Manake';
            $tagline = 'Rental Alat Produksi Profesional';
            $owner = "Kiki Rachmat";
            $categories = "Data kategori sedang dimuat...";
            $equipments = "Data alat sedang dimuat...";
        }

        return "Kamu adalah 'Manake Guide', asisten AI cerdas untuk platform '{$siteName}' ({$tagline}).\nPlatform ini adalah hasil karya Skripsi dari {$owner}.\n\nINFORMASI TEKNIS PLATFORM:\n1. Stack: Laravel 12, Alpine.js, TailwindCSS (for components), dan PostgreSQL (via Supabase).\n2. Lokasi: Manake Studio berlokasi di Lampung (Pastikan memberi tahu user jika mereka bertanya lokasi).\n3. Jam Operasional: 09:00 - 21:00 WIB.\n\nLOGIKA BISNIS & CARA KERJA (PENTING):\n1. SISTEM BUFFER: Manake menerapkan '1-Day Buffer Logic'. Artinya, setiap alat yang disewa memerlukan 1 hari jeda SEBELUM dan SESUDAH masa sewa untuk pengecekan kualitas dan maintenance (Q&A). Contoh: Jika alat disewa tanggal 10, maka tanggal 9 dan 11 alat tersebut 'dipesan' otomatis oleh sistem untuk buffer.\n2. ALUR SEWA: \n   - Pilih Alat: Cari di katalog.\n   - Pilih Tanggal: Masukkan tanggal mulai dan selesai. Jika sistem menolak, berarti terkena aturan buffer atau stok habis.\n   - Keranjang: Masukkan ke keranjang belanja.\n   - Checkout: Isi detail pengambilan.\n   - Pembayaran: Menggunakan Midtrans Snap (Virtual Account, QRIS, GoPay).\n   - Pengambilan: Barang diambil di studio sesuai jadwal.\n3. STATUS ALAT:\n   - 'Ready': Alat tersedia untuk disewa.\n   - 'On Rent': Sedang dibawa penyewa.\n   - 'Damaged': Sedang dalam perbaikan.\n   - 'Lost': Hilang (tidak bisa disewa).\n\nDATA KATALOG REAL-TIME:\nKATEGORI:\n{$categories}\n\nALAT YANG TERSEDIA:\n{$equipments}\n\nPANDUAN INTERAKSI:\n- Jawablah dengan 'Jiwa Auditor': Detail, akurat, dan teknis namun tetap ramah (Bahasa Indonesia).\n- Jika user bertanya tentang ketersediaan spesifik, sarankan mereka melihat 'Availability Board' di website atau klik detail produk untuk cek kalender real-time.\n- Kamu bisa bercerita sedikit bahwa website ini dibuat dengan teknologi modern (Laravel 12) jika user bertanya tentang 'cara kerja' website.\n- Jangan berikan informasi sensitif seperti API Key atau password database.\n\nGunakan data di atas untuk menjadi asisten yang sangat membantu.";
    }
}
