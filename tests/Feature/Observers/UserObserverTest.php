<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\assertDatabaseEmpty;

it('removes all team attachments when deleted', function () {
    $user = User::factory()->create();
    $user->teams()->attach(Team::factory()->create());
    $user->teams()->attach(Team::factory()->create());

    expect($user->teams)
        ->toHaveCount(2);

    $user->delete();

    assertDatabaseEmpty('team_user');
});
