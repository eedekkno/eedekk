<?php

declare(strict_types=1);

use App\Models\Team;

it('loads the teams on mount', function (): void {
    $user = $this->userWithTeam();
    $teams = $user->teams;

    Livewire::actingAs($user)
        ->test(\App\Livewire\Components\ChangeTeamDropdown::class)
        ->assertSet('teams', $teams);
});

it('can change to an allowed team', function (): void {
    $user = $this->userWithTeam();
    $user->teams()->attach($anotherTeam = Team::factory()->create());

    Livewire::actingAs($user)
        ->test(\App\Livewire\Components\ChangeTeamDropdown::class)
        ->call('changeTeam', $anotherTeam)
        ->assertDispatched('teamChanged');

    $this->assertTrue($user->team->is($anotherTeam));
});

it('cannot change to a team the user does not belong to', function (): void {
    $user = $this->userWithTeam();
    $anotherTeam = Team::factory()->create();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Components\ChangeTeamDropdown::class)
        ->call('changeTeam', $anotherTeam)
        ->assertStatus(403);
})->todo('Fix this, "No query result for model Team');

it('renders the dropdown correctly', function (): void {
    $user = $this->userWithTeam();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Components\ChangeTeamDropdown::class)
        ->assertSee($user->team->name)
        ->assertSeeInOrder($user->teams->pluck('name')->toArray());
});
