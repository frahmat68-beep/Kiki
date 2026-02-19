<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderRescheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_reschedule_rejects_when_duration_changes(): void
    {
        $baseDate = now()->startOfDay();
        $user = User::factory()->create();
        $equipment = $this->createEquipment();
        $order = $this->createOrderWithItem(
            $user,
            $equipment,
            $baseDate->copy()->addDays(4)->toDateString(),
            $baseDate->copy()->addDays(5)->toDateString(),
            1
        );

        $response = $this->actingAs($user)->patch(route('account.orders.reschedule', $order), [
            'rental_start_date' => $baseDate->copy()->addDays(8)->toDateString(),
            'rental_end_date' => $baseDate->copy()->addDays(10)->toDateString(),
        ]);

        $response->assertRedirect(route('account.orders.show', $order));
        $response->assertSessionHas('error', function ($message) {
            return str_contains((string) $message, 'Durasi reschedule harus tetap 2 hari');
        });
    }

    public function test_reschedule_rejects_when_buffer_day_before_other_booking_is_full(): void
    {
        $baseDate = now()->startOfDay();
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $equipment = $this->createEquipment([
            'stock' => 1,
        ]);

        $initialDate = $baseDate->copy()->addDays(3)->toDateString();
        $order = $this->createOrderWithItem(
            $user,
            $equipment,
            $initialDate,
            $initialDate,
            1
        );

        $nextBookingDate = $baseDate->copy()->addDays(9)->toDateString();
        $this->createOrderWithItem(
            $otherUser,
            $equipment,
            $nextBookingDate,
            $nextBookingDate,
            1,
            'paid',
            'lunas'
        );

        $targetRescheduleDate = $baseDate->copy()->addDays(8)->toDateString();
        $response = $this->actingAs($user)->patch(route('account.orders.reschedule', $order), [
            'rental_start_date' => $targetRescheduleDate,
            'rental_end_date' => $targetRescheduleDate,
        ]);

        $response->assertRedirect(route('account.orders.show', $order));
        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertSame($initialDate, $order->rental_start_date?->toDateString());
        $this->assertSame($initialDate, $order->rental_end_date?->toDateString());
    }

    public function test_reschedule_keeps_item_quantity_when_dates_change(): void
    {
        $baseDate = now()->startOfDay();
        $user = User::factory()->create();
        $equipment = $this->createEquipment([
            'stock' => 5,
            'price_per_day' => 250000,
        ]);

        $initialStartDate = $baseDate->copy()->addDays(2)->toDateString();
        $initialEndDate = $baseDate->copy()->addDays(3)->toDateString();
        $order = $this->createOrderWithItem(
            $user,
            $equipment,
            $initialStartDate,
            $initialEndDate,
            3
        );

        $newStartDate = $baseDate->copy()->addDays(10)->toDateString();
        $newEndDate = Carbon::parse($newStartDate)->addDays(1)->toDateString();
        $response = $this->actingAs($user)->patch(route('account.orders.reschedule', $order), [
            'rental_start_date' => $newStartDate,
            'rental_end_date' => $newEndDate,
        ]);

        $response->assertRedirect(route('account.orders.show', $order));

        $order->refresh();
        $item = $order->items()->first();

        $this->assertSame(3, (int) ($item?->qty ?? 0));
        $this->assertSame($newStartDate, $order->rental_start_date?->toDateString());
        $this->assertSame($newEndDate, $order->rental_end_date?->toDateString());
    }

    private function createEquipment(array $overrides = []): Equipment
    {
        $category = Category::create([
            'name' => 'Reschedule Gear',
            'slug' => 'reschedule-gear-' . strtolower((string) str()->random(6)),
        ]);

        return Equipment::create(array_merge([
            'category_id' => $category->id,
            'name' => 'Tentacle Sync E',
            'slug' => 'tentacle-sync-' . strtolower((string) str()->random(6)),
            'description' => 'Timecode',
            'price_per_day' => 150000,
            'stock' => 3,
            'status' => 'ready',
            'image' => null,
        ], $overrides));
    }

    private function createOrderWithItem(
        User $user,
        Equipment $equipment,
        string $startDate,
        string $endDate,
        int $qty,
        string $paymentStatus = 'pending',
        string $orderStatus = 'menunggu_pembayaran'
    ): Order {
        $orderNumber = 'MNK-RS-' . strtoupper((string) str()->random(8));
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'midtrans_order_id' => $orderNumber,
            'status_pembayaran' => $paymentStatus,
            'status_pesanan' => $orderStatus,
            'status' => $paymentStatus === 'paid' ? 'paid' : 'pending',
            'total_amount' => 0,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'paid_at' => $paymentStatus === 'paid' ? now() : null,
        ]);

        $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $lineSubtotal = (int) $equipment->price_per_day * $qty * $days;

        $order->items()->create([
            'equipment_id' => $equipment->id,
            'qty' => $qty,
            'price' => (int) $equipment->price_per_day,
            'subtotal' => $lineSubtotal,
            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,
            'rental_days' => $days,
        ]);

        $order->update(['total_amount' => $lineSubtotal]);

        return $order->fresh();
    }
}
