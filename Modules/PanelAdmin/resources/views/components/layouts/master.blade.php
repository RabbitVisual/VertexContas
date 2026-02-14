<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      :class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => {
          localStorage.setItem('color-theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- Anti-FOUC: Flowbite/Tailwind dark mode - deve rodar antes do primeiro paint --}}
    <script>
        (function(){var d=localStorage.getItem('color-theme')==='dark'||(!('color-theme' in localStorage)&&window.matchMedia('(prefers-color-scheme: dark)').matches);document.documentElement.classList.toggle('dark',d);})();
    </script>

    <title>@yield('title', $title ?? 'Vertex Contas - Admin Panel')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/logos/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Module Specific Assets --}}
    @stack('styles')
</head>
<body class="font-sans text-gray-900 dark:text-gray-100 bg-background dark:bg-background antialiased" x-data="{ sidebarOpen: true }">
    <x-loading-overlay />

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-paneladmin::sidebar />

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Navbar -->
            <x-paneladmin::navbar />

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-background p-6">
                {{ $slot ?? '' }}
            </main>

            <x-notifications::toast />
        </div>
    </div>

    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>
