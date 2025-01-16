<div>
    <div
        class="mb-5 flex flex-col gap-3 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left"
    >
        <div>
            <h3 class="mb-1 font-semibold">{{ __('Prices') }}</h3>
        </div>
        <div class="flex items-center justify-center gap-2">
            <flux:button x-on:click="Livewire.dispatch('openModal', { component: 'modals.create-pricegroup' })">{{ __('Create pricegroup') }}</flux:button>
            <flux:button x-on:click="Livewire.dispatch('openModal', { component: 'modals.create-price' })" variant="primary">{{ __('Create price') }}</flux:button>
        </div>
    </div>

    <div class="grow border-b border-gray-100 pb-5 dark:border-gray-700">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('Search') }}..." />
    </div>

    <!-- Responsive Table Container -->
    <div
        class="min-w-full overflow-x-auto rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800"
    >
        <!-- Table -->
        <table class="min-w-full whitespace-nowrap align-middle text-sm">
            <!-- Table Header -->
            <thead>
            <tr>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-left font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Name') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-left font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Group') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-right font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Price') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-right font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Actions') }}
                </th>
            </tr>
            </thead>
            <!-- END Table Header -->

            <!-- Table Body -->
            <tbody>

                @foreach ($prices as $price)
                <tr class="even:bg-gray-50 dark:even:bg-gray-900/50">
                    <td class="p-3">
                        <p class="font-medium">{{ $price->name }}</p>
                    </td>
                    <td class="p-3"><span class="cursor-pointer" x-on:click="Livewire.dispatch('openModal', { component: 'modals.create-pricegroup', arguments: { pricegroup: {{ $price->pricegroup->id }} }})">{{ $price->pricegroup->name }}</span></td>
                    <td class="p-3 text-right">
                        {{ Number::currency($price->price, in: 'NOK') }}
                    </td>
                    <td class="p-3 text-right">
                        <flux:button size="xs" x-on:click="Livewire.dispatch('openModal', { component: 'modals.create-price', arguments: { price: {{ $price->id }} }})">Edit</flux:button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <!-- END Table Body -->
        </table>
        @if ($prices->hasPages())
            <div class="grow border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                <div class="text-center dark:text-gray-100">
                    {{ $prices->links() }}
                </div>
            </div>
        @endif
        <!-- END Table -->
    </div>
</div>
