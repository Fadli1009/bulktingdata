<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores the payment proof and marks the order processing', function () {
    Storage::fake('local');

    $user = customer();
    $package = Package::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $user->id,
        'package_id' => $package->id,
        'status' => OrderStatus::Pending,
    ]);

    $this->actingAs($user)
        ->post(route('orders.proof', $order), [
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
            'payment_reference' => 'Budi Santoso',
        ])
        ->assertRedirect();

    $order->refresh();

    expect($order->status)->toBe(OrderStatus::Processing)
        ->and($order->payment_proof_path)->not->toBeNull()
        ->and($order->paid_at)->not->toBeNull();

    Storage::disk('local')->assertExists($order->payment_proof_path);
});

it('forbids viewing another customers order', function () {
    $order = Order::factory()->create(['user_id' => customer()->id]);

    $this->actingAs(customer())
        ->get(route('orders.show', $order))
        ->assertForbidden();
});

it('rejects a proof upload for a confirmed order', function () {
    Storage::fake('local');

    $user = customer();
    $order = Order::factory()->done()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('orders.proof', $order), ['proof' => UploadedFile::fake()->image('x.jpg')])
        ->assertSessionHasErrors('proof');
});

it('requires a valid proof file type', function () {
    Storage::fake('local');

    $user = customer();
    $order = Order::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('orders.proof', $order), ['proof' => UploadedFile::fake()->create('malware.exe', 10)])
        ->assertSessionHasErrors('proof');
});
