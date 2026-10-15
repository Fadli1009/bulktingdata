<?php

namespace Database\Factories;

use App\Enums\UploadStatus;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Upload>
 */
class UploadFactory extends Factory
{
    protected $model = Upload::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'owner_id' => User::factory(),
            'subscription_id' => null,
            'original_name' => fake()->word().'.xlsx',
            'path' => 'uploads/'.fake()->uuid().'.xlsx',
            'size' => fake()->numberBetween(10_000, 5_000_000),
            'status' => UploadStatus::Pending,
            'rows_processed' => null,
        ];
    }
}
