<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('associates the user with the given team and redirects back', function () {
    $user = User::factory()->has(Team::factory()->count(2))->create();
    $team = $user->teams->first();

    actingAs($user)
        ->patch(route('user.setTeam', $team))
        ->assertRedirect();

    expect($user->fresh()->team->id)
        ->toBe($team->id);
});

it('throws validation error for invalid team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    actingAs($user)
        ->patch(route('user.setTeam', $team))
        ->assertForbidden();
});
