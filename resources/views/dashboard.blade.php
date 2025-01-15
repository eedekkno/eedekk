<x-app-layout>
                <div class="p-4">
                    <!-- Titles -->
                    <div class="grid grid-cols-6 gap-4 bg-gray-200 p-4 rounded-t-lg">
                        <div class="font-bold text-gray-700">Regnr</div>
                        <div class="font-bold text-gray-700">Customer Name</div>
                        <div class="font-bold text-gray-700">Location</div>
                        <div class="font-bold text-gray-700">Car Model</div>
                        <div class="font-bold text-gray-700">Description</div>
                        <div class="font-bold text-gray-700 text-right">Season</div>
                    </div>

                    <!-- Rows -->
                    <div class="space-y-2">
                        <!-- Single Row -->
                        <div class="grid grid-cols-6 gap-4 bg-gray-100 p-4 rounded-lg shadow-md">
                            <div class="text-gray-600">AB12345</div>
                            <div class="text-gray-600">John Doe</div>
                            <div class="text-gray-600">Garage 12</div>
                            <div class="text-gray-600">Tesla Model S</div>
                            <div class="text-gray-600">Winter tires</div>
                            <div class="text-gray-600 flex justify-end items-center"><flux:icon.sun/></div>
                            <!-- Action Buttons -->
                            <div class="col-span-6 flex justify-end items-center space-x-2 mt-2">
                                <flux:button icon="pencil-square" variant="ghost">{{ __('Edit') }}</flux:button>
                                <flux:button icon="printer" variant="ghost">{{ __('Print') }}</flux:button>
                                <flux:button icon="sparkles" variant="ghost">{{ __('Washed') }}</flux:button>
                                <flux:button icon="arrow-path" variant="primary">
                                    {{ __('Change season') }} (500 NOK)
                                </flux:button>
                            </div>
                        </div>
                        <!-- Additional Rows -->
                        <div class="grid grid-cols-6 gap-4 bg-gray-100 p-4 rounded-lg shadow-md">
                            <div class="text-gray-600">AB12345</div>
                            <div class="text-gray-600">John Doe</div>
                            <div class="text-gray-600">Garage 12</div>
                            <div class="text-gray-600">Tesla Model S</div>
                            <div class="text-gray-600">Winter tires</div>
                            <div class="text-gray-600 flex justify-end items-center"><flux:icon.thermometer-snowflake/></div>
                            <!-- Action Buttons -->
                            <div class="col-span-6 flex justify-end items-center space-x-2 mt-2">
                                <flux:button icon="pencil-square" variant="ghost">{{ __('Edit') }}</flux:button>
                                <flux:button icon="printer" variant="ghost">{{ __('Print') }}</flux:button>
                                <flux:button icon="sparkles" variant="ghost">{{ __('Washed') }}</flux:button>
                                <flux:button icon="arrow-path" variant="primary">
                                    {{ __('Change season') }} (500 NOK)
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
</x-app-layout>
