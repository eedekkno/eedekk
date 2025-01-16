<div>
    <div
        class="mb-5 flex flex-col gap-3 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left"
    >
        <div>
            <h3 class="mb-1 font-semibold">{{ __('Customers') }}</h3>
        </div>
        <div class="flex items-center justify-center gap-2">
            @can('createCustomer', [auth()->user()->team])
                <flux:button href="{{ route('customers.create') }}" variant="primary">{{ __('Create customer') }}</flux:button>
            @endcan
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
                    {{ __('Address') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-left font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('City') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-left font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Phone') }}
                </th>
                <th
                    class="bg-gray-100/75 px-3 py-4 text-left font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Email') }}
                </th>

                <th
                    class="bg-gray-100/75 px-3 py-4 text-right font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                >
                    {{ __('Type') }}
                </th>
                @can('createCustomer', [auth()->user()->team])
                    <th
                        class="bg-gray-100/75 px-3 py-4 text-center font-semibold text-gray-900 dark:bg-gray-700/25 dark:text-gray-50"
                    >
                        Actions
                    </th>
                @endcan
            </tr>
            </thead>
            <!-- END Table Header -->

            <!-- Table Body -->
            <tbody>
            @foreach ($customers as $customer)
                <tr class="even:bg-gray-50 dark:even:bg-gray-900/50" wire:key="{{ $customer->id }}">
                    <td class="p-3">
                        <p class="font-medium">{{ $customer->name }}</p>
                    </td>
                    <td class="p-3">{{ $customer->address }}</td>
                    <td class="p-3">
                        {{ $customer->city }}
                    </td>
                    <td class="p-3">{{ $customer->phone }}</td>
                    <td class="p-3">{{ $customer->email }}</td>
                    <td class="p-3 text-right"><flux:badge :color="$customer->type->color()" variant="pill" size="sm">{{ $customer->type->label() }}</flux:badge></td>
                    @can('updateCustomer', [auth()->user()->team, $customer])
                        <td class="p-3 text-center">
                            <flux:button size="sm" href="{{ route('customers.edit', $customer) }}">{{ __('Edit') }}</flux:button>
                        </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
            <!-- END Table Body -->
        </table>
        @if ($customers->hasPages())
            <div class="grow border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                <div class="text-center dark:text-gray-100">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
        <!-- END Table -->
    </div>
</div>
