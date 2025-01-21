<?php

declare(strict_types=1);

use App\Livewire\Modals\ViewPriceGroup;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $this->actingAs($user = $this->userWithTeam());

    Livewire::test(ViewPriceGroup::class)
        ->assertStatus(200);
});
