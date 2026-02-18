<x-homepage::layouts.master
    :title="'Planos - Vertex Contas'"
    :metaDescription="'Conheça o Vertex PRO: CFO Virtual, projeções com IA, relatórios de elite e suporte VIP. Planos mensal e anual.'"
    :metaKeywords="'vertex pro, plano financeiro, assinatura, cfo virtual, ia finanças'"
>
    <x-homepage::layouts.navbar />

    <main class="font-['Poppins'] min-h-screen bg-slate-50 dark:bg-slate-900 pt-24 pb-20">
        {{-- Hero --}}
        <section class="relative bg-slate-950 overflow-hidden py-20 lg:py-28">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[100px] -z-10" aria-hidden="true"></div>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-black text-white leading-tight mb-6">{{ $headline }}</h1>
                <p class="text-xl text-slate-400 max-w-3xl mx-auto">{{ $subhead }}</p>
            </div>
        </section>

        {{-- Features (custom HTML or default) --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            @if($featuresHtml)
                <div class="prose prose-slate dark:prose-invert max-w-none">
                    {!! $featuresHtml !!}
                </div>
            @else
                <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-10 text-center">O que você desbloqueia no Nível PRO</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex gap-4 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <x-icon name="robot" style="duotone" class="text-primary w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Vertex AI: Seu Mentor de Estratégia</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Nossa IA analisa seus dados reais, cruza com a metodologia 50/30/20 e te diz exatamente onde você está desperdiçando dinheiro e como acelerar seus aportes.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <x-icon name="chart-line" style="duotone" class="text-primary w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Máquina do Tempo (Projeções)</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">O que acontece se você economizar 10% a mais hoje? Nossa IA projeta seu patrimônio. Veja o impacto das suas decisões antes de tomá-las.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <x-icon name="file-lines" style="duotone" class="text-primary w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Relatórios de Elite (Business Statement)</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Documento mensal de nível institucional, com Conclusão Estratégica assinada pela nossa IA. Ideal para auditoria ou reuniões de negócios.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <x-icon name="file-import" style="duotone" class="text-primary w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Importação Inteligente de Extratos</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Arraste o CSV do seu banco. Nossa IA categoriza tudo automaticamente e economiza horas de digitação.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:border-primary/30 transition-all md:col-span-2">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <x-icon name="comments" style="duotone" class="text-primary w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white mb-2">Suporte VIP via Chat Real-Time</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Usuários PRO têm acesso direto ao Chat VIP na Central de Comando, com atendimento prioritário e humano.</p>
                        </div>
                    </div>
                </div>
            @endif
        </section>

        {{-- Table (custom HTML or default) --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if($tableHtml)
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    {!! $tableHtml !!}
                </div>
            @else
                <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-8 text-center">Escolha o seu Plano de Liberdade</h2>
                <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
                    <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-bold">Recurso</th>
                                <th class="px-6 py-4 font-bold">Vertex FREE</th>
                                <th class="px-6 py-4 font-bold">{{ plan_pro_name() }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr><td class="px-6 py-4">Registro de Receitas</td><td class="px-6 py-4">Básico</td><td class="px-6 py-4 font-medium">Ilimitado</td></tr>
                            <tr><td class="px-6 py-4">Controle de Despesas</td><td class="px-6 py-4">Limitado</td><td class="px-6 py-4 font-medium">Ilimitado</td></tr>
                            <tr><td class="px-6 py-4">Metodologia 50/30/20</td><td class="px-6 py-4">Sim</td><td class="px-6 py-4 font-medium">Com Análise de IA</td></tr>
                            <tr><td class="px-6 py-4">CFO Virtual (IA)</td><td class="px-6 py-4">—</td><td class="px-6 py-4 font-medium text-primary">Ativado</td></tr>
                            <tr><td class="px-6 py-4">Projeções de Futuro</td><td class="px-6 py-4">—</td><td class="px-6 py-4 font-medium text-primary">Ativado</td></tr>
                            <tr><td class="px-6 py-4">Relatórios Business PDF</td><td class="px-6 py-4">—</td><td class="px-6 py-4 font-medium text-primary">Ativado</td></tr>
                            <tr><td class="px-6 py-4">Importação de Extratos</td><td class="px-6 py-4">—</td><td class="px-6 py-4 font-medium text-primary">Ativado</td></tr>
                            <tr><td class="px-6 py-4">Suporte</td><td class="px-6 py-4">Ticket</td><td class="px-6 py-4 font-medium text-primary">Chat VIP Real-Time</td></tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- CTA + planos dinâmicos --}}
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <p class="text-lg text-slate-600 dark:text-slate-400 mb-6">Comece sua jornada para a elite hoje.</p>
            @if(isset($paidPlans) && $paidPlans->isNotEmpty())
                <div class="flex flex-wrap items-center justify-center gap-4 mb-6">
                    @foreach($paidPlans as $p)
                        <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold transition-all {{ $loop->first ? 'bg-primary hover:bg-primary-dark text-white shadow-xl shadow-primary/25' : 'bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600' }}">
                            {{ $p->name }} – R$ {{ $p->amount ? number_format((float) $p->amount, 2, ',', '.') : '—' }}/{{ $p->billing_interval === 'yearly' ? 'ano' : 'mês' }}
                        </a>
                    @endforeach
                </div>
            @else
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-primary hover:bg-primary-dark text-white font-bold rounded-2xl shadow-xl shadow-primary/25 hover:shadow-primary/40 transition-all text-lg">
                    {{ $ctaText }} – R$ {{ $monthlyPrice }}/mês
                </a>
                <p class="mt-6 text-slate-500 dark:text-slate-400 text-sm">
                    ou economize R$ {{ $yearlySavings }} no plano anual por apenas R$ {{ $yearlyPrice }}
                </p>
            @endif
        </section>
    </main>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
