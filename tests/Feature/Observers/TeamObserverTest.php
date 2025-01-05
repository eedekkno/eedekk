<?php

declare(strict_types=1);

use App\Enums\TeamStatus;
use App\Models\Team;
use Illuminate\Support\Carbon;

beforeEach(function (): void {
    Config::set('app.free_trial_days', 14);
});

it('sets trial_ends_at for teams in trial status', function (): void {
    Carbon::setTestNow(Carbon::parse('1st January 2000'));

    $team = Team::factory()->create([
        'status' => TeamStatus::TRIAL,
        'trial_ends_at' => null,
    ]);

    expect($team->refresh()->trial_ends_at)
        ->not->toBeNull();
});

it('does not overwrite trial_ends_at if it is already set', function (): void {
    $trialEndsAt = now()->addDays(10)->endOfDay()->startOfSecond();

    $team = Team::factory()->create([
        'status' => TeamStatus::TRIAL,
        'trial_ends_at' => $trialEndsAt,
    ]);

    expect($team->refresh()->trial_ends_at)
        ->toEqual($trialEndsAt);
});

it('does not set trial_ends_at for non-trial teams', function (): void {
    $team = Team::factory()->create([
        'status' => TeamStatus::PREMIUM,
        'trial_ends_at' => null,
    ]);

    expect($team->fresh()->trial_ends_at)->toBeNull();
});
