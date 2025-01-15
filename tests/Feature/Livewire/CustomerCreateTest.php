<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Team;
use HelgeSverre\Telefonkatalog\Data\Person;
use Livewire\Livewire;

it('can create a customer', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(\App\Livewire\CustomerCreate::class)
        ->set('form.name', 'Test Kunde')
        ->set('form.address', 'Testveien 123')
        ->set('form.zip', '1234')
        ->set('form.city', 'Testby')
        ->set('form.phone', '12345678')
        ->set('form.email', 'test@example.com')
        ->set('form.type', 'private')
        ->call('createCustomer')
        ->assertRedirect(route('customers.index'));

    $this->assertDatabaseHas('customers', [
        'name' => 'Test Kunde',
        'address' => 'Testveien 123',
        'zip' => '1234',
        'city' => 'Testby',
        'phone' => '12345678',
        'email' => 'test@example.com',
        'team_id' => $user->team->id,
    ]);
});

it('can update a customer', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $customer = Customer::factory()->create(['team_id' => $user->team->id]);

    Livewire::test(\App\Livewire\CustomerCreate::class, ['customer' => $customer])
        ->set('form.name', 'Oppdatert Kunde')
        ->set('form.address', 'Oppdatert Adresse')
        ->call('createCustomer')
        ->assertRedirect(route('customers.index'));

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Oppdatert Kunde',
        'address' => 'Oppdatert Adresse',
    ]);
});

it('can not update a customer if no permission', function (): void {
    $this->actingAs($user = $this->userWithTeam(roleAdmin: false));

    $customer = Customer::factory()->create(['team_id' => $user->team->id]);

    Livewire::test(\App\Livewire\CustomerCreate::class, ['customer' => $customer])
        ->assertForbidden();
});

it('can not update a customer that belongs to another team', function (): void {
    $this->actingAs($this->userWithTeam());

    $anotherTeam = Team::factory()->create();

    $customer = Customer::factory()->create(['team_id' => $anotherTeam->id]);

    Livewire::test(\App\Livewire\CustomerCreate::class, ['customer' => $customer])
        ->assertForbidden();
});

it('can select a person from the list', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    // Mock en liste over personer
    $mockedPersons = [
        new Person(
            phone: '12345678',
            name: 'John Doe',
            address: '123 Main Street',
            city: 'Oslo',
            postalCode: '1234',
        ),
        new Person(
            phone: '87654321',
            name: 'Jane Doe',
            address: '456 Elm Street',
            city: 'Bergen',
            postalCode: '5678',
        ),
    ];

    Livewire::test(\App\Livewire\CustomerCreate::class)
        ->set('persons', $mockedPersons)
        ->call('selectPerson', 0)
        ->assertSet('form.name', 'John Doe')
        ->assertSet('form.address', '123 Main Street')
        ->assertSet('form.phone', '12345678')
        ->assertSet('form.zip', '1234')
        ->assertSet('form.city', 'Oslo')
        ->assertSet('persons', null);
});

it('shows error if person index is invalid', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $mockedPersons = [
        new Person(
            phone: '12345678',
            name: 'John Doe',
            address: '123 Main Street',
            city: 'Oslo',
            postalCode: '1234',
        ),
    ];

    Livewire::test(\App\Livewire\CustomerCreate::class)
        ->set('persons', $mockedPersons)
        ->call('selectPerson', 5)
        ->assertHasErrors(['form.name' => 'Person not found at index 5']);
});

it('can reset the persons list', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    $mockedPersons = [
        new Person(
            phone: '12345678',
            name: 'John Doe',
            address: '123 Main Street',
            city: 'Oslo',
            postalCode: '1234',
        ),
        new Person(
            phone: '87654321',
            name: 'Jane Doe',
            address: '456 Elm Street',
            city: 'Bergen',
            postalCode: '5678',
        ),
    ];

    Livewire::test(\App\Livewire\CustomerCreate::class)
        ->set('persons', $mockedPersons)
        ->call('resetPersons')
        ->assertSet('persons', null);
});
