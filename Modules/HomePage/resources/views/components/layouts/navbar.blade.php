<nav x-data="{
    isScrolling: false,
    mobileMenuOpen: false
}"
x-init="window.addEventListener('scroll', () => { isScrolling = window.scrollY > 20 })"
:class="{ 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg shadow-lg py-3': isScrolling, 'bg-transparent py-5': !isScrolling }"
class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out font-['Poppins']">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('homepage') }}" class="flex items-center gap-2 group decoration-transparent">
                <x-logo type="full" size="text-2xl" />
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('features.index') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">Funcionalidades</a>
                <a href="{{ route('features.goals') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">Metas</a>
                <a href="{{ route('features.reports') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">Relatórios</a>
                <a href="{{ route('faq') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">Dúvidas</a>
                <a href="{{ route('help-center') }}" class="text-sm font-semibold text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary transition-colors">Ajuda</a>
            </div>

            <!-- Actions -->
            <div class="hidden md:flex items-center gap-4">
                <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-slate-500 dark:text-slate-400">
                    <x-icon x-show="!darkMode" name="moon" />
                    <x-icon x-show="darkMode" name="sun" />
                </button>

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-primary transition-colors px-4 decoration-transparent">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-primary-dark text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all decoration-transparent">
                        Começar Agora
                    </a>
                @endguest

                @auth
                    @php
                        $dashboardRoute = 'user.index';
                        if(Auth::user()->hasRole('admin')) $dashboardRoute = 'admin.index';
                        elseif(Auth::user()->hasRole('support')) $dashboardRoute = 'support.index';
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3 p-1.5 pr-4 rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all group decoration-transparent">
                        <div class="relative">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-8 h-8 rounded-full object-cover border-2 border-primary/20 group-hover:border-primary transition-all">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-xs border-2 border-primary/20 group-hover:border-primary transition-all">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-white dark:border-slate-800 rounded-full"></div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5">Olá,</span>
                            <span class="text-xs font-bold text-slate-700 dark:text-gray-200 leading-none">{{ Auth::user()->first_name }}</span>
                        </div>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center gap-3">
                <button @click="darkMode = !darkMode" class="p-2 rounded-full text-slate-500 dark:text-slate-400">
                    <x-icon x-show="!darkMode" name="moon" />
                    <x-icon x-show="darkMode" name="sun" />
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-700 dark:text-white p-2">
                    <x-icon name="bars-staggered" x-show="!mobileMenuOpen" />
                    <x-icon name="xmark" x-show="mobileMenuOpen" />
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 px-4 pt-4 pb-6 absolute left-0 right-0 top-full">
        <div class="flex flex-col gap-4">
            <a href="{{ route('features.index') }}" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 dark:text-slate-300">Funcionalidades</a>
            <a href="{{ route('features.goals') }}" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 dark:text-slate-300">Metas e Objetivos</a>
            <a href="{{ route('features.reports') }}" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 dark:text-slate-300">Relatórios</a>
            <a href="{{ route('faq') }}" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 dark:text-slate-300">Dúvidas</a>
            <a href="{{ route('help-center') }}" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 dark:text-slate-300">Central de Ajuda</a>
            <hr class="border-slate-100 dark:border-slate-800">
            @guest
                <a href="{{ route('login') }}" class="text-base font-bold text-slate-700 dark:text-slate-300 decoration-transparent">Entrar</a>
                <a href="{{ route('register') }}" class="bg-primary text-white text-center py-3 rounded-xl font-bold shadow-lg shadow-primary/25 decoration-transparent">
                    Começar Agora
                </a>
            @endguest

            @auth
                @php
                    $dashboardRoute = 'user.index';
                    if(Auth::user()->hasRole('admin')) $dashboardRoute = 'admin.index';
                    elseif(Auth::user()->hasRole('support')) $dashboardRoute = 'support.index';
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="relative">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-12 h-12 rounded-2xl object-cover border-2 border-primary/20">
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-xl border-2 border-primary/20">
                                    {{ substr(Auth::user()->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ Auth::user()->full_name }}</span>
                            <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Conectado</span>
                        </div>
                    </div>
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center justify-center gap-2 w-full py-3 bg-primary text-white rounded-xl font-bold shadow-lg shadow-primary/20 decoration-transparent">
                        <x-icon name="grid-2" class="text-sm" />
                        Acessar Meu Painel
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
