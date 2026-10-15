<?php

namespace App\Models;

use App\Enums\BillingPeriod;
use App\Enums\PackageType;
use Database\Factories\PackageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'name', 'slug', 'description', 'type', 'price', 'billing_period',
    'upload_limit', 'seat_limit', 'status', 'sort_order', 'badge',
    'is_featured', 'features',
])]
class Package extends Model
{
    /** @use HasFactory<PackageFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => PackageType::class,
            'billing_period' => BillingPeriod::class,
            'price' => 'integer',
            'upload_limit' => 'integer',
            'seat_limit' => 'integer',
            'sort_order' => 'integer',
            'is_featured' => 'boolean',
            'features' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Package $package): void {
            if (blank($package->slug)) {
                $package->slug = static::uniqueSlug($package->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'paket';
        $slug = $base;
        $suffix = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    public function scopeOfType(Builder $query, PackageType $type): void
    {
        $query->where('type', $type);
    }

    public function hasUnlimitedUploads(): bool
    {
        return $this->upload_limit === null;
    }

    public function hasUnlimitedSeats(): bool
    {
        return $this->seat_limit === null;
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
