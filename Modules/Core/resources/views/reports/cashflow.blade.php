<x-paneluser::layouts.master :title="'Fluxo de Caixa'">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-xl mr-3">
                    <x-icon name="money-bill-trend-up" style="duotone" class="text-blue-600 dark:text-blue-400" />
                </div>
                Fluxo de Caixa
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Acompanhe suas receitas e despesas.</p>
        </div>

        <div class="flex gap-2">
            @if(auth()->user()->hasRole('pro_user') || auth()->user()->hasRole('admin'))
                <a href="{{ route('core.reports.export.cashflow.pdf', ['months' => $months]) }}" class="flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-bold text-sm">
                    <x-icon name="file-pdf" style="solid" class="mr-2" /> PDF
                </a>
                <a href="{{ route('core.reports.export.cashflow.csv', ['months' => $months]) }}" class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-bold text-sm">
                    <x-icon name="file-csv" style="solid" class="mr-2" /> CSV
                </a>
            @else
                <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed font-bold text-sm" title="Disponível no plano PRO">
                    <x-icon name="lock" style="solid" class="mr-2" /> PDF
                </button>
                <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed font-bold text-sm" title="Disponível no plano PRO">
                    <x-icon name="lock" style="solid" class="mr-2" /> CSV
                </button>
            @endif
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700 mb-8">
        <div id="cashFlowChart" class="sensitive-value"></div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white">Detalhamento Mensal</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-lg">Mês</th>
                        <th class="px-6 py-4 text-emerald-600 dark:text-emerald-400">Receitas</th>
                        <th class="px-6 py-4 text-red-600 dark:text-red-400">Despesas</th>
                        <th class="px-6 py-4 rounded-tr-lg">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($cashFlow->reverse() as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">{{ $item['month'] }}</td>
                            <td class="px-6 py-4 text-emerald-600 font-medium"><span class="sensitive-value">R$ {{ number_format($item['income'], 2, ',', '.') }}</span></td>
                            <td class="px-6 py-4 text-red-600 font-medium"><span class="sensitive-value">R$ {{ number_format($item['expense'], 2, ',', '.') }}</span></td>
                            <td class="px-6 py-4 font-bold {{ $item['balance'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                <span class="sensitive-value">R$ {{ number_format($item['balance'], 2, ',', '.') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const options = {
                    series: [{
                        name: 'Receitas',
                        data: @json($cashFlow->pluck('income')->values())
                    }, {
                        name: 'Despesas',
                        data: @json($cashFlow->pluck('expense')->values())
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false },
                        fontFamily: 'Poppins, sans-serif'
                    },
                    colors: ['#10b981', '#ef4444'],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 5,
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: { enabled: false },
                    stroke: { show: true, width: 2, colors: ['transparent'] },
                    xaxis: {
                        categories: @json($cashFlow->pluck('month')->values()),
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "R$ " + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "R$ " + val.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4,
                    },
                    legend: {
                        position: 'top',
                    }
                };

                const chart = new ApexCharts(document.querySelector("#cashFlowChart"), options);
                chart.render();
            });
        </script>
    @endpush
</x-paneluser::layouts.master>
