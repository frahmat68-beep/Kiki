<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeHeroCarouselTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_ready_carousel_loads_ready_items_from_all_categories(): void
    {
        $camera = Category::create([
            'name' => 'Camera',
            'slug' => 'camera',
        ]);

        $audio = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
        ]);

        Equipment::create([
            'name' => 'Sony A7 III',
            'slug' => 'sony-a7-iii',
            'category_id' => $camera->id,
            'price_per_day' => 350000,
            'stock' => 5,
            'status' => 'ready',
        ]);

        Equipment::create([
            'name' => 'Zoom H6',
            'slug' => 'zoom-h6',
            'category_id' => $audio->id,
            'price_per_day' => 90000,
            'stock' => 4,
            'status' => 'ready',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Sony A7 III');
        $response->assertSee('Zoom H6');
        $response->assertSee('data-slide-count="2"', false);
    }

    public function test_home_ready_carousel_includes_fit_image_and_single_slide_fallback_logic(): void
    {
        $camera = Category::create([
            'name' => 'Camera',
            'slug' => 'camera',
        ]);

        Equipment::create([
            'name' => 'DJI RS3',
            'slug' => 'dji-rs3',
            'category_id' => $camera->id,
            'price_per_day' => 250000,
            'stock' => 2,
            'status' => 'ready',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('object-contain', false);
        $response->assertSee('watchOverflow: true', false);
        $response->assertDontSee('cloneNode(true)', false);
    }

    public function test_home_shows_damage_fee_alert_and_popup_when_additional_fee_is_outstanding(): void
    {
        $user = User::factory()->create();

        Order::create([
            'user_id' => $user->id,
            'order_number' => 'MNK-DAMAGE-OUTSTANDING',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'barang_kembali',
            'status' => 'paid',
            'total_amount' => 500000,
            'penalty_amount' => 0,
            'additional_fee' => 100000,
            'additional_fee_note' => 'terlambat pengembalian',
            'rental_start_date' => now()->subDays(2)->toDateString(),
            'rental_end_date' => now()->subDay()->toDateString(),
            'midtrans_order_id' => 'MNK-DAMAGE-OUTSTANDING',
            'paid_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertSee('Perhatian Tagihan Tambahan');
        $response->assertSee('Rp 100.000');
        $response->assertSee('id="damage-fee-popup"', false);
        $response->assertSee('data-damage-popup-pay', false);
    }

    public function test_home_hides_damage_fee_alert_after_damage_payment_is_paid(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'MNK-DAMAGE-PAID',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'barang_kembali',
            'status' => 'paid',
            'total_amount' => 500000,
            'penalty_amount' => 0,
            'additional_fee' => 100000,
            'additional_fee_note' => 'kerusakan ringan',
            'rental_start_date' => now()->subDays(2)->toDateString(),
            'rental_end_date' => now()->subDay()->toDateString(),
            'midtrans_order_id' => 'MNK-DAMAGE-PAID',
            'paid_at' => now()->subDay(),
        ]);

        Payment::create([
            'order_id' => $order->id,
            'provider' => Payment::PROVIDER_MIDTRANS_DAMAGE,
            'midtrans_order_id' => 'MNK-DAMAGE-FEE-PAID',
            'transaction_status' => 'settlement',
            'gross_amount' => 100000,
            'status' => 'paid',
            'transaction_id' => 'trx-damage-paid-1',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk();
        $response->assertDontSee('Perhatian Tagihan Tambahan');
        $response->assertDontSee('id="damage-fee-popup"', false);
    }
}
