<?php

namespace App\Enums;

enum UserStatus: string
{
    case ENABLE = 'enable';
    case DISABLE = 'disable';

    /**
     * Retrieve array with all values contained in this enum.
     *
     * @return array<mixed>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
