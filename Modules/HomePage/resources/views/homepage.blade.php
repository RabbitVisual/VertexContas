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
<x-homepage::layouts.master
    :title="'Vertex Contas - Domine sua Liberdade Financeira com a Regra 50/30/20'"
    :metaDescription="'Gerencie suas finanças com inteligência. O único sistema com mentor virtual, análise 50/30/20 e consultoria mensal automática em PDF. Comece grátis!'"
    :metaKeywords="'gestão financeira, regra 50/30/20, finanças pessoais, planejamento financeiro, vertex contas, saas financeiro'"
>
    <x-homepage::layouts.navbar />

    <main class="font-['Poppins'] overflow-x-hidden">
        <!-- Hero Section - Vertex Azul/Roxo Dark -->
        <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 bg-slate-950 overflow-hidden">
            <!-- Background Glow Orbs -->
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/3 -z-10" aria-hidden="true"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-violet-600/15 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/3 -z-10" aria-hidden="true"></div>
            <div class="absolute top-1/2 left-1/2 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 -z-10" aria-hidden="true"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left flex flex-col lg:flex-row items-center gap-16">
                <div class="w-full lg:w-3/5 space-y-8">
                    @auth
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isPro ?? false ? 'bg-amber-500/20 text-amber-400' : 'bg-indigo-500/20 text-indigo-300' }} text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down border border-amber-500/30 dark:border-indigo-500/30">
                            <x-icon name="circle-user" style="duotone" />
                            Bem-vindo de volta, {{ Auth::user()->first_name }}!
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider backdrop-blur-sm animate-fade-in-down border border-indigo-500/30">
                            <x-icon name="shield-check" style="duotone" />
                            Gestão 100% Local e Segura
                        </div>
                    @endauth

                    <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1] animate-fade-in-up">
                        @if($isPro ?? false)
                            {{ $greeting }}, {{ $firstName }}!<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-amber-300 to-violet-400">Suas finanças em um só lugar.</span>
                        @else
                            Não apenas anote seus gastos.<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400">Domine sua liberdade financeira.</span>
                        @endif
                    </h1>

                    <p class="text-xl text-slate-400 leading-relaxed max-w-2xl mx-auto lg:mx-0 animate-fade-in-up delay-75">
                        @if($isPro ?? false)
                            Aqui está o que importa para suas finanças este mês.
                        @else
                            {{ setting('homepage_hero_subtitle') ?: 'O único sistema que utiliza a regra 50/30/20 e um mentor virtual para transformar sua relação com o dinheiro.' }}
                        @endif
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4 animate-fade-in-up delay-150 flex-wrap">
                        @auth
                            <a href="{{ route($dashboardRoute) }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-400 hover:to-violet-400 text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-xl shadow-indigo-500/25 hover:shadow-[0_0_30px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Acessar Meu Painel
                                <x-icon name="grid-2" style="duotone" class="group-hover:scale-110 transition-transform" />
                            </a>
                            @if($isPro ?? false)
                                @if(Route::has('core.transactions.create'))
                                    <a href="{{ route('core.transactions.create') }}" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-700 text-white border border-slate-600 px-10 py-4 rounded-2xl text-lg font-bold transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                        <x-icon name="plus" style="solid" class="group-hover:scale-110 transition-transform" />
                                        Nova Transação
                                    </a>
                                @endif
                                @if(Route::has('core.reports.index'))
                                    <a href="{{ route('core.reports.index') }}" class="w-full sm:w-auto bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 border border-slate-600 px-10 py-4 rounded-2xl text-lg font-bold transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                        <x-icon name="chart-simple" style="duotone" class="group-hover:scale-110 transition-transform" />
                                        Relatórios
                                    </a>
                                @endif
                            @endif
                            <a href="{{ route('help-center') }}" class="w-full sm:w-auto bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 border border-slate-600 px-10 py-4 rounded-2xl text-lg font-bold transition-all flex items-center justify-center gap-3 decoration-transparent">
                                Central de Ajuda
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-400 hover:to-violet-400 text-white px-10 py-4 rounded-2xl text-lg font-bold shadow-xl shadow-indigo-500/25 hover:shadow-[0_0_30px_rgba(99,102,241,0.5)] transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 group decoration-transparent">
                                Começar Agora Gratuitamente
                                <x-icon name="arrow-right" style="duotone" class="group-hover:translate-x-1 transition-transform" />
                            </a>
                            <a href="#features" class="w-full sm:w-auto bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 border border-slate-600 px-10 py-4 rounded-2xl text-lg font-bold transition-all flex items-center justify-center gap-3 decoration-transparent">
                                Ver Como Funciona
                            </a>
                        @endauth
                        @guest
                        <a href="{{ route('help-center') }}" class="w-full sm:w-auto bg-slate-800/50 hover:bg-slate-700/50 text-slate-300 border border-slate-600 px-10 py-4 rounded-2xl text-lg font-bold transition-all flex items-center justify-center gap-3 decoration-transparent">
                            Central de Ajuda
                        </a>
                        @endguest
                    </div>

                    @auth
                    <div class="flex items-center justify-center lg:justify-start gap-6 lg:gap-8 pt-8 flex-wrap {{ $financialSnapshot ? '' : 'opacity-60' }}">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black text-white">+<x-core::financial-value :value="$financialSnapshot['monthly_income'] ?? 0" /></span>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Renda Bruta</span>
                        </div>
                        <div class="w-px h-8 bg-slate-600"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black text-white">{{ $financialSnapshot ? $financialSnapshot['savings_rate'] . '%' : '0%' }}</span>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Economia</span>
                        </div>
                        @if(($isPro ?? false) && isset($financialSnapshot['monthly_balance']))
                        <div class="w-px h-8 bg-slate-600"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black {{ ($financialSnapshot['monthly_balance'] ?? 0) >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                <x-core::financial-value :value="$financialSnapshot['monthly_balance'] ?? 0" />
                            </span>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Balanço Mensal</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex items-center justify-center lg:justify-start gap-8 pt-8 opacity-60">
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black text-white">+<x-core::financial-value :value="0" /></span>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Renda Bruta</span>
                        </div>
                        <div class="w-px h-8 bg-slate-600"></div>
                        <div class="flex flex-col items-center lg:items-start">
                            <span class="sensitive-value text-2xl font-black text-white">0%</span>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Economia</span>
                        </div>
                    </div>
                    @endauth
                </div>

                <!-- Hero Mockup/Illustration -->
                <div class="w-full lg:w-2/5 relative animate-fade-in-right">
                    <div class="relative bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-3xl shadow-2xl border border-slate-600/50 transform lg:rotate-2 group hover:rotate-0 transition-transform duration-700">
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
                                        <x-icon name="crown" style="solid" class="w-3 h-3" /> {{ plan_pro_name() }}
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
                        <div class="bg-gradient-to-br from-indigo-600 to-violet-600 p-6 rounded-2xl mb-6 text-white overflow-hidden relative">
                            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex justify-between items-start mb-8">
                                <span class="text-xs font-bold uppercase opacity-80">Saldo Principal</span>
                                <x-icon name="nfc" class="text-xl opacity-60" />
                            </div>
                            <div class="text-3xl font-black mb-1">@auth <span class="sensitive-value">@if($financialSnapshot)<x-core::financial-value :value="$financialSnapshot['total_balance']" />@else<x-core::financial-value :value="0" />@endif</span> @else <span class="sensitive-value"><x-core::financial-value :value="15750" /></span> @endauth</div>
                            <div class="text-xs font-medium opacity-60">@auth {{ Auth::user()->isPro() ? plan_pro_name() . ' - Platinum' : config('app.name') }} @else {{ plan_pro_name() }} - Platinum @endauth</div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="space-y-4">
                            @auth
                            @if($financialSnapshot && $financialSnapshot['recent_transactions']->isNotEmpty())
                                @foreach($financialSnapshot['recent_transactions'] as $tx)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/80 shadow-sm border border-slate-600/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $tx->type === 'income' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500' }} flex items-center justify-center">
                                            <x-icon name="{{ $tx->type === 'income' ? 'plus' : 'cart-shopping' }}" style="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $tx->description ?: ($tx->category?->name ?? 'Transação') }}</div>
                                            <div class="text-xs text-slate-400">{{ $tx->date->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$tx->amount" />
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/80 shadow-sm border border-slate-600/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                                            <x-icon name="plus" style="solid" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">Renda Mensal</div>
                                            <div class="text-xs text-slate-400">Hoje, 10:45</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black text-emerald-400">+ <x-core::financial-value :value="5000" /></span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/80 shadow-sm border border-slate-600/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center">
                                            <x-icon name="cart-shopping" style="duotone" />
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">Supermercado</div>
                                            <div class="text-xs text-slate-400">Ontem, 20:30</div>
                                        </div>
                                    </div>
                                    <span class="sensitive-value text-sm font-black text-red-500">- <x-core::financial-value :value="450.20" /></span>
                                </div>
                            @endif
                            @else
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/80 shadow-sm border border-slate-600/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-500/10 text-green-500 flex items-center justify-center">
                                        <x-icon name="plus" style="solid" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">Renda Mensal</div>
                                        <div class="text-xs text-slate-400">Hoje, 10:45</div>
                                    </div>
                                </div>
                                <span class="sensitive-value text-sm font-black text-emerald-400">+ <x-core::financial-value :value="5000" /></span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-800/80 shadow-sm border border-slate-600/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-red-500/10 text-red-400 flex items-center justify-center">
                                        <x-icon name="cart-shopping" style="duotone" />
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-white">Supermercado</div>
                                        <div class="text-xs text-slate-400">Ontem, 20:30</div>
                                    </div>
                                </div>
                                <span class="sensitive-value text-sm font-black text-rose-400">- <x-core::financial-value :value="450.20" /></span>
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
                {{-- Guest/Free: Killer Features Grid (Glassmorphism) --}}
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white">O que torna o Vertex único</h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400">Recursos que diferenciam o Vertex Contas de qualquer outro sistema de gestão financeira.</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Registro e categorização, fluxo de caixa (saldo, receitas e despesas), metas, orçamentos, relatórios e foco em reserva de emergência: os pilares da gestão financeira pessoal em um só lugar.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    {{-- 1. Inteligência 50/30/20 --}}
                    <div class="group p-6 lg:p-8 backdrop-blur-xl bg-white/5 dark:bg-slate-800/50 border border-white/10 dark:border-slate-700/50 rounded-3xl hover:border-indigo-500/30 hover:shadow-xl transition-all duration-300">
                        <div class="w-14 h-14 bg-indigo-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="chart-pie" style="duotone" class="text-indigo-400 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">Inteligência 50/30/20</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">O sistema categoriza automaticamente suas transações em <strong>Essencial (50%)</strong>, <strong>Desejo (30%)</strong> e <strong>Investimento (20%)</strong>.</p>
                    </div>

                    {{-- 2. Mentor Virtual (Vertex Bot) --}}
                    <div class="group p-6 lg:p-8 backdrop-blur-xl bg-white/5 dark:bg-slate-800/50 border border-white/10 dark:border-slate-700/50 rounded-3xl hover:border-indigo-500/30 hover:shadow-xl transition-all duration-300">
                        <div class="w-14 h-14 bg-violet-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="robot" style="duotone" class="text-violet-400 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">Mentor Virtual (Vertex Bot)</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-3">Dicas personalizadas baseadas nos seus dados reais. Um coach financeiro 24h ao seu lado.</p>
                        <div class="p-3 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/10 border border-emerald-500/20 text-sm text-emerald-700 dark:text-emerald-300 italic">
                            "Parabéns! Você economizou 15% em relação ao mês passado."
                        </div>
                    </div>

                    {{-- 3. Consultoria PRO em PDF --}}
                    <div class="group p-6 lg:p-8 backdrop-blur-xl bg-white/5 dark:bg-slate-800/50 border border-white/10 dark:border-slate-700/50 rounded-3xl hover:border-indigo-500/30 hover:shadow-xl transition-all duration-300">
                        <div class="w-14 h-14 bg-amber-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="file-pdf" style="duotone" class="text-amber-400 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">Consultoria PRO em PDF</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">Relatório mensal automático que funciona como um consultor financeiro pessoal. Score, análise 50/30/20 e recomendações.</p>
                        @include('homepage::partials.consulting-pdf-preview')
                    </div>

                    {{-- 4. Segurança de Elite (LGPD) --}}
                    <div class="group p-6 lg:p-8 backdrop-blur-xl bg-white/5 dark:bg-slate-800/50 border border-white/10 dark:border-slate-700/50 rounded-3xl hover:border-indigo-500/30 hover:shadow-xl transition-all duration-300">
                        <div class="w-14 h-14 bg-emerald-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <x-icon name="shield-check" style="duotone" class="text-emerald-400 text-2xl" />
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-slate-800 dark:text-white">Segurança de Elite (LGPD)</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Dados protegidos com criptografia, máscaras de privacidade e total conformidade com a Lei Geral de Proteção de Dados.</p>
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- Gamification Showcase - Sua Jornada de Conquistas --}}
        @if(($medals ?? collect())->isNotEmpty())
        <section class="py-24 bg-slate-950 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600/10 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-violet-600/10 rounded-full blur-[100px] translate-x-1/2 translate-y-1/2" aria-hidden="true"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-black text-white mb-4">Sua Jornada de Conquistas</h2>
                    <p class="text-lg text-slate-400">Economizar no Vertex é como subir de nível em um jogo. Desbloqueie medalhas ao atingir metas financeiras.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($medals as $medal)
                        @php
                            $rarity = $medal->rarity ?? 'silver';
                            $rarityCardClass = match($rarity) {
                                'bronze' => 'bg-gradient-to-br from-amber-800/80 to-amber-900/90 border-amber-700/50',
                                'silver' => 'bg-gradient-to-br from-slate-400 to-slate-600 border-slate-500/50 shadow-inner',
                                'gold' => 'bg-gradient-to-br from-amber-400 via-yellow-300 to-amber-600 border-amber-500/50 shadow-lg shadow-amber-500/30',
                                'platinum' => 'bg-gradient-to-br from-indigo-400 via-purple-400 to-pink-400 border-purple-400/60',
                                default => 'bg-gradient-to-br from-slate-500 to-slate-700 border-slate-500/50',
                            };
                            $rarityIconClass = match($rarity) {
                                'bronze' => 'bg-amber-900/40 text-amber-200',
                                'silver' => 'bg-slate-200/50 text-slate-800',
                                'gold' => 'bg-amber-200/40 text-amber-900',
                                'platinum' => 'bg-white/30 text-white',
                                default => 'bg-slate-600/50 text-slate-200',
                            };
                        @endphp
                        <div class="relative rounded-2xl border backdrop-blur-xl p-6 {{ $rarityCardClass }} hover:scale-[1.03] transition-transform duration-300" title="{{ $medal->description }}">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 {{ $rarityIconClass }}">
                                    <x-icon name="{{ $medal->icon_name ?? 'medal' }}" style="duotone" class="w-10 h-10" />
                                </div>
                                <h3 class="font-bold text-white text-sm leading-tight drop-shadow-sm">{{ $medal->title }}</h3>
                                @if($medal->description)
                                    <p class="text-xs text-white/80 mt-2 line-clamp-2">{{ Str::limit($medal->description, 60) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

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

        {{-- Pricing Table - planos dinâmicos (tabela plans) --}}
        @php
            $proHasLimits = $proHasLimits ?? false;
            $limitsPro = $limitsPro ?? ['account' => -1, 'income' => -1, 'expense' => -1, 'goal' => -1, 'budget' => -1];
            $freeLimits = $freeLimits ?? ['account' => 1, 'income' => 15, 'expense' => 15, 'goal' => 1, 'budget' => 1];
            $planFreeName = $planFree ? $planFree->name : 'Plano Gratuito';
            $proAccountDisplay = $proHasLimits && (($limitsPro['account'] ?? -1) >= 0) ? 'Até ' . $limitsPro['account'] . ' contas' : 'Ilimitado';
            $proIncomeDisplay = ($proHasLimits ?? false) && (($limitsPro['income'] ?? -1) >= 0) ? 'Até ' . ($limitsPro['income'] ?? 5000) : 'Ilimitado';
            $proExpenseDisplay = ($proHasLimits ?? false) && (($limitsPro['expense'] ?? -1) >= 0) ? 'Até ' . ($limitsPro['expense'] ?? 5000) : 'Ilimitado';
            $proGoalDisplay = ($proHasLimits ?? false) && (($limitsPro['goal'] ?? -1) >= 0) ? 'Até ' . ($limitsPro['goal'] ?? 15) : 'Ilimitado';
            $proBudgetDisplay = ($proHasLimits ?? false) && (($limitsPro['budget'] ?? -1) >= 0) ? 'Até ' . ($limitsPro['budget'] ?? 20) : 'Ilimitado';
            $paidPlans = $paidPlans ?? collect();
        @endphp
        <section id="pricing" class="py-24 bg-slate-50 dark:bg-slate-950 transition-colors duration-500">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black text-slate-800 dark:text-white mb-4">Planos</h2>
                    <p class="text-lg text-slate-500 dark:text-slate-400">Escolha o plano ideal para sua jornada financeira.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- FREE --}}
                    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-8 shadow-sm">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-1">{{ $planFreeName }}</h3>
                        <p class="text-3xl font-black text-slate-600 dark:text-slate-300 mb-6">Gratuito</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="building-columns" style="duotone" class="w-4 h-4 text-slate-400" />
                                Até {{ $freeLimits['account'] ?? 1 }} conta(s)
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="arrow-up" style="duotone" class="w-4 h-4 text-slate-400" />
                                Até {{ $freeLimits['income'] ?? 15 }} receitas
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="arrow-down" style="duotone" class="w-4 h-4 text-slate-400" />
                                Até {{ $freeLimits['expense'] ?? 15 }} despesas
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="bullseye" style="duotone" class="w-4 h-4 text-slate-400" />
                                Até {{ $freeLimits['goal'] ?? 1 }} meta(s)
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="chart-pie" style="duotone" class="w-4 h-4 text-slate-400" />
                                Até {{ $freeLimits['budget'] ?? 1 }} orçamento(s)
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500">
                                <x-icon name="minus" style="solid" class="w-4 h-4" />
                                Relatórios PDF
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="robot" style="duotone" class="w-4 h-4 text-slate-400" />
                                Vertex Bot básico
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Suporte via Ticket
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full py-4 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-center hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Começar Grátis
                        </a>
                    </div>
                    @foreach($paidPlans as $index => $paidPlan)
                    <div class="rounded-3xl border-2 border-indigo-500/50 bg-gradient-to-br from-indigo-500/10 to-violet-500/10 dark:from-indigo-900/20 dark:to-violet-900/20 p-8 shadow-xl relative">
                        @if($index === 0)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-indigo-500 text-white text-xs font-black uppercase">Recomendado</div>
                        @endif
                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-1">{{ $paidPlan->name }}</h3>
                        <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400 mb-1">R$ {{ $paidPlan->amount ? number_format((float) $paidPlan->amount, 2, ',', '.') : '29,90' }}<span class="text-lg font-medium text-slate-500">/{{ $paidPlan->billing_interval === 'yearly' ? 'ano' : 'mês' }}</span></p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">@if($paidPlan->billing_interval === 'monthly')7 dias grátis · @endif Cancele quando quiser</p>
                        <ul class="space-y-3 mb-8">
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                {{ $proAccountDisplay }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Receitas {{ $proIncomeDisplay }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Despesas {{ $proExpenseDisplay }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Metas {{ $proGoalDisplay }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Orçamentos {{ $proBudgetDisplay }}
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Relatórios PDF e Excel
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Vertex Bot + Consultoria PRO
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                <x-icon name="check" style="solid" class="w-4 h-4 text-emerald-500" />
                                Suporte prioritário
                            </li>
                        </ul>
                        <a href="{{ auth()->check() ? route('user.subscription.index') : route('register') }}" class="block w-full py-4 rounded-2xl bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-400 hover:to-violet-400 text-white font-bold text-center shadow-lg shadow-indigo-500/25 hover:shadow-[0_0_30px_rgba(99,102,241,0.4)] transition-all">
                            Assinar {{ $paidPlan->name }}
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

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
