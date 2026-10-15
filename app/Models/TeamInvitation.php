<?php

namespace App\Models;

use App\Enums\TeamInvitationStatus;
use Database\Factories\TeamInvitationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'owner_id', 'email', 'token', 'status', 'expires_at', 'accepted_at',
])]
class TeamInvitation extends Model
{
    /** @use HasFactory<TeamInvitationFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TeamInvitationStatus::class,
            'expires_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function scopePending(Builder $query): void
    {
        $query->where('status', TeamInvitationStatus::Pending);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return $this->status === TeamInvitationStatus::Pending && ! $this->isExpired();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
