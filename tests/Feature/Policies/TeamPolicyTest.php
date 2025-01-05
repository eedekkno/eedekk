<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;

beforeEach(function (): void {
    $this->policy = new TeamPolicy;
});

describe('create', function (): void {
    it('allows creating a team', function (): void {
        $user = User::factory()->create();

        expect($this->policy->create($user))->toBeTrue();
    });
});

describe('setCurrentTeam', function (): void {
    it('allows setting current team if user belongs to the team', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        expect($this->policy->setCurrentTeam($user, $team))->toBeTrue();
    });

    it('denies setting current team if user does not belong to the team', function (): void {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        expect($this->policy->setCurrentTeam($user, $team))->toBeFalse();
    });
});

describe('update', function (): void {
    it('allows updating a team if user has permission', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();
        setPermissionsTeamId($team->id);

        $user->givePermissionTo('update team');

        expect($this->policy->update($user, $team))->toBeTrue();
    });

    it('denies updating a team if user lacks permission', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        expect($this->policy->update($user, $team))->toBeFalse();
    });
});

describe('leave', function (): void {
    it('allows leaving a team if user belongs to multiple teams', function (): void {
        $user = User::factory()->has(Team::factory()->count(2))->create();
        $team = $user->teams->first();

        expect($this->policy->leave($user, $team))->toBeTrue();
    });

    it('denies leaving the last team', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        expect($this->policy->leave($user, $team))->toBeFalse();
    });
});

describe('removeTeamMember', function (): void {
    it('allows removing a team member if user has permission', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();
        $member = User::factory()->create();
        $team->users()->attach($member);

        setPermissionsTeamId($team->id);
        $user->givePermissionTo('remove team members');

        expect($this->policy->removeTeamMember($user, $team, $member))->toBeTrue();
    });

    it('denies removing a team member if user is the member', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        expect($this->policy->removeTeamMember($user, $team, $user))->toBeFalse();
    });

    it('denies removing a team member if member does not belong to the team', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();
        $member = User::factory()->create();

        expect($this->policy->removeTeamMember($user, $team, $member))->toBeFalse();
    });
});

describe('inviteToTeam', function (): void {
    it('allows inviting to a team if user has permission', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        setPermissionsTeamId($team->id);
        $user->givePermissionTo('invite to team');

        expect($this->policy->inviteToTeam($user, $team))->toBeTrue();
    });

    it('denies inviting to a team if user does not belong to the team', function (): void {
        $user = User::factory()->create();
        $team = Team::factory()->create();

        expect($this->policy->inviteToTeam($user, $team))->toBeFalse();
    });

    it('denies inviting to a team if user lacks permission', function (): void {
        $user = User::factory()->has(Team::factory())->create();
        $team = $user->teams->first();

        expect($this->policy->inviteToTeam($user, $team))->toBeFalse();
    });
});
