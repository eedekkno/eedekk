<?php

declare(strict_types=1);

use App\Livewire\CustomerIndex;
use App\Models\Customer;
use App\Models\Team;
use Livewire\Livewire;

it('renders customer index view', function (): void {
    $user = $this->userWithTeam();

    // Lag noen kunder til teamet
    $customers = Customer::factory(3)->create(['team_id' => $user->team->id]);

    // Logg inn som brukeren
    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertViewIs('livewire.customers.index')
        ->assertSee($customers[0]->name)
        ->assertSee($customers[1]->name)
        ->assertSee($customers[2]->name);
});

it('can paginate the customer list', function (): void {
    $user = $this->userWithTeam();

    $customers = Customer::factory(51)->create(['team_id' => $user->team->id]);

    $customers->sortBy('name');

    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertSee('Pagination');

});

it('displays the create customer button', function (): void {
    $user = $this->userWithTeam();

    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertSee(__('Create customer'))
        ->assertSee(route('customers.create'));
});

it('does not display the edit customer button for members', function (): void {
    $user = $this->userWithTeam(roleAdmin: false);

    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertDontSee(__('Edit'));
});

it('shows customer details in the table', function (): void {
    $user = $this->userWithTeam();

    $customer = Customer::factory()->create(['team_id' => $user->team->id]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertSee($customer->name)
        ->assertSee($customer->address)
        ->assertSee($customer->city)
        ->assertSee($customer->phone)
        ->assertSee($customer->email);
});

it('does not show customers from other teams', function (): void {
    $user = $this->userWithTeam();

    $otherTeam = Team::factory()->create();
    $customerInOtherTeam = Customer::factory()->create(['team_id' => $otherTeam->id]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\CustomerIndex::class)
        ->assertDontSee($customerInOtherTeam->name);
});

it('filters customers based on the search term', function (): void {
    $user = $this->userWithTeam();

    $customer1 = Customer::factory()->create([
        'name' => 'Alice Smith',
        'team_id' => $user->team->id,
    ]);

    $customer2 = Customer::factory()->create([
        'name' => 'Bob Johnson',
        'team_id' => $user->team->id,
    ]);

    $customer3 = Customer::factory()->create([
        'name' => 'Charlie Brown',
        'team_id' => $user->team->id,
    ]);

    // Simuler søk med Livewire
    Livewire::actingAs($user)
        ->test(CustomerIndex::class)
        ->set('search', 'Alice')
        ->assertSee('Alice Smith')
        ->assertDontSee('Bob Johnson')
        ->assertDontSee('Charlie Brown');

    Livewire::actingAs($user)
        ->test(CustomerIndex::class)
        ->set('search', 'Charlie')
        ->assertSee('Charlie Brown')
        ->assertDontSee('Alice Smith')
        ->assertDontSee('Bob Johnson');

    Livewire::actingAs($user)
        ->test(CustomerIndex::class)
        ->set('search', 'Nonexistent')
        ->assertDontSee('Alice Smith')
        ->assertDontSee('Bob Johnson')
        ->assertDontSee('Charlie Brown');
});
