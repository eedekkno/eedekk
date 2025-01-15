<div>
    <div class="relative inline-block">
        <flux:dropdown>
            <flux:button icon-trailing="chevron-down"><span class="inline-block sm:hidden flex items-center justify-center size-6 rounded-full bg-gray-300 text-gray-800 font-semibold">
                {{ substr(auth()->user()->team->name, 0, 1) }}
            </span>
                <span class="hidden sm:inline">{{ auth()->user()->team->name }}</span></flux:button>

            <flux:menu>
                @foreach ($teams as $team)
                    <flux:menu.radio
                        :checked="auth()->user()->team->id === $team->id"
                        class="cursor-pointer"
                        wire:click="changeTeam({{ $team->id }})"
                    >
                        {{ $team->name }}
                    </flux:menu.radio>
                @endforeach
                    <flux:menu.separator />
                    <flux:menu.item
                        :href="route('team.edit')"
                        icon="pencil-square"
                    >{{ __('Team settings') }}
                    </flux:menu.item>

                    <flux:menu.item
                        :href="route('team.create')"
                        icon="plus"
                    >{{ __('Create team') }}
                    </flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </div>
</div>
@push('scripts')
    <script>
        Livewire.on('teamChanged', () => {
            location.reload();
        });
    </script>
@endpush
