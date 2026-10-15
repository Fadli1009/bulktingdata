<?php

namespace App\Enums;

enum UploadStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Done = 'done';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Diproses',
            self::Done => 'Selesai',
            self::Failed => 'Gagal',
        };
    }
}
