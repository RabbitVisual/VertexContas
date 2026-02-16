<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => {
          requestAnimationFrame(() => {
              document.documentElement.classList.toggle('dark', val);
              localStorage.setItem('color-theme', val ? 'dark' : 'light');
          });
      })"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Anti-FOUC: Flowbite/Tailwind dark mode --}}
    <script>
        (function(){var d=localStorage.getItem('color-theme')==='dark'||(!('color-theme' in localStorage)&&window.matchMedia('(prefers-color-scheme: dark)').matches);document.documentElement.classList.toggle('dark',d);})();
    </script>

    <title>{{ $title ?? 'Vertex Contas - Support Panel' }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/logos/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Module Specific Assets --}}
    @stack('styles')
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 bg-background dark:bg-background antialiased" x-data="{ sidebarOpen: true }">
    <x-loading-overlay />

    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        <x-panelsuporte::sidebar />

        <!-- Mobile Drawer (Flowbite) -->
        <div id="support-sidebar-drawer"
             data-drawer-target="support-sidebar-drawer"
             class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-r border-gray-200/50 dark:border-slate-800/50 w-72"
             tabindex="-1"
             aria-labelledby="drawer-label">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h5 id="drawer-label" class="sr-only">Menu de Navegação</h5>
                    <button type="button"
                            data-drawer-hide="support-sidebar-drawer"
                            aria-controls="support-sidebar-drawer"
                            class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800">
                        <x-icon name="xmark" style="duotone" class="text-xl" />
                    </button>
                </div>
                <x-panelsuporte::sidebar-nav />
            </div>
        </div>

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 min-w-0 overflow-hidden">
            <x-panelsuporte::navbar>
                @isset($navbarTitle)
                    <x-slot name="title">{{ $navbarTitle }}</x-slot>
                @endisset
            </x-panelsuporte::navbar>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-background p-6 min-h-0">
                <div class="max-w-[1600px] w-full mx-auto">
                    {{ $slot ?? '' }}
                </div>
            </main>

            <x-notifications::toast />
        </div>
    </div>

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
