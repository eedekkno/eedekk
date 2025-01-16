<x-modal-wrapper title="{{ __('Create price') }}">
    <form wire:submit.prevent="savePrice" class="space-y-4">
        <div>
            <flux:input wire:model="form.name" label="{{ __('Name') }}" />
        </div>

        <div>
            <flux:input wire:model.live="form.price" label="{{ __('Price') }}" />
        </div>

        <div>
            <flux:select wire:model="form.pricegroup_id" label="{{ __('Pricegroup') }}">
                @foreach ($pricegroups as $pricegroup)
                    <flux:option value="{{ $pricegroup->id }}">
                        {{ $pricegroup->name }}
                    </flux:option>
                @endforeach
            </flux:select>
        </div>

        <div class="flex justify-end">
            <flux:button type="submit">{{ __('Save') }}</flux:button>
        </div>
    </form>
</x-modal-wrapper>
