<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Pagamentos</x-slot>

    <x-paneladmin::page title="Logs de Pagamento" subtitle="Receita e histórico de transações dos gateways.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="wallet" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Receita (30 dias)</p>
                        <p class="text-xl font-bold text-slate-900 dark:text-white tabular-nums">{{ format_currency($revenue30d ?? 0) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Pagamentos OK (30d)</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $countSucceeded ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="clock" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Pendentes</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $countPending ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <x-icon name="triangle-exclamation" style="duotone" class="w-6 h-6 text-red-600 dark:text-red-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Falhas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $countFailed ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <x-paneladmin::card title="Receita (Últimos 30 dias)" subtitle="Soma diária de pagamentos concluídos.">
            <div class="p-6">
                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 text-sm mb-4">
                    <x-icon name="chart-line" style="duotone" class="w-4 h-4" />
                    <span>Valores por dia</span>
                </div>
                <div id="revenueChart" class="h-64 w-full"></div>
            </div>
        </x-paneladmin::card>

        {{-- Table --}}
        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative inline-block">
                        <select name="gateway" class="payments-select pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] min-w-[160px]" style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Todos os gateways</option>
                            <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                        </span>
                    </div>
                    <div class="relative inline-block">
                        <select name="status" class="payments-select pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] min-w-[160px]" style="appearance: none; -webkit-appearance: none; -moz-appearance: none;">
                            <option value="">Todos os status</option>
                            <option value="succeeded" {{ request('status') == 'succeeded' ? 'selected' : '' }}>Sucesso</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Falha</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                        </span>
                    </div>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="filter" style="duotone" class="w-4 h-4" />
                        Filtrar
                    </button>
                </form>
            </x-slot>

            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Data</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuário</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Gateway</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Valor</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">ID Externo</th>
                    </tr>
                </x-slot>
                @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm tabular-nums">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($payment->user)
                                <div class="flex items-center gap-2">
                                    <x-icon name="user" style="duotone" class="w-4 h-4 text-slate-400 shrink-0" />
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-white">{{ $payment->user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $payment->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                                    <x-icon name="user-xmark" style="duotone" class="w-3.5 h-3.5" />
                                    Usuário removido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 uppercase font-bold text-xs text-slate-700 dark:text-slate-300">
                                <x-icon name="credit-card" style="duotone" class="w-3.5 h-3.5" />
                                {{ $payment->gateway_slug }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white tabular-nums">
                            {{ format_currency($payment->amount, $payment->currency ?? 'R$') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = match($payment->status) {
                                    'succeeded', 'paid' => ['class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'icon' => 'circle-check', 'label' => 'Sucesso'],
                                    'pending' => ['class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'icon' => 'clock', 'label' => 'Pendente'],
                                    'failed' => ['class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'icon' => 'triangle-exclamation', 'label' => 'Falha'],
                                    default => ['class' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300', 'icon' => 'circle-question', 'label' => ucfirst($payment->status)]
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $statusConfig['class'] }}">
                                <x-icon name="{{ $statusConfig['icon'] }}" style="duotone" class="w-3.5 h-3.5" />
                                {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->subscription_id)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/20 dark:text-emerald-400 border border-[#11C76F]/20">
                                    <x-icon name="arrows-rotate" style="duotone" class="w-3.5 h-3.5" />
                                    Recorrente
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400 truncate max-w-[140px]" title="{{ $payment->external_id }}">{{ $payment->external_id }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="credit-card" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum registro de pagamento encontrado.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Ajuste os filtros ou aguarde novas transações.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            @if($payments->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $payments->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>

    @push('styles')
    <style>
        .payments-select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: none !important;
        }
    </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var options = {
                    series: [{
                        name: 'Receita',
                        data: @json($chartValues)
                    }],
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        categories: @json($chartDates),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8' } }
                    },
                    yaxis: {
                        labels: { style: { colors: '#94a3b8' } }
                    },
                    theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' },
                    colors: ['#11C76F'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.2, stops: [0, 90, 100] }
                    },
                    grid: { borderColor: document.documentElement.classList.contains('dark') ? '#334155' : '#e2e8f0' }
                };

                var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
                chart.render();
            });
        </script>
    @endpush
</x-paneladmin::layouts.master>
