<?php

namespace App\Models;

use App\Enums\UploadStatus;
use Database\Factories\UploadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'owner_id', 'subscription_id', 'original_name', 'path',
    'size', 'status', 'rows_processed',
])]
class Upload extends Model
{
    /** @use HasFactory<UploadFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UploadStatus::class,
            'size' => 'integer',
            'rows_processed' => 'integer',
        ];
    }

    public function scopeOwnedBy(Builder $query, User|int $owner): void
    {
        $query->where('owner_id', $owner instanceof User ? $owner->id : $owner);
    }

    /**
     * The account that uploaded the file.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The subscription owner the upload counts against.
     *
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsTo<Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
