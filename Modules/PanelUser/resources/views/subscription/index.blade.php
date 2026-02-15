<x-paneluser::layouts.master :title="'Planos e Assinatura'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 px-4 pb-12">
    {{-- Hero (padrão CBAV / my-rosters) --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/10 dark:bg-amber-500/20 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ route('paneluser.index') }}" class="hover:underline">Painel</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                    <span class="text-gray-400 dark:text-gray-500">Planos e Assinatura</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">
                    @if($isPro)
                        Seu plano <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500 dark:from-amber-400 dark:to-orange-400">Vertex PRO</span>
                    @else
                        Planos e <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Assinatura</span>
                    @endif
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">
                    @if($isPro)
                        Obrigado por fazer parte do Vertex PRO. Aproveite todos os benefícios configurados pelo painel.
                    @else
                        Evolua seu controle financeiro. 7 dias grátis, depois R$ 29,90/mês. Cancele quando quiser.
                    @endif
                </p>
            </div>

            @if($isPro && $activeSubscription)
                <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0" role="region" aria-label="Status da assinatura">
                    <div class="flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <x-icon name="crown" style="solid" class="w-6 h-6" aria-hidden="true" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Assinatura ativa</p>
                            <p class="text-lg font-black text-gray-900 dark:text-white leading-tight">
                                @if(($activeSubscription->metadata ?? [])['cancel_at_period_end'] ?? false)
                                    Encerra em {{ $activeSubscription->current_period_end?->format('d/m/Y') }}
                                @else
                                    Próxima cobrança {{ $activeSubscription->current_period_end?->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isPro)
        {{-- PRO: Benefícios (limites = Ilimitado, alinhado ao PanelAdmin) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'building-columns', 'title' => 'Contas', 'desc' => 'Ilimitado', 'style' => 'duotone'],
                ['icon' => 'chart-simple', 'title' => 'Relatórios', 'desc' => 'PDF e Excel', 'style' => 'duotone'],
                ['icon' => 'bullseye', 'title' => 'Metas e orçamentos', 'desc' => 'Ilimitado', 'style' => 'duotone'],
                ['icon' => 'headset', 'title' => 'Suporte VIP', 'desc' => 'Prioritário', 'style' => 'duotone'],
            ] as $benefit)
                <div class="relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                            <x-icon name="{{ $benefit['icon'] }}" style="{{ $benefit['style'] ?? 'duotone' }}" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $benefit['title'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $benefit['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center justify-center gap-4">
            @if(Route::has('core.invoices.index'))
                <a href="{{ route('core.invoices.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-500/20 transition-all">
                    <x-icon name="file-invoice-dollar" style="duotone" class="w-5 h-5" />
                    Ver minhas faturas
                </a>
            @endif
            @if(Route::has('core.accounts.index'))
                <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="building-columns" style="duotone" class="w-5 h-5" />
                    Ir para Contas
                </a>
            @endif
            @if($activeSubscription && ! (($activeSubscription->metadata ?? [])['cancel_at_period_end'] ?? false))
                <div x-data="{ open: false }" class="inline-flex">
                    <button @click="open = true" type="button" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl border border-rose-200 dark:border-rose-900/50 text-rose-600 dark:text-rose-400 font-medium hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                        <x-icon name="circle-xmark" style="duotone" class="w-5 h-5" />
                        Cancelar assinatura
                    </button>
                    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-transition>
                        <div @click.away="open = false" class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-200 dark:border-white/10 p-8 max-w-md w-full">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Cancelar assinatura?</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Se você estiver no período de teste, o cancelamento é imediato e não há cobrança. Após o trial, você mantém o PRO até o fim do período já pago.</p>
                            <form action="{{ route('user.subscription.cancel') }}" method="POST" class="flex flex-wrap gap-3">
                                @csrf
                                <input type="hidden" name="confirm" value="yes">
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm">Sim, cancelar</button>
                                <button type="button" @click="open = false" class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium text-sm">Voltar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @else
        {{-- FREE: Comparação com limites dinâmicos do PanelAdmin --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
            {{-- Plano Grátis --}}
            <div class="relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm flex flex-col">
                <div class="p-8 border-b border-gray-200 dark:border-white/5">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Plano Grátis</h2>
                    <p class="mt-4 flex items-baseline">
                        <span class="text-4xl font-black text-gray-900 dark:text-white">Grátis</span>
                        <span class="ml-1 text-gray-500 dark:text-gray-400">/sempre</span>
                    </p>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Limites definidos pelo painel administrativo.</p>
                </div>
                <ul class="p-8 space-y-4 flex-1">
                    <li class="flex items-start gap-3">
                        <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">Controle de receitas e despesas</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $limits['account'] }} {{ $limits['account'] === 1 ? 'conta' : 'contas' }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">Até {{ $limits['income'] + $limits['expense'] }} transações (receitas + despesas)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">{{ $limits['goal'] }} {{ $limits['goal'] === 1 ? 'meta' : 'metas' }}, {{ $limits['budget'] }} {{ $limits['budget'] === 1 ? 'orçamento' : 'orçamentos' }}</span>
                    </li>
                    <li class="flex items-start gap-3 text-gray-400 dark:text-gray-500">
                        <x-icon name="xmark" style="solid" class="w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-sm line-through">Relatórios exportáveis (PDF/CSV)</span>
                    </li>
                    <li class="flex items-start gap-3 text-gray-400 dark:text-gray-500">
                        <x-icon name="xmark" style="solid" class="w-5 h-5 shrink-0 mt-0.5" />
                        <span class="text-sm line-through">Suporte prioritário</span>
                    </li>
                </ul>
                <div class="p-8 pt-0">
                    <button disabled class="w-full py-3.5 rounded-2xl border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 font-bold bg-gray-50 dark:bg-gray-800/50 cursor-not-allowed text-sm">
                        Plano atual
                    </button>
                </div>
            </div>

            {{-- Plano PRO --}}
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500 rounded-[1.75rem] blur opacity-30 group-hover:opacity-50 transition-opacity"></div>
                <div class="relative bg-gray-900 dark:bg-gray-950 rounded-3xl p-8 flex flex-col h-full border-2 border-amber-500/30 shadow-xl">
                    <div class="absolute top-0 right-0 flex flex-col gap-1">
                        <span class="inline-block bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider px-4 py-1.5 rounded-bl-xl rounded-tr-3xl">7 dias grátis</span>
                        <span class="inline-block bg-gradient-to-r from-amber-400 to-orange-500 text-gray-900 text-[10px] font-black uppercase tracking-wider px-4 py-1.5 rounded-bl-xl rounded-tr-3xl">Popular</span>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <x-icon name="crown" style="solid" class="text-amber-400 w-6 h-6" />
                            Vertex PRO
                        </h2>
                        <p class="mt-4 flex items-baseline">
                            <span class="text-4xl font-black text-white">R$ 29,90</span>
                            <span class="ml-1 text-gray-400">/mês</span>
                        </p>
                        <p class="mt-2 text-sm text-gray-400">Após 7 dias grátis. Cancele quando quiser. Reembolso automático se cancelar no trial.</p>
                    </div>

                    <ul class="mt-8 space-y-4 flex-1">
                        @foreach(['Contas ilimitadas', 'Transações ilimitadas', 'Relatórios PDF/CSV', 'Metas e orçamentos ilimitados', 'Suporte prioritário VIP'] as $item)
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 shrink-0 flex items-center justify-center rounded-full bg-amber-500/20">
                                    <x-icon name="check" style="solid" class="text-amber-400 w-3.5 h-3.5" />
                                </div>
                                <span class="text-gray-300 text-sm font-medium">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div x-data="{ open: false }" class="mt-8">
                        @if(session()->has('impersonate_inspection_id'))
                            <button disabled class="w-full py-4 px-6 rounded-2xl text-gray-500 font-bold bg-gray-800 cursor-not-allowed border border-gray-700 text-sm">
                                Compra desabilitada (inspeção)
                            </button>
                        @else
                            <button @click="open = !open" type="button" class="w-full py-4 px-6 rounded-2xl text-gray-900 font-bold bg-gradient-to-r from-amber-300 via-amber-400 to-orange-500 hover:from-amber-400 hover:to-orange-600 shadow-xl shadow-amber-500/30 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide hover:-translate-y-0.5 active:scale-[0.98]">
                                <span x-text="open ? 'Selecionar método' : 'Assinar PRO'"></span>
                                <x-icon name="arrow-right" style="solid" class="w-5 h-5" x-show="!open" />
                                <x-icon name="chevron-down" style="solid" class="w-5 h-5" x-show="open" />
                            </button>
                            <div x-show="open" x-collapse class="mt-4 space-y-3">
                                @forelse($gateways as $gateway)
                                    <a href="{{ route('checkout.init', $gateway->slug) }}" class="flex items-center justify-center w-full py-3 px-4 rounded-2xl border border-gray-600 bg-gray-800 hover:bg-gray-700 text-white transition-colors gap-2 text-sm font-bold">
                                        @if($gateway->slug === 'stripe')
                                            <x-icon name="stripe" style="brands" class="w-5 h-5" /> Stripe
                                        @elseif($gateway->slug === 'mercadopago')
                                            <x-icon name="credit-card" style="duotone" class="w-5 h-5 text-blue-400" /> Mercado Pago
                                        @else
                                            {{ $gateway->name }}
                                        @endif
                                    </a>
                                @empty
                                    <p class="text-center text-sm text-gray-500 py-2">Nenhum método disponível no momento.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap justify-center gap-8 text-center text-sm text-gray-500 dark:text-gray-400">
            <span class="flex items-center gap-2 justify-center">
                <x-icon name="shield-check" style="duotone" class="text-emerald-500 w-4 h-4" />
                Pagamento seguro
            </span>
            <span class="flex items-center gap-2 justify-center">
                <x-icon name="rotate" style="duotone" class="text-emerald-500 w-4 h-4" />
                Cancele quando quiser
            </span>
            <span class="flex items-center gap-2 justify-center">
                <x-icon name="headset" style="duotone" class="text-emerald-500 w-4 h-4" />
                Suporte em português
            </span>
        </div>
    @endif

    {{-- Histórico de pagamentos --}}
    <div class="relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/30 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-500 dark:text-gray-400 ring-1 ring-black/5 dark:ring-white/10">
                <x-icon name="clock-rotate-left" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900 dark:text-white">Histórico de pagamentos</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pagamentos realizados</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300" role="table">
                <caption class="sr-only">Histórico de pagamentos da assinatura</caption>
                <thead class="text-xs font-bold uppercase bg-gray-100 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-4">Data</th>
                        <th scope="col" class="px-6 py-4">Método</th>
                        <th scope="col" class="px-6 py-4">Valor</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Referência</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @php
                        $methodLabels = ['stripe' => 'Stripe', 'mercadopago' => 'Mercado Pago'];
                    @endphp
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white font-mono text-xs">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $methodLabels[strtolower($payment->gateway_slug ?? '')] ?? ucfirst($payment->gateway_slug) }}</td>
                            <td class="px-6 py-4 font-mono font-semibold tabular-nums"><span class="sensitive-value">{{ $payment->currency }} {{ number_format((float) $payment->amount, 2, ',', '.') }}</span></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $payment->status === 'succeeded' ? 'Pago' : $payment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ Str::limit($payment->external_id ?? '-', 12) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-500 dark:text-gray-400">
                                    <x-icon name="receipt" style="duotone" class="w-12 h-12 opacity-50" />
                                    <p class="font-medium">Nenhum pagamento encontrado</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-paneluser::layouts.master>
