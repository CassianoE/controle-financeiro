<?php

namespace App\Enums;

enum AccountStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BLOCKED = 'blocked';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
