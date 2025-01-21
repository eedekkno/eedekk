<?php

declare(strict_types=1);

namespace App\Livewire\Modals;

use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class ViewPriceGroup extends ModalComponent
{
    /** @var string[] */
    protected $listeners = [
        'price-group-updated' => '$refresh',
    ];

    public function render(): View
    {
        return view('livewire.modals.view-price-group', [
            'priceGroups' => auth()->user()->team->pricegroups()->withCount('prices')->orderBy('name')->get(),
        ]);
    }
}
