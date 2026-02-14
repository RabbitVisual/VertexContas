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
        <!-- Sidebar -->
        <x-panelsuporte::sidebar />

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <!-- Header -->
            <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-10 border-b border-gray-200 dark:border-gray-800 h-16 flex items-center px-6 justify-between transition-all duration-300">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 focus:outline-none transition-colors">
                         <x-icon name="bars-staggered" style="solid" class="text-xl" />
                    </button>
                    <div class="hidden md:block">
                        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Suporte Técnico</h2>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                     <!-- Notifications -->
                     <x-notifications::bell />

                     <div class="h-8 w-px bg-gray-200 dark:border-gray-700 mx-1"></div>

                     <div class="flex items-center gap-3">
                         <div class="text-right hidden sm:block">
                             <p class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                             <p class="text-xs text-primary font-medium mt-1">Agente de Suporte</p>
                         </div>
                         <a href="{{ route('support.profile.show') }}" class="relative group">
                             @if(Auth::user()->photo)
                                 <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}" class="h-10 w-10 rounded-xl object-cover ring-2 ring-primary/20 group-hover:ring-primary transition-all">
                             @else
                                 <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold ring-2 ring-primary/20 group-hover:ring-primary transition-all">
                                     {{ substr(Auth::user()->first_name, 0, 1) }}
                                 </div>
                             @endif
                             <div class="absolute -bottom-1 -right-1 bg-primary text-white text-[8px] p-0.5 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                 <x-icon name="pen" class="w-2 h-2" />
                             </div>
                         </a>
                     </div>
                </div>
            </header>

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
