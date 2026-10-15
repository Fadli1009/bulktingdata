<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PackageType;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $package = Package::factory();

        return [
            'order_number' => 'INV-'.now()->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'user_id' => User::factory(),
            'package_id' => $package,
            'ordered_by' => null,
            'package_name' => 'Paket Contoh',
            'package_type' => PackageType::Personal,
            'total' => fake()->numberBetween(50, 500) * 10000,
            'status' => OrderStatus::Pending,
            'notes' => null,
            'ordered_at' => now(),
            'confirmed_at' => null,
        ];
    }

    public function done(): static
    {
        return $this->state(fn (): array => [
            'status' => OrderStatus::Done,
            'confirmed_at' => now(),
        ]);
    }
}
