<x-modal-wrapper title="{{ __('Create pricegroup') }}">
    <form wire:submit.prevent="savePricegroup" class="space-y-4">
        <div>
            <x-input-label for="name" value="{{ __('Name') }}" />
            <x-text-input id="name" class="w-full" wire:model="form.name" />
            <x-input-error :messages="$errors->get('form.name')" class="mt-1" />
        </div>

        <div class="flex justify-end">
            <flux:button type="submit">{{ __('Save') }}</flux:button>
        </div>
    </form>
</x-modal-wrapper>
