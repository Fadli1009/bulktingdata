<?php

use App\Enums\BillingPeriod;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Order;
use App\Models\Package;
use App\Models\Subscription;
use App\Services\PurchaseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('activates a subscription when the order is confirmed', function () {
    $user = customer();
    $package = Package::factory()->personal()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'package_id' => $package->id]);

    app(PurchaseService::class)->confirm($order);

    $order->refresh();
    $subscription = Subscription::where('order_id', $order->id)->first();

    expect($order->status)->toBe(OrderStatus::Done)
        ->and($order->confirmed_at)->not->toBeNull()
        ->and($subscription)->not->toBeNull()
        ->and($subscription->status)->toBe(SubscriptionStatus::Active)
        ->and($subscription->user_id)->toBe($user->id)
        ->and($subscription->starts_at->isToday())->toBeTrue();
});

it('extends from the end date of the current active subscription', function () {
    $user = customer();
    $package = Package::factory()->personal()->create(['billing_period' => BillingPeriod::Monthly]);

    $existingEnd = now()->addDays(10);
    $existing = Subscription::factory()->create([
        'user_id' => $user->id,
        'package_id' => $package->id,
        'starts_at' => now(),
        'ends_at' => $existingEnd,
    ]);

    $order = Order::factory()->create(['user_id' => $user->id, 'package_id' => $package->id]);

    app(PurchaseService::class)->confirm($order);

    $new = Subscription::where('order_id', $order->id)->first();

    expect($existing->fresh()->status)->toBe(SubscriptionStatus::Cancelled)
        ->and($new->ends_at->toDateString())->toBe($existingEnd->copy()->addMonthNoOverflow()->toDateString());
});

it('activates a yearly package one year out', function () {
    $user = customer();
    $package = Package::factory()->personal()->create(['billing_period' => BillingPeriod::Yearly]);
    $order = Order::factory()->create(['user_id' => $user->id, 'package_id' => $package->id]);

    app(PurchaseService::class)->confirm($order);

    $new = Subscription::where('order_id', $order->id)->first();

    expect($new->ends_at->toDateString())->toBe(now()->addYearNoOverflow()->toDateString());
});

it('cancels a pending order', function () {
    $order = Order::factory()->create(['status' => OrderStatus::Pending]);

    app(PurchaseService::class)->cancel($order);

    expect($order->fresh()->status)->toBe(OrderStatus::Cancelled);
});

it('refuses to cancel a confirmed order', function () {
    $order = Order::factory()->done()->create();

    expect(fn () => app(PurchaseService::class)->cancel($order))
        ->toThrow(ValidationException::class);
});
