<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\TeamStatus;
use App\Models\Team;

class TeamObserver
{
    public function created(Team $team): void
    {
        if ($team->status === TeamStatus::TRIAL && is_null($team->trial_ends_at)) {
            $team->update([
                'trial_ends_at' => now()
                    ->addDays(config('app.free_trial_days'))
                    ->endOfDay(),
            ]);
        }
    }
}
