@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $userName = $user?->full_name ?? $user?->first_name ?? 'Usuário';
    $userEmail = $user?->email ?? '';
    $photoUrl = $user?->photo_url ?? asset('images/default-avatar.svg');
    $hasPhoto = !empty($user?->photo);

    // Breadcrumb PRO: mapeamento de rotas para rótulos
    $breadcrumbLabels = [
        'paneluser.index' => 'Dashboard',
        'core.dashboard' => 'Dashboard',
        'core.accounts.index' => 'Contas', 'core.accounts.create' => 'Nova Conta', 'core.accounts.edit' => 'Editar Conta',
        'core.transactions.index' => 'Transações', 'core.transactions.create' => 'Nova Transação', 'core.transactions.edit' => 'Editar Transação', 'core.transactions.show' => 'Transação', 'core.transactions.transfer' => 'Transferências',
        'core.goals.index' => 'Metas', 'core.goals.create' => 'Nova Meta', 'core.goals.edit' => 'Editar Meta',
        'core.budgets.index' => 'Orçamentos', 'core.budgets.create' => 'Novo Orçamento', 'core.budgets.edit' => 'Editar Orçamento',
        'core.categories.index' => 'Categorias',
        'core.reports.index' => 'Relatórios',
        'user.tickets.index' => 'Chamados', 'user.tickets.create' => 'Novo Chamado', 'user.tickets.show' => 'Chamado',
        'core.invoices.index' => 'Faturas',
        'user.subscription.index' => 'Planos',
        'user.profile.edit' => 'Perfil',
        'user.notifications.index' => 'Notificações',
    ];
    $routeName = request()->route()?->getName() ?? '';
    $breadcrumbCurrent = $breadcrumbLabels[$routeName] ?? ($breadcrumb ?? 'Painel');
