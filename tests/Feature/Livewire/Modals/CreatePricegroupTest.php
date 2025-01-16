<?php

declare(strict_types=1);

use App\Livewire\Modals\CreatePricegroup;
use App\Models\Pricegroup;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePricegroup::class)
        ->assertStatus(200);
});

it('fills the form when a pricegroup is provided', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $pricegroup = Pricegroup::factory()->for($user->team)->create();

    Livewire::test(CreatePricegroup::class, ['pricegroup' => $pricegroup])
        ->assertSet('form.name', $pricegroup->name);
});

it('creates a new pricegroup', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePricegroup::class)
        ->set('form.name', 'Test group')
        ->call('savePricegroup')
        ->assertDispatched('pricegroup-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('pricegroups', [
        'name' => 'Test group',
        'team_id' => $user->team->id,
    ]);
});

it('updates an existing pricegroup', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $pricegroup = Pricegroup::factory()->for($user->team)->create();

    Livewire::test(CreatePricegroup::class, ['pricegroup' => $pricegroup])
        ->set('form.name', 'Updated group')
        ->call('savePricegroup')
        ->assertDispatched('pricegroup-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('pricegroups', [
        'id' => $pricegroup->id,
        'name' => 'Updated group',
    ]);
});

it('validates required fields', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePricegroup::class)
        ->set('form.name', '')
        ->call('savePricegroup')
        ->assertHasErrors([
            'form.name' => 'required',
        ]);
});
