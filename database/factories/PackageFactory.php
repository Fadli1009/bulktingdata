<?php

namespace Database\Factories;

use App\Enums\BillingPeriod;
use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Paket '.fake()->unique()->word(),
            'description' => fake()->sentence(),
            'type' => PackageType::Personal,
            'price' => fake()->numberBetween(50, 500) * 10000,
            'billing_period' => BillingPeriod::Monthly,
            'upload_limit' => fake()->numberBetween(1, 20),
            'seat_limit' => null,
            'status' => 'active',
            'sort_order' => fake()->numberBetween(0, 10),
            'badge' => null,
            'is_featured' => false,
            'features' => null,
        ];
    }

    public function personal(): static
    {
        return $this->state(fn (): array => [
            'type' => PackageType::Personal,
            'upload_limit' => fake()->numberBetween(1, 20),
            'seat_limit' => null,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn (): array => [
            'type' => PackageType::Business,
            'upload_limit' => null,
            'seat_limit' => fake()->numberBetween(2, 10),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => [
            'is_featured' => true,
            'badge' => 'Populer',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => [
            'status' => 'inactive',
        ]);
    }
}
