<?php

namespace Tests\Unit;

use App\Models\Equipment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Profile;
use App\Models\User;
use App\Services\MidtransService;
use Carbon\Carbon;
use Tests\TestCase;

class MidtransServiceTest extends TestCase
{
    public function test_build_snap_payload_uses_item_subtotal_for_consistent_total(): void
    {
        $order = new Order([
            'id' => 10,
            'midtrans_order_id' => 'MNK-20260216-10-ABC123',
            'total_amount' => 1300000,
            'rental_start_date' => Carbon::parse('2026-02-26'),
            'rental_end_date' => Carbon::parse('2026-02-27'),
        ]);

        $user = new User([
            'name' => 'Odoy',
            'email' => 'odoy@example.com',
        ]);
        $profile = new Profile([
            'full_name' => 'Odoy',
            'phone' => '087800000000',
        ]);

        $equipment = new Equipment([
            'id' => 1,
            'name' => 'Lumix S1H Mirrorless',
        ]);

        $item = new OrderItem([
            'id' => 101,
            'equipment_id' => 1,
            'qty' => 1,
            'price' => 650000,
            'subtotal' => 1300000,
        ]);

        $user->setRelation('profile', $profile);
        $order->setRelation('user', $user);
        $item->setRelation('equipment', $equipment);
        $order->setRelation('items', collect([$item]));

        $service = new MidtransService();
        $payload = $service->buildSnapPayload($order);

        $this->assertSame(1443000, $payload['transaction_details']['gross_amount']);
        $this->assertCount(2, $payload['item_details']);
        $this->assertSame(1300000, $payload['item_details'][0]['price']);
        $this->assertSame(1, $payload['item_details'][0]['quantity']);
        $this->assertStringContainsString('2 hari', $payload['item_details'][0]['name']);
        $this->assertSame(143000, $payload['item_details'][1]['price']);
        $this->assertSame('PPN 11%', $payload['item_details'][1]['name']);
    }

    public function test_build_snap_payload_creates_fallback_item_when_order_items_are_empty(): void
    {
        $order = new Order([
            'id' => 11,
            'midtrans_order_id' => 'MNK-20260216-11-XYZ123',
            'total_amount' => 500000,
            'rental_start_date' => Carbon::parse('2026-02-20'),
            'rental_end_date' => Carbon::parse('2026-02-20'),
        ]);

        $user = new User([
            'name' => 'Tester',
            'email' => 'tester@example.com',
        ]);

        $order->setRelation('user', $user);
        $order->setRelation('items', collect());

        $service = new MidtransService();
        $payload = $service->buildSnapPayload($order);

        $this->assertCount(2, $payload['item_details']);
        $this->assertSame(500000, $payload['item_details'][0]['price']);
        $this->assertSame('Total Sewa', $payload['item_details'][0]['name']);
        $this->assertSame(55000, $payload['item_details'][1]['price']);
        $this->assertSame(555000, $payload['transaction_details']['gross_amount']);
    }

    public function test_build_damage_fee_payload_does_not_add_ppn(): void
    {
        $order = new Order([
            'id' => 12,
            'order_number' => 'MNK-20260219-12-ABC12',
            'midtrans_order_id' => 'MNK-20260219-12-ABC12',
            'total_amount' => 500000,
        ]);

        $user = new User([
            'name' => 'Damage User',
            'email' => 'damage@example.com',
        ]);
        $profile = new Profile([
            'full_name' => 'Damage User',
            'phone' => '081200000000',
        ]);
        $user->setRelation('profile', $profile);
        $order->setRelation('user', $user);

        $service = new MidtransService();
        $payload = $service->buildDamageFeePayload($order, 150000, 'MNK-20260219-12-ABC12-DMG-1');

        $this->assertSame(150000, $payload['transaction_details']['gross_amount']);
        $this->assertCount(1, $payload['item_details']);
        $this->assertSame(150000, $payload['item_details'][0]['price']);
        $this->assertSame(1, $payload['item_details'][0]['quantity']);
        $this->assertStringNotContainsString('PPN', $payload['item_details'][0]['name']);
    }
}
