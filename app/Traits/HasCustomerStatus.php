<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/22/2025
 * @time 3:24 PM
 */

namespace App\Traits;

use App\Enums\CustomerStatus;

trait HasCustomerStatus
{

    public function getStatusEnum(): CustomerStatus
    {
        return CustomerStatus::from($this->status);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusEnum()->label();
    }

    public function getStatusColorAttribute(): string
    {
        return $this->getStatusEnum()->color();
    }

    public function scopeActive($query)
    {
        return $query->where('status', CustomerStatus::ACTIVE->value);
    }

    public function setStatus(CustomerStatus $status): void
    {
        $this->status = $status->value;
    }

    public function isActive(): bool
    {
        return $this->getStatusEnum() === CustomerStatus::ACTIVE;
    }

    public function getToggleStatusActionTextAttribute(): string
    {
        return match ($this->getStatusEnum()) {
            CustomerStatus::ACTIVE => 'Suspend',
            CustomerStatus::INACTIVE, CustomerStatus::SUSPENDED => 'Activate',
            default => 'N/A',
        };
    }

    public function getToggleStatusIconAttribute(): string
    {
        return match ($this->getStatusEnum()) {
            CustomerStatus::ACTIVE => 'lock',
            CustomerStatus::INACTIVE, CustomerStatus::SUSPENDED => 'unlock',
            default => 'slash',
        };
    }

}
