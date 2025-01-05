<?php

declare(strict_types=1);

use App\Enums\TeamStatus;
use App\Models\Team;
use Illuminate\Support\Carbon;

it('returns true if the team status is premium', function (): void {
    $team = Team::factory()->create(['status' => TeamStatus::PREMIUM]);

    expect($team->isPremium())
        ->toBeTrue();
});

it('returns false if the team status is not premium', function (): void {
    $team = Team::factory()->create(['status' => TeamStatus::TRIAL]);

    expect($team->isPremium())
        ->toBeFalse();
});

it('returns true if the premium subscription is overdue', function (): void {
    Carbon::setTestNow('2025-01-01');

    $team = Team::factory()->create([
        'status' => TeamStatus::PREMIUM,
        'subscription_ends_at' => now()->subDays(21), // Overdue by 1 day
    ]);

    expect($team->isOverdue())
        ->toBeTrue();
});

it('returns false if the premium subscription is not overdue', function (): void {
    Carbon::setTestNow('2025-01-01');

    $team = Team::factory()->create([
        'status' => TeamStatus::PREMIUM,
        'subscription_ends_at' => now()->subDays(19),
    ]);

    expect($team->isOverdue())
        ->toBeFalse();
});

it('returns false if the team is not premium', function (): void {
    $team = Team::factory()->create(['status' => TeamStatus::TRIAL]);

    expect($team->isOverdue())
        ->toBeFalse();
});

it('calculates the number of trial days left correctly', function (): void {
    Carbon::setTestNow('2025-01-01');

    $team = Team::factory()->create([
        'trial_ends_at' => now()->addDays(10),
    ]);

    expect($team->freeTrialDaysLeft())
        ->toEqual(10);
});

it('returns 0 if the trial has ended', function (): void {
    Carbon::setTestNow('2025-01-01');

    $team = Team::factory()->create([
        'trial_ends_at' => now()->subDay(),
    ]);

    expect($team->freeTrialDaysLeft())
        ->toEqual(-1);
});
