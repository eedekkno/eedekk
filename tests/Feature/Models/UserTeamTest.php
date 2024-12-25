<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

it('has teams', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());

    expect($user->teams)
        ->toHaveCount(1)
        ->first()->name->toBe($team->name);
});
