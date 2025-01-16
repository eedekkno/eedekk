<div class="p-6 dark:bg-gray-800">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-lg dark:text-gray-200">{{ __($title) }}</h2>
        <button wire:click="$dispatch('closeModal')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 dark:text-gray-200">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{ $slot }}

</div>
