<x-modal-wrapper title="{{ __('Edit pricegroup') }}">
    <ul>
    @foreach($priceGroups as $priceGroup)
        <li class="cursor-pointer" x-on:click="Livewire.dispatch('openModal', { component: 'modals.create-price-group', arguments: { priceGroup: {{ $priceGroup->id }} }})">{{ $priceGroup->name }} ({{ $priceGroup->prices_count }})</li>
    @endforeach
    </ul>
</x-modal-wrapper>
