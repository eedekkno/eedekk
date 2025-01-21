<x-modal-wrapper title="{{ is_null($priceGroup) ? __('Create pricegroup') : __('Edit pricegroup') }}">
    <form wire:submit.prevent="savePriceGroup" class="space-y-4">
        <div>
            <x-input-label for="name" value="{{ __('Name') }}" />
            <x-text-input id="name" class="w-full" wire:model="form.name" />
            <x-input-error :messages="$errors->get('form.name')" class="mt-1" />
        </div>

        <div class="flex justify-end gap-4">
            @if(! is_null($priceGroup) && $priceGroup->prices_count === 0)
                <flux:button wire:click="deletePriceGroup" variant="danger">{{ __('Delete') }}</flux:button>
            @endif
            <flux:button type="submit">{{ __('Save') }}</flux:button>
        </div>
    </form>
</x-modal-wrapper>
