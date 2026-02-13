<header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
        <!-- Mobile Sidebar Toggle -->
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <x-icon name="bars" class="w-6 h-6" />
        </button>

        <!-- Page Title -->
        <h1 class="text-xl font-semibold text-gray-800 dark:text-white ml-4 lg:ml-0">
            @yield('title', 'Painel do Usuário')
        </h1>
    </div>

    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <x-notifications::bell />

        <!-- Theme Toggle -->
        <button @click="darkMode = !darkMode" class="flex text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none">
            <x-icon name="sun" x-show="!darkMode" class="w-5 h-5" />
            <x-icon name="moon" x-show="darkMode" class="w-5 h-5" style="solid" />
        </button>

        <!-- User Dropdown (Simple) -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative z-10 block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                @if(auth()->user()->photo)
                    <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                @else
                    <div class="h-full w-full bg-primary flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(auth()->user()->first_name, 0, 1) }}
                    </div>
                @endif
            </button>
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-md overflow-hidden shadow-xl z-20 border border-gray-100 dark:border-gray-700" style="display: none;">
                <a href="{{ route('user.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary hover:text-white transition-colors">
                    <x-icon name="user" class="mr-2 w-3 h-3 inline" /> Perfil
                </a>
                <a href="{{ route('user.subscription.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-primary hover:text-white transition-colors">
                    <x-icon name="star" class="mr-2 w-3 h-3 inline" /> Assinatura
                </a>
                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-red-500 hover:text-white transition-colors">
                        <x-icon name="right-from-bracket" class="mr-2 w-3 h-3 inline" /> Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
