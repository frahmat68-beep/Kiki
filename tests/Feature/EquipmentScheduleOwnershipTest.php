<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EquipmentScheduleOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_detail_shows_current_user_booking_in_schedule(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
        ]);

        $equipment = Equipment::create([
            'category_id' => $category->id,
            'name' => 'HT WLAN UHF',
            'slug' => 'ht-wlan-uhf',
            'description' => 'Walkie talkie',
            'price_per_day' => 10000,
            'stock' => 20,
            'status' => 'ready',
            'image' => null,
        ]);

        $startDate = now()->addDays(3)->toDateString();
        $endDate = now()->addDays(6)->toDateString();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'MNK-SELF-SCHEDULE',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'lunas',
            'status' => 'paid',
            'total_amount' => 600000,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'midtrans_order_id' => 'MNK-SELF-SCHEDULE',
            'paid_at' => now(),
        ]);

        $order->items()->create([
            'equipment_id' => $equipment->id,
            'qty' => 15,
            'price' => 10000,
            'subtotal' => 600000,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'rental_days' => 4,
        ]);

        $response = $this->actingAs($user)->get(route('product.show', $equipment->slug));

        $response->assertOk();
        $response->assertSee(__('app.product.my_booking_label'));
        $response->assertSee('Qty 15');
    }
}
