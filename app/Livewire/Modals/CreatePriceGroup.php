<?php

declare(strict_types=1);

namespace App\Livewire\Modals;

use App\Livewire\Forms\PriceGroupForm;
use App\Models\PriceGroup;
use Illuminate\View\View;
use LivewireUI\Modal\ModalComponent;

class CreatePriceGroup extends ModalComponent
{
    public ?PriceGroup $priceGroup = null;

    public PriceGroupForm $form;

    public function mount(): void
    {
        if (! is_null($this->priceGroup)) {
            $this->priceGroup->loadCount('prices');
            $this->form->fill($this->priceGroup->toArray());
        }
    }

    public function savePriceGroup(): void
    {
        $this->form->validate();

        if (is_null($this->priceGroup)) {
            PriceGroup::create([
                'name' => $this->form->name,
                'team_id' => auth()->user()->team->id,
            ]);

            flash()->success(__('PriceGroup created'));
        } else {
            $this->priceGroup->update(
                $this->form->only([
                    'name',
                ])
            );

            flash()->success(__('PriceGroup updated'));
        }

        $this->dispatch('price-group-updated');

        $this->dispatch('closeModal');

    }

    public function deletePriceGroup(): void
    {
        $this->priceGroup->loadCount('prices');

        if ($this->priceGroup->prices_count > 0) {
            flash()->error(__('Cannot delete a price group with prices attached.'));

            return;
        }

        $this->priceGroup->delete();

        flash()->success(__('PriceGroup deleted'));

        $this->dispatch('price-group-updated');

        $this->dispatch('closeModal');
    }

    public function render(): View
    {
        return view('livewire.modals.create-price-group');
    }
}
