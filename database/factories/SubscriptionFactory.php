<?php

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'package_id' => Package::factory(),
            'order_id' => null,
            'status' => SubscriptionStatus::Active,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::Expired,
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->subMonth(),
        ]);
    }
}
