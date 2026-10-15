<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'avatar', 'account_type', 'phone', 'profile_completed_at', 'is_admin', 'team_owner_id'])]
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
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include registered customers (non-admin users).
     */
    public function scopeCustomers(Builder $query): void
    {
        $query->where('is_admin', false);
    }

    /**
     * The customer tracking status derived from onboarding progress.
     */
    protected function status(): Attribute
    {
        return Attribute::get(
            fn (): string => $this->hasCompletedProfile() ? 'success' : 'pending',
        );
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

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isTeamMember(): bool
    {
        return $this->team_owner_id !== null;
    }

    /**
     * The account that owns the active subscription (self, or the team owner).
     */
    public function subscriptionOwner(): self
    {
        return $this->teamOwner ?? $this;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function teamOwner(): BelongsTo
    {
        return $this->belongsTo(self::class, 'team_owner_id');
    }

    /**
     * @return HasMany<User, $this>
     */
    public function teamMembers(): HasMany
    {
        return $this->hasMany(self::class, 'team_owner_id');
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return HasOne<Subscription, $this>
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->active()->latestOfMany();
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<Upload, $this>
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    /**
     * @return HasMany<TeamInvitation, $this>
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class, 'owner_id');
    }
}
