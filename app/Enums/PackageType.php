<?php

namespace App\Enums;

enum PackageType: string
{
    case Personal = 'personal';
    case Business = 'bisnis';

    public function label(): string
    {
        return match ($this) {
            self::Personal => 'Personal',
            self::Business => 'Bisnis',
        };
    }
}
