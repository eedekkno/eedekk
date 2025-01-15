<nav class="space-y-1">
    <div class="lg:hidden">
        <form method="GET" action="#">
            <input
                type="text"
                class="mb-4 block w-full rounded-lg border border-gray-200 py-2 text-sm leading-5 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-500/50 dark:border-gray-700 dark:bg-gray-900/25 dark:focus:border-blue-500"
                id="search"
                name="search"
                value="{{ request('search') ?? null }}"
                placeholder="{{ __('Search') }}.."
            />
        </form>
    </div>

    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
        <span
            class="flex flex-none items-center text-blue-500 dark:text-gray-300"
        >
          <svg
              class="hi-outline hi-home inline-block size-5"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
          >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
            />
          </svg>
        </span>
        <span class="grow py-1">Dashboard</span>
    </x-nav-link>

    @include('layouts.navigation.partials.main')
    @include('layouts.navigation.partials.account')

</nav>
