<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Http\Middleware\TeamPermissionMiddleware;
use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('renders the team creation view', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->get(route('team.create'))
        ->assertStatus(200)
        ->assertViewIs('team.create');
});

it('can update team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->patch(route('team.update', $user->team), [
            'name' => $name = 'A new team name',
        ])
        ->assertRedirect();

    expect($user->fresh()->team->name)
        ->toBe($name);
});

it('can not update if not in team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $anotherUser = User::factory()->create();
    $anotherUser->teams()->attach($team = Team::factory()->create());
    $anotherUser->team()->associate($team)->save();

    actingAs($user)
        ->patch(route('team.update', $anotherUser->team), [
            'name' => 'A new team name',
        ])
        ->assertForbidden();
});

it('gives the admin role to the team it creates', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('team.store'), [
            'name' => 'A new team name',
        ])
        ->assertRedirect();

    $team = Team::whereName('A new team name')->firstOrFail();

    setPermissionsTeamId($team->id);

    expect($user->fresh()->hasRole(TeamRole::ADMIN))
        ->toBeTrue();
});

it('can not update a team without permission', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach(
        $anotherTeam = Team::factory()->create()
    );

    setPermissionsTeamId($anotherTeam->id);

    $user->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->withoutMiddleware(TeamPermissionMiddleware::class)
        ->patch(route('team.update', $anotherTeam), [
            'name' => 'A new team name',
        ])
        ->assertForbidden();
});

it('can leave a team', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach(Team::factory()->create());
    $user->teams()->attach($teamToLeave = Team::factory()->create());
    $user->team()->associate($teamToLeave)->save();

    $teamToLeave = $user->team;

    actingAs($user)
        ->delete(route('team.leave', $teamToLeave))
        ->assertRedirect('dashboard');

    expect($user->fresh()->teams->contains($teamToLeave->id))
        ->toBeFalse()
        ->and($user->fresh()->team->id)
        ->not->toEqual($teamToLeave->id);
});

it('can not leave team if we have one team remaining', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    actingAs($user)
        ->delete(route('team.leave', $user->team))
        ->assertForbidden();
});

it('can not leave a team that we don\'t belong to', function (): void {
    $user = User::factory()->create();
    $anotherUser = User::factory()->create();
    $anotherUser->teams()->attach($team = Team::factory()->create());
    $anotherUser->team()->associate($team)->save();

    actingAs($user)
        ->delete(route('team.leave', $anotherUser->fresh()->team))
        ->assertForbidden();
});

it('should show a list of members for admins', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->fresh()->team->users()->attach(
        $members = User::factory()->times(2)->create()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->get('team')
        ->assertSeeText($members->pluck('email')->toArray())
        ->assertSeeText($members->pluck('name')->toArray());
});

it('should show a list of members for users', function (): void {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    $user->fresh()->team->users()->attach(
        $members = User::factory()->times(2)->create()
    );

    setPermissionsTeamId($user->team->id);

    $user->assignRole(TeamRole::MEMBER);

    actingAs($user)
        ->get('team')
        ->assertSeeText($members->pluck('email')->toArray())
        ->assertSeeText($members->pluck('name')->toArray());
});
