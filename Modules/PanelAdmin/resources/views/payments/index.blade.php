@section('title', 'Auditoria de Pagamentos')

<x-paneladmin::layouts.master>

    <!-- Revenue Chart -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Receita (Últimos 30 Dias)</h3>
        <div id="revenueChart" class="h-64 w-full"></div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Logs de Pagamento</h2>

            <form action="{{ route('admin.payments.index') }}" method="GET" class="flex items-center gap-2">
                <select name="gateway" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">Todos Gateway</option>
                    <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                </select>
                <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">Todos Status</option>
                    <option value="succeeded" {{ request('status') == 'succeeded' ? 'selected' : '' }}>Sucesso</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Falha</option>
                </select>
                <button type="submit" class="px-3 py-2 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600">
                    <x-icon name="filter" class="w-4 h-4" />
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-semibold text-gray-500 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Data</th>
                        <th class="px-6 py-4">Usuário</th>
                        <th class="px-6 py-4">Gateway</th>
                        <th class="px-6 py-4">Valor</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">ID Externo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($payment->user)
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $payment->user->name }}</div>
                                    <div class="text-xs">{{ $payment->user->email }}</div>
                                @else
                                    <span class="text-xs text-red-500">Usuário Deletado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 uppercase font-bold text-xs">{{ $payment->gateway_slug }}</td>
                            <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">
                                {{ $payment->currency }} {{ number_format($payment->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($payment->status) {
                                        'succeeded', 'paid' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'failed' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 rounded-md text-xs font-bold {{ $statusColor }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $payment->external_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Nenhum registro de pagamento encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->links() }}
        </div>
    </div>

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
