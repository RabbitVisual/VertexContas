@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $limitSvc = $user ? app(\Modules\Core\Services\SubscriptionLimitService::class) : null;
    $accountCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'account') : 0;
    $transactionCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'income') + $limitSvc->getCurrentCount($user, 'expense') : 0;
    $goalCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'goal') : 0;
    $budgetCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'budget') : 0;

    $navItemClass = 'flex items-center p-2.5 rounded-xl group transition-colors duration-150 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
    $navItemActiveClass = 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 shadow-sm';
    $sectionLabelClass = 'px-3 py-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest';
@endphp
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 shadow-xl" aria-label="Menu lateral">
    <div class="h-full flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto px-3 py-4">
            <ul class="space-y-1 font-medium">
                {{-- Visão Geral --}}
                <li>
                    <span class="{{ $sectionLabelClass }}">Visão Geral</span>
                </li>
                <li>
                    <a href="{{ route('paneluser.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('paneluser.index') ? $navItemActiveClass : '' }}">
                        <x-icon name="gauge" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('paneluser.index') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                {{-- Financeiro --}}
                <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="{{ $sectionLabelClass }}">Financeiro</span>
                </li>
                @if($isPro && Route::has('core.dashboard'))
                <li>
                    <a href="{{ route('core.dashboard') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.dashboard') ? $navItemActiveClass : '' }}">
                        <x-icon name="chart-line" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Dashboard Financeiro Pro</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                    </a>
                </li>
                @endif
                @if(Route::has('core.accounts.index'))
                <li>
                    <a href="{{ route('core.accounts.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.accounts.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="building-columns" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.accounts.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Contas</span>
                        @if(!$isPro)
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $accountCount }}/1</span>
                        @endif
                    </a>
                </li>
                @endif
                @if(Route::has('core.transactions.index'))
                <li>
                    <a href="{{ route('core.transactions.index') }}"
                       class="{{ $navItemClass }} {{ (request()->routeIs('core.transactions.index') || request()->routeIs('core.transactions.show') || request()->routeIs('core.transactions.create') || request()->routeIs('core.transactions.edit')) ? $navItemActiveClass : '' }}">
                        <x-icon name="receipt" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.transactions.*') && !request()->routeIs('core.transactions.transfer') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Transações</span>
                        @if(!$isPro)
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded" title="Receitas/Despesas">{{ $transactionCount }}/10</span>
                        @endif
                    </a>
                </li>
                @endif
                @if(Route::has('core.transactions.transfer'))
                <li>
                    <a href="{{ route('core.transactions.transfer') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.transactions.transfer') ? $navItemActiveClass : '' }}">
                        <x-icon name="right-left" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.transactions.transfer') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3">Transferências</span>
                    </a>
                </li>
                @endif
                @if(Route::has('core.goals.index'))
                <li>
                    <a href="{{ route('core.goals.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.goals.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="bullseye" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.goals.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Metas</span>
                        @if(!$isPro)
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $goalCount }}/1</span>
                        @endif
                    </a>
                </li>
                @endif
                @if(Route::has('core.budgets.index'))
                <li>
                    <a href="{{ route('core.budgets.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.budgets.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="chart-pie" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.budgets.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Orçamentos</span>
                        @if(!$isPro)
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $budgetCount }}/1</span>
                        @endif
                    </a>
                </li>
                @endif
                @if(Route::has('core.categories.index'))
                <li>
                    <a href="{{ route('core.categories.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.categories.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="tags" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.categories.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Categorias</span>
                        @if(!$isPro)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                        @endif
                    </a>
                </li>
                @endif
                @if(Route::has('core.reports.index'))
                <li>
                    <a href="{{ route('core.reports.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('core.reports.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="chart-simple" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.reports.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Relatórios</span>
                        @if(!$isPro)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded" title="Exportar PDF/CSV é exclusivo PRO">PRO</span>
                        @endif
                    </a>
                </li>
                @endif

                {{-- Suporte --}}
                <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="{{ $sectionLabelClass }}">Suporte</span>
                </li>
                <li>
                    <a href="{{ route('user.tickets.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('user.tickets.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="ticket" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.tickets.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Chamados</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ $navItemClass }} opacity-75 cursor-not-allowed" aria-disabled="true">
                        <x-icon name="file-invoice-dollar" style="solid" class="w-5 h-5 shrink-0 text-gray-400 dark:text-gray-500" />
                        <span class="ms-3 flex-1">Faturas</span>
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full">Em breve</span>
                    </a>
                </li>

                {{-- Assinatura --}}
                <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="{{ $sectionLabelClass }}">Assinatura</span>
                </li>
                <li>
                    <a href="{{ route('user.subscription.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('user.subscription.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="credit-card" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.subscription.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3 flex-1">Planos</span>
                        @if($isPro)
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                        @endif
                    </a>
                </li>

                {{-- Conteúdo --}}
                @if(Route::has('blog.index'))
                <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="{{ $sectionLabelClass }}">Conteúdo</span>
                </li>
                <li>
                    <a href="{{ route('blog.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('blog.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="newspaper" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('blog.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3">Blog</span>
                    </a>
                </li>
                @endif

                {{-- Conta --}}
                <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="{{ $sectionLabelClass }}">Conta</span>
                </li>
                <li>
                    <a href="{{ route('user.profile.show') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('user.profile.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="user" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.profile.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3">Perfil</span>
                    </a>
                </li>
                @if(Route::has('user.notifications.index'))
                <li>
                    <a href="{{ route('user.notifications.index') }}"
                       class="{{ $navItemClass }} {{ (request()->routeIs('user.notifications.*') || request()->routeIs('notifications.*')) ? $navItemActiveClass : '' }}">
                        <x-icon name="bell" style="solid" class="w-5 h-5 shrink-0 transition duration-75" />
                        <span class="ms-3 flex-1">Notificações</span>
                        @if($user && $user->unreadNotifications->count() > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 rounded-full">{{ $user->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('user.security.index') }}"
                       class="{{ $navItemClass }} {{ request()->routeIs('user.security.*') ? $navItemActiveClass : '' }}">
                        <x-icon name="user-shield" style="solid" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.security.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                        <span class="ms-3">Segurança</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                           class="{{ $navItemClass }} hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 dark:hover:text-rose-400">
                            <x-icon name="right-from-bracket" style="solid" class="w-5 h-5 shrink-0 text-gray-500 dark:text-gray-400 group-hover:text-rose-600 dark:group-hover:text-rose-400" />
                            <span class="ms-3">Sair</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>

        {{-- CTA Upgrade (somente usuários free/gratuito; pode fechar, reaparece no próximo login) --}}
        @if(!$isPro && !session('sidebar_cta_dismissed') && Route::has('user.subscription.index'))
        <div class="shrink-0 p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50" x-data="{ visible: true }" x-show="visible" x-transition>
            <div class="relative p-4 rounded-xl bg-indigo-600 dark:bg-indigo-700 shadow-lg">
                <button type="button" @click="
                    fetch('{{ route('user.cta-sidebar.dismiss') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({})
                    }).then(r => r.ok && (visible = false));
                " class="absolute top-2 right-2 p-1 rounded-lg text-indigo-200 hover:text-white hover:bg-indigo-500/50 transition-colors" aria-label="Fechar">
                    <x-icon name="xmark" style="solid" class="w-4 h-4" />
                </button>
                <p class="text-[11px] font-bold text-indigo-100 uppercase tracking-widest mb-1 pr-6">Desbloqueie mais</p>
                <p class="text-xs text-indigo-100/90 mb-3">Categorias ilimitadas, relatórios exportáveis e muito mais.</p>
                <a href="{{ route('user.subscription.index') }}" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 text-sm font-bold text-indigo-600 bg-white hover:bg-indigo-50 rounded-lg shadow-md transition-all hover:shadow-lg active:scale-[0.98]">
                    <x-icon name="bolt" style="solid" class="w-4 h-4" />
                    Ver Planos
                </a>
            </div>
        </div>
        @endif
    </div>
</aside>
