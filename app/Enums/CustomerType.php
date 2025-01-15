<?php

declare(strict_types=1);

namespace App\Enums;

enum CustomerType: string
{
    case PRIVATE = 'private';
    case COMPANY = 'company';

    public function label(): string
    {
        return match ($this) {
            self::PRIVATE => __('Private'),
            self::COMPANY => __('Company'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PRIVATE => 'sky',
            self::COMPANY => 'amber',
        };
    }
}
