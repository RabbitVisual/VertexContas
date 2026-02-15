@php
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
    $firstName = Auth::check() ? (Auth::user()->first_name ?? 'Membro') : 'Visitante';
    $dashboardRoute = 'paneluser.index';
    if (Auth::check()) {
        if (Auth::user()->hasRole('admin')) $dashboardRoute = 'admin.index';
        elseif (Auth::user()->hasRole('support')) $dashboardRoute = 'support.index';
        elseif (Auth::user()->isPro()) $dashboardRoute = 'core.dashboard';
        else $dashboardRoute = 'paneluser.index';
    }
@endphp
<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="font-['Poppins'] overflow-x-hidden">
        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 bg-white dark:bg-slate-900 transition-colors duration-500">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-gradient-to-br from-primary/5 to-transparent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 -z-10"></div>
            <div class="absolute bottom-0 left-0 w-1/3 h-1/3 bg-gradient-to-tr from-primary/5 to-transparent rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-3/5 space-y-8">
                    @auth
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isPro ?? false ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'bg-emerald-500/10 text-emerald-500' }} text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down">
                            <x-icon name="circle-user" />
                            Bem-vindo de volta, {{ Auth::user()->first_name }}!
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down">
                            <x-icon name="shield-check" />
                            Gestão 100% Local e Segura
                        </div>
                    @endauth

                    <h1 class="text-5xl lg:text-7xl font-black text-slate-800 dark:text-white leading-[1.1] animate-fade-in-up">
                        @if($isPro ?? false)
                            {{ $greeting }}, {{ $firstName }}!<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-primary-dark">Suas finanças em um só lugar.</span>
                        @else
                            Domine cada <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-dark">centavo</span> da sua vida.
                        @endif
                    </h1>

                    <p class="text-xl text-slate-500 dark:text-slate-400 leading-relaxed max-w-2xl mx-auto lg:mx-0 animate-fade-in-up delay-75">
                        @if($isPro ?? false)
                            Aqui está o que importa para suas finanças este mês.
                        @else
                            Gerencie receitas, despesas, orçamentos e metas em uma interface profissional, projetada para quem busca liberdade financeira total sem depender de conexões externas.
                        @endif
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4 animate-fade-in-up delay-150 flex-wrap">
                        @auth
                            <a href="{{ route($dashboardRoute) }}" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-2xl shadow-primary/40 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Acessar Meu Painel
                                <x-icon name="grid-2" class="group-hover:scale-110 transition-transform" />
                            </a>
                            @if($isPro ?? false)
                                @if(Route::has('core.transactions.create'))
                                    <a href="{{ route('core.transactions.create') }}" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-xl shadow-amber-500/20 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                        <x-icon name="plus" style="solid" class="group-hover:scale-110 transition-transform" />
                                        Nova Transação
                                    </a>
                                @endif
                                @if(Route::has('core.reports.index'))
                                    <a href="{{ route('core.reports.index') }}" class="w-full sm:w-auto bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-10 py-4 rounded-2xl text-lg font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                        <x-icon name="chart-simple" style="duotone" class="group-hover:scale-110 transition-transform" />
                                        Relatórios
                                    </a>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-primary hover:bg-primary-dark text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-2xl shadow-primary/40 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Criar Conta Grátis
                                <x-icon name="arrow-right" class="group-hover:translate-x-1 transition-transform" />
                            </a>
                        @endauth
                        <a href="{{ route('help-center') }}" class="w-full sm:w-auto bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-10 py-4 rounded-2xl text-lg font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-3 decoration-transparent">
                            Ver Central de Ajuda
                        </a>
                    </div>

                    @auth
                    <div class="flex items-center justify-center lg:justify-start gap-6 lg:gap-8 pt-8 flex-wrap {{ $financialSnapshot ? '' : 'opacity-60' }}">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black dark:text-white">+<x-core::financial-value :value="$financialSnapshot['monthly_income'] ?? 0" /></span>
                            <span class="text-xs font-bold uppercase tracking-widest">Renda Bruta</span>
                        </div>
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-700"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black dark:text-white">{{ $financialSnapshot ? $financialSnapshot['savings_rate'] . '%' : '0%' }}</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Economia</span>
                        </div>
                        @if(($isPro ?? false) && isset($financialSnapshot['monthly_balance']))
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-700"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black dark:text-white {{ ($financialSnapshot['monthly_balance'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                <x-core::financial-value :value="$financialSnapshot['monthly_balance'] ?? 0" />
                            </span>
                            <span class="text-xs font-bold uppercase tracking-widest">Balanço Mensal</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex items-center justify-center lg:justify-start gap-8 pt-8 opacity-60">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black dark:text-white">+<x-core::financial-value :value="0" /></span>
                            <span class="text-xs font-bold uppercase tracking-widest">Renda Bruta</span>
                        </div>
                        <div class="w-px h-8 bg-slate-300 dark:bg-slate-700"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black dark:text-white">0%</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Economia</span>
                        </div>
                    </div>
                    @endauth
                </div>

                <!-- Hero Mockup/Illustration -->
                <div class="w-full lg:w-2/5 relative animate-fade-in-right">
                    <div class="relative bg-gradient-to-br from-slate-100 to-white dark:from-slate-800 dark:to-slate-900 p-6 rounded-3xl shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] border border-slate-200 dark:border-slate-700 transform lg:rotate-2 group hover:rotate-0 transition-transform duration-700">
                        @auth
                            @if($isPro ?? false)
                            {{-- PRO Card: premium design --}}
                            <div class="absolute -top-10 -left-10 w-80 bg-gradient-to-br from-amber-50 via-white to-amber-50/50 dark:from-amber-950/30 dark:via-slate-900 dark:to-amber-950/20 rounded-[2.5rem] shadow-2xl p-6 border-2 border-amber-200/60 dark:border-amber-500/30 z-10 animate-fade-in-up rotate-[-6deg] group-hover:rotate-0 transition-transform duration-500 overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                                <div class="relative flex flex-col items-center text-center">
                                    <div class="relative mb-4">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-24 h-24 rounded-[2rem] object-cover ring-4 ring-amber-400/40">
                                        @else
                                            <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-amber-400 to-amber-600 text-white flex items-center justify-center font-black text-4xl ring-4 ring-amber-400/40">
                                                {{ substr(Auth::user()->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-2 -right-2 bg-amber-500 text-white p-2 rounded-2xl shadow-lg shadow-amber-500/30">
                                            <x-icon name="crown" style="solid" class="text-xs" />
                                        </div>
                                    </div>
                                    <h4 class="text-lg font-black text-slate-800 dark:text-white leading-tight mb-1">{{ Auth::user()->full_name }}</h4>
                                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-gradient-to-r from-amber-400 to-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full mb-4 shadow-lg shadow-amber-500/25">
                                        <x-icon name="crown" style="solid" class="w-3 h-3" /> Vertex PRO
                                    </span>
                                    @if($financialSnapshot)
                                    <div class="w-full mb-4 p-4 rounded-2xl bg-amber-500/10 dark:bg-amber-500/5 border border-amber-200/50 dark:border-amber-500/20">
                                        <div class="sensitive-value text-2xl font-black text-slate-800 dark:text-white"><x-core::financial-value :value="$financialSnapshot['total_balance']" /></div>
                                        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Saldo Total</span>
                                    </div>
                                    @endif
                                    <div class="w-full space-y-3 pt-4 border-t border-amber-200/50 dark:border-amber-500/20">
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Nascimento</span>
                                            <span class="sensitive-value text-slate-600 dark:text-gray-300">{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('d/m/Y') : 'Não informado' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Status</span>
                                            <span class="text-emerald-500 flex items-center gap-1 uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                                Online
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            {{-- FREE Card --}}
                            <div class="absolute -top-10 -left-10 w-72 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl p-6 border border-slate-100 dark:border-slate-800 z-10 animate-fade-in-up rotate-[-6deg] group-hover:rotate-0 transition-transform duration-500">
                                <div class="flex flex-col items-center text-center">
                                    <div class="relative mb-4">
                                        @if(Auth::user()->photo)
                                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-24 h-24 rounded-[2rem] object-cover ring-4 ring-primary/20">
                                        @else
                                            <div class="w-24 h-24 rounded-[2rem] bg-primary/10 text-primary flex items-center justify-center font-black text-4xl ring-4 ring-primary/20">
                                                {{ substr(Auth::user()->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-2 -right-2 bg-primary text-white p-2 rounded-2xl shadow-lg">
                                            <x-icon name="check" class="text-xs" />
                                        </div>
                                    </div>
                                    <h4 class="text-lg font-black text-slate-800 dark:text-white leading-tight mb-1">{{ Auth::user()->full_name }}</h4>
                                    @php
                                        $roleName = 'Usuário Free';
                                        if(Auth::user()->hasRole('admin')) $roleName = 'Administrador';
                                        elseif(Auth::user()->hasRole('support')) $roleName = 'Agente de Suporte';
                                    @endphp
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full mb-6">
                                        {{ $roleName }}
                                    </span>
                                    <div class="w-full space-y-3 pt-6 border-t border-slate-50 dark:border-slate-800">
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Nascimento</span>
                                            <span class="sensitive-value text-slate-600 dark:text-gray-300">{{ Auth::user()->birth_date ? Auth::user()->birth_date->format('d/m/Y') : 'Não informado' }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-[10px] font-bold">
                                            <span class="text-slate-400 uppercase tracking-widest">Status</span>
                                            <span class="text-emerald-500 flex items-center gap-1 uppercase tracking-widest">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                                Online
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endauth

                        <!-- Balance Card -->
                        <div class="bg-primary p-6 rounded-2xl mb-6 text-white overflow-hidden relative">
                            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex justify-between items-start mb-8">
                                <span class="text-xs font-bold uppercase opacity-80">Saldo Principal</span>
                                <x-icon name="nfc" class="text-xl opacity-60" />
                            </div>
                            <div class="text-3xl font-black mb-1">@auth <span class="sensitive-value">@if($financialSnapshot)<x-core::financial-value :value="$financialSnapshot['total_balance']" />@else<x-core::financial-value :value="0" />@endif</span> @else <span class="sensitive-value"><x-core::financial-value :value="15750" /></span> @endauth</div>
                            <div class="text-xs font-medium opacity-60">@auth {{ Auth::user()->isPro() ? 'Vertex PRO - Platinum' : 'Vertex Contas' }} @else Vertex Oh Pro - Platinum @endauth</div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="space-y-4">
                            @auth
                            @if($financialSnapshot && $financialSnapshot['recent_transactions']->isNotEmpty())
                                @foreach($financialSnapshot['recent_transactions'] as $tx)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $tx->type === 'income' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }} flex items-center justify-center">
                                            <x-icon name="{{ $tx->type === 'income' ? 'plus' : 'cart-shopping' }}" style="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold dark:text-white">{{ $tx->description ?: ($tx->category?->name ?? 'Transação') }}</div>
                                            <div class="text-xs text-slate-400">{{ $tx->date->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black {{ $tx->type === 'income' ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$tx->amount" />
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                                            <x-icon name="plus" style="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold dark:text-white">Renda Mensal</div>
                                            <div class="text-xs text-slate-400">Hoje, 10:45</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black text-green-500">+ <x-core::financial-value :value="5000" /></span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center">
                                            <x-icon name="cart-shopping" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold dark:text-white">Supermercado</div>
                                            <div class="text-xs text-slate-400">Ontem, 20:30</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black text-red-500">- <x-core::financial-value :value="450.20" /></span>
                                </div>
                            @endif
                            @else
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                                        <x-icon name="plus" style="solid" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Renda Mensal</div>
                                        <div class="text-xs text-slate-400">Hoje, 10:45</div>
                                    </div>
                                </div>
                                <span class="sensitive-value text-sm font-black text-green-500">+ <x-core::financial-value :value="5000" /></span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center">
                                        <x-icon name="cart-shopping" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold dark:text-white">Supermercado</div>
                                        <div class="text-xs text-slate-400">Ontem, 20:30</div>
                                    </div>
                                </div>
                                <span class="sensitive-value text-sm font-black text-red-500">- <x-core::financial-value :value="450.20" /></span>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features / PRO Insights Section -->
        <section id="features" class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors duration-500 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(($isPro ?? false) && isset($proHomeData))
                {{-- PRO: Tudo que você precisa saber --}}
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white">Tudo que você precisa saber</h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400">Insights e dicas baseados nos seus dados deste mês.</p>
                </div>

                @php $insights = $proHomeData['insights'] ?? []; $snap = $proHomeData['financialSnapshot'] ?? []; @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- 1. Orçamentos em risco --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-rose-500/30 transition-all hover:shadow-2xl hover:shadow-rose-500/5">
                        <div class="w-14 h-14 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="triangle-exclamation" class="text-rose-500 text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Orçamentos em Risco</h3>
                        @if(!empty($insights['budget_exceeded']) || !empty($insights['budget_warning']))
                            <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400 mb-4">
                                @foreach($insights['budget_exceeded'] ?? [] as $b)
                                    <li class="flex items-center gap-2"><span class="text-rose-500">●</span> {{ $b['name'] }} está {{ $b['pct_over'] }}% acima do limite</li>
                                @endforeach
                                @foreach($insights['budget_warning'] ?? [] as $b)
                                    <li class="flex items-center gap-2"><span class="text-amber-500">●</span> {{ $b['name'] }} em {{ $b['usage_pct'] }}% do limite</li>
                                @endforeach
                            </ul>
                            @if(Route::has('core.budgets.index'))
                                <a href="{{ route('core.budgets.index') }}" class="text-rose-600 dark:text-rose-400 font-semibold text-sm hover:underline">Ver orçamentos →</a>
                            @endif
                        @else
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Nenhum orçamento estourado ou em alerta este mês.</p>
                            @if(Route::has('core.budgets.index'))
                                <a href="{{ route('core.budgets.index') }}" class="text-primary font-semibold text-sm hover:underline">Gerenciar orçamentos →</a>
                            @endif
                        @endif
                    </div>

                    {{-- 2. Déficit / Balanço --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-emerald-500/30 transition-all hover:shadow-2xl hover:shadow-emerald-500/5">
                        <div class="w-14 h-14 {{ ($insights['monthly_deficit'] ?? null) ? 'bg-rose-500/10' : 'bg-emerald-500/10' }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="chart-line" class="{{ ($insights['monthly_deficit'] ?? null) ? 'text-rose-500' : 'text-emerald-500' }} text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Balanço do Mês</h3>
                        @if($insights['monthly_deficit'] ?? null)
                            <p class="text-rose-600 dark:text-rose-400 font-bold mb-2">Despesas superam receitas em <span class="sensitive-value"><x-core::financial-value :value="$insights['monthly_deficit']" /></span></p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Revise seus gastos ou considere aumentar sua renda.</p>
                        @else
                            <p class="text-emerald-600 dark:text-emerald-400 font-bold mb-2">Saldo positivo este mês</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Suas receitas estão cobrindo suas despesas. Bom trabalho!</p>
                        @endif
                    </div>

                    {{-- 3. Metas em destaque --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-amber-500/30 transition-all hover:shadow-2xl hover:shadow-amber-500/5">
                        <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="bullseye-arrow" class="text-amber-500 text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Metas em Destaque</h3>
                        @if($insights['top_goal'] ?? null)
                            <p class="text-slate-600 dark:text-slate-400 text-sm mb-2">{{ $insights['top_goal']['name'] }} está a {{ $insights['top_goal']['pct'] }}%</p>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 mb-4"><div class="bg-amber-500 h-full rounded-full transition-all" style="width: {{ min($insights['top_goal']['pct'], 100) }}%"></div></div>
                        @else
                            <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Nenhuma meta ativa. Crie metas para acompanhar seus objetivos.</p>
                        @endif
                        @if(Route::has('core.goals.index'))
                            <a href="{{ route('core.goals.index') }}" class="text-amber-600 dark:text-amber-400 font-semibold text-sm hover:underline">Ver metas →</a>
                        @endif
                    </div>

                    {{-- 4. Maior gasto do mês --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-indigo-500/30 transition-all hover:shadow-2xl hover:shadow-indigo-500/5">
                        <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="chart-pie" class="text-indigo-500 text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Maior Gasto do Mês</h3>
                        @if($insights['top_category'] ?? null)
                            <p class="sensitive-value text-2xl font-black text-indigo-600 dark:text-indigo-400 mb-1"><x-core::financial-value :value="$insights['top_category']['amount']" /></p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm">{{ $insights['top_category']['name'] }}</p>
                        @else
                            <p class="text-slate-500 dark:text-slate-400 text-sm">Nenhuma despesa registrada este mês.</p>
                        @endif
                        @if(Route::has('core.reports.index'))
                            <a href="{{ route('core.reports.index') }}" class="inline-block mt-3 text-indigo-600 dark:text-indigo-400 font-semibold text-sm hover:underline">Ver relatórios →</a>
                        @endif
                    </div>

                    {{-- 5. Dica de economia --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-emerald-500/30 transition-all hover:shadow-2xl hover:shadow-emerald-500/5">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="piggy-bank" class="text-emerald-500 text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Dica de Economia</h3>
                        @if($insights['savings_tip'] ?? null)
                            <p class="text-slate-600 dark:text-slate-400 text-sm">{{ $insights['savings_tip'] }}</p>
                        @else
                            <p class="text-emerald-600 dark:text-emerald-400 font-semibold text-sm">Ótima taxa de economia! Continue assim.</p>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Taxa atual: {{ $snap['savings_rate'] ?? 0 }}%</p>
                        @endif
                    </div>

                    {{-- 6. Acesso rápido --}}
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="bolt" class="text-primary text-2xl" style="duotone" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Acesso Rápido</h3>
                        <div class="flex flex-wrap gap-3">
                            @if(Route::has('core.transactions.create'))
                                <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary/10 text-primary rounded-xl text-sm font-bold hover:bg-primary/20">Nova Transação</a>
                            @endif
                            @if(Route::has('core.transactions.transfer'))
                                <a href="{{ route('core.transactions.transfer') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700">Transferir</a>
                            @endif
                            @if(Route::has('core.reports.index'))
                                <a href="{{ route('core.reports.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700">Relatórios</a>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                {{-- Guest/Free: Features genéricas --}}
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white">Tudo o que você precisa</h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400">Gerencie todas as nuances do seu dinheiro sem sair de casa, com ferramentas que facilitam o planejamento financeiro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="money-bill-trend-up" class="text-indigo-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Renda & Despesas</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Categorize suas entradas e saídas de forma inteligente. Saiba exatamente para onde cada real está indo.</p>
                    </div>
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="bullseye-arrow" class="text-emerald-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Metas Financeiras</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Crie planos para sua reserva de emergência, viagens ou a compra de um carro. Acompanhe o progresso em tempo real.</p>
                    </div>
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="calculator" class="text-amber-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Orçamentos Conscientes</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Defina limites mensais para cada categoria e receba alertas para evitar gastos impulsivos.</p>
                    </div>
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="chart-pie-simple" class="text-purple-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Relatórios Detalhados</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Visualize sua saúde financeira através de gráficos dinâmicos e exporte dados para análises profundas.</p>
                    </div>
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-rose-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="lock" class="text-rose-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Dados 100% Locais</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Seus dados financeiros nunca saem da sua máquina. Privacidade e segurança absoluta para sua tranquilidade.</p>
                    </div>
                    <div class="group p-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-primary/30 transition-all hover:shadow-2xl hover:shadow-primary/5">
                        <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="moon-stars" class="text-blue-500 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Interface Adaptativa</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Trabalhe confortavelmente em qualquer iluminação com suporte nativo a Dark Mode premium.</p>
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- PRO: Gráficos --}}
        @if(($isPro ?? false) && isset($proHomeData) && isset($proHomeData['cashFlowData']))
        <section class="py-16 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-slate-50 dark:bg-slate-950 rounded-3xl p-6 lg:p-8 border border-slate-100 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                            <x-icon name="chart-area" style="duotone" class="text-primary w-5 h-5" />
                            Fluxo de Caixa
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Últimos 6 meses</p>
                        <div id="homeCashFlowChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 260px;"></div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-950 rounded-3xl p-6 lg:p-8 border border-slate-100 dark:border-slate-800">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2 flex items-center gap-2">
                            <x-icon name="chart-pie" style="duotone" class="text-primary w-5 h-5" />
                            Gastos por Categoria
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Distribuição das despesas deste mês</p>
                        <div id="homeCategoryChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 260px;"></div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- CTA Section (oculto para PRO) -->
        @if(!($isPro ?? false))
        <section class="py-24 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-primary to-primary-dark rounded-[40px] p-12 lg:p-20 relative overflow-hidden text-center text-white shadow-2xl shadow-primary/25">
                    <div class="absolute inset-0 opacity-10 pointer-events-none">
                        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-1/3 translate-y-1/3"></div>
                    </div>

                    <div class="relative z-10 space-y-8 max-w-3xl mx-auto">
                        <h2 class="text-4xl lg:text-6xl font-black">Pronto para transformar sua finanças?</h2>
                        <p class="text-lg opacity-80 font-medium">Cadastre-se hoje e comece a trilhar seu caminho para a prosperidade com organização e inteligência.</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-white text-primary px-12 py-5 rounded-2xl text-xl font-bold hover:shadow-xl hover:scale-105 transition-all">
                                Criar Conta Agora
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @else
        {{-- PRO: Resumo rápido --}}
        <section class="py-16 bg-white dark:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="rounded-3xl p-8 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-1">Tudo sob controle</h2>
                        <p class="text-slate-500 dark:text-slate-400">Acesse seu painel completo para mais detalhes e relatórios.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-2xl font-bold shadow-lg shadow-primary/20 transition-all">Painel</a>
                        @if(Route::has('core.reports.index'))
                            <a href="{{ route('core.reports.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-2xl font-bold hover:bg-slate-300 dark:hover:bg-slate-700 transition-all">Relatórios</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

    <x-homepage::layouts.footer />

    @if(($isPro ?? false) && isset($proHomeData) && isset($proHomeData['cashFlowData']))
    @php
        $chartCashFlow = $proHomeData['cashFlowData'] ?? ['income' => [0,0,0,0,0,0], 'expenses' => [0,0,0,0,0,0], 'months' => []];
        $chartCategory = $proHomeData['categoryData'] ?? ['labels' => ['Sem dados'], 'values' => [0]];
    @endphp
    @push('scripts')
    <script>
        (function() {
            var cashFlowData = @json($chartCashFlow);
            var categoryData = @json($chartCategory);
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof ApexCharts === 'undefined') return;
                var isDark = document.documentElement.classList.contains('dark');
                var textColor = isDark ? '#94a3b8' : '#64748b';
                var cfEl = document.querySelector('#homeCashFlowChart');
                if (cfEl) {
                    new ApexCharts(cfEl, {
                        series: [
                            { name: 'Receitas', data: cashFlowData.income || [0,0,0,0,0,0] },
                            { name: 'Despesas', data: cashFlowData.expenses || [0,0,0,0,0,0] }
                        ],
                        chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                        colors: ['#10b981', '#ef4444'],
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        fill: { type: 'gradient', gradient: { opacityFrom: 0.5, opacityTo: 0.1 } },
                        xaxis: { categories: cashFlowData.months || [], labels: { style: { colors: textColor, fontSize: '11px' } } },
                        yaxis: { labels: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR'); }, style: { colors: textColor } } },
                        legend: { position: 'top', horizontalAlign: 'right', labels: { colors: textColor } },
                        tooltip: { y: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } }
                    }).render();
                }
                var catEl = document.querySelector('#homeCategoryChart');
                if (catEl) {
                    new ApexCharts(catEl, {
                        series: categoryData.values || [0],
                        chart: { type: 'donut', height: 260, fontFamily: 'Inter, sans-serif' },
                        labels: categoryData.labels || ['Sem dados'],
                        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'],
                        dataLabels: { enabled: true, formatter: function(v) { return v.toFixed(1) + '%'; } },
                        legend: { position: 'bottom', labels: { colors: textColor } },
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, name: { fontSize: '12px' }, value: { formatter: function(v) { return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } }, total: { show: true, label: 'Total', formatter: function(w) { return 'R$ ' + w.globals.seriesTotals.reduce(function(a,b){return a+b},0).toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } } } } },
                        tooltip: { y: { formatter: function(v) { return 'R$ ' + v.toLocaleString('pt-BR', { minimumFractionDigits: 2 }); } } }
                    }).render();
                }
            });
        })();
    </script>
    @endpush
    @endif

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.8s ease-out forwards; }
        .animate-fade-in-down { animation: fade-in-down 0.8s ease-out forwards; }
        .animate-fade-in-right { animation: fade-in-right 1s ease-out forwards; }
    </style>
</x-homepage::layouts.master>
