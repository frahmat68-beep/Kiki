<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityBoardPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_availability_board_is_accessible_for_guest(): void
    {
        $response = $this->get(route('availability.board'));

        $response->assertOk();
        $response->assertSee('Pusat Cek Ketersediaan Alat');
        $response->assertSee('Klik tanggal di kalender');
        $response->assertDontSee('Matriks Ketersediaan Alat');
    }

    public function test_availability_board_shows_busy_equipment_for_selected_date(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
        ]);

        $equipment = Equipment::create([
            'category_id' => $category->id,
            'name' => 'Mic Wireless Pro',
            'slug' => 'mic-wireless-pro',
            'description' => 'Audio device',
            'price_per_day' => 120000,
            'stock' => 8,
            'status' => 'ready',
            'image' => null,
        ]);

        $startDate = now()->addDays(2)->toDateString();
        $endDate = now()->addDays(4)->toDateString();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'MNK-AVAIL-BOARD',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'lunas',
            'status' => 'paid',
            'total_amount' => 360000,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'midtrans_order_id' => 'MNK-AVAIL-BOARD',
            'paid_at' => now(),
        ]);

        $order->items()->create([
            'equipment_id' => $equipment->id,
            'qty' => 4,
            'price' => 120000,
            'subtotal' => 1440000,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'rental_days' => 3,
        ]);

        $response = $this->get(route('availability.board', [
            'month' => now()->addDays(2)->format('Y-m'),
            'date' => $startDate,
        ]));

        $response->assertOk();
        $response->assertSee('Mic Wireless Pro');
        $response->assertSee('Alat Terpakai di');
        $response->assertSee('dipakai 4 unit');
    }
}
