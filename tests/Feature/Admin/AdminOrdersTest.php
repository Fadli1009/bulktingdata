<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('forbids non admins from confirming orders', function () {
    $order = Order::factory()->create();

    $this->actingAs(customer())
        ->post(route('admin.orders.confirm', $order))
        ->assertRedirect(route('admin.login'));
});

it('lets an admin confirm a paid order and activate the subscription', function () {
    $admin = User::factory()->admin()->create();
    $package = Package::factory()->personal()->create();
    $order = Order::factory()->create(['package_id' => $package->id, 'status' => OrderStatus::Processing]);

    $this->actingAs($admin)
        ->post(route('admin.orders.confirm', $order))
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(OrderStatus::Done)
        ->and($order->fresh()->subscription)->not->toBeNull();
});

it('lets an admin cancel an order', function () {
    $admin = User::factory()->admin()->create();
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    $this->actingAs($admin)
        ->post(route('admin.orders.cancel', $order))
        ->assertRedirect();

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled);
});

it('renders the admin orders list', function () {
    $admin = User::factory()->admin()->create();
    Order::factory()->create(['order_number' => 'INV-ADMIN-1']);

    $this->actingAs($admin)
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSee('INV-ADMIN-1');
});
