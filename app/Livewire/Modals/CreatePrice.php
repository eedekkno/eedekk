<?php

declare(strict_types=1);

namespace App\Livewire\Modals;

use App\Livewire\Forms\PriceForm;
use App\Models\Price;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class CreatePrice extends ModalComponent
{
    public ?Price $price = null;

    public PriceForm $form;

    public function mount(): void
    {
        if (! is_null($this->price)) {
            $this->form->fill($this->price->toArray());
        }
    }

    public function savePrice(): void
    {
        $this->form->validate();

        $this->form->price = is_string($this->form->price) ? str_replace(',', '.', $this->form->price) : $this->form->price;

        if (is_null($this->price)) {
            Price::create([
                'name' => $this->form->name,
                'pricegroup_id' => $this->form->pricegroup_id,
                'team_id' => auth()->user()->team->id,
                'price' => $this->form->price,
            ]);

            flash()->success(__('Price created.'));

        } else {
            $this->price->update(
                $this->form->only([
                    'name',
                    'pricegroup_id',
                    'price',
                ])
            );
            flash()->success(__('Price updated.'));
        }

        $this->dispatch('price-updated');

        $this->dispatch('closeModal');
    }

    public function render(): View
    {
        return view('livewire.modals.create-price', [
            'pricegroups' => auth()->user()->team->pricegroups()->orderBy('name')->get(),
        ]);
    }
}
