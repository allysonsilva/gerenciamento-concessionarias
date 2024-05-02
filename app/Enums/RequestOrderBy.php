<?php

namespace App\Enums;

enum RequestOrderBy: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    /**
     * Retrieve array with all values contained in this enum
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