@endphp
<nav class="{{ $isPro ? 'sticky top-0 z-50 w-full bg-white dark:bg-gray-900 border-b-2 border-amber-400/20 dark:border-amber-500/20 shadow-sm' : 'fixed top-0 left-0 right-0 z-50 w-full bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm' }}">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
            {{-- PRO: Toggle + Breadcrumb | FREE: Logo + Toggle --}}
            <div class="flex items-center gap-2 md:gap-4">
                <button id="drawer-toggle-btn" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center justify-center p-2.5 text-gray-500 rounded-xl hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 {{ $isPro ? 'lg:hidden' : 'md:hidden' }}">
                    <span class="sr-only">Abrir menu lateral</span>
                    <x-icon name="bars" style="solid" class="w-5 h-5" />
                </button>
                @if($isPro)
                    {{-- Breadcrumb estilo Vertex CBAV --}}
                    <div class="hidden md:flex items-center gap-2 text-sm">
                        <a href="{{ Route::has('core.dashboard') ? route('core.dashboard') : route('paneluser.index') }}" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                            <x-icon name="house" style="solid" class="w-4 h-4 mr-1.5" />
                            Dashboard
                        </a>
                        @if($breadcrumbCurrent !== 'Dashboard')
                            <x-icon name="chevron-right" style="solid" class="w-3.5 h-3.5 text-gray-300 dark:text-gray-600" />
                            <span class="text-gray-900 dark:text-white font-semibold tracking-wide">{{ $breadcrumbCurrent }}</span>
                        @endif
                    </div>
                @else
                    <a href="{{ ($user && $user->isPro() && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index') }}" class="flex items-center gap-2.5 group">
                        <img src="{{ asset('storage/logos/logo.svg') }}" class="h-8 dark:hidden transition-transform group-hover:scale-105" alt="Vertex Contas" />
                        <img src="{{ asset('storage/logos/logo-white.svg') }}" class="h-8 hidden dark:block transition-transform group-hover:scale-105" alt="Vertex Contas" />
                        <span class="self-center text-lg font-bold whitespace-nowrap text-gray-900 dark:text-white hidden sm:inline">Vertex Contas</span>
                    </a>
                @endif
            </div>

            {{-- Right: Privacidade, Theme, Notifications, Profile --}}
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Toggle privacidade: esconder valores sensíveis em locais públicos --}}
                <x-paneluser::sensitive-toggle />
                {{-- Theme Toggle (liga/desliga com ícones sol e lua - Font Awesome local) --}}
                <label for="theme-toggle-checkbox" class="relative block h-8 w-14 rounded-full bg-gray-300 transition-colors cursor-pointer [-webkit-tap-highlight-color:transparent] has-checked:bg-primary-500 dark:has-checked:bg-primary-600" aria-label="Alternar tema claro/escuro">
                    <input type="checkbox" id="theme-toggle-checkbox" class="peer sr-only">
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full bg-white shadow-sm ring-4 ring-white ring-inset transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none"></span>
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full flex items-center justify-center text-amber-500 transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none opacity-100 peer-checked:opacity-0 peer-checked:invisible">
                        <x-icon name="sun" style="solid" class="w-3.5 h-3.5" />
                    </span>
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full flex items-center justify-center text-indigo-200 transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none opacity-0 invisible peer-checked:opacity-100 peer-checked:visible">
                        <x-icon name="moon" style="solid" class="w-3.5 h-3.5" />
                    </span>
                </label>

                {{-- Notifications Bell (full component with dropdown) --}}
                <div class="relative">
                    <x-notifications::bell />
                </div>

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 p-1.5 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 {{ $isPro ? 'hover:bg-amber-50 dark:hover:bg-amber-500/10 focus:ring-amber-400/50 rounded-2xl' : 'hover:bg-gray-100 dark:hover:bg-gray-800 focus:ring-primary-500' }}"
                        aria-expanded="false">
                        <span class="sr-only">Abrir menu do usuário</span>
                        <div class="relative shrink-0">
                            <div class="w-9 h-9 rounded-full overflow-hidden {{ $isPro ? 'ring-2 ring-amber-400/60 dark:ring-amber-500/50 ring-offset-2 ring-offset-white dark:ring-offset-gray-900 shadow-sm shadow-amber-400/20' : 'ring-2 ring-gray-200 dark:ring-gray-600' }}">
                                @if($hasPhoto)
                                    <img src="{{ $photoUrl }}" alt="{{ $userName }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-sm font-bold {{ $isPro ? 'bg-gradient-to-br from-amber-500 to-amber-600 text-white' : 'bg-primary-600 text-white' }}">
                                        {{ strtoupper(mb_substr($userName, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            @if($isPro)
                                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-amber-500 flex items-center justify-center ring-2 ring-white dark:ring-gray-900 shadow-sm z-10">
                                    <x-icon name="crown" style="solid" class="w-3 h-3 text-white" />
                                </div>
                            @endif
                        </div>
                        <div class="hidden lg:flex flex-col items-start text-left">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white leading-tight flex items-center gap-2">
                                {{ $userName }}
                                @if($isPro)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">PRO</span>
                                @endif
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[140px]">{{ $userEmail }}</span>
                        </div>
                        <span class="hidden sm:block transition-transform duration-200" :class="{ 'rotate-180': open }"><x-icon name="chevron-down" style="solid" class="w-4 h-4 text-gray-400" /></span>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-xl overflow-hidden z-50 {{ $isPro ? 'bg-white dark:bg-gray-800 shadow-xl shadow-amber-400/5 border-2 border-amber-200/50 dark:border-amber-500/20' : 'bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700' }}"
                         x-cloak>
                        <div class="px-4 py-3 border-b {{ $isPro ? 'border-amber-200/50 dark:border-amber-500/20 bg-gradient-to-r from-amber-50/50 to-transparent dark:from-amber-500/5 dark:to-transparent' : 'border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50' }}">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate flex items-center gap-2">
                                {{ $userName }}
                                @if($isPro)
                                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400">
                                        <x-icon name="crown" style="solid" class="w-3 h-3" /> Vertex PRO
                                    </span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                        </div>
                        <ul class="py-2">
                            <li>
                                <a href="{{ ($user && $user->isPro() && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="gauge" style="solid" class="w-4 h-4 text-gray-400" />
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile.show') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="user" style="solid" class="w-4 h-4 text-gray-400" />
                                    Perfil
                                </a>
                            </li>
                            @if(Route::has('user.notifications.index'))
                            <li>
                                <a href="{{ route('user.notifications.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="bell" style="solid" class="w-4 h-4 text-gray-400" />
                                    Notificações
                                    @if($user && $user->unreadNotifications->count() > 0)
                                        <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-rose-500 text-white rounded-full">{{ $user->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('user.subscription.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="credit-card" style="solid" class="w-4 h-4 text-gray-400" />
                                    Assinatura
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.security.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="user-shield" style="solid" class="w-4 h-4 text-gray-400" />
                                    Segurança
                                </a>
                            </li>
                        </ul>
                        <div class="border-t border-gray-100 dark:border-gray-700 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                    <x-icon name="right-from-bracket" style="solid" class="w-4 h-4" />
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
(function(){
    var cb = document.getElementById('theme-toggle-checkbox');
    if (!cb) return;
    cb.checked = document.documentElement.classList.contains('dark');
    cb.addEventListener('change', function(){
        var isDark = cb.checked;
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
    });
})();
</script>
