<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamRole: string
{
    case ADMIN = 'team admin';
    case MEMBER = 'team member';
}
