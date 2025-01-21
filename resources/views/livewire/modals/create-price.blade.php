<x-modal-wrapper title="{{ is_null($price) ? __('Create price') : __('Edit price') }}">
    <form wire:submit.prevent="savePrice" class="space-y-4">
        <div>
            <flux:input wire:model="form.name" label="{{ __('Name') }}" autofocus />
        </div>

        <div>
            <flux:input wire:model.live="form.price" label="{{ __('Price') }}" />
        </div>

        <div>
            <flux:select wire:model="form.price_group_id" label="{{ __('PriceGroup') }}">
                @foreach ($priceGroups as $priceGroup)
                    <flux:option value="{{ $priceGroup->id }}">
                        {{ $priceGroup->name }}
                    </flux:option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex justify-end">
            <flux:button type="submit">{{ __('Save') }}</flux:button>
        </div>
    </form>
</x-modal-wrapper>
