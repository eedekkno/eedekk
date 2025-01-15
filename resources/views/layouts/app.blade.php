<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxStyles
</head>
<body class="font-sans antialiased">

@if (app()->environment('local'))
    <div
        class="fixed right-0 bottom-20 z-60 m-2 flex items-center justify-between rounded-lg bg-rose-700 p-4 shadow-lg sm:m-4"
    >
        <div class="inline-flex items-center text-rose-50">
            <p class="text-sm font-medium">
                LOCAL ENVIRONMENT
            </p>
        </div>
    </div>
@endif

<!-- Page Container -->
<div
    x-data="{ userDropdownOpen: false, departmentDropdownOpen: false, mobileSidebarOpen: false, mobileSearchOpen: false, desktopSidebarOpen: true }"
    x-bind:class="{
    'lg:pl-64': desktopSidebarOpen
  }"
    id="page-container"
    class="mx-auto flex min-h-dvh w-full min-w-[320px] flex-col bg-gray-100 dark:bg-gray-900 dark:text-gray-100 lg:pl-64"
>
    <!-- Page Sidebar -->
    <nav
        x-bind:class="{
      '-translate-x-full': !mobileSidebarOpen,
      'translate-x-0': mobileSidebarOpen,
      'lg:-translate-x-full': !desktopSidebarOpen,
      'lg:translate-x-0': desktopSidebarOpen,
    }"
        id="page-sidebar"
        class="dark fixed bottom-0 left-0 top-0 z-50 flex h-full w-full -translate-x-full flex-col border-r border-gray-800 bg-gray-800 text-gray-200 transition-transform duration-500 ease-out lg:w-64 lg:translate-x-0"
        aria-label="Main Sidebar Navigation"
    >
        <!-- Sidebar Header -->
        <div
            class="flex h-16 w-full flex-none items-center justify-between px-4 dark:bg-gray-600/25 lg:justify-center"
        >
            <!-- Brand -->
            <a
                href="{{ route('dashboard') }}"
                class="group inline-flex items-center gap-2 text-lg font-bold tracking-wide text-gray-900 hover:text-gray-600 dark:text-gray-100 dark:hover:text-gray-300"
            >
                <img src="{{ asset('logo.png') }}"
                     class="inline-block size-8 rounded-full transition group-hover:scale-110"
                     alt="{{ config('app.name') }} logo"/>
                <span>{{ config('app.name') }}</span>
            </a>
            <!-- END Brand -->

            <!-- Close Sidebar on Mobile -->
            <div class="lg:hidden">
                <button
                    x-on:click="mobileSidebarOpen = false"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold leading-5 text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300/25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600/40 dark:active:border-gray-700"
                >
                    <svg
                        class="hi-mini hi-x-mark -mx-0.5 inline-block size-6"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"
                        />
                    </svg>
                </button>
            </div>
            <!-- END Close Sidebar on Mobile -->
        </div>
        <!-- END Sidebar Header -->

        <!-- Sidebar Navigation -->
        <div class="overflow-y-auto">
            <div class="w-full p-4">
                @include('layouts.navigation')
            </div>
        </div>
        <!-- END Sidebar Navigation -->
    </nav>
    <!-- Page Sidebar -->

    <!-- Page Header -->
    <header
        x-bind:class="{
      'lg:pl-64': desktopSidebarOpen
    }"
        id="page-header"
        class="fixed left-0 right-0 top-0 z-30 flex h-16 flex-none items-center bg-white shadow-sm dark:bg-gray-800 lg:pl-64"
    >
        <div class="mx-auto flex w-full max-w-10xl justify-between px-4 lg:px-8">
            <!-- Left Section -->
            <div class="flex items-center gap-2">
                <!-- Toggle Sidebar on Desktop -->
                <div class="hidden lg:block">
                    <button
                        x-on:click="desktopSidebarOpen = !desktopSidebarOpen"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold leading-5 text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300/25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600/40 dark:active:border-gray-700"
                    >
                        <svg
                            class="hi-solid hi-menu-alt-1 inline-block size-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <!-- END Toggle Sidebar on Desktop -->

                <!-- Toggle Sidebar on Mobile -->
                <div class="lg:hidden">
                    <button
                        x-on:click="mobileSidebarOpen = !mobileSidebarOpen"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold leading-5 text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300/25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600/40 dark:active:border-gray-700"
                    >
                        <svg
                            class="hi-solid hi-menu-alt-1 inline-block size-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                </div>
                <!-- END Toggle Sidebar on Mobile -->

                <!-- Search -->
                <div class="hidden lg:block">
                    <form method="GET" action="#">
                        <flux:input id="search" name="search" icon="magnifying-glass" placeholder="{{ __('Search') }}.." value="{{ request('search') ?? null }}" clearable />
                    </form>
                </div>
                <!-- END Search -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="flex items-center gap-2">
                <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />

                <flux:dropdown position="bottom" align="end">
                    <flux:button icon-trailing="chevron-down">
                        <span class="inline-block sm:hidden flex items-center justify-center size-6 rounded-full bg-gray-300 text-gray-800 font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </span>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span></flux:button>
                    <flux:navmenu>
                        <flux:navmenu.item href="{{ route('profile.edit') }}" icon="user">{{ __('Profile') }}</flux:navmenu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:navmenu.item onclick="event.preventDefault();
                                            this.closest('form').submit();" icon="lock-closed" variant="danger">{{ __('Log Out') }}</flux:navmenu.item>
                        </form>
                    </flux:navmenu>
                </flux:dropdown>

                <livewire:components.change-team-dropdown/>

            </div>
            <!-- END Right Section -->
        </div>
    </header>
    <!-- END Page Header -->

    <!-- Page Content -->
    <main id="page-content" class="flex max-w-full flex-auto flex-col pt-16">
        <!-- Page Section -->
        <div class="mx-auto w-full max-w-10xl p-4 lg:p-8">

            {{ $slot }}

        </div>
        <!-- END Page Section -->
    </main>
    <!-- END Page Content -->

    <!-- Page Footer -->
    <footer
        id="page-footer"
        class="flex flex-none items-center bg-white dark:bg-gray-800/50"
    >
        <div
            class="mx-auto flex w-full max-w-10xl flex-col px-4 text-center text-sm md:flex-row md:justify-between md:text-left lg:px-8"
        >
            <div class="pb-1 pt-4 md:pb-4">
                <a
                    href="https://eeapp.no"
                    target="_blank"
                    class="font-medium text-blue-600 hover:text-blue-400 dark:text-blue-400 dark:hover:text-blue-300"
                >EEAPP Enevoldsen</a
                >
                © 2020 - {{ now()->year }}
            </div>
            <div class="inline-flex items-center justify-center pb-4 pt-1 md:pt-4">
                <span>Version: <span class="font-medium text-blue-600 dark:text-blue-400">v2.0.0</span>
                <svg
                    class="hi-solid hi-heart mx-1 inline-block size-4 text-red-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                        clip-rule="evenodd"
                    />
                </svg>
                </span>
            </div>
        </div>
    </footer>
    <!-- END Page Footer -->
</div>
<!-- END Page Container -->
@livewireScripts
@livewire('wire-elements-modal')
@stack('scripts')
@fluxScripts
</body>
</html>
