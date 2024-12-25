<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamStatus: string
{
    case PREMIUM = 'premium';
    case FREE = 'free';
    case TRIAL = 'trial';
    case CLOSED = 'closed';
}
