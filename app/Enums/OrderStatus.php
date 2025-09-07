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
 * @time 3:26 PM
 */

namespace App\Enums;

enum OrderStatus: string
{

    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Shipped => 'Shipped',
            self::Delivered => 'Delivered',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Failed => 'Failed',
        };
    }

    /**
     * Get the corresponding color class for the status.
     *
     * @return string
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'secondary',
            self::Processing => 'info',
            self::Shipped => 'primary',
            self::Delivered, self::Completed => 'success',
            self::Cancelled, self::Failed => 'danger',
        };
    }

    /**
     * Get the numerical step for the progress bar.
     */
    public function step(): int
    {
        return match ($this) {
            self::Pending => 1,
            self::Processing => 2,
            self::Shipped => 3,
            self::Delivered => 4,
            self::Completed => 5,
            self::Cancelled, self::Failed => 0,
        };
    }

}
