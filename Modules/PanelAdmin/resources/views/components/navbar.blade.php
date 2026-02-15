<header {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 shadow-sm border-b border-gray-100 dark:border-white/5 h-16 flex items-center px-4 md:px-6 justify-between transition-all duration-300 relative z-20 shrink-0']) }}>
    {{-- Left: Sidebar Toggle + Page Title/Breadcrumb (no logo) --}}
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" type="button" aria-label="Alternar menu"
            class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/[0.03] text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 transition-all flex items-center justify-center border border-gray-100 dark:border-white/5 active:scale-95">
            <x-icon name="bars-staggered" style="solid" class="text-lg" />
        </button>

        <div class="hidden sm:block">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white leading-none">
                {{ $title ?? 'Vertex Admin' }}
            </h2>
            <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider leading-none mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#11C76F] animate-pulse"></span>
                Sistema Operacional
            </span>
        </div>
    </div>

    {{-- Right: Links + User Dropdown (HyperUI: links and user dropdown on the right) --}}
    <div class="flex items-center gap-2 md:gap-4">
        {{-- Search (optional) --}}
        <div class="hidden lg:flex items-center bg-gray-50 dark:bg-white/[0.03] rounded-xl border border-gray-100 dark:border-white/5 px-3 py-2 w-52 group focus-within:border-[#11C76F]/30 transition-all">
            <x-icon name="magnifying-glass" style="duotone" class="text-slate-400 text-sm group-focus-within:text-[#11C76F] transition-colors shrink-0" />
            <input type="text" placeholder="Buscar..." class="bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-600 dark:text-slate-300 placeholder-slate-400 w-full ml-2">
        </div>

        {{-- Notifications --}}
        <div class="relative">
            <x-notifications::bell />
        </div>

        {{-- Dark Mode Toggle --}}
        <button @click="darkMode = !darkMode" type="button" aria-label="Alternar tema"
            class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-white/[0.03] text-slate-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all flex items-center justify-center border border-gray-100 dark:border-white/5">
            <x-icon x-show="!darkMode" name="moon" style="solid" class="text-base" />
            <x-icon x-show="darkMode" name="sun" style="solid" class="text-base" />
        </button>

        {{-- User Dropdown (Ver perfil, Sair) --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" type="button"
                class="flex items-center gap-2 md:gap-3 pl-3 md:pl-4 border-l border-gray-100 dark:border-white/10 group focus:outline-none focus:ring-2 focus:ring-[#11C76F]/20 rounded-r-lg py-1">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-bold text-slate-900 dark:text-white leading-none group-hover:text-[#11C76F] transition-colors">
                        {{ Auth::user()->first_name ?? Auth::user()->name }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mt-0.5">
                        {{ Auth::user()->roles->first()?->name ?? 'Admin' }}
                    </span>
                </div>
                <div class="relative shrink-0">
                    @if(Auth::user()->photo)
                        <img src="{{ Storage::url(Auth::user()->photo) }}" alt="" class="w-9 h-9 md:w-10 md:h-10 rounded-xl object-cover shadow-md border-2 border-white dark:border-slate-800 ring-2 ring-gray-100 dark:ring-white/5 group-hover:ring-[#11C76F]/30 transition-all">
                    @else
                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-bold text-base border-2 border-[#11C76F]/5 group-hover:border-[#11C76F]/20 transition-all">
                            {{ substr(Auth::user()->first_name ?? Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                </div>
                <x-icon name="chevron-down" style="duotone" class="w-3.5 h-3.5 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''" />
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 z-50 py-2 overflow-hidden">
                <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                    <x-icon name="user" style="duotone" class="w-5 h-5 text-slate-400" />
                    Ver Perfil
                </a>
                <div class="border-t border-gray-100 dark:border-slate-700 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors text-left">
                        <x-icon name="right-from-bracket" style="duotone" class="w-5 h-5" />
                        Sair do Sistema
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
