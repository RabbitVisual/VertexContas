@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $limitSvc = $user ? app(\Modules\Core\Services\SubscriptionLimitService::class) : null;
    $accountCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'account') : 0;
    $transactionCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'income') + $limitSvc->getCurrentCount($user, 'expense') : 0;
    $goalCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'goal') : 0;
    $budgetCount = $limitSvc ? $limitSvc->getCurrentCount($user, 'budget') : 0;

    // PRO: design VertexCBAV-inspired com amber, duotone, animações
    // FREE: design padrão
    $proNavBase = 'flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group';
    $proNavActive = 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 shadow-sm';
    $proNavInactive = 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-200';
    $proIconActive = 'text-amber-600 dark:text-amber-400';
    $proIconInactive = 'text-gray-400 dark:text-gray-500 group-hover:text-amber-500 dark:group-hover:text-amber-400 transition-colors duration-200';

    $navItemClass = 'flex items-center p-2.5 rounded-xl group transition-colors duration-150 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
    $navItemActiveClass = 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 shadow-sm';
    $sectionLabelClass = 'px-3 py-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest';
    $proSectionLabel = 'px-4 text-[11px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-wider';
@endphp
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen {{ $isPro ? 'pt-0' : 'pt-20' }} transition-transform duration-300 ease-in-out -translate-x-full sm:translate-x-0 shadow-xl {{ $isPro ? 'bg-white dark:bg-slate-900 border-r-2 border-amber-400/20 dark:border-amber-500/10' : 'bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700' }}" aria-label="Menu lateral">
    @if($isPro)
        {{-- Bloco da logo no topo (estilo Vertex CBAV) --}}
        <div class="h-16 shrink-0 flex items-center justify-center px-6 border-b border-gray-200 dark:border-amber-500/10">
            <div class="relative">
                <img src="{{ asset('storage/logos/logo.svg') }}" alt="Vertex Contas" class="h-9 block dark:hidden" />
                <img src="{{ asset('storage/logos/logo-white.svg') }}" alt="Vertex Contas" class="h-9 hidden dark:block" />
                <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-amber-500 flex items-center justify-center ring-2 ring-white dark:ring-slate-900" title="Vertex PRO">
                    <x-icon name="crown" style="solid" class="w-2.5 h-2.5 text-white" />
                </span>
            </div>
        </div>
    @endif
    <div class="{{ $isPro ? 'h-[calc(100%-4rem)]' : 'h-full' }} flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto px-4 py-6 {{ $isPro ? 'pt-6' : 'px-3 py-4' }} space-y-1">
            @if($isPro)
                {{-- PRO Sidebar: design VertexCBAV-inspired --}}
                <nav class="space-y-1 font-medium">
                    {{-- Visão Geral --}}
                    <div class="pt-0 pb-2">
                        <p class="{{ $proSectionLabel }}">Visão Geral</p>
                    </div>
                    @php $activeDashboard = request()->routeIs('core.dashboard') || request()->routeIs('paneluser.index'); @endphp
                    <a href="{{ ($isPro && Route::has('core.dashboard')) ? route('core.dashboard') : route('paneluser.index') }}"
                        class="{{ $proNavBase }} {{ $activeDashboard ? $proNavActive : $proNavInactive }}">
                        <x-icon name="gauge" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ $activeDashboard ? $proIconActive : $proIconInactive }}" />
                        Dashboard
                    </a>

                    {{-- Financeiro --}}
                    <div class="pt-4 pb-2">
                        <p class="{{ $proSectionLabel }}">Financeiro</p>
                    </div>
                    @if(Route::has('core.accounts.index'))
                    <a href="{{ route('core.accounts.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.accounts.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="building-columns" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.accounts.*') ? $proIconActive : $proIconInactive }}" />
                        Contas
                    </a>
                    @endif
                    {{-- Extrato e movimentações (mesma categoria) --}}
                    <div class="pt-2 pb-1">
                        <p class="{{ $proSectionLabel }}">Extrato e movimentações</p>
                    </div>
                    @if(Route::has('core.transactions.index'))
                    <a href="{{ route('core.transactions.index') }}"
                        class="{{ $proNavBase }} {{ (request()->routeIs('core.transactions.index') || request()->routeIs('core.transactions.show') || request()->routeIs('core.transactions.edit')) ? $proNavActive : $proNavInactive }}">
                        <x-icon name="receipt" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ (request()->routeIs('core.transactions.*') && !request()->routeIs('core.transactions.transfer') && !request()->routeIs('core.transactions.create')) ? $proIconActive : $proIconInactive }}" />
                        Extrato
                    </a>
                    @endif
                    @can('create', \Modules\Core\Models\Transaction::class)
                    @if(Route::has('core.transactions.create'))
                    <a href="{{ route('core.transactions.create') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.transactions.create') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="plus" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.transactions.create') ? $proIconActive : $proIconInactive }}" />
                        Nova transação
                    </a>
                    @endif
                    @endcan
                    @if(Route::has('core.transactions.transfer'))
                    <a href="{{ route('core.transactions.transfer') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.transactions.transfer') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="right-left" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.transactions.transfer') ? $proIconActive : $proIconInactive }}" />
                        Transferências
                    </a>
                    @endif
                    @if(Route::has('core.goals.index'))
                    <a href="{{ route('core.goals.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.goals.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="bullseye" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.goals.*') ? $proIconActive : $proIconInactive }}" />
                        Metas
                    </a>
                    @endif
                    @if(Route::has('core.budgets.index'))
                    <a href="{{ route('core.budgets.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.budgets.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.budgets.*') ? $proIconActive : $proIconInactive }}" />
                        Orçamentos
                    </a>
                    @endif
                    @if(Route::has('core.income.index'))
                    <a href="{{ route('core.income.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.income.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="money-bill-trend-up" style="duotone" class="w-5 shrink-0 mr-3 {{ request()->routeIs('core.income.*') ? $proIconActive : $proIconInactive }}" />
                        Minha Renda
                    </a>
                    @endif
                    @if(Route::has('core.categories.index'))
                    <a href="{{ route('core.categories.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.categories.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="tags" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.categories.*') ? $proIconActive : $proIconInactive }}" />
                        <span class="flex-1">Categorias</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                    </a>
                    @endif
                    @if(Route::has('core.reports.index'))
                    <a href="{{ route('core.reports.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.reports.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="chart-simple" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.reports.*') ? $proIconActive : $proIconInactive }}" />
                        Relatórios
                    </a>
                    @endif

                    {{-- Suporte --}}
                    <div class="pt-4 pb-2">
                        <p class="{{ $proSectionLabel }}">Suporte</p>
                    </div>
                    <a href="{{ route('user.tickets.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('user.tickets.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="ticket" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('user.tickets.*') ? $proIconActive : $proIconInactive }}" />
                        Chamados
                    </a>
                    @if($isPro && Route::has('core.invoices.index'))
                    <a href="{{ route('core.invoices.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('core.invoices.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="file-invoice-dollar" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('core.invoices.*') ? $proIconActive : $proIconInactive }}" />
                        <span class="flex-1">Faturas</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                    </a>
                    @endif

                    {{-- Assinatura --}}
                    <div class="pt-4 pb-2">
                        <p class="{{ $proSectionLabel }}">Assinatura</p>
                    </div>
                    <a href="{{ route('user.subscription.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('user.subscription.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="credit-card" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('user.subscription.*') ? $proIconActive : $proIconInactive }}" />
                        <span class="flex-1">Planos</span>
                        <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                    </a>

                    {{-- Conteúdo --}}
                    @if(Route::has('blog.index'))
                    <div class="pt-4 pb-2">
                        <p class="{{ $proSectionLabel }}">Conteúdo</p>
                    </div>
                    <a href="{{ route('blog.index') }}"
                        class="{{ $proNavBase }} {{ request()->routeIs('blog.*') ? $proNavActive : $proNavInactive }}">
                        <x-icon name="newspaper" style="duotone" class="w-5 h-5 mr-3 shrink-0 {{ request()->routeIs('blog.*') ? $proIconActive : $proIconInactive }}" />
                        Blog
                    </a>
                    @endif

                    {{-- Sair --}}
                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-800">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="{{ $proNavBase }} hover:bg-rose-50 dark:hover:bg-rose-900/20 text-gray-600 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400">
                                <x-icon name="right-from-bracket" style="duotone" class="w-5 h-5 mr-3 shrink-0 text-gray-400 dark:text-gray-500 group-hover:text-rose-500 transition-colors" />
                                Sair
                            </a>
                        </form>
                    </div>
                </nav>
            @else
                {{-- FREE Sidebar: design padrão --}}
                <ul class="space-y-1 font-medium">
                    <li><span class="{{ $sectionLabelClass }}">Visão Geral</span></li>
                    <li>
                        <a href="{{ route('paneluser.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('paneluser.index') ? $navItemActiveClass : '' }}">
                            <x-icon name="gauge" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('paneluser.index') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700"><span class="{{ $sectionLabelClass }}">Financeiro</span></li>
                    @if(Route::has('core.accounts.index'))
                    <li>
                        <a href="{{ route('core.accounts.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.accounts.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="building-columns" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.accounts.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Contas</span>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $accountCount }}/1</span>
                        </a>
                    </li>
                    @endif
                    <li class="pt-3 pb-1"><span class="{{ $sectionLabelClass }}">Extrato e movimentações</span></li>
                    @if(Route::has('core.transactions.index'))
                    <li>
                        <a href="{{ route('core.transactions.index') }}" class="{{ $navItemClass }} {{ (request()->routeIs('core.transactions.index') || request()->routeIs('core.transactions.show') || request()->routeIs('core.transactions.edit')) ? $navItemActiveClass : '' }}">
                            <x-icon name="receipt" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ (request()->routeIs('core.transactions.*') && !request()->routeIs('core.transactions.transfer') && !request()->routeIs('core.transactions.create')) ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Extrato</span>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded" title="Receitas/Despesas">{{ $transactionCount }}/10</span>
                        </a>
                    </li>
                    @endif
                    @can('create', \Modules\Core\Models\Transaction::class)
                    @if(Route::has('core.transactions.create'))
                    <li>
                        <a href="{{ route('core.transactions.create') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.transactions.create') ? $navItemActiveClass : '' }}">
                            <x-icon name="plus" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.transactions.create') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3">Nova transação</span>
                        </a>
                    </li>
                    @endif
                    @endcan
                    @if(Route::has('core.transactions.transfer'))
                    <li>
                        <a href="{{ route('core.transactions.transfer') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.transactions.transfer') ? $navItemActiveClass : '' }}">
                            <x-icon name="right-left" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.transactions.transfer') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3">Transferências</span>
                        </a>
                    </li>
                    @endif
                    @if(Route::has('core.goals.index'))
                    <li>
                        <a href="{{ route('core.goals.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.goals.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="bullseye" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.goals.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Metas</span>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $goalCount }}/1</span>
                        </a>
                    </li>
                    @endif
                    @if(Route::has('core.budgets.index'))
                    <li>
                        <a href="{{ route('core.budgets.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.budgets.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="chart-pie" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.budgets.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Orçamentos</span>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $budgetCount }}/1</span>
                        </a>
                    </li>
                    @endif
                    @if(Route::has('core.income.index'))
                    <li>
                        <a href="{{ route('core.income.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.income.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="money-bill-trend-up" style="duotone" class="w-5 shrink-0 transition duration-75 {{ request()->routeIs('core.income.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3">Minha Renda</span>
                        </a>
                    </li>
                    @endif
                    @if(Route::has('core.categories.index'))
                    <li>
                        <a href="{{ route('core.categories.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.categories.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="tags" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.categories.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Categorias</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded">PRO</span>
                        </a>
                    </li>
                    @endif
                    @if(Route::has('core.reports.index'))
                    <li>
                        <a href="{{ route('core.reports.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('core.reports.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="chart-simple" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('core.reports.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Relatórios</span>
                            <span class="px-1.5 py-0.5 text-[9px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded" title="Exportar PDF/CSV é exclusivo PRO">PRO</span>
                        </a>
                    </li>
                    @endif
                    <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700"><span class="{{ $sectionLabelClass }}">Suporte</span></li>
                    <li>
                        <a href="{{ route('user.tickets.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('user.tickets.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="ticket" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.tickets.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Chamados</span>
                        </a>
                    </li>
                    <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700"><span class="{{ $sectionLabelClass }}">Assinatura</span></li>
                    <li>
                        <a href="{{ route('user.subscription.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('user.subscription.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="credit-card" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('user.subscription.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3 flex-1">Planos</span>
                        </a>
                    </li>
                    @if(Route::has('blog.index'))
                    <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700"><span class="{{ $sectionLabelClass }}">Conteúdo</span></li>
                    <li>
                        <a href="{{ route('blog.index') }}" class="{{ $navItemClass }} {{ request()->routeIs('blog.*') ? $navItemActiveClass : '' }}">
                            <x-icon name="newspaper" style="duotone" class="w-5 h-5 shrink-0 transition duration-75 {{ request()->routeIs('blog.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300' }}" />
                            <span class="ms-3">Blog</span>
                        </a>
                    </li>
                    @endif
                    <li class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="{{ $navItemClass }} hover:bg-rose-50 dark:hover:bg-rose-900/20 hover:text-rose-600 dark:hover:text-rose-400">
                                <x-icon name="right-from-bracket" style="duotone" class="w-5 h-5 shrink-0 text-gray-500 dark:text-gray-400 group-hover:text-rose-600 dark:group-hover:text-rose-400" />
                                <span class="ms-3">Sair</span>
                            </a>
                        </form>
                    </li>
                </ul>
            @endif
        </div>

        {{-- CTA Upgrade (somente FREE) --}}
        @if(!$isPro && !session('sidebar_cta_dismissed') && Route::has('user.subscription.index'))
        <div class="shrink-0 p-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50" x-data="{ visible: true }" x-show="visible" x-transition>
            <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-amber-500 via-amber-600 to-orange-600 dark:from-amber-600 dark:to-orange-700 shadow-lg p-4">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-6 -mt-6"></div>
                <button type="button" @click="
                    fetch('{{ route('user.cta-sidebar.dismiss') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({})
                    }).then(r => r.ok && (visible = false));
                " class="absolute top-2 right-2 p-1 rounded-lg text-amber-100 hover:text-white hover:bg-white/20 transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Fechar">
                    <x-icon name="xmark" style="solid" class="w-4 h-4" />
                </button>
                <div class="relative z-10 flex items-center gap-2 mb-2">
                    <x-icon name="crown" style="solid" class="w-4 h-4 text-amber-200" />
                    <p class="text-[11px] font-bold text-amber-100 uppercase tracking-widest">Vertex PRO</p>
                </div>
                <p class="text-xs text-amber-50/95 mb-3 pr-6">Contas ilimitadas, relatórios em PDF/CSV, metas e suporte VIP.</p>
                <a href="{{ route('user.subscription.index') }}" class="relative z-10 flex items-center justify-center gap-2 w-full py-2.5 px-4 text-sm font-bold text-amber-700 bg-white hover:bg-amber-50 rounded-xl shadow-md transition-all hover:shadow-lg active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-amber-600">
                    <x-icon name="bolt" style="solid" class="w-4 h-4" />
                    Ver Planos
                </a>
            </div>
        </div>
        @endif
    </div>
</aside>
