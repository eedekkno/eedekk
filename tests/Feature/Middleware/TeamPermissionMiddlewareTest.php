<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('aborts the request if the user does not belong to the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create();

    $user->team()->associate($team)->save();

    actingAs($user)
        ->get(route('dashboard'))
        ->assertForbidden();
});
