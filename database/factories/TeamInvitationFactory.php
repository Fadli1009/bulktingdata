<?php

namespace Database\Factories;

use App\Enums\TeamInvitationStatus;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TeamInvitation>
 */
class TeamInvitationFactory extends Factory
{
    protected $model = TeamInvitation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'email' => fake()->unique()->safeEmail(),
            'token' => Str::random(48),
            'status' => TeamInvitationStatus::Pending,
            'expires_at' => now()->addDays(7),
            'accepted_at' => null,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (): array => [
            'status' => TeamInvitationStatus::Accepted,
            'accepted_at' => now(),
        ]);
    }
}
