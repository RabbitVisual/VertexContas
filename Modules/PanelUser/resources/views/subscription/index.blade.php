@php
    $isPro = auth()->user()?->isPro() ?? false;
@endphp
<x-paneluser::layouts.master :title="'Planos e Assinatura'">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Planos e Assinatura</li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $isPro ? 'Seu Plano Vertex PRO' : 'Planos e Assinatura' }}
                    </h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">
                        {{ $isPro ? 'Obrigado por fazer parte do Vertex PRO. Aproveite todos os benefícios.' : 'Evolua seu controle financeiro. Escolha o plano ideal para você.' }}
                    </p>
                </div>
            </div>

            @if($isPro)
                {{-- PRO: Hero de agradecimento --}}
                <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-200">
                    <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                        <div class="absolute -top-24 -left-20 w-96 h-96 bg-amber-400 dark:bg-amber-600 rounded-full blur-[100px]"></div>
                        <div class="absolute top-1/2 -right-20 w-80 h-80 bg-primary/60 dark:bg-primary/40 rounded-full blur-[100px]"></div>
                    </div>
                    <div class="relative px-8 py-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-2xl bg-amber-100 dark:bg-amber-900/30 shadow-lg">
                            <x-icon name="crown" style="solid" class="w-10 h-10 text-amber-600 dark:text-amber-400" />
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                            Obrigado por ser Vertex PRO!
                        </h2>
                        <p class="mt-4 text-lg text-gray-600 dark:text-slate-400 max-w-2xl mx-auto">
                            Você faz parte de um grupo seleto com acesso ao melhor controle financeiro. Aproveite tudo que oferecemos.
                        </p>
                    </div>
                </div>

                {{-- Benefícios PRO --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach([
                        ['icon' => 'building-columns', 'title' => 'Contas e cartões ilimitados', 'desc' => 'Organize todas as suas contas bancárias, cartões e dinheiro em um só lugar.'],
                        ['icon' => 'chart-simple', 'title' => 'Relatórios em PDF e Excel', 'desc' => 'Exporte seus dados para análise onde e quando quiser.'],
                        ['icon' => 'bullseye', 'title' => 'Metas e orçamentos sem limite', 'desc' => 'Defina quantas metas quiser e monitore orçamentos por categoria.'],
                        ['icon' => 'headset', 'title' => 'Suporte prioritário VIP', 'desc' => 'Seu chamado é tratado com prioridade pela nossa equipe.'],
                    ] as $benefit)
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <x-icon name="{{ $benefit['icon'] }}" style="solid" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $benefit['title'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- CTAs PRO --}}
                <div class="flex flex-wrap justify-center gap-4">
                    @if(Route::has('core.invoices.index'))
                        <a href="{{ route('core.invoices.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/25">
                            <x-icon name="file-invoice-dollar" style="solid" class="w-5 h-5" />
                            Ver minhas faturas
                        </a>
                    @endif
                    @if(Route::has('core.accounts.index'))
                        <a href="{{ route('core.accounts.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                            <x-icon name="building-columns" style="solid" class="w-5 h-5" />
                            Ir para Contas
                        </a>
                    @endif
                </div>

            @else
                {{-- FREE: Hero persuasivo --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="relative px-8 py-12 sm:px-12 sm:py-16 text-center">
                        <p class="text-amber-400 text-sm font-bold uppercase tracking-widest">Evolua seu controle</p>
                        <h2 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white">
                            Tudo ilimitado por menos de R$ 1/dia
                        </h2>
                        <p class="mt-4 text-slate-300 max-w-xl mx-auto">
                            Contas, transações, metas, relatórios exportáveis e suporte VIP. Sem taxas ocultas. Cancele quando quiser.
                        </p>
                    </div>
                </div>

                {{-- Comparação de Planos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                    {{-- Plano Grátis --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                        <div class="p-8 border-b border-gray-100 dark:border-slate-800">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Plano Grátis</h3>
                            <p class="mt-4 flex items-baseline">
                                <span class="text-4xl font-extrabold text-gray-900 dark:text-white">Grátis</span>
                                <span class="ml-1 text-gray-500 dark:text-slate-400">/sempre</span>
                            </p>
                            <p class="mt-4 text-sm text-gray-500 dark:text-slate-400">Funcionalidades essenciais para começar.</p>
                        </div>
                        <ul class="p-8 space-y-4 flex-1">
                            <li class="flex items-start gap-3">
                                <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                                <span class="text-gray-600 dark:text-slate-300 text-sm">Controle básico de receitas e despesas</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <x-icon name="check" style="solid" class="text-emerald-500 w-5 h-5 shrink-0 mt-0.5" />
                                <span class="text-gray-600 dark:text-slate-300 text-sm">1 conta</span>
                            </li>
                            <li class="flex items-start gap-3 text-gray-400 dark:text-slate-500">
                                <x-icon name="xmark" style="solid" class="w-5 h-5 shrink-0 mt-0.5" />
                                <span class="text-sm line-through">Relatórios avançados (PDF/CSV)</span>
                            </li>
                            <li class="flex items-start gap-3 text-gray-400 dark:text-slate-500">
                                <x-icon name="xmark" style="solid" class="w-5 h-5 shrink-0 mt-0.5" />
                                <span class="text-sm line-through">Metas e orçamentos ilimitados</span>
                            </li>
                        </ul>
                        <div class="p-8 pt-0">
                            <button disabled class="w-full py-3.5 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-slate-400 font-bold bg-gray-50 dark:bg-slate-800/50 cursor-not-allowed text-sm">
                                Plano Atual
                            </button>
                        </div>
                    </div>

                    {{-- Plano PRO (destaque) --}}
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500 rounded-[1.75rem] blur opacity-40 group-hover:opacity-60 transition-opacity"></div>
                        <div class="relative bg-slate-900 dark:bg-slate-950 rounded-3xl p-8 flex flex-col h-full border-2 border-amber-500/30">
                            <div class="absolute top-0 right-0">
                                <span class="inline-block bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 text-[10px] font-black uppercase tracking-wider px-4 py-1.5 rounded-bl-xl rounded-tr-3xl">
                                    Popular
                                </span>
                            </div>

                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                    <x-icon name="crown" style="solid" class="text-amber-400 w-6 h-6" />
                                    Vertex PRO
                                </h3>
                                <p class="mt-4 flex items-baseline">
                                    <span class="text-4xl font-extrabold text-white">R$ 29,90</span>
                                    <span class="ml-1 text-slate-400">/mês</span>
                                </p>
                                <p class="mt-3 text-sm text-slate-400">Assinatura recorrente. Cancele quando quiser.</p>
                            </div>

                            <ul class="mt-8 space-y-4 flex-1">
                                @foreach(['Transações ilimitadas', 'Contas e cartões ilimitados', 'Relatórios avançados (PDF/CSV)', 'Metas e orçamentos ilimitados', 'Suporte prioritário VIP'] as $item)
                                    <li class="flex items-start gap-3">
                                        <div class="w-6 h-6 shrink-0 flex items-center justify-center rounded-full bg-amber-500/20">
                                            <x-icon name="check" style="solid" class="text-amber-400 w-3.5 h-3.5" />
                                        </div>
                                        <span class="text-slate-300 text-sm font-medium">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div x-data="{ open: false }" class="mt-8">
                                @if(session()->has('impersonate_inspection_id'))
                                    <button disabled class="w-full py-4 px-6 rounded-xl text-slate-500 font-bold bg-slate-800 cursor-not-allowed border border-slate-700">
                                        Compra desabilitada (inspeção)
                                    </button>
                                @else
                                    <button @click="open = !open" type="button" class="w-full py-4 px-6 rounded-xl text-slate-900 font-bold bg-gradient-to-r from-amber-300 via-amber-400 to-orange-500 hover:from-amber-400 hover:to-orange-600 shadow-xl shadow-amber-500/30 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide hover:-translate-y-0.5 active:scale-[0.98]">
                                        <span x-text="open ? 'Selecionar método' : 'Assinar PRO Agora'"></span>
                                        <x-icon name="arrow-right" style="solid" class="w-5 h-5" x-show="!open" />
                                        <x-icon name="chevron-down" style="solid" class="w-5 h-5" x-show="open" />
                                    </button>

                                    <div x-show="open" x-collapse class="mt-4 space-y-3">
                                        @forelse($gateways as $gateway)
                                            <a href="{{ route('checkout.init', $gateway->slug) }}" class="flex items-center justify-center w-full py-3 px-4 rounded-xl border border-slate-600 bg-slate-800 hover:bg-slate-700 text-white transition-colors gap-2 text-sm font-bold">
                                                @if($gateway->slug === 'stripe')
                                                    <x-icon name="stripe" style="brands" class="w-5 h-5" /> Pagar com Stripe
                                                @elseif($gateway->slug === 'mercadopago')
                                                    <x-icon name="credit-card" style="solid" class="w-5 h-5 text-blue-400" /> Mercado Pago
                                                @else
                                                    {{ $gateway->name }}
                                                @endif
                                            </a>
                                        @empty
                                            <p class="text-center text-sm text-slate-500 py-2">Nenhum método disponível.</p>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trust badges / Garantias --}}
                <div class="flex flex-wrap justify-center gap-8 text-center text-sm text-gray-500 dark:text-slate-400">
                    <span class="flex items-center gap-2 justify-center">
                        <x-icon name="shield-check" style="solid" class="text-emerald-500 w-4 h-4" />
                        Pagamento seguro
                    </span>
                    <span class="flex items-center gap-2 justify-center">
                        <x-icon name="rotate" style="solid" class="text-emerald-500 w-4 h-4" />
                        Cancele quando quiser
                    </span>
                    <span class="flex items-center gap-2 justify-center">
                        <x-icon name="headset" style="solid" class="text-emerald-500 w-4 h-4" />
                        Suporte em português
                    </span>
                </div>
            @endif

            {{-- Histórico de Pagamentos (todos) --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2.5 bg-slate-100 dark:bg-slate-800 rounded-xl">
                        <x-icon name="clock-rotate-left" style="solid" class="text-slate-600 dark:text-slate-400 w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Histórico de Pagamentos</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Seus pagamentos realizados</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest bg-gray-50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Data</th>
                                <th class="px-6 py-4">Método</th>
                                <th class="px-6 py-4">Valor</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Referência</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            @forelse($payments as $payment)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ $payment->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-slate-300 capitalize">{{ $payment->gateway_slug }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                        <span class="sensitive-value">{{ $payment->currency }} {{ number_format($payment->amount, 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                            {{ $payment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">{{ Str::limit($payment->external_id, 12) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                                        <x-icon name="receipt" style="solid" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                        <p class="font-medium">Nenhum pagamento encontrado</p>
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
