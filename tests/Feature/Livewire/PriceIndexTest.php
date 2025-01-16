<?php

declare(strict_types=1);

use App\Livewire\PriceIndex;
use App\Models\Price;

it('renders the price index component correctly', function (): void {
    $user = $this->userWithTeam();

    $user->team->pricegroups()->create(['name' => 'Group A']);
    $user->team->pricegroups()->first()->prices()->create(['name' => 'Test Price', 'price' => 5000, 'team_id' => $user->team->id]);

    Livewire::actingAs($user)
        ->test(PriceIndex::class)
        ->assertSee(__('Prices'))
        ->assertSee(__('Create pricegroup'))
        ->assertSee(__('Create price'))
        ->assertSee('Test Price')
        ->assertSee('Group A')
        ->assertSee('5,000.00')
        ->assertSee('NOK');
});

it('filters prices by search query', function (): void {
    $user = $this->userWithTeam();

    $groupA = $user->team->pricegroups()->create(['name' => 'Group A']);
    $groupB = $user->team->pricegroups()->create(['name' => 'Group B']);

    Price::factory()->create(['name' => 'Price A', 'pricegroup_id' => $groupA->id, 'price' => 3000, 'team_id' => $user->team->id]);
    Price::factory()->create(['name' => 'Price B', 'pricegroup_id' => $groupB->id, 'price' => 4000, 'team_id' => $user->team->id]);

    Livewire::actingAs($user)
        ->test(PriceIndex::class)
        ->set('search', 'Price A')
        ->assertSee('Price A')
        ->assertDontSee('Price B');

    Livewire::actingAs($user)
        ->test(PriceIndex::class)
        ->set('search', 'Group B')
        ->assertSee('Price B')
        ->assertDontSee('Price A');
});

it('paginates results correctly', function (): void {
    $user = $this->userWithTeam();

    $groupA = $user->team->pricegroups()->create(['name' => 'Group A']);

    Price::factory()->count(30)->create(['team_id' => $user->team->id, 'pricegroup_id' => $groupA->id]);

    Livewire::actingAs($user)
        ->test(PriceIndex::class)
        ->assertSee('Pagination')
        ->call('nextPage')
        ->assertSee('Pagination');
});
