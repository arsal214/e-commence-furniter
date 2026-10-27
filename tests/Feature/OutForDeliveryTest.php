<?php

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

function ofdOrder(string $status = 'shipped'): Order
{
    return Order::forceCreate(['name' => 'Pat Buyer', 'email' => 'pat@example.com', 'phone' => '1', 'address' => '1 Main St', 'city' => 'Jersey City', 'state' => 'NJ', 'zip' => '07302', 'country' => 'US',
        'payment_method' => 'stripe', 'payment_status' => 'paid', 'shipping' => 'free', 'subtotal' => 40, 'total' => 40, 'status' => $status, 'tracking_number' => 'T-' . strtoupper(uniqid())]);
}

it('lets an admin mark an order out for delivery, emails the customer and shows it by name', function () {
    Mail::fake();
    $admin = User::factory()->create(['role' => 'admin']);
    $o = ofdOrder();

    $this->actingAs($admin)->put(route('admin.orders.update', $o), ['status' => 'out_for_delivery'])->assertSessionHasNoErrors()->assertSessionHas('success');
    expect($o->fresh()->status)->toBe('out_for_delivery');
    Mail::assertSent(OrderStatusUpdatedMail::class, fn ($m) => $m->hasTo('pat@example.com'));

    $this->actingAs($admin)->get(route('admin.orders.index', ['status' => 'out_for_delivery']))->assertOk()->assertSee('Out for delivery');
    $this->actingAs($admin)->get(route('admin.orders.show', $o))->assertOk()->assertSee('Out for delivery');
    $this->actingAs($admin)->put(route('admin.orders.update', $o), ['status' => 'not-a-status'])->assertSessionHasErrors('status');
});

it('shows the customer the new step on the tracking page and in their account', function () {
    $o = ofdOrder('out_for_delivery');
    $this->get('/track-order?tracking=' . $o->tracking_number)->assertOk()->assertSee('Out for delivery');

    expect($o->statusLabel())->toBe('Out for delivery')->and((new Order(['status' => 'shipped']))->statusLabel())->toBe('Shipped');
});
