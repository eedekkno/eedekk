<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Http\Middleware\TeamPermissionMiddleware;
use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('can remove a member from the team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);

    $anotherUser = User::factory()->create();
    $anotherUser->teams()->attach($team);
    $anotherUser->team()->associate($team)->save();
    $anotherUser->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->delete(route('team.members.destroy', [$user->team, $anotherUser]))
        ->assertRedirect();

    expect($user->fresh()->team->users->contains($anotherUser))
        ->toBeFalse()
        ->and($anotherUser->fresh()->current_team_id)
        ->not->toEqual($user->team->id)
        ->and($anotherUser->fresh()->roles)
        ->toHaveCount(0);
});

it('can not remove a member from the team without permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);

    $anotherUser = User::factory()->create();
    $anotherUser->teams()->attach($team);
    $anotherUser->team()->associate($team)->save();

    setPermissionsTeamId($anotherUser->team->id);

    $anotherUser->assignRole(TeamRole::MEMBER);

    actingAs($anotherUser)
        ->withoutMiddleware(TeamPermissionMiddleware::class)
        ->delete(route('team.members.destroy', [$anotherUser->team, $user]))
        ->assertForbidden();
});

it('can not remove self from team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->delete(route('team.members.destroy', [$user->team, $user]))
        ->assertForbidden();
});

it('updates a role', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->team->users()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);
    $member->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->patch(route('team.members.update', [$user->team, $member]), [
            'role' => TeamRole::ADMIN->value,
        ])
        ->assertRedirect();

    expect($member->hasRole(TeamRole::ADMIN))
        ->toBeTrue()
        ->and($member->roles->count())
        ->toBe(1);
});

it('only updates role if provided', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->team->users()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);
    $member->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->patch(route('team.members.update', [$user->team, $member]), [
            //
        ])
        ->assertRedirect();

    expect($member->hasRole(TeamRole::MEMBER))
        ->toBeTrue()
        ->and($member->roles->count())
        ->toBe(1);
});

it('does not update the role if no permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->team->users()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::MEMBER);
    $member->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->patch(route('team.members.update', [$user->team, $member]), [
            'role' => TeamRole::ADMIN->value,
        ])
        ->assertForbidden();

});

it('does not update the user if they are not in the team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($user->team->id);
    $user->assignRole(TeamRole::ADMIN);

    $anotherUser = User::factory()->create();
    $anotherUser->teams()->attach($anotherTeam = Team::factory()->create());
    $anotherUser->team()->associate($anotherTeam)->save();

    setPermissionsTeamId($anotherUser->team->id);
    $anotherUser->assignRole(TeamRole::ADMIN);

    actingAs($anotherUser)
        ->patch(route('team.members.update', [$anotherUser->team, $user]), [
            'role' => TeamRole::MEMBER->value,
        ])
        ->assertForbidden();
});

it('validates the role to make sure it exists', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->team->users()->attach(
        $member = User::factory()->createQuietly()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);
    $member->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->patch(route('team.members.update', [$user->team, $member]), [
            'role' => 'some wrong role',
        ])
        ->assertInvalid()
        ->assertSessionHasErrors(['role']);
});
