<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Pagamentos</x-slot>

    <x-paneladmin::page title="Logs de Pagamento" subtitle="Receita e histórico de transações.">
        <!-- Revenue Chart -->
        <x-paneladmin::card title="Receita (Últimos 30 Dias)">
            <div id="revenueChart" class="h-64 w-full"></div>
        </x-paneladmin::card>

        <!-- Payments Table -->
        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <select name="gateway" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="">Todos Gateway</option>
                        <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                    </select>
                    <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="">Todos Status</option>
                        <option value="succeeded" {{ request('status') == 'succeeded' ? 'selected' : '' }}>Sucesso</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Falha</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="filter" style="duotone" class="w-4 h-4" /> Filtrar
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
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            @if($payment->user)
                                <div class="font-medium text-slate-900 dark:text-white">{{ $payment->user->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $payment->user->email }}</div>
                            @else
                                <span class="text-xs text-red-500">Usuário Deletado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 uppercase font-bold text-xs text-slate-700 dark:text-slate-300">{{ $payment->gateway_slug }}</td>
                        <td class="px-6 py-4 font-mono font-medium text-slate-900 dark:text-white">
                            {{ format_currency($payment->amount, $payment->currency ?? 'R$') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($payment->status) {
                                    'succeeded', 'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-bold {{ $statusColor }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->subscription_id)
                                <span class="inline-flex px-2 py-1 rounded-lg text-xs font-medium bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/20 dark:text-emerald-400">
                                    Recorrente
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">{{ $payment->external_id }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            Nenhum registro de pagamento encontrado.
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $payments->links() }}
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                    colors: ['#3b82f6'],
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
