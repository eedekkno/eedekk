<?php

declare(strict_types=1);

namespace App\Livewire\Modals;

use App\Livewire\Forms\PricegroupForm;
use App\Models\Pricegroup;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class CreatePricegroup extends ModalComponent
{
    public ?Pricegroup $pricegroup = null;

    public PricegroupForm $form;

    public function mount(): void
    {
        if (! is_null($this->pricegroup)) {
            $this->form->fill($this->pricegroup->toArray());
        }
    }

    public function savePricegroup(): void
    {
        $this->form->validate();

        if (is_null($this->pricegroup)) {
            Pricegroup::create([
                'name' => $this->form->name,
                'team_id' => auth()->user()->team->id,
            ]);

            flash()->success(__('Pricegroup created'));
        } else {
            $this->pricegroup->update(
                $this->form->only([
                    'name',
                ])
            );

            flash()->success(__('Pricegroup updated'));
        }

        $this->dispatch('pricegroup-updated');

        $this->dispatch('closeModal');

    }

    public function render(): View
    {
        return view('livewire.modals.create-pricegroup');
    }
}
