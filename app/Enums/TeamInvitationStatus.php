<?php

namespace App\Enums;

enum TeamInvitationStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Expired = 'expired';
    case Revoked = 'revoked';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Accepted => 'Diterima',
            self::Expired => 'Kedaluwarsa',
            self::Revoked => 'Dibatalkan',
        };
    }
}
