<div :class="sidebarOpen ? 'w-72' : 'w-20'" class="bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 flex flex-col z-20 hidden md:flex h-screen sticky top-0">
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-800 transition-all duration-300">
        <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="flex items-center gap-3">
            <x-logo type="icon" class="h-9 w-9 text-primary drop-shadow-sm" />
            <div class="flex flex-col">
                <span class="font-extrabold text-lg tracking-tight text-slate-900 dark:text-white leading-none">Vertex</span>
                <span class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mt-0.5">Support</span>
            </div>
        </div>
        <div x-show="!sidebarOpen" x-transition class="flex justify-center w-full">
            <x-logo type="icon" class="h-8 w-8 text-primary shadow-sm" />
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 scrollbar-hide">
        <!-- Main Group -->
        <div class="space-y-1">
            <p x-show="sidebarOpen" class="px-3 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Principal</p>

            <a href="{{ route('support.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('support.index') ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center justify-center w-6 transition-colors {{ request()->routeIs('support.index') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                    <x-icon name="grid-2" style="duotone" class="text-xl" />
                </div>
                <span x-show="sidebarOpen" class="font-bold text-sm">Dashboard</span>
            </a>
        </div>

        <!-- Support Group -->
        <div class="space-y-1">
            <p x-show="sidebarOpen" class="px-3 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Atendimento</p>

            <a href="{{ route('support.tickets.index') }}"
               class="flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('support.tickets.*') ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-6 transition-colors {{ request()->routeIs('support.tickets.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                        <x-icon name="ticket" style="duotone" class="text-xl" />
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-sm">Gerenciar Tickets</span>
                </div>
            </a>
            <a href="{{ route('support.wiki.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('support.wiki.*') ? 'bg-primary/10 text-primary shadow-sm shadow-primary/5' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center justify-center w-6 transition-colors {{ request()->routeIs('support.wiki.*') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}">
                    <x-icon name="book-open-reader" style="duotone" class="text-xl" />
                </div>
                <span x-show="sidebarOpen" class="font-bold text-sm">Wiki Técnica</span>
            </a>
        </div>

        <!-- Help Group -->
        <div class="space-y-1">
            <p x-show="sidebarOpen" class="px-3 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Sistema</p>

            <a href="{{ route('homepage') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white">
                <div class="flex items-center justify-center w-6 transition-colors text-gray-400 group-hover:text-primary">
                    <x-icon name="arrow-up-right-from-square" style="duotone" class="text-xl" />
                </div>
                <span x-show="sidebarOpen" class="font-bold text-sm">Ver Site</span>
            </a>
        </div>
    </nav>

    <!-- Footer Area -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-800 space-y-4">
        <!-- User Info -->
        <a href="{{ route('support.profile.show') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors group">
            <div class="flex-shrink-0">
                @if(Auth::user()->photo)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Avatar" class="h-10 w-10 rounded-xl object-cover ring-2 ring-primary/20 group-hover:ring-primary transition-all">
                @else
                    <div class="h-10 w-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black ring-2 ring-primary/20 group-hover:ring-primary transition-all">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div x-show="sidebarOpen" class="flex flex-col min-w-0">
                <span class="text-sm font-black text-slate-800 dark:text-white truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate">Perfil de Agente</span>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all group font-bold text-sm">
                <div class="flex items-center justify-center w-6 transition-transform group-hover:-translate-x-1">
                    <x-icon name="power-off" style="duotone" class="text-xl" />
                </div>
                <span x-show="sidebarOpen">Sair do Painel</span>
            </button>
        </form>
    </div>
</div>
