<?php

declare(strict_types=1);

use App\Livewire\Modals\CreatePriceGroup;
use App\Models\Price;
use App\Models\PriceGroup;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePriceGroup::class)
        ->assertStatus(200);
});

it('fills the form when a price group is provided', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePriceGroup::class, ['priceGroup' => $priceGroup])
        ->assertSet('form.name', $priceGroup->name);
});

it('creates a new pricegroup', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePriceGroup::class)
        ->set('form.name', 'Test group')
        ->call('savePriceGroup')
        ->assertDispatched('price-group-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('price_groups', [
        'name' => 'Test group',
        'team_id' => $user->team->id,
    ]);
});

it('updates an existing price group', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePriceGroup::class, ['priceGroup' => $priceGroup])
        ->set('form.name', 'Updated group')
        ->call('savePriceGroup')
        ->assertDispatched('price-group-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('price_groups', [
        'id' => $priceGroup->id,
        'name' => 'Updated group',
    ]);
});

it('validates required fields', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePriceGroup::class)
        ->set('form.name', '')
        ->call('savePriceGroup')
        ->assertHasErrors([
            'form.name' => 'required',
        ]);
});

it('deletes the price group if its empty', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePriceGroup::class, ['priceGroup' => $priceGroup])
        ->call('deletePriceGroup');

    $this->assertDatabaseMissing('price_groups', [
        'id' => $priceGroup->id,
    ]);
});

it('does not delete the price group if it contains a price', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Price::factory()->for($user->team)->create([
        'price_group_id' => $priceGroup->id
    ]);

    Livewire::test(CreatePriceGroup::class, ['priceGroup' => $priceGroup])
        ->call('deletePriceGroup');

    $this->assertDatabaseHas('price_groups', [
        'id' => $priceGroup->id,
    ]);
});
