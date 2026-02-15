<x-paneluser::layouts.master :title="'Minhas Faturas'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero (padrão CBAV / my-rosters) --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-600/5 dark:bg-teal-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ route('user.subscription.index') }}" class="hover:underline">Planos</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800" aria-hidden="true"></span>
                    <span class="text-gray-400 dark:text-gray-500">Faturas</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Minhas <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-400 dark:to-teal-400">Faturas</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Consulte a próxima data de cobrança e o histórico de pagamentos do seu plano Vertex PRO.</p>
            </div>

            <div class="bg-gray-50 dark:bg-white/5 backdrop-blur-xl rounded-3xl p-6 border border-gray-200 dark:border-white/10 ring-1 ring-black/5 dark:ring-white/5 shadow-xl shrink-0" role="region" aria-label="Próxima cobrança">
                <div class="flex items-center gap-4 text-left">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                        <x-icon name="file-invoice-dollar" style="duotone" class="w-6 h-6" aria-hidden="true" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest leading-none mb-1">Próxima cobrança</p>
                        <p class="text-lg font-black text-gray-900 dark:text-white leading-tight">{{ $nextDueLabel }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $nextDueSubtext }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-white/10 flex items-center gap-2">
                    <x-icon name="crown" style="solid" class="w-4 h-4 text-amber-500" aria-hidden="true" />
                    <span class="text-xs font-bold text-amber-700 dark:text-amber-400">Vertex PRO Mensal</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Histórico em card --}}
    <div class="relative overflow-hidden bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 shadow-sm hover:shadow-xl transition-shadow duration-300">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50/50 dark:bg-gray-950/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-500 dark:text-gray-400 ring-1 ring-black/5 dark:ring-white/10">
                    <x-icon name="clock-rotate-left" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 dark:text-white">Histórico de Pagamentos</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Todas as suas faturas pagas</p>
                </div>
            </div>
            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:underline focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 rounded-lg">
                <x-icon name="credit-card" style="duotone" class="w-4 h-4" />
                Planos e assinatura
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300" role="table">
                <caption class="sr-only">Histórico de faturas pagas do plano Vertex PRO</caption>
                <thead class="text-xs font-bold uppercase bg-gray-100 dark:bg-gray-800/50 text-gray-600 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-4">Data</th>
                        <th scope="col" class="px-6 py-4">Método</th>
                        <th scope="col" class="px-6 py-4">Valor</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Referência</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @forelse($invoices as $invoice)
                        @php
                            $methodLabel = match(strtolower($invoice->gateway_slug ?? '')) {
                                'stripe' => 'Cartão (Stripe)',
                                'mercadopago' => 'Mercado Pago',
                                default => $invoice->gateway_slug ? ucfirst($invoice->gateway_slug) : '—',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white font-mono text-xs">
                                {{ $invoice->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">{{ $methodLabel }}</td>
                            <td class="px-6 py-4 font-mono font-semibold tabular-nums">
                                <span class="sensitive-value">{{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status === 'succeeded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    {{ $invoice->status === 'succeeded' ? 'Pago' : ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-400 dark:text-gray-500">
                                {{ Str::limit($invoice->external_id ?? '-', 14) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-0">
                                <div class="flex flex-col items-center justify-center py-24 text-center bg-gray-50 dark:bg-gray-950/50" role="status">
                                    <div class="w-24 h-24 rounded-full bg-white dark:bg-gray-900 flex items-center justify-center text-gray-300 dark:text-gray-700 mb-6 shadow-sm border border-gray-100 dark:border-white/5" aria-hidden="true">
                                        <x-icon name="file-invoice" style="duotone" class="w-12 h-12 opacity-40 dark:opacity-20" />
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">Nenhuma fatura ainda</h3>
                                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto text-sm leading-relaxed">Quando você realizar pagamentos do plano PRO, as faturas aparecerão aqui. Para alterar a forma de pagamento ou cancelar, acesse Planos.</p>
                                    <a href="{{ route('user.subscription.index') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                                        <x-icon name="credit-card" style="duotone" class="w-4 h-4" />
                                        Ver planos e assinatura
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Dica --}}
    <div class="rounded-3xl border border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-950/50 p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0" aria-hidden="true">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-1">Alterar pagamento ou cancelar</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Para trocar o cartão, atualizar dados de cobrança ou cancelar sua assinatura Vertex PRO, acesse <a href="{{ route('user.subscription.index') }}" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline">Planos e assinatura</a>.</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center sm:justify-start">
        <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <x-icon name="arrow-left" style="solid" class="w-4 h-4" aria-hidden="true" />
            Voltar para Planos
        </a>
    </div>
</div>
</x-paneluser::layouts.master>
