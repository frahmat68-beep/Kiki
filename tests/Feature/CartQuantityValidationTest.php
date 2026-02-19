<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartQuantityValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_add_rejects_quantity_above_stock(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment([
            'stock' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'equipment_id' => $equipment->id,
            'qty' => 2,
        ]);

        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error', function ($message) use ($equipment) {
            return str_contains((string) $message, "Stok {$equipment->name} tersedia 1 unit");
        });
        $this->assertSame([], session()->get('cart.items', []));
    }

    public function test_cart_increment_rejects_when_quantity_exceeds_stock(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment([
            'stock' => 1,
        ]);
        $key = 'equipment:' . $equipment->id;

        $response = $this
            ->actingAs($user)
            ->withSession([
                'cart.items' => [
                    $key => [
                        'key' => $key,
                        'equipment_id' => $equipment->id,
                        'name' => $equipment->name,
                        'slug' => $equipment->slug,
                        'price' => $equipment->price_per_day,
                        'qty' => 1,
                    ],
                ],
            ])
            ->patch(route('cart.increment', $key));

        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error');

        $items = session()->get('cart.items', []);
        $this->assertSame(1, (int) data_get($items, $key . '.qty'));
    }

    public function test_cart_update_rejects_when_quantity_exceeds_stock(): void
    {
        $user = User::factory()->create();
        $equipment = $this->createEquipment([
            'stock' => 1,
        ]);
        $key = 'equipment:' . $equipment->id;

        $response = $this
            ->actingAs($user)
            ->withSession([
                'cart.items' => [
                    $key => [
                        'key' => $key,
                        'equipment_id' => $equipment->id,
                        'name' => $equipment->name,
                        'slug' => $equipment->slug,
                        'price' => $equipment->price_per_day,
                        'qty' => 1,
                    ],
                ],
            ])
            ->patch(route('cart.update', $key), [
                'qty' => 3,
            ]);

        $response->assertRedirect(route('cart'));
        $response->assertSessionHas('error');

        $items = session()->get('cart.items', []);
        $this->assertSame(1, (int) data_get($items, $key . '.qty'));
    }

    private function createEquipment(array $overrides = []): Equipment
    {
        $category = Category::create([
            'name' => 'Cart Validation',
            'slug' => 'cart-validation-' . strtolower((string) str()->random(6)),
        ]);

        return Equipment::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Stock Limited Gear',
            'slug' => 'stock-limited-' . strtolower((string) str()->random(6)),
            'description' => 'Testing stock lock',
            'price_per_day' => 100000,
            'stock' => 1,
            'status' => 'ready',
            'image' => null,
        ], $overrides));
    }
}
