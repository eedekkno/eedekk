@php use App\Enums\CustomerType; @endphp
    <div>
        <form wire:submit="createCustomer">
            <div
                class="mb-5 flex gap-3 flex-row items-center justify-between text-left"
            >
                <div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        <h1 class="text-2xl font-semibold">{{ __('Create customer') }}</h1>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-4">
                    @if (!is_null($customer))
                        <flux:button variant="filled" icon="printer" href="" target="_new"></flux:button>
                        <flux:button variant="danger" x-on:click="$wire.dispatch('openModal', { component: 'modals.delete-order', arguments: { order: {{ $customer->id }}} })">
                            {{ __('Delete') }}
                        </flux:button>

                    @endif

                    <flux:button href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}">
                        {{ __('Back') }}
                    </flux:button>

                    <flux:button variant="primary" type="submit">
                        @if (is_null($customer))
                            {{ __('Create customer') }}
                        @else
                            {{ __('Update customer') }}
                        @endif
                    </flux:button>

                </div>
            </div>


            <div class="min-w-full overflow-x-auto md:gap-4">
                <div class="w-full">
                    <!-- Cards: Simple -->
                    <div
                        class="flex flex-col overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800 dark:text-gray-100 mb-4 md:mb-8">
                        <!-- Card Header -->
                        <div class="bg-blue-500 text-white px-5 py-2 dark:bg-blue-700/50">
                            <h3 class="font-medium">{{ __('Customer') }}</h3>
                        </div>
                        <!-- END Card Header -->

                        <!-- Card Body -->
                        <div class="grow p-5">
                            <div class="mb-2">
                                <flux:input.group label="{{ __('Name') }}">
                                    <flux:input wire:model="form.name"
                                    />

                                    <flux:button icon="magnifying-glass" wire:click="lookupPhone('name')"></flux:button>
                                </flux:input.group>
                            </div>
                            <div class="mb-2">
                                <flux:input label="{{ __('Address') }}"
                                            wire:model="form.address"
                                />
                            </div>
                            <div class="flex flex-col md:flex-row items-center gap-4">

                                <div class="mb-2 w-full md:w-1/4">
                                    <flux:input label="{{ __('Zip') }}"
                                                wire:model="form.zip"
                                    />
                                </div>
                                <div class="mb-2 w-full md:w-3/4">
                                    <flux:input label="{{ __('Postal City') }}"
                                                wire:model="form.city"
                                    />
                                </div>
                            </div>

                            <div class="mb-2">
                                <flux:input.group label="{{ __('Phone') }}">
                                    <flux:input wire:model.live="form.phone"
                                                wire:keydown.enter="lookupPhone('phone')"
                                                @keydown.enter.prevent="lookupPhone('phone')"
                                    />

                                    <flux:button icon="magnifying-glass" wire:click="lookupPhone('phone')"></flux:button>
                                </flux:input.group>
                            </div>
                            @if (! is_null($persons))
                                <div class="relative w-full bg-white shadow-lg border border-gray-300 rounded-md mt-2 dark:border-gray-600 dark:bg-gray-800 dark:placeholder-gray-400">
                                    <button
                                        type="button"
                                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 focus:outline-none"
                                        wire:click="resetPersons"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    <div class="p-4">
                                        @forelse ($persons as $index => $person)
                                            <div class="mb-2 cursor-pointer" wire:click="selectPerson({{ $index }})"
                                                 wire:key="{{ $index }}">
                                                <div class="font-bold">{{ $person->name ?? null }}</div>
                                                <div>{{ $person->address ?? null }}</div>
                                                <div>{{ $person->postalCode ?? null }} {{ $person->city ?? null }}</div>
                                                <div>{{ $person->phone ?? null }}</div>
                                                <div class="text-xs">{{ $person->source ? 'Source: ' . $person->source : '' }}</div>
                                            </div>
                                            @if (!$loop->last)
                                                <div class="my-1 flex items-center">
                                          <span
                                              aria-hidden="true"
                                              class="h-0.5 grow rounded bg-gray-200 dark:bg-gray-700/75"
                                          ></span>
                                                    <svg
                                                        class="hi-mini hi-plus-circle mx-3 inline-block size-5 text-gray-600 dark:text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                        aria-hidden="true"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    <span
                                                        aria-hidden="true"
                                                        class="h-0.5 grow rounded bg-gray-200 dark:bg-gray-700/75"
                                                    ></span>
                                                </div>
                                            @endif
                                        @empty
                                            <span>Ingen resultater</span>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                            <div class="mb-2">
                                <flux:input label="{{ __('Email') }}"
                                            wire:model="form.email"
                                />
                            </div>

                            <div class="mb-2">
                                <flux:radio.group wire:model="form.type" variant="cards" :indicator="false" label="{{ __('Type') }}">
                                    @foreach (CustomerType::cases() as $type)
                                        <flux:radio value="{{ $type->value }}" label="{{ $type->label() }}" />
                                    @endforeach
                                </flux:radio.group>
                            </div>
                        </div>
                        <!-- END Card Body -->
                    </div>

                </div>
            </div>
        </form>
    </div>
