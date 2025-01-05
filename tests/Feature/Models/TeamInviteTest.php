<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;

it('has fillable properties', function (): void {
    $fillable = (new TeamInvite)->getFillable();

    expect($fillable)->toBe([
        'team_id',
        'invited_by',
        'email',
        'token',
    ]);
});

it('belongs to a user (invitedBy)', function (): void {
    $user = User::factory()->create();
    $invite = TeamInvite::factory()->create(['invited_by' => $user->id]);

    expect($invite->invitedBy)->toBeInstanceOf(User::class)
        ->and($invite->invitedBy->id)->toBe($user->id);
});

it('belongs to a team', function (): void {
    $team = Team::factory()->create();
    $invite = TeamInvite::factory()->create(['team_id' => $team->id]);

    expect($invite->team)->toBeInstanceOf(Team::class)
        ->and($invite->team->id)->toBe($team->id);
});
