<?php

declare(strict_types=1);

use App\Enums\TeamRole;
use App\Mail\TeamInvitation;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

afterEach(function () {
    Str::createRandomStringsNormally();
});

it('creates an invite', function () {
    Mail::fake();

    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    Str::createRandomStringsUsing(fn () => 'abc');

    actingAs($user)
        ->post(route('team.invites.store', $user->team), [
            'email' => $email = fake()->safeEmail(),
        ])
        ->assertRedirect();

    Mail::assertSent(TeamInvitation::class, function (TeamInvitation $mail) use ($email) {
        return $mail->hasTo($email) &&
            $mail->teamInvite->token === 'abc';
    });

    assertDatabaseHas('team_invites', [
        'team_id' => $user->team->id,
        'invited_by' => $user->id,
        'email' => $email,
        'token' => 'abc',
    ]);
});

it('requires an email address', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->post(route('team.invites.store', $user->team))
        ->assertSessionHasErrors('email');
});

it('requires an valid email address', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    actingAs($user)
        ->post(route('team.invites.store', $user->team), [
            'email' => 'abc',
        ])
        ->assertSessionHasErrors('email');
});

it('fails to create invite if email already used', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    TeamInvite::factory()->create([
        'team_id' => $user->team->id,
        'invited_by' => $user->id,
        'email' => $email = fake()->safeEmail(),
    ]);

    actingAs($user)
        ->post(route('team.invites.store', $user->team), [
            'email' => $email,
        ])
        ->assertInvalid();
});
it('creates invite if email already invited to another team', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();
    setPermissionsTeamId($team->id);
    $user->assignRole(TeamRole::ADMIN);

    $anotherTeam = Team::factory()->create();

    TeamInvite::factory()->create([
        'team_id' => $anotherTeam->id,
        'invited_by' => $user->id,
        'email' => $email = fake()->safeEmail(),
    ]);

    actingAs($user)
        ->post(route('team.invites.store', $user->team), [
            'email' => $email,
        ])
        ->assertValid();
});

it('can revoke an invite', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::ADMIN);

    $invite = TeamInvite::factory()->create([
        'team_id' => $user->team->id,
    ]);

    actingAs($user)
        ->delete(route('team.invites.destroy', [$user->team, $invite]))
        ->assertRedirect('team');

    assertDatabaseMissing('team_invites', [
        'team_id' => $user->team->id,
        'token' => $invite->token,
        'email' => $invite->email,
    ]);
});

it('can not revoke an invite without permission', function () {
    $user = User::factory()->create();
    $user->teams()->attach($team = Team::factory()->create());
    $user->team()->associate($team)->save();

    setPermissionsTeamId($team->id);

    $user->assignRole(TeamRole::MEMBER);

    $invite = TeamInvite::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->delete(route('team.invites.destroy', [$user->team, $invite]))
        ->assertForbidden();
});

it('fails to accept invite if route is not signed', function () {
    $invite = TeamInvite::factory()
        ->for(Team::factory()->create())
        ->create();

    $acceptingUser = User::factory()->create();

    actingAs($acceptingUser)
        ->get('team/invites/accept?token='.$invite->token)
        ->assertForbidden();
});

it('can accept an invite', function () {
    $invite = TeamInvite::factory()
        ->for(Team::factory()->create())
        ->create();

    $acceptingUser = User::factory()->create();

    actingAs($acceptingUser)
        ->withoutMiddleware(ValidateSignature::class)
        ->get('team/invites/accept?token='.$invite->token)
        ->assertRedirect('/dashboard');

    expect($acceptingUser->teams->contains($invite->team))
        ->toBeTrue()
        ->and($acceptingUser->hasRole(TeamRole::MEMBER))
        ->toBeTrue()
        ->and($acceptingUser->team->id)
        ->toBe($invite->team_id);

    assertDatabaseMissing('team_invites', [
        'team_id' => $invite->team_id,
        'token' => $invite->token,
        'email' => $invite->email,
    ]);
});
