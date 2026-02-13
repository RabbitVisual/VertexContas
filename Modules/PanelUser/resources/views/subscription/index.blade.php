<x-paneluser::layouts.master>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-primary font-semibold tracking-wide uppercase text-sm mb-2">Seja Premium</h2>
            <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white sm:text-5xl tracking-tight mb-4">
                Desbloqueie todo o potencial do <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-600">Vertex Contas</span>
            </h1>
            <p class="text-xl text-slate-500 dark:text-slate-400">
                Gerencie suas finanças sem limites. Obtenha acesso vitalício a recursos exclusivos e suporte prioritário.
            </p>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-5xl mx-auto mb-20">

            <!-- Free Plan -->
            <div class="relative bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Gratuito</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Para quem está começando.</p>
                </div>
                <div class="mb-6">
                    <span class="text-4xl font-extrabold text-slate-900 dark:text-white">R$ 0</span>
                    <span class="text-slate-500 dark:text-slate-400 text-lg">/mês</span>
                </div>

                <ul class="space-y-4 mb-8 flex-1">
                    <li class="flex items-center text-slate-600 dark:text-slate-300">
                        <x-icon name="check" class="text-emerald-500 mr-3 text-sm" /> Controle de Receitas e Despesas
                    </li>
                    <li class="flex items-center text-slate-600 dark:text-slate-300">
                        <x-icon name="check" class="text-emerald-500 mr-3 text-sm" /> Limite de 1 Contas
                    </li>
                    <li class="flex items-center text-slate-600 dark:text-slate-300">
                        <x-icon name="check" class="text-emerald-500 mr-3 text-sm" /> Dashboard Básico
                    </li>
                     <li class="flex items-center text-slate-400 dark:text-slate-500 line-through decoration-slate-400/50">
                        <x-icon name="xmark" class="text-slate-300 dark:text-slate-600 mr-3 text-sm" /> Relatórios Avançados (PDF/CSV)
                    </li>
                    <li class="flex items-center text-slate-400 dark:text-slate-500 line-through decoration-slate-400/50">
                        <x-icon name="xmark" class="text-slate-300 dark:text-slate-600 mr-3 text-sm" /> Metas Ilimitadas
                    </li>
                </ul>

                <button disabled class="w-full py-3 px-6 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold bg-slate-50 dark:bg-slate-900/50 cursor-not-allowed">
                    Plano Atual
                </button>
            </div>

            <!-- PRO Plan -->
            <div class="relative bg-slate-900 dark:bg-slate-800 rounded-3xl p-8 border border-primary/50 shadow-2xl shadow-primary/20 flex flex-col transform md:-translate-y-4 relative overflow-hidden group">
                <!-- Glow Effects -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl group-hover:bg-primary/30 transition-colors"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>

                <div class="absolute top-0 left-1/2 -translate-x-1/2 bg-gradient-to-r from-amber-200 to-yellow-400 text-yellow-900 text-xs font-bold uppercase tracking-widest px-4 py-1 rounded-b-lg shadow-lg">
                    Recomendado
                </div>

                <div class="relative z-10">
                    <div class="mb-4 flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-white flex items-center">
                                <x-icon name="crown" class="text-amber-400 mr-2" />
                                Vertex PRO
                            </h3>
                            <p class="text-slate-300 text-sm mt-1">Para controle total e sem limites.</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-baseline">
                            <span class="text-5xl font-extrabold text-white">R$ 29,90</span>
                            <span class="text-slate-300 text-lg ml-2">/vitalício</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Pagamento único. Acesso para sempre.</p>
                    </div>

                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-center text-white">
                            <div class="bg-amber-500/20 p-1 rounded-full mr-3">
                                <x-icon name="check" class="text-amber-400 text-xs" />
                            </div>
                            <span><strong class="text-amber-200">Transações Ilimitadas</strong></span>
                        </li>
                        <li class="flex items-center text-white">
                            <div class="bg-amber-500/20 p-1 rounded-full mr-3">
                                <x-icon name="check" class="text-amber-400 text-xs" />
                            </div>
                            <span>Contas e Cartões Ilimitados</span>
                        </li>
                        <li class="flex items-center text-white">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3">
                                <x-icon name="check" class="text-amber-400 text-xs" />
                            </div>
                            <span>Relatórios Avançados (PDF/CSV)</span>
                        </li>
                        <li class="flex items-center text-white">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3">
                                <x-icon name="check" class="text-amber-400 text-xs" />
                            </div>
                            <span>Metas e Orçamentos Ilimitados</span>
                        </li>
                         <li class="flex items-center text-white">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3">
                                <x-icon name="check" class="text-amber-400 text-xs" />
                            </div>
                            <span>Suporte Prioritário VIP</span>
                        </li>
                    </ul>

                     <!-- Gateways -->
                     <div x-data="{ open: false }">
                         <button @click="open = !open" class="w-full py-4 px-6 rounded-xl text-amber-900 font-bold bg-gradient-to-r from-amber-200 to-yellow-400 hover:from-amber-300 hover:to-yellow-500 shadow-lg shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all flex items-center justify-center">
                            <span x-show="!open">Quero ser PRO agora</span>
                            <span x-show="open">Escolha o método</span>
                             <x-icon x-show="!open" name="arrow-right" class="ml-2" />
                             <x-icon x-show="open" name="chevron-down" class="ml-2" />
                        </button>

                        <div x-show="open" x-collapse class="mt-4 space-y-3">
                             @forelse($gateways as $gateway)
                                <a href="{{ route('checkout.init', $gateway->slug) }}" class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-slate-600 bg-slate-800/50 hover:bg-slate-700 text-white transition-colors gap-2">
                                     @if($gateway->slug === 'stripe')
                                        <x-icon name="stripe" style="brands" class="text-xl" /> Pay with Stripe
                                    @elseif($gateway->slug === 'mercadopago')
                                        <x-icon name="handshake" class="text-xl" /> Mercado Pago
                                    @else
                                        {{ $gateway->name }}
                                    @endif
                                </a>
                             @empty
                                <div class="text-center text-sm text-slate-400 py-2">
                                    Nenhum método disponível.
                                </div>
                             @endforelse
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="max-w-5xl mx-auto">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                <x-icon name="clock-rotate-left" class="mr-2 text-slate-400" /> Histórico de Pagamentos
            </h2>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500 dark:text-slate-400">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50 dark:bg-slate-700/50 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th scope="col" class="px-6 py-4">Data</th>
                                <th scope="col" class="px-6 py-4">Método</th>
                                <th scope="col" class="px-6 py-4">Valor</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Referência</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 capitalize">
                                        {{ $payment->gateway_slug }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $payment->currency }} {{ number_format($payment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ Str::limit($payment->external_id, 12) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                        Você ainda não possui pagamentos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
