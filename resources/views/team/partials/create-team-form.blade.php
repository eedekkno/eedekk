<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Team information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Create your team
        </p>
    </header>
    <form method="post" action="{{ route('team.store') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'team-created')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Created.') }}</p>
            @endif
        </div>
    </form>
</section>
