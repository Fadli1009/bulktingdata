<?php

use App\Models\Order;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to login when starting checkout', function () {
    $package = Package::factory()->create();

    $this->get(route('checkout.show', $package))->assertRedirect(route('login'));
});

it('shows the checkout page for a logged in customer', function () {
    $package = Package::factory()->personal()->create(['name' => 'Paket Pro']);

    $this->actingAs(customer())
        ->get(route('checkout.show', $package))
        ->assertOk()
        ->assertSee('Paket Pro');
});

it('rejects inactive packages', function () {
    $package = Package::factory()->inactive()->create();

    $this->actingAs(customer())
        ->get(route('checkout.show', $package))
        ->assertNotFound();
});

it('creates a pending order with a price snapshot', function () {
    $package = Package::factory()->personal()->create(['name' => 'Paket Pro', 'price' => 149000]);
    $user = customer();

    $response = $this->actingAs($user)->post(route('checkout.store', $package));

    $order = Order::first();

    expect($order)->not->toBeNull()
        ->and($order->status->value)->toBe('pending')
        ->and($order->total)->toBe(149000)
        ->and($order->user_id)->toBe($user->id)
        ->and($order->package_id)->toBe($package->id);

    $response->assertRedirect(route('orders.show', $order));
});

it('lists a customers own orders', function () {
    $user = customer();
    $other = customer();

    Order::factory()->create(['user_id' => $user->id, 'order_number' => 'INV-MINE']);
    Order::factory()->create(['user_id' => $other->id, 'order_number' => 'INV-THEIRS']);

    $this->actingAs($user)
        ->get(route('dashboard.orders'))
        ->assertOk()
        ->assertSee('INV-MINE')
        ->assertDontSee('INV-THEIRS');
});
