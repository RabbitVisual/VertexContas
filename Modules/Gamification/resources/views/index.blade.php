@php
    $user = auth()->user();
    $isPro = $user?->isPro() ?? false;
    $gamificationService = app(\Modules\Core\Services\GamificationService::class);
    $analysis = $gamificationService->analyzeUser($user, 'gamification.index');
    $financialScore = (int) ($analysis['financial_score'] ?? 0);
    $scoreLabel = $financialScore === 0 ? 'Em análise' : ($financialScore <= 40 ? 'Em risco' : ($financialScore <= 70 ? 'Em atenção' : 'Saudável'));
    $scoreColor = $financialScore === 0 ? 'text-slate-500' : ($financialScore <= 40 ? 'text-rose-500' : ($financialScore <= 70 ? 'text-amber-500' : 'text-emerald-500'));
@endphp

<x-gamification::layouts.master>
    <div class="min-h-screen bg-slate-950 text-slate-50">
        <div class="max-w-5xl mx-auto px-4 py-10 space-y-8">
            {{-- Hero / Cabeçalho --}}
            <div class="rounded-3xl border border-slate-700/80 bg-linear-to-br from-slate-900/90 via-slate-950 to-slate-950 shadow-2xl shadow-slate-900/60 p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-emerald-400 mb-2">
                            Gamificação Financeira
                        </p>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight mb-2">
                            Seu Nível Financeiro no Vertex
                        </h1>
                        <p class="text-sm text-slate-300 max-w-xl">
                            Aqui você acompanha conquistas, medalhas e desafios que te ajudam a criar
                            uma rotina financeira leve, sem julgamentos e focada em pequenos passos consistentes.
                        </p>
                    </div>
                    <div class="shrink-0">
                        <div class="rounded-2xl border border-slate-700 bg-slate-900/80 px-5 py-4 shadow-lg flex flex-col gap-2 min-w-[220px]">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[11px] uppercase tracking-[0.2em] text-slate-400 font-bold">
                                    Nível Financeiro
                                </span>
                                <span class="text-[11px] font-semibold {{ $scoreColor }}">
                                    {{ $scoreLabel }}
                                </span>
                            </div>
                            <div class="flex items-end gap-3">
                                <span class="text-3xl sm:text-4xl font-black tabular-nums">{{ $financialScore }}</span>
                                <span class="text-xs text-slate-400 mb-1">de 100</span>
                            </div>
                            <p class="text-[11px] text-slate-400">
                                Este número resume como sua renda, gastos e reservas estão hoje.
                                Ele não é um rótulo, e sim um ponto de partida para próximos passos gentis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seção de Medidas e Progresso --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 rounded-3xl border border-slate-800 bg-slate-900/70 p-6 space-y-4">
                    <h2 class="text-sm font-semibold text-slate-100 flex items-center gap-2">
                        <i class="fa-pro fa-solid fa-medal text-amber-400"></i>
                        Suas medalhas 50/30/20
                    </h2>
                    <p class="text-xs text-slate-300">
                        Toda vez que você mantém a regra 50/30/20 próxima do ideal, o Vertex registra uma conquista.
                        O foco é reforçar o que você está fazendo bem e sugerir pequenos ajustes quando fizer sentido.
                    </p>
                    <p class="text-[11px] text-slate-400">
                        As medalhas <span class="font-semibold text-emerald-400">Equilibrista</span>,
                        <span class="font-semibold text-amber-300">Mestre dos Desejos</span> e
                        <span class="font-semibold text-indigo-300">Visionário</span>
                        são concedidas quando seus pilares de Necessidades, Desejos e Futuro estão em uma zona saudável ao longo do mês.
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Não se preocupe em “acertar de primeira”. O objetivo é aprender com os números, ajustar um hábito por vez
                        e celebrar cada avanço.
                    </p>
                </div>
                <div class="rounded-3xl border border-slate-800 bg-slate-900/70 p-6 space-y-3">
                    <h3 class="text-sm font-semibold text-slate-100 flex items-center gap-2">
                        <i class="fa-pro fa-solid fa-arrow-trend-up text-emerald-400"></i>
                        Próximo passo sugerido
                    </h3>
                    <p class="text-xs text-slate-300">
                        Use o Score apenas como bússola. Escolha um único foco para este mês
                        (por exemplo, reduzir um gasto de desejo ou começar uma pequena reserva)
                        e acompanhe sua evolução no Vertex.
                    </p>
                    <p class="text-[11px] text-slate-400">
                        Com o tempo, a combinação de medalhas, score e desafios te ajuda a criar uma rotina financeira
                        sólida, sem radicalismos.
                    </p>
                </div>
            </div>

            {{-- Paywall suave: Desafios de Coaching --}}
            <div class="rounded-3xl border border-slate-800 bg-slate-900/80 p-6 sm:p-7 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-100 flex items-center gap-2">
                            <i class="fa-pro fa-solid fa-flag-checkered text-amber-400"></i>
                            Desafios de Coaching (Exclusivo PRO)
                        </h2>
                        <p class="text-xs text-slate-300 max-w-xl">
                            São missões guiadas que conectam seus números reais com tarefas simples.
                            A ideia é te acompanhar mês a mês, como se fosse um treinador financeiro ao seu lado.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($isPro)
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/15 text-emerald-300 border border-emerald-500/40">
                                Ativo para sua conta PRO
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                                Disponível no Vertex Pro
                            </span>
                        @endif
                    </div>
                </div>

                <div class="relative mt-2">
                    @if(! $isPro)
                        {{-- Camada de paywall suave para Free --}}
                        <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-[2px] pointer-events-none rounded-2xl z-10"></div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 rounded-2xl border border-slate-800 bg-slate-900/80 p-4">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-slate-100">Desafio 50/30/20</p>
                            <p class="text-[11px] text-slate-300">
                                Manter os desejos em até 30% da renda por um mês, sem cortar tudo de uma vez.
                                O foco é identificar 1 ou 2 gastos que podem ser suavemente reduzidos.
                            </p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-slate-100">Desafio da Reserva</p>
                            <p class="text-[11px] text-slate-300">
                                Escolher um valor fixo para reserva (mesmo que pequeno) e automatizar a contribuição.
                                A medalha Visionário te acompanha nesse caminho.
                            </p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-slate-100">Desafio da Consistência</p>
                            <p class="text-[11px] text-slate-300">
                                Registrar transações em pelo menos 10 dias no mês, para enxergar com clareza
                                para onde o dinheiro está indo.
                            </p>
                        </div>
                    </div>

                    @if(! $isPro)
                        <div class="relative z-20 mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-[11px] text-slate-300">
                                Os Desafios de Coaching usam seus dados reais para sugerir missões personalizadas,
                                sempre em linguagem simples e acolhedora.
                            </p>
                            <a href="{{ route('user.subscription.index') }}"
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-linear-to-r from-amber-500 to-violet-600 text-xs font-semibold text-white shadow-lg shadow-amber-500/25 hover:shadow-xl hover:scale-[1.02] transition-all">
                                <i class="fa-pro fa-solid fa-crown"></i>
                                Desbloquear com Vertex Pro
                            </a>
                        </div>
                    @else
                        <p class="mt-4 text-[11px] text-slate-300">
                            Como membro PRO, você pode acompanhar esses desafios em tempo real e usar o Mentor Vertex
                            para tirar dúvidas sobre cada missão. Lembre-se: o ritmo é seu, e não existe “atraso”.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-gamification::layouts.master>
