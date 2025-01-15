<?php

declare(strict_types=1);

namespace Tests;

use App\Enums\TeamRole;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function userWithTeam($roleAdmin = true): User
    {
        $user = User::factory()->create();
        $user->teams()->attach($team = Team::factory()->create());
        $user->team()->associate($team)->save();

        setPermissionsTeamId($team->id);

        if ($roleAdmin) {
            $user->assignRole(TeamRole::ADMIN);
        } else {
            $user->assignRole(TeamRole::MEMBER);
        }

        return $user;
    }
}
