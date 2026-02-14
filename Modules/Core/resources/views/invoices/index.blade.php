<x-paneluser::layouts.master :title="'Minhas Faturas'">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-primary/10 dark:bg-primary/15 border border-primary/20 dark:border-primary/30">
                    <x-icon name="file-invoice-dollar" style="duotone" class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                Minhas Faturas
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1.5 ml-14">Histórico de pagamentos e próxima cobrança</p>
        </div>

        {{-- Próxima cobrança (assinatura mensal) --}}
        <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Próxima cobrança</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $nextDueLabel }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $nextDueSubtext }}</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                    <x-icon name="crown" style="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Vertex PRO Mensal</span>
                </div>
            </div>
        </div>

        {{-- Histórico de faturas --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                <h3 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                    <x-icon name="clock-rotate-left" style="solid" class="w-5 h-5 text-primary-500" />
                    Histórico de Pagamentos
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Todas as suas faturas pagas</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                    <thead class="text-xs uppercase bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">Data</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Método</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Valor</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Referência</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white font-mono text-xs">
                                    {{ $invoice->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 capitalize">{{ $invoice->gateway_slug }}</td>
                                <td class="px-6 py-4 font-mono font-semibold tabular-nums">
                                    {{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoice->status === 'succeeded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ $invoice->status === 'succeeded' ? 'Pago' : ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    {{ Str::limit($invoice->external_id ?? '-', 14) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                                        <x-icon name="file-invoice" style="duotone" class="w-12 h-12 opacity-50" />
                                        <p class="text-sm">Nenhuma fatura encontrada.</p>
                                        <p class="text-xs">Seu histórico de pagamentos aparecerá aqui após a assinatura do plano PRO.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium text-sm">
                <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                Voltar para Planos
            </a>
        </div>
    </div>
</x-paneluser::layouts.master>
