<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Diproses',
            self::Done => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
