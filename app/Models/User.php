<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'avatar', 'account_type', 'phone', 'profile_completed_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasChosenAccountType(): bool
    {
        return in_array($this->account_type, ['personal', 'bisnis'], true);
    }

    public function isPersonal(): bool
    {
        return $this->account_type === 'personal';
    }

    public function isBusiness(): bool
    {
        return $this->account_type === 'bisnis';
    }

    public function hasCompletedProfile(): bool
    {
        return $this->profile_completed_at !== null;
    }
}
