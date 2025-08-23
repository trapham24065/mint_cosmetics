<?php

/**
 * @project mint_cosmetics
 *
 * @author PhamTra
 *
 * @email trapham24065@gmail.com
 *
 * @date 8/22/2025
 *
 * @time 3:27 PM
 */

namespace App\Enums;

enum CustomerStatus: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case DELETED = 3;

    public function label(): string
    {
        return match ($this) {
            self::INACTIVE => 'Inactive',
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::DELETED => 'Deleted',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INACTIVE => 'secondary',
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::DELETED => 'danger',
        };
    }
}
