<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\TeamStatus;
use App\Models\Team;

class TeamObserver
{
    public function created(Team $team): void
    {
        /** @var int $trialDays */
        $trialDays = config('app.free_trial_days');

        if ($team->status === TeamStatus::TRIAL && is_null($team->trial_ends_at)) {

            $team->update([
                'trial_ends_at' => now()
                    ->addDays($trialDays)
                    ->endOfDay(),
            ]);
        }
    }
}
