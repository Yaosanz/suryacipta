<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InquiryStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case Contacted = 'contacted';
    case Qualified = 'qualified';
    case Closed = 'closed';
    case Spam = 'spam';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Sudah dihubungi',
            self::Qualified => 'Prospek potensial',
            self::Closed => 'Selesai',
            self::Spam => 'Spam',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New => 'warning',
            self::Contacted => 'info',
            self::Qualified => 'success',
            self::Closed => 'gray',
            self::Spam => 'danger',
        };
    }
}
