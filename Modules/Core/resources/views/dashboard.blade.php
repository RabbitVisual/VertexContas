{{-- Dashboard PRO - Vertex CBAV: completo, profissional, ícones Font Awesome locais --}}
@php
    $user = auth()->user();
    $userName = $user->full_name ?? $user->first_name ?? 'Membro PRO';
    $firstName = explode(' ', $userName)[0] ?? $userName;
    $financialScore = $vertexBot['financial_score'] ?? 0;
    // 0 = em análise (sem dados); 1-40 = em risco; 41-70 = atenção; 71-100 = sem risco
    $scoreLabel = $financialScore === 0 ? 'Em análise' : ($financialScore <= 40 ? 'Em risco' : ($financialScore <= 70 ? 'Atenção' : 'Sem risco'));
    $scoreTextClass = $financialScore === 0 ? 'text-slate-500 dark:text-slate-400' : ($financialScore <= 40 ? 'text-red-600 dark:text-red-400' : ($financialScore <= 70 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'));
    $scoreBorderClass = $financialScore === 0 ? 'hover:border-slate-400/30' : ($financialScore <= 40 ? 'hover:border-red-500/30' : ($financialScore <= 70 ? 'hover:border-amber-500/30' : 'hover:border-emerald-500/30'));
    $greeting = match (true) {
        now()->hour < 12 => 'Bom dia',
        now()->hour < 18 => 'Boa tarde',
        default => 'Boa noite',
    };
    $photoUrl = $user->photo_url ?? null;
    $hasPhoto = !empty($user->photo);
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
    <div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
        {{-- Hero - Vertex CBAV style (card claro, blur orbs, breadcrumb) --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none" data-tour="dashboard-intro">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                        <span>Painel</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                        <span class="text-gray-400 dark:text-gray-500">Dashboard</span>
                    </nav>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider border border-amber-200 dark:border-amber-500/30">{{ plan_pro_name() }}</span>
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">
                        {{ $greeting }}, {{ $firstName }}!<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-emerald-600 dark:from-amber-400 dark:to-emerald-400">Painel Financeiro</span>
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed mb-6">
                        Bem-vindo ao seu centro de controle financeiro. Aqui está o resumo das suas finanças.
                    </p>
                    <div class="flex flex-wrap gap-3" data-tour="dashboard-actions">
                        @if(!empty($dashboardTourId) && count($dashboardTourSteps ?? []) > 0)
                            <x-core::tour-guide :tour-id="$dashboardTourId" label="Ver tour desta página" />
                        @endif
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-500/20 transition-all">
                                <x-icon name="plus" style="solid" class="w-5 h-5" />
                                Nova Transação
                            </a>
                            <a href="{{ route('core.transactions.transfer') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                                <x-icon name="right-left" style="duotone" class="w-5 h-5 text-emerald-500" />
                                Transferir
                            </a>
                        @endif
                        <a href="{{ route('core.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white font-bold text-sm hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                            <x-icon name="chart-simple" style="duotone" class="w-5 h-5 text-amber-500" />
                            Relatórios
                        </a>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0" data-tour="dashboard-balance">
                    <div class="flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <x-icon name="wallet" style="duotone" class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Saldo total</p>
                            <p class="sensitive-value text-2xl font-black text-gray-900 dark:text-white leading-tight"><x-core::financial-value :value="$totalBalance" /></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Distribuição 50/30/20 - Mentor Financeiro --}}
        @php
            $pillars503020 = $distribution503020['pillars'] ?? [];
            $income503020 = $distribution503020['income'] ?? 0;
        @endphp
        <div
            x-data="{
                loaded: false,
                init() {
                    requestAnimationFrame(() => { this.loaded = true; });
                }
            }"
            class="rounded-3xl border border-emerald-200/70 dark:border-emerald-700/60 bg-emerald-50/60 dark:bg-emerald-950/40 px-6 py-6 sm:px-8 sm:py-7 flex flex-col gap-5"
        >
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-black text-emerald-900 dark:text-emerald-100 flex items-center gap-2">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-emerald-500/15 text-emerald-700 dark:text-emerald-300">
                            <x-icon name="scale-balanced" style="duotone" class="w-4 h-4" />
                        </span>
                        Regra 50/30/20 aplicada à sua renda
                    </h2>
                    <p class="mt-1 text-sm text-emerald-900/80 dark:text-emerald-100/80 max-w-2xl">
                        Estes três blocos mostram como sua renda do mês está sendo distribuída entre <span class="font-semibold">Necessidades</span>, <span class="font-semibold">Desejos</span> e <span class="font-semibold">Futuro e Metas</span>.
                        Não é sobre cortar tudo, e sim ajustar aos poucos.
                    </p>
                </div>
                <div class="text-xs text-emerald-900/70 dark:text-emerald-100/80 space-y-1">
                    <p>Renda considerada: <span class="sensitive-value font-semibold"><x-core::financial-value :value="$income503020" /></span></p>
                    <p>Objetivo saudável: <span class="font-semibold">50%</span> Necessidades · <span class="font-semibold">30%</span> Desejos · <span class="font-semibold">20%</span> Futuro</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $pillarOrder = [
                        'necessities' => ['label' => 'Necessidades', 'color' => 'emerald'],
                        'wants' => ['label' => 'Desejos', 'color' => 'amber'],
                        'future' => ['label' => 'Futuro e Metas', 'color' => 'indigo'],
                    ];
                @endphp
                @foreach($pillarOrder as $key => $meta)
                    @php
                        $pillar = $pillars503020[$key] ?? ['percentage' => 0, 'target' => $key === 'necessities' ? 50 : ($key === 'wants' ? 30 : 20), 'amount' => 0, 'status' => 'ok'];
                        $pct = (float) ($pillar['percentage'] ?? 0);
                        $target = (int) ($pillar['target'] ?? 0);
                        $status = $pillar['status'] ?? 'ok';
                        $barBase = [
                            'emerald' => 'bg-emerald-500',
                            'amber' => 'bg-amber-500',
                            'indigo' => 'bg-indigo-500',
                        ][$meta['color']];
                        $barColor = $status === 'danger'
                            ? 'bg-rose-500'
                            : ($status === 'warning'
                                ? 'bg-amber-500'
                                : $barBase);
                        $statusLabel = $status === 'danger'
                            ? 'Atenção extra'
                            : ($status === 'warning' ? 'Ajuste gentil recomendado' : 'Dentro da zona saudável');
                    @endphp
                    <div class="rounded-2xl bg-white/80 dark:bg-gray-950/40 border border-emerald-100/80 dark:border-emerald-800/60 px-4 py-4 shadow-sm flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-emerald-900/90 dark:text-emerald-50 uppercase tracking-wider">{{ $meta['label'] }}</p>
                                <p class="text-[11px] text-emerald-900/70 dark:text-emerald-100/80">
                                    Alvo: <span class="font-semibold">{{ $target }}%</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-emerald-900 dark:text-emerald-100 tabular-nums">
                                    {{ number_format($pct, 1, ',', '.') }}%
                                </p>
                                <p class="text-[10px] text-emerald-900/70 dark:text-emerald-100/70">{{ $statusLabel }}</p>
                            </div>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-emerald-100/70 dark:bg-emerald-900/60 overflow-hidden">
                            <div
                                class="{{ $barColor }} h-2.5 rounded-full transition-all duration-700"
                                :style="loaded ? 'width: {{ max(0, min(100, $pct)) }}%' : 'width: 0%'"
                            ></div>
                        </div>
                        <p class="text-[11px] text-emerald-900/80 dark:text-emerald-100/80">
                            Valor estimado: <span class="sensitive-value font-semibold"><x-core::financial-value :value="$pillar['amount'] ?? 0" /></span>
                        </p>
                        @if($key === 'necessities')
                            <p class="text-[11px] text-emerald-900/80 dark:text-emerald-100/80">
                                Inclui moradia, alimentação, contas essenciais e transporte. Se este bloco estiver muito alto, vamos olhar com carinho para as despesas fixas.
                            </p>
                        @elseif($key === 'wants')
                            <p class="text-[11px] text-emerald-900/80 dark:text-emerald-100/80">
                                Aqui entram lazer, presentes e confortos do dia a dia. A ideia não é cortar tudo, mas escolher com mais intenção o que faz sentido manter.
                            </p>
                        @else
                            <p class="text-[11px] text-emerald-900/80 dark:text-emerald-100/80">
                                Espaço reservado para reserva de emergência e metas. Mesmo um valor pequeno e constante já te coloca em movimento na direção certa.
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Stats Grid - CBAV style (Score, Receitas, Despesas, Capacidade mensal; Saldo só no hero) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Score Financeiro (Gauge) --}}
            <x-core::financial-score-card
                :score="$financialScore"
                :label="$scoreLabel"
                :score-text-class="$scoreTextClass"
                :score-border-class="$scoreBorderClass"
                :is-pro="auth()->user()?->isPro() ?? false"
            />
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6" data-tour="dashboard-income">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 ring-1 ring-black/5 dark:ring-white/10 shrink-0">
                        <x-icon name="arrow-trend-up" style="duotone" class="w-6 h-6" />
                    </div>
                    <span class="flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-lg">+{{ format_percent($incomeTrendPercentage, 1) }}</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Receitas do mês</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$monthlyIncome" /></h3>
                <div>
                @if(isset($monthlyCapacity))
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Capacidade mensal (recorrente): <span class="sensitive-value font-semibold text-gray-700 dark:text-gray-300"><x-core::financial-value :value="$monthlyCapacity" /></span></p>
                @endif
                @if(isset($monthlyGoalContributions) && $monthlyGoalContributions > 0)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Comprometido com metas: <span class="sensitive-value font-semibold text-teal-600 dark:text-teal-400"><x-core::financial-value :value="$monthlyGoalContributions" /></span>/mês</p>
                @endif
                @if(isset($amountToGoalsThisMonth) && $amountToGoalsThisMonth > 0)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Destinado a metas este mês: <span class="sensitive-value font-semibold text-teal-600 dark:text-teal-400"><x-core::financial-value :value="$amountToGoalsThisMonth" /></span></p>
                @endif
                </div>
                @if($user->isPro() && isset($incomeBreakdown) && $incomeBreakdown->count() > 1)
                    <div class="mt-2" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium flex items-center gap-1">
                            Ver detalhe por fonte
                            <span class="inline-block transition-transform" :class="open ? 'rotate-180' : ''"><x-icon name="chevron-down" style="solid" class="w-3 h-3" /></span>
                        </button>
                        <ul x-show="open" x-collapse class="mt-1.5 space-y-3 text-xs text-gray-600 dark:text-gray-400">
                            @foreach($incomeBreakdown as $item)
                                <li class="flex flex-col gap-0.5 py-1.5 border-b border-gray-100 dark:border-white/5 last:border-0">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item['description'] }}</span>
                                    <span class="sensitive-value tabular-nums text-emerald-600 dark:text-emerald-400 font-semibold"><x-core::financial-value :value="$item['amount']" /></span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-rose-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 dark:bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 ring-1 ring-black/5 dark:ring-white/10 shrink-0">
                        <x-icon name="arrow-trend-down" style="duotone" class="w-6 h-6" />
                    </div>
                    <span class="flex items-center text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-100 dark:bg-rose-900/30 px-2.5 py-1 rounded-lg">{{ format_percent($expenseTrendPercentage, 1) }}</span>
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Despesas do mês</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$monthlyExpenses" /></h3>
            </div>

            {{-- Capacidade mensal (GFP); Balanço mensal como linha secundária --}}
            <div class="group relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 shadow-sm hover:shadow-xl transition-all duration-500 p-6" data-tour="dashboard-capacity" @if(isset($availableToPlan)) data-available-to-plan="{{ $availableToPlan['available'] }}" @endif>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 ring-1 ring-black/5 dark:ring-white/10 mb-4 shrink-0">
                    <x-icon name="chart-line" style="duotone" class="w-6 h-6" />
                </div>
                <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Capacidade mensal</p>
                <h3 class="sensitive-value text-2xl lg:text-3xl font-black text-gray-900 dark:text-white mt-1 tabular-nums"><x-core::financial-value :value="$monthlyCapacity" /></h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Balanço do mês (receitas − despesas): <span class="sensitive-value font-semibold tabular-nums {{ $monthlyBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}"><x-core::financial-value :value="$monthlyBalance" /></span></p>
                @if(isset($availableToPlan))
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 pt-2 border-t border-gray-100 dark:border-white/5">
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Dinheiro livre para planejar:</span>
                        <span class="sensitive-value font-bold tabular-nums text-emerald-600 dark:text-emerald-400"><x-core::financial-value :value="$availableToPlan['available']" /></span>
                        <span class="block mt-0.5 text-[10px] text-gray-500 dark:text-gray-400">Renda prevista menos orçamentos fixos e metas automáticas.</span>
                    </p>
                @endif
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Dica: na regra 50/30/20, parte do 20% de poupança pode ir para reserva de emergência e metas.</p>
            </div>
        </div>

        {{-- Por onde começar (fluxo GFP para novos ou poucos dados) --}}
        @if(!empty($showOnboardingFlow) && $showOnboardingFlow)
        <div class="rounded-3xl border border-emerald-200 dark:border-emerald-800/40 bg-emerald-50/50 dark:bg-emerald-950/30 p-6 lg:p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 dark:bg-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <x-icon name="route" style="duotone" class="w-5 h-5" />
                </div>
                Por onde começar
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Siga a ordem abaixo para organizar suas finanças no Vertex Contas.</p>
            <ol class="space-y-3">
                <li class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold">1</span>
                    <a href="{{ route('core.accounts.index') }}" class="flex-1 font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Cadastre suas contas</a>
                    <x-icon name="chevron-right" style="solid" class="w-4 h-4 text-gray-400" />
                </li>
                <li class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold">2</span>
                    <a href="{{ route('core.income.index') }}" class="flex-1 font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Configure Minha Renda</a>
                    <x-icon name="chevron-right" style="solid" class="w-4 h-4 text-gray-400" />
                </li>
                <li class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold">3</span>
                    <a href="{{ route('core.transactions.index') }}" class="flex-1 font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Registre receitas e despesas no Extrato</a>
                    <x-icon name="chevron-right" style="solid" class="w-4 h-4 text-gray-400" />
                </li>
                <li class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold">4</span>
                    <a href="{{ route('core.budgets.index') }}" class="flex-1 font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Defina orçamentos por categoria</a>
                    <x-icon name="chevron-right" style="solid" class="w-4 h-4 text-gray-400" />
                </li>
                <li class="flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5">
                    <span class="flex w-8 h-8 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white text-sm font-bold">5</span>
                    <a href="{{ route('core.goals.index') }}" class="flex-1 font-semibold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Crie metas</a>
                    <x-icon name="chevron-right" style="solid" class="w-4 h-4 text-gray-400" />
                </li>
            </ol>
        </div>
        @endif

        {{-- Projeção Vertex AI - Card interativo --}}
        <div
            x-data="{
                projection: null,
                loading: false,
                error: null,
                async analyze() {
                    this.loading = true;
                    this.error = null;
                    this.projection = null;
                    try {
                        const r = await fetch('{{ route('core.projection.analyze') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                            }
                        });
                        const data = await r.json();
                        if (!r.ok) {
                            this.error = data.error || 'Erro ao analisar.';
                            return;
                        }
                        this.projection = data.projection || null;
                    } catch (e) {
                        this.error = 'Falha na conexão. Tente novamente.';
                    } finally {
                        this.loading = false;
                    }
                }
            }"
            class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden"
        >
            <div class="p-6 lg:p-8">
                <div class="flex items-center gap-2.5 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <x-icon name="chart-line" style="duotone" class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Projeção Vertex AI</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Análise e projeção do seu futuro financeiro</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ $projectionData['trend_summary'] ?? 'Carregando tendências...' }}</p>
                <div class="flex flex-wrap gap-4 mb-4 text-xs">
                    <span class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300">
                        Reserva: {{ $projectionData['reserve_months'] ?? 0 }} meses
                    </span>
                    <span class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-300">
                        Taxa de poupança: {{ $projectionData['savings_rate'] ?? 0 }}%
                    </span>
                    @if(isset($aiReportUsage) && $aiReportUsage['limit'] > 0)
                        <span class="px-3 py-1.5 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                            Relatórios IA: {{ $aiReportUsage['count'] }} de {{ $aiReportUsage['limit'] }}/mês
                        </span>
                    @endif
                </div>
                <button
                    @click="analyze()"
                    :disabled="loading"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-bold text-sm transition-colors"
                >
                    <span x-show="!loading" class="flex items-center gap-2">
                        <x-icon name="wand-magic-sparkles" style="solid" class="w-4 h-4" />
                        Analisar meu futuro
                    </span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Analisando...
                    </span>
                </button>
                <div x-show="error" x-text="error" class="mt-3 text-sm text-rose-600 dark:text-rose-400"></div>
                <div x-show="projection" x-transition class="mt-4 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800/50">
                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-200 mb-2">Projeção em 1 ano:</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300" x-text="projection"></p>
                </div>
            </div>
        </div>

        {{-- Charts Section - Visão Geral (CBAV cards) --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Minhas Contas --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                            </div>
                            Minhas Contas
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $accounts->count() }} conta(s) cadastrada(s)</p>
                    </div>
                    @can('create', \Modules\Core\Models\Account::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.accounts.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova</a>
                        @endif
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-2 max-h-80 overflow-y-auto">
                    @forelse($accounts as $account)
                        <a href="{{ route('core.accounts.show', $account) }}"
                           class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-white/5 hover:bg-primary-50 dark:hover:bg-primary-500/10 border border-transparent hover:border-primary-200 dark:hover:border-primary-500/20 transition-all group">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300">{{ $account->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ $account->type }}</p>
                            </div>
                            <p class="sensitive-value font-bold text-primary-600 dark:text-primary-400 tabular-nums"><x-core::financial-value :value="$account->balance" /></p>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="building-columns" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma conta cadastrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.accounts.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar conta
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Gráficos --}}
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-900/50 rounded-3xl p-6 lg:p-8 border border-gray-200 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                    <x-icon name="chart-area" style="duotone" class="w-5 h-5" />
                                </div>
                                Fluxo de Caixa
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Últimos 6 meses</p>
                        </div>
                        <a href="{{ route('core.reports.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg transition-colors" title="Ver relatórios">
                            <x-icon name="ellipsis" style="solid" class="w-5 h-5 text-gray-400" />
                        </a>
                    </div>
                    <div id="cashFlowChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 280px;" @if(\Modules\Core\Services\InspectionGuard::shouldHideFinancialData()) title="Oculto por privacidade durante a inspeção" @endif></div>
                </div>
                <div class="bg-white dark:bg-gray-900/50 rounded-3xl p-6 lg:p-8 border border-gray-200 dark:border-white/5 shadow-sm">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                                <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                            </div>
                            Gastos por Categoria
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Distribuição das despesas</p>
                    </div>
                    <div id="categoryChart" class="sensitive-value w-full {{ \Modules\Core\Services\InspectionGuard::maskClasses() }}" style="min-height: 220px;" @if(\Modules\Core\Services\InspectionGuard::shouldHideFinancialData()) title="Oculto por privacidade durante a inspeção" @endif></div>
                </div>
            </div>
        </div>

        {{-- Atividade Recente - 2 cols estilo CBAV --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Transações Recentes --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <x-icon name="receipt" style="duotone" class="w-5 h-5" />
                        </div>
                        Transações Recentes
                    </h3>
                    <a href="{{ route('core.transactions.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Ver todas</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-96 overflow-y-auto">
                    @forelse($recentTransactions as $transaction)
                        <a href="{{ ($inspectionReadOnly ?? false) ? route('core.transactions.index') : route('core.transactions.edit', $transaction) }}" class="flex items-center justify-between p-4 lg:px-8 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }} flex items-center justify-center shrink-0">
                                    <x-icon name="{{ $transaction->type === 'income' ? 'arrow-up' : 'arrow-down' }}" style="solid" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $transaction->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d/m/Y') }} · {{ $transaction->category->name ?? 'Geral' }}</p>
                                </div>
                            </div>
                            <span class="sensitive-value font-mono font-bold tabular-nums {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} <x-core::financial-value :value="$transaction->amount" />
                            </span>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="receipt" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma transação registrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.transactions.create') }}" class="inline-flex items-center gap-2 mt-2 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">Registrar transação</a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Metas em Destaque --}}
            <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                            <x-icon name="bullseye" style="duotone" class="w-5 h-5" />
                        </div>
                        Metas em Destaque
                    </h3>
                    @can('create', \Modules\Core\Models\Goal::class)
                        @if(!($inspectionReadOnly ?? false))
                            <a href="{{ route('core.goals.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Nova Meta</a>
                        @endif
                    @endcan
                </div>
                <div class="p-4 lg:p-6 space-y-4 max-h-96 overflow-y-auto">
                    @forelse($goals as $goal)
                        <x-core::goal-card :goal="$goal" />
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="bullseye" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhuma meta cadastrada</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.goals.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar meta
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Orçamentos --}}
        <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="p-6 lg:p-8 border-b border-gray-100 dark:border-white/5 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5" />
                    </div>
                    Orçamentos
                </h3>
                @can('create', \Modules\Core\Models\Budget::class)
                    @if(!($inspectionReadOnly ?? false))
                        <a href="{{ route('core.budgets.create') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:underline transition-colors">Novo Orçamento</a>
                    @endif
                @endcan
            </div>
            <div class="p-4 lg:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($budgets as $budget)
                        <x-core::budget-card :budget="$budget" />
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 text-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/50 mx-4 mb-4">
                            <div class="w-16 h-16 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4 shadow-sm border border-gray-100 dark:border-none">
                                <x-icon name="chart-pie" style="duotone" class="w-8 h-8 opacity-40 dark:opacity-20" />
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white mb-1">Nenhum orçamento cadastrado</p>
                            @if(!($inspectionReadOnly ?? false))
                                <a href="{{ route('core.budgets.create') }}" class="inline-flex items-center gap-2 mt-3 text-primary-600 dark:text-primary-400 text-sm font-semibold hover:underline">
                                    <x-icon name="plus-circle" style="duotone" class="w-4 h-4" /> Criar orçamento
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Dicas PRO / Ações Rápidas - CBAV style --}}
        <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 p-6 lg:p-8 space-y-6">
            <h4 class="font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="lightbulb" style="duotone" class="w-5 h-5" />
                </div>
                Dicas PRO
            </h4>
            {{-- Consultoria IA & Projeções - Paywall visual --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 relative rounded-2xl border border-indigo-200 dark:border-indigo-800/60 bg-white dark:bg-gray-900/60 p-5 overflow-hidden">
                    @php $isProUser = auth()->user()?->isPro() ?? false; @endphp
                    @if(!$isProUser)
                        <div class="absolute inset-0 bg-white/60 dark:bg-gray-900/70 backdrop-blur-sm pointer-events-none"></div>
                    @endif
                    <div class="relative z-10 flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <x-icon name="sparkles" style="solid" class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Consultoria IA & Projeções</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Um mentor financeiro ao seu lado, usando seus números reais para sugerir próximos passos inteligentes.
                                </p>
                            </div>
                        </div>
                        @if($isProUser && ($proInsights['available'] ?? false))
                            <ul class="mt-1 space-y-1.5 text-xs text-gray-700 dark:text-gray-300">
                                @foreach(($proInsights['highlights'] ?? []) as $highlight)
                                    <li class="flex items-start gap-2">
                                        <span class="mt-0.5 h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @if(!empty($proInsights['actions']))
                                <p class="mt-2 text-xs font-semibold text-gray-900 dark:text-white">Próximos passos sugeridos:</p>
                                <ul class="mt-1 space-y-1.5 text-xs text-gray-700 dark:text-gray-300">
                                    @foreach($proInsights['actions'] as $action)
                                        <li class="flex items-start gap-2">
                                            <x-icon name="circle-check" style="duotone" class="w-3.5 h-3.5 text-emerald-500 mt-0.5" />
                                            <span>{{ $action }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <p class="mt-3 text-[11px] text-gray-500 dark:text-gray-400">
                                {{ $proInsights['message'] ?? '' }}
                            </p>
                        @else
                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                No plano Grátis você já tem a visão 50/30/20. No Vertex Pro, o sistema analisa seus dados todos os meses e sugere
                                pequenos ajustes práticos, sem julgamentos, para você avançar no seu ritmo.
                            </p>
                        @endif
                    </div>
                    @if(!$isProUser)
                        <div class="relative z-20 mt-4 flex justify-end">
                            <a href="{{ route('user.subscription.index') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-500/30 transition-colors">
                                <x-icon name="crown" style="solid" class="w-3.5 h-3.5" />
                                Desbloquear com Pro
                            </a>
                        </div>
                    @endif
                </div>
                <div class="space-y-4">
                    <div class="rounded-2xl border border-gray-200 dark:border-white/10 bg-white/90 dark:bg-gray-900/60 p-4 text-xs text-gray-700 dark:text-gray-300">
                        <p class="font-semibold text-gray-900 dark:text-white mb-1">Como usar este painel</p>
                        <p>
                            Use o 50/30/20 como bússola, não como regra rígida. Escolha um único ajuste por vez e acompanhe por 1 mês.
                            Assim você cria progresso sem peso ou culpa.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('core.reports.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-primary-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="chart-simple" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Relatórios e exportações</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">PDF, CSV e análise completa</p>
                    </div>
                </a>
                @if(!($inspectionReadOnly ?? false))
                    <a href="{{ route('core.transactions.transfer') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-emerald-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-icon name="right-left" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">Transferir entre contas</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mova saldo entre suas contas</p>
                        </div>
                    </a>
                @endif
                <a href="{{ route('core.invoices.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-amber-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="file-invoice-dollar" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Minhas faturas</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Próxima cobrança e histórico</p>
                    </div>
                </a>
                <a href="{{ route('user.tickets.index') }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 hover:border-blue-500/30 hover:shadow-lg hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 dark:bg-blue-500/20 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <x-icon name="headset" style="duotone" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">Suporte prioritário</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Abertura de chamados</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            var cashFlowData = {!! json_encode($cashFlowData ?? ['income' => [0, 0, 0, 0, 0, 0], 'expenses' => [0, 0, 0, 0, 0, 0], 'months' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun']]) !!};
            var categoryData = {!! json_encode($categoryData ?? ['labels' => ['Sem dados'], 'values' => [0]]) !!};

            document.addEventListener('DOMContentLoaded', function() {
                if (typeof ApexCharts === 'undefined') return;

                var isDark = document.documentElement.classList.contains('dark');
                var textColor = isDark ? '#94a3b8' : '#64748b';

                if (document.querySelector('#cashFlowChart')) {
                    new ApexCharts(document.querySelector('#cashFlowChart'), {
                        series: [
                            { name: 'Receitas', data: cashFlowData.income || [0,0,0,0,0,0] },
                            { name: 'Despesas', data: cashFlowData.expenses || [0,0,0,0,0,0] }
                        ],
                        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
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

                if (document.querySelector('#categoryChart')) {
                    new ApexCharts(document.querySelector('#categoryChart'), {
                        series: categoryData.values || [0],
                        chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
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

    @if(!empty($dashboardTourId) && !empty($dashboardTourSteps))
    @push('scripts')
    <script>
    (function() {
        var tourId = @json($dashboardTourId);
        var steps = @json($dashboardTourSteps);
        function register() {
            if (window.registerVertexTourSteps && steps && steps.length) {
                window.registerVertexTourSteps(tourId, steps);
                return;
            }
            setTimeout(register, 50);
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', register);
        } else {
            register();
        }
    })();
    </script>
    @endpush
    @endif
</x-paneluser::layouts.master>
