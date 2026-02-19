<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAvailabilityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_availability_endpoint_returns_available_when_no_conflict(): void
    {
        $equipment = $this->createEquipment([
            'name' => 'Sony FX3',
            'slug' => 'sony-fx3',
            'stock' => 3,
            'status' => 'ready',
        ]);

        $response = $this->getJson(route('product.availability', ['slug' => $equipment->slug], false) . '?start_date=' . now()->addDays(4)->toDateString() . '&end_date=' . now()->addDays(5)->toDateString() . '&qty=1');

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'status' => 'available',
        ]);
    }

    public function test_availability_endpoint_returns_partially_available_when_only_some_days_conflict(): void
    {
        $equipment = $this->createEquipment([
            'name' => 'Aputure 600D',
            'slug' => 'aputure-600d',
            'stock' => 2,
            'status' => 'ready',
        ]);

        $renter = User::factory()->create();
        $order = Order::create([
            'user_id' => $renter->id,
            'order_number' => 'MNK-AVAIL-PARTIAL',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'lunas',
            'status' => 'paid',
            'total_amount' => 600000,
            'rental_start_date' => now()->addDays(2)->toDateString(),
            'rental_end_date' => now()->addDays(3)->toDateString(),
            'midtrans_order_id' => 'MNK-AVAIL-PARTIAL',
            'paid_at' => now(),
        ]);

        $order->items()->create([
            'equipment_id' => $equipment->id,
            'qty' => 1,
            'price' => 300000,
            'subtotal' => 600000,
            'rental_start_date' => now()->addDays(2)->toDateString(),
            'rental_end_date' => now()->addDays(3)->toDateString(),
            'rental_days' => 2,
        ]);

        $response = $this->getJson(route('product.availability', ['slug' => $equipment->slug], false) . '?start_date=' . now()->addDays(1)->toDateString() . '&end_date=' . now()->addDays(3)->toDateString() . '&qty=2');

        $response->assertOk();
        $response->assertJson([
            'ok' => false,
            'status' => 'partially_available',
        ]);
        $this->assertNotEmpty($response->json('conflicts'));
    }

    public function test_availability_endpoint_blocks_buffer_day_after_existing_booking(): void
    {
        $equipment = $this->createEquipment([
            'name' => 'GoPro Hero 4',
            'slug' => 'gopro-hero-4',
            'stock' => 1,
            'status' => 'ready',
        ]);

        $renter = User::factory()->create();
        $endDate = now()->addDays(5)->toDateString();
        $order = Order::create([
            'user_id' => $renter->id,
            'order_number' => 'MNK-AVAIL-BUFFER',
            'status_pembayaran' => 'paid',
            'status_pesanan' => 'lunas',
            'status' => 'paid',
            'total_amount' => 125000,
            'rental_start_date' => $endDate,
            'rental_end_date' => $endDate,
            'midtrans_order_id' => 'MNK-AVAIL-BUFFER',
            'paid_at' => now(),
        ]);

        $order->items()->create([
            'equipment_id' => $equipment->id,
            'qty' => 1,
            'price' => 125000,
            'subtotal' => 125000,
            'rental_start_date' => $endDate,
            'rental_end_date' => $endDate,
            'rental_days' => 1,
        ]);

        $bufferDate = now()->addDays(6)->toDateString();
        $response = $this->getJson(route('product.availability', ['slug' => $equipment->slug], false) . '?start_date=' . $bufferDate . '&end_date=' . $bufferDate . '&qty=1');

        $response->assertOk();
        $response->assertJson([
            'ok' => false,
            'status' => 'not_available',
        ]);
    }

    private function createEquipment(array $overrides = []): Equipment
    {
        $category = Category::create([
            'name' => 'Testing Gear',
            'slug' => 'testing-gear',
        ]);

        return Equipment::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Equipment',
            'slug' => 'equipment',
            'description' => 'Testing equipment',
            'price_per_day' => 250000,
            'stock' => 1,
            'status' => 'ready',
            'image' => null,
        ], $overrides));
    }
}
