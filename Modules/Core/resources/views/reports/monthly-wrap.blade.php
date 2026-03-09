<x-paneluser::layouts.master :title="'Resumo Mensal'">
@php
    $isPro = $isPro ?? (auth()->user()?->isPro() ?? false);
    $pillars = $distribution['pillars'] ?? [];
    $income = (float) ($distribution['income'] ?? 0);
    $totalExpenses = (float) ($distribution['total_expenses'] ?? 0);
    $savingsPct = (float) ($distribution['savings_pct'] ?? 0);

    $necessities = $pillars['necessities'] ?? null;
    $wants = $pillars['wants'] ?? null;
    $future = $pillars['future'] ?? null;

    $wantsPct = $wants['percentage'] ?? 0;
    $wantsTarget = $wants['target'] ?? 30;
    $wantsStatus = $wants['status'] ?? 'ok';

    $monthName = \Carbon\Carbon::create($targetYear, $targetMonth, 1)
        ->locale('pt_BR')
        ->translatedFormat('F');
    $currentMonthName = now()->locale('pt_BR')->translatedFormat('F');
@endphp
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
    {{-- Hero Celebrativo --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-emerald-600 via-teal-600 to-slate-900 dark:from-emerald-500 dark:via-teal-500 dark:to-slate-900 border border-emerald-400/40 dark:border-emerald-300/30 p-8 sm:p-12 shadow-xl">
        <div class="absolute inset-0 opacity-30 mix-blend-soft-light pointer-events-none">
            <div class="absolute -top-10 -right-10 w-64 h-64 rounded-full bg-emerald-300/40 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-8 w-72 h-72 rounded-full bg-teal-400/40 blur-3xl"></div>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-4">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-900/40 border border-emerald-300/40 text-emerald-50 text-[11px] font-black uppercase tracking-[0.18em]">
                    <i class="fa-pro fa-solid fa-chart-pie text-emerald-200 text-xs"></i>
                    Resumo Mensal · Regra 50/30/20
                </span>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                    Fechamento de {{ \Illuminate\Support\Str::ucfirst($monthName) }} {{ $targetYear }}
                </h1>
                <p class="text-emerald-50/90 text-sm sm:text-base max-w-xl leading-relaxed">
                    Aqui vai o seu <strong>\"Financial Wrapped\"</strong> de {{ \Illuminate\Support\Str::lower($monthName) }}: como você distribuiu a renda entre necessidades, desejos e futuro — sem julgamentos, só com orientação gentil.
                </p>
            </div>
            <div class="shrink-0 w-full md:w-auto">
                <div class="rounded-3xl bg-emerald-900/40 border border-emerald-300/30 px-6 py-5 shadow-lg backdrop-blur-md max-w-xs ml-auto">
                    <p class="text-[10px] font-black text-emerald-100/80 uppercase tracking-[0.2em] mb-1">
                        No mês passado, seus desejos foram
                    </p>
                    <div class="flex items-end gap-3 mb-3">
                        <p class="text-4xl font-black text-emerald-50 tabular-nums">
                            {{ number_format($wantsPct, 1, ',', '.') }}%
                        </p>
                        <p class="text-xs text-emerald-100/80 leading-snug">
                            da sua renda em <br> {{ \Illuminate\Support\Str::lower($monthName) }}
                        </p>
                    </div>
                    <p class="text-xs text-emerald-50/90">
                        A referência saudável são <strong>{{ $wantsTarget }}%</strong> para desejos.
                        @if($wantsStatus === 'danger')
                            Você saiu bastante da faixa, mas o importante é que agora isso está claro — e cada ajuste daqui pra frente conta.
                        @elseif($wantsStatus === 'warning')
                            Ficou um pouco acima da meta, um ótimo ponto de atenção suave para os próximos meses.
                        @else
                            Você se manteve próximo da meta de {{ $wantsTarget }}%. Excelente ponto de equilíbrio entre curtir o presente e cuidar do futuro.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards 50/30/20 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $cards = [
                [
                    'key' => 'necessities',
                    'label' => 'Necessidades',
                    'subtitle' => 'Contas fixas, moradia, saúde',
                    'accent' => 'emerald',
                ],
                [
                    'key' => 'wants',
                    'label' => 'Desejos',
                    'subtitle' => 'Lazer, confortos, mimos',
                    'accent' => 'amber',
                ],
                [
                    'key' => 'future',
                    'label' => 'Futuro e Metas',
                    'subtitle' => 'Reserva, dívidas, investimentos',
                    'accent' => 'sky',
                ],
            ];
        @endphp

        @foreach($cards as $card)
            @php
                $pillar = $pillars[$card['key']] ?? ['percentage' => 0, 'target' => $card['key'] === 'necessities' ? 50 : ($card['key'] === 'wants' ? 30 : 20), 'status' => 'ok'];
                $pct = (float) ($pillar['percentage'] ?? 0);
                $target = (int) ($pillar['target'] ?? 0);
                $status = $pillar['status'] ?? 'ok';

                $statusLabel = match ($status) {
                    'danger' => 'Ponto de alerta',
                    'warning' => 'Ponto de atenção',
                    default => 'Dentro da rota',
                };

                $barColor = match ($card['accent']) {
                    'emerald' => 'bg-emerald-500',
                    'amber' => 'bg-amber-500',
                    default => 'bg-sky-500',
                };

                $badgeBg = match ($status) {
                    'danger' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                    'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                    default => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                };
            @endphp
            <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-900/40 border border-gray-200 dark:border-white/10 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-40 blur-2xl
                    @if($card['accent'] === 'emerald') bg-emerald-500/20
                    @elseif($card['accent'] === 'amber') bg-amber-500/20
                    @else bg-sky-500/20 @endif"></div>
                <div class="relative z-10 p-6 space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.19em] mb-1">
                                Pilar {{ $card['key'] === 'necessities' ? '50%' : ($card['key'] === 'wants' ? '30%' : '20%') }}
                            </p>
                            <h2 class="text-lg font-black text-gray-900 dark:text-white">{{ $card['label'] }}</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $card['subtitle'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Usado em {{ \Illuminate\Support\Str::lower($monthName) }}</p>
                            <p class="text-2xl font-black text-gray-900 dark:text-white tabular-nums">
                                {{ number_format($pct, 1, ',', '.') }}%
                            </p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Meta: {{ $target }}%</p>
                        </div>
                    </div>
                    <div class="h-2.5 bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-500"
                             style="width: {{ min(max($pct, 0), 130) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] font-semibold text-gray-600 dark:text-gray-300">
                        <span class="{{ $badgeBg }} inline-flex items-center gap-1 px-2 py-0.5 rounded-lg">
                            <span class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>
                            {{ $statusLabel }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            {{ $income > 0 ? 'Baseado na renda do mês' : 'Aguardando mais dados de renda' }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Medalhas do mês --}}
    <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-white/10 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <x-icon name="trophy" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 dark:text-white">Medalhas de {{ \Illuminate\Support\Str::ucfirst($monthName) }}</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Conquistas que refletem seus hábitos naquele mês.</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            @forelse($medals as $medal)
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-950/40 border border-gray-200 dark:border-white/10 mb-3 last:mb-0">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0"
                             style="background-color: {{ $medal['color'] ?? '#facc15' }}1a; color: {{ $medal['color'] ?? '#facc15' }};">
                            <i class="fa-pro fa-solid fa-medal text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-900 dark:text-white truncate">{{ $medal['title'] }}</p>
                            @if(!empty($medal['description']))
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ $medal['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!empty($medal['unlocked_at']))
                        <p class="text-xs text-gray-500 dark:text-gray-400 shrink-0">
                            Desbloqueada em {{ $medal['unlocked_at']->locale('pt_BR')->translatedFormat('d \\d\\e F') }}
                        </p>
                    @endif
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-10 text-center rounded-2xl bg-gray-50 dark:bg-gray-950/50 border border-dashed border-gray-200 dark:border-white/10">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-600 mb-4">
                        <x-icon name="trophy" style="duotone" class="w-8 h-8" />
                    </div>
                    <p class="font-bold text-gray-900 dark:text-white mb-1">Nenhuma medalha registrada nesse mês</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        Não tem problema. Às vezes a vida aperta mesmo. A boa notícia é que qualquer passo que você der nos próximos meses já pode render conquistas aqui.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Mentor VIP / Foco para o mês atual --}}
    <div class="relative rounded-3xl bg-slate-900 text-slate-50 border border-slate-700/70 shadow-2xl overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 rounded-full bg-emerald-500/30 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 w-72 h-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
        </div>
        <div class="relative z-10 p-6 sm:p-8 md:p-10 flex flex-col md:flex-row gap-8 md:items-center">
            <div class="flex-1 space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/60 text-emerald-100 text-[11px] font-black uppercase tracking-[0.2em]">
                    <i class="fa-pro fa-solid fa-robot text-emerald-200 text-xs"></i>
                    Mentor VIP · Foco para {{ \Illuminate\Support\Str::ucfirst($currentMonthName) }}
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight">Seu próximo passo importante</h2>
                @if($isPro && ($mentorInsights['available'] ?? false))
                    <p class="text-sm text-slate-200/90 max-w-2xl">
                        {{ $mentorInsights['message'] ?? 'Com base na sua distribuição 50/30/20 e nos seus hábitos recentes, aqui vão alguns pontos de foco sugeridos.' }}
                    </p>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach(($mentorInsights['highlights'] ?? []) as $idx => $highlight)
                            <div class="rounded-2xl bg-white/5 border border-emerald-400/20 p-4">
                                <p class="text-xs font-bold text-emerald-200 mb-1">Ponto {{ $idx + 1 }}</p>
                                <p class="text-sm text-slate-50">{{ $highlight }}</p>
                            </div>
                        @endforeach
                        @foreach(($mentorInsights['actions'] ?? []) as $idx => $action)
                            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/40 p-4">
                                <p class="text-xs font-bold text-emerald-200 mb-1">Ação sugerida</p>
                                <p class="text-sm text-slate-50">{{ $action }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="relative mt-2">
                        <div class="blur-sm pointer-events-none select-none">
                            <p class="text-sm text-slate-200/80 max-w-2xl">
                                Aqui você veria um resumo em linguagem simples do que mais te ajudaria nesse momento:
                                ajustes suaves em desejos, caminhos para fortalecer o futuro e como equilibrar o mês seguinte sem abrir mão do que é importante pra você.
                            </p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-200/80 list-disc list-inside">
                                <li>Quais categorias mais pesaram nos seus desejos.</li>
                                <li>Quanto falta para chegar perto da meta de 20% para futuro e metas.</li>
                                <li>Um plano em 2–3 passos, sem radicalismos, para o próximo mês.</li>
                            </ul>
                        </div>
                        <div class="absolute inset-0 bg-slate-900/75 backdrop-blur-md flex flex-col items-start justify-center p-4 sm:p-6 md:p-8">
                            <p class="text-xs font-black text-amber-300 uppercase tracking-[0.2em] mb-2">Conteúdo exclusivo {{ plan_pro_name() }}</p>
                            <h3 class="text-xl sm:text-2xl font-black text-white mb-3">Desbloqueie o Mentor VIP do seu mês</h3>
                            <p class="text-sm text-slate-200/90 max-w-xl mb-4">
                                No plano {{ plan_pro_name() }}, o Vertex lê a sua distribuição 50/30/20 e transforma em um foco simples para cada mês —
                                para você evoluir sem precisar virar especialista em finanças.
                            </p>
                            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-black text-sm uppercase tracking-[0.18em] shadow-lg shadow-amber-500/30 transition-all hover:scale-[1.02] active:scale-95">
                                <i class="fa-pro fa-solid fa-crown text-sm"></i>
                                Fazer upgrade para ver meu foco
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="shrink-0 w-full md:w-64">
                <div class="rounded-3xl bg-black/20 border border-white/10 p-5 space-y-4">
                    <p class="text-xs font-bold text-slate-200/80 uppercase tracking-[0.18em]">Resumo numérico</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-300">Renda considerada</span>
                            <span class="sensitive-value font-bold text-emerald-300">
                                <x-core::financial-value :value="$income" />
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-300">Total de gastos</span>
                            <span class="sensitive-value font-bold text-rose-300">
                                <x-core::financial-value :value="$totalExpenses" />
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-300">Poupança estimada</span>
                            <span class="font-bold text-emerald-200">
                                {{ number_format($savingsPct, 1, ',', '.') }}%
                            </span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-2">
                        Estes números não são um julgamento, e sim um espelho gentil do mês que passou — para que o próximo passo faça sentido pra você.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>

