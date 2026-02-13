<header {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 shadow-sm border-b border-gray-100 dark:border-white/5 h-20 flex items-center px-8 justify-between transform transition-all duration-300 relative z-20']) }}>
    <!-- Sidebar Toggle & Breadcrumbs/Title -->
    <div class="flex items-center gap-6">
        <button @click="sidebarOpen = !sidebarOpen" class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-white/[0.03] text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 transition-all flex items-center justify-center border border-gray-100 dark:border-white/5 shadow-sm active:scale-90">
             <x-icon name="bars-staggered" style="solid" class="text-xl" />
        </button>

        <div class="hidden sm:flex flex-col">
            <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest leading-none">Vertex Panel</h2>
            <span class="text-[10px] font-black text-[#11C76F] uppercase tracking-[0.2em] leading-none mt-1.5 flex items-center gap-1.5">
                <div class="w-1 h-1 rounded-full bg-[#11C76F] animate-pulse"></div>
                Sistema Operacional
            </span>
        </div>
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-4 md:gap-6">
        <!-- Search Bar (Aesthetic) -->
        <div class="hidden lg:flex items-center bg-gray-50 dark:bg-white/[0.03] rounded-2xl border border-gray-100 dark:border-white/5 px-4 py-2.5 w-64 group focus-within:border-[#11C76F]/30 transition-all">
            <x-icon name="magnifying-glass" class="text-slate-400 text-sm group-focus-within:text-[#11C76F] transition-colors" />
            <input type="text" placeholder="Buscar no sistema..." class="bg-transparent border-none focus:ring-0 text-xs font-bold text-slate-600 dark:text-slate-300 placeholder-slate-400 w-full ml-2">
        </div>

        <!-- Notification Center -->
        <div class="relative">
            <x-notifications::bell />
        </div>

        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode" class="w-11 h-11 rounded-2xl bg-gray-50 dark:bg-white/[0.03] text-slate-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all flex items-center justify-center border border-gray-100 dark:border-white/5">
            <x-icon x-show="!darkMode" name="moon" style="solid" class="text-lg" />
            <x-icon x-show="darkMode" name="sun" style="solid" class="text-lg" />
        </button>

        <!-- Profile Quick Dropdown (Icon Only) -->
        <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-3 pl-4 border-l border-gray-100 dark:border-white/10 group">
             <div class="flex flex-col text-right hidden md:flex">
                <span class="text-xs font-black text-slate-900 dark:text-white leading-none mb-1 group-hover:text-[#11C76F] transition-colors">{{ Auth::user()->first_name }}</span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">{{ Auth::user()->roles->first()?->name ?? 'Admin' }}</span>
             </div>
             <div class="relative">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-11 h-11 rounded-2xl object-cover shadow-lg border-2 border-white dark:border-slate-800 ring-2 ring-gray-100 dark:ring-white/5 transition-transform group-hover:scale-110">
                @else
                    <div class="w-11 h-11 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-lg border-2 border-[#11C76F]/5 transition-transform group-hover:scale-110">
                        {{ substr(Auth::user()->first_name, 0, 1) }}
                    </div>
                @endif
             </div>
        </a>
    </div>
</header>
