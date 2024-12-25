<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

it('sets the current team to the personal team', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    expect($user->current_team_id)
        ->toBe($user->teams->first()->id)
        ->and($user->team->id)
        ->toBe($user->teams->first()->id);
});
