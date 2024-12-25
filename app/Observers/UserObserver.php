<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function deleting(User $user): void
    {
        $user->teams()->detach();
    }
}
