<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PackageType;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_number', 'user_id', 'package_id', 'ordered_by', 'package_name',
    'package_type', 'total', 'status', 'notes', 'ordered_at', 'confirmed_at',
])]
#[Hidden(['package_name', 'package_type'])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'package_type' => PackageType::class,
            'status' => OrderStatus::class,
            'total' => 'integer',
            'ordered_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    /**
     * The customer who owns the order.
     *
     * @return BelongsTo<User, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The account that created the order (owner or admin).
     *
     * @return BelongsTo<User, $this>
     */
    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    /**
     * @return BelongsTo<Package, $this>
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
