@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp
<x-paneluser::layouts.master :title="'Planos e Assinatura'">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        @if($isPro)
            {{-- PRO: Mensagem de agradecimento e benefícios --}}
            <div class="max-w-4xl mx-auto mb-16">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-amber-500/10 via-primary/5 to-emerald-500/10 dark:from-amber-500/20 dark:via-primary/10 dark:to-emerald-500/20 border border-amber-200/50 dark:border-amber-500/30 p-8 md:p-12">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/10 rounded-full -mr-32 -mt-32"></div>
                    <div class="relative z-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-400/20 dark:bg-amber-500/30 mb-6">
                            <x-icon name="crown" style="solid" class="w-8 h-8 text-amber-500 dark:text-amber-400" />
                        </div>
                        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">
                            Obrigado por ser Vertex PRO!
                        </h2>
                        <p class="mt-4 text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                            Você faz parte de um grupo seleto que tem acesso ao melhor controle financeiro. Aproveite tudo que oferecemos para você.
                        </p>
                    </div>
                </div>

                <div class="mt-10">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 text-center">Tudo o que você tem direito</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-3xl mx-auto">
                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <x-icon name="building-columns" style="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Contas e cartões ilimitados</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Organize todas as suas contas bancárias, cartões e dinheiro em um só lugar.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <x-icon name="chart-simple" style="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Relatórios completos em PDF e Excel</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Exporte seus dados para análise onde e quando quiser.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <x-icon name="bullseye" style="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Metas e orçamentos sem limite</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Defina quantas metas quiser e monitore orçamentos por categoria.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-slate-700">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <x-icon name="headset" style="solid" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Suporte prioritário VIP</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Seu chamado é tratado com prioridade pela nossa equipe.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-center gap-4">
                    <a href="{{ route('core.invoices.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors">
                        <x-icon name="file-invoice-dollar" style="solid" class="w-5 h-5" />
                        Ver minhas faturas
                    </a>
                    <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-colors">
                        Ir para Contas
                    </a>
                </div>
            </div>
        @else
            {{-- FREE: Comparação de planos --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base font-semibold text-primary-600 uppercase tracking-wide">Preços</h2>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Evolua seu controle financeiro
                </p>
                <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                    Escolha o plano ideal para você. Sem taxas ocultas.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-20 items-stretch">
            <!-- Free Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-8 hover:shadow-lg transition-all duration-300 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Plano Grátis</h3>
                    <p class="mt-4 flex items-baseline text-gray-900 dark:text-white">
                        <span class="text-5xl font-extrabold tracking-tight">Grátis</span>
                        <span class="ml-1 text-xl font-semibold text-gray-500 dark:text-gray-400">/sempre</span>
                    </p>
                    <p class="mt-6 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Funcionalidades essenciais para controle pessoal.</p>
                </div>

                <ul role="list" class="mt-6 space-y-4 flex-1">
                    <li class="flex items-start">
                        <x-icon name="check" style="solid" class="text-emerald-500 mt-1 mr-3" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">Controle básico de receitas e despesas</span>
                    </li>
                     <li class="flex items-start">
                        <x-icon name="check" style="solid" class="text-emerald-500 mt-1 mr-3" />
                        <span class="text-gray-600 dark:text-gray-300 text-sm">Até 2 contas</span>
                    </li>
                    <li class="flex items-start text-gray-400 dark:text-gray-500 line-through decoration-gray-400/50">
                        <x-icon name="xmark" style="solid" class="text-gray-300 dark:text-gray-600 mt-1 mr-3" />
                        <span class="text-sm">Relatórios avançados (PDF/CSV)</span>
                    </li>
                    <li class="flex items-start text-gray-400 dark:text-gray-500 line-through decoration-gray-400/50">
                        <x-icon name="xmark" style="solid" class="text-gray-300 dark:text-gray-600 mt-1 mr-3" />
                        <span class="text-sm">Metas ilimitadas</span>
                    </li>
                </ul>

                <button disabled class="mt-8 w-full py-3 px-6 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold bg-gray-50 dark:bg-gray-900/50 cursor-not-allowed text-sm uppercase tracking-wide">
                    Plano Atual
                </button>
            </div>

            <!-- PRO Plan -->
            <div class="relative group">
                <!-- Glow Effect -->
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-400 to-orange-600 rounded-3xl blur opacity-30 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>

                <div class="relative bg-gray-900 rounded-3xl p-8 flex flex-col h-full border border-gray-800">
                    <div class="absolute top-0 right-0 -mr-1 -mt-1 w-24 h-24 overflow-hidden rounded-tr-3xl">
                         <div class="absolute transform rotate-45 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-xs font-bold py-1 right-[-35px] top-[32px] w-[170px] text-center shadow-sm">
                            POPULAR
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <x-icon name="crown" style="solid" class="text-amber-400 mr-2" />
                            Vertex PRO
                        </h3>
                         <p class="mt-4 flex items-baseline text-white">
                            <span class="text-5xl font-extrabold tracking-tight">R$ 29,90</span>
                            <span class="ml-1 text-xl font-semibold text-gray-400">/mês</span>
                        </p>
                        <p class="mt-6 text-gray-400 text-sm leading-relaxed">Assinatura mensal recorrente. Cancele quando quiser.</p>
                    </div>

                    <ul role="list" class="mt-6 space-y-4 flex-1">
                        <li class="flex items-start">
                            <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <x-icon name="check" style="solid" class="text-amber-400 text-xs" />
                            </div>
                            <span class="text-gray-300 text-sm font-medium">Transações ilimitadas</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <x-icon name="check" style="solid" class="text-amber-400 text-xs" />
                            </div>
                            <span class="text-gray-300 text-sm">Contas e cartões ilimitados</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <x-icon name="check" style="solid" class="text-amber-400 text-xs" />
                            </div>
                            <span class="text-gray-300 text-sm">Relatórios avançados (PDF/CSV)</span>
                        </li>
                        <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <x-icon name="check" style="solid" class="text-amber-400 text-xs" />
                            </div>
                            <span class="text-gray-300 text-sm">Metas e orçamentos ilimitados</span>
                        </li>
                         <li class="flex items-start">
                             <div class="bg-amber-500/20 p-1 rounded-full mr-3 shrink-0">
                                <x-icon name="check" style="solid" class="text-amber-400 text-xs" />
                            </div>
                            <span class="text-gray-300 text-sm">Suporte prioritário VIP</span>
                        </li>
                    </ul>

                     <!-- Gateways Selection -->
                     <div x-data="{ open: false }" class="mt-8">
                        @if(session()->has('impersonate_inspection_id'))
                             <button disabled class="w-full py-4 px-6 rounded-xl text-gray-500 font-bold bg-gray-800 cursor-not-allowed border border-gray-700">
                                Compra desabilitada (inspeção)
                            </button>
                        @else
                             <button @click="open = !open" type="button" class="w-full py-4 px-6 rounded-xl text-amber-900 font-bold bg-gradient-to-r from-amber-300 to-orange-500 hover:from-amber-400 hover:to-orange-600 shadow-lg shadow-amber-500/20 transform hover:-translate-y-0.5 transition-all flex items-center justify-center text-sm uppercase tracking-wide">
                                <span x-show="!open">Assinar PRO Agora</span>
                                <span x-show="open">Selecionar Método</span>
                                 <x-icon x-show="!open" name="arrow-right" style="solid" class="ml-2" />
                                 <x-icon x-show="open" name="chevron-down" style="solid" class="ml-2" />
                            </button>

                            <div x-show="open" x-collapse class="mt-4 space-y-3">
                                 @forelse($gateways as $gateway)
                                    <a href="{{ route('checkout.init', $gateway->slug) }}" class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-gray-600 bg-gray-800 hover:bg-gray-700 text-white transition-colors gap-2 text-sm font-medium group-link">
                                         @if($gateway->slug === 'stripe')
                                            <x-icon name="stripe" style="brands" class="text-xl group-link-hover:text-indigo-400 transition-colors" /> Pagar com Stripe
                                        @elseif($gateway->slug === 'mercadopago')
                                            <x-icon name="handshake" style="solid" class="text-xl text-blue-400" /> Mercado Pago
                                        @else
                                            {{ $gateway->name }}
                                        @endif
                                    </a>
                                 @empty
                                    <div class="text-center text-sm text-gray-500 py-2">
                                        Nenhum método de pagamento disponível.
                                    </div>
                                 @endforelse
                            </div>
                        @endif
                     </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment History (PRO e FREE) -->
        <div class="max-w-5xl mx-auto">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <x-icon name="clock-rotate-left" style="solid" class="mr-2 text-primary-500" />
                Histórico de Pagamentos
            </h2>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-4">Data</th>
                                <th scope="col" class="px-6 py-4">Método</th>
                                <th scope="col" class="px-6 py-4">Valor</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Referência</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
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
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                        {{ Str::limit($payment->external_id, 12) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Nenhum pagamento encontrado.
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
