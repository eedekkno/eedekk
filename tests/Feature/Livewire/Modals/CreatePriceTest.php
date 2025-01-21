<?php

declare(strict_types=1);

use App\Livewire\Modals\CreatePrice;
use App\Models\Price;
use App\Models\PriceGroup;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePrice::class)
        ->assertStatus(200);
});

it('fills the form when a price is provided', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $price = Price::factory()->for($user->team)->create();

    Livewire::test(CreatePrice::class, ['price' => $price])
        ->assertSet('form.name', $price->name)
        ->assertSet('form.price', $price->price)
        ->assertSet('form.price_group_id', $price->price_group_id);
});

it('creates a new price without decimal', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePrice::class)
        ->set('form.name', 'Test Price')
        ->set('form.price', '123')
        ->set('form.price_group_id', $priceGroup->id)
        ->call('savePrice')
        ->assertDispatched('price-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('prices', [
        'name' => 'Test Price',
        'price' => 12300,
        'price_group_id' => $priceGroup->id,
        'team_id' => $user->team->id,
    ]);
});

it('creates a new price with comma', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePrice::class)
        ->set('form.name', 'Test Price')
        ->set('form.price', '123,45')
        ->set('form.price_group_id', $priceGroup->id)
        ->call('savePrice')
        ->assertDispatched('price-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('prices', [
        'name' => 'Test Price',
        'price' => 12345,
        'price_group_id' => $priceGroup->id,
        'team_id' => $user->team->id,
    ]);
});

it('creates a new price with dot', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();

    Livewire::test(CreatePrice::class)
        ->set('form.name', 'Test Price')
        ->set('form.price', '123.45')
        ->set('form.price_group_id', $priceGroup->id)
        ->call('savePrice')
        ->assertDispatched('price-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('prices', [
        'name' => 'Test Price',
        'price' => 12345,
        'price_group_id' => $priceGroup->id,
        'team_id' => $user->team->id,
    ]);
});

it('updates an existing price', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $priceGroup = PriceGroup::factory()->for($user->team)->create();
    $price = Price::factory()->for($user->team)->create([
        'name' => 'Old Price',
        'price' => 5000,
        'price_group_id' => $priceGroup->id,
    ]);

    Livewire::test(CreatePrice::class, ['price' => $price])
        ->set('form.name', 'Updated Price')
        ->set('form.price', '123,45')
        ->set('form.price_group_id', $price->price_group_id)
        ->call('savePrice')
        ->assertDispatched('price-updated')
        ->assertDispatched('closeModal');

    $this->assertDatabaseHas('prices', [
        'id' => $price->id,
        'name' => 'Updated Price',
        'price' => 12345,
        'price_group_id' => $price->price_group_id,
    ]);
});

it('validates required fields', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(CreatePrice::class)
        ->set('form.name', '')
        ->set('form.price', '')
        ->set('form.price_group_id', '')
        ->call('savePrice')
        ->assertHasErrors([
            'form.name' => 'required',
            'form.price' => 'required',
            'form.price_group_id' => 'required',
        ]);
});
