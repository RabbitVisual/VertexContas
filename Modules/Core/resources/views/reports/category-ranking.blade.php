<x-paneluser::layouts.master :title="'Ranking de Categorias'">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-black text-3xl text-slate-800 dark:text-white flex items-center">
                <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-xl mr-3">
                    <x-icon name="chart-pie" style="duotone" class="text-purple-600 dark:text-purple-400" />
                </div>
                Ranking de Categorias
            </h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1 ml-14">Descubra para onde seu dinheiro está indo.</p>
        </div>

        <div class="flex gap-2">
            @if(auth()->user()->hasRole('pro_user') || auth()->user()->hasRole('admin'))
                <a href="{{ route('core.reports.export.categories.csv') }}" class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors font-bold text-sm">
                    <x-icon name="file-csv" style="solid" class="mr-2" /> CSV
                </a>
            @else
                <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg cursor-not-allowed font-bold text-sm" title="Disponível no plano PRO">
                    <x-icon name="lock" style="solid" class="mr-2" /> CSV
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Chart -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6 text-center">Distribuição de Despesas</h3>
            <div id="categoryChart" class="flex justify-center"></div>
        </div>

        <!-- Summary & Stats -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Receitas</span>
                    <p class="text-2xl font-black text-emerald-700 dark:text-emerald-300">R$ {{ number_format($summary['income'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-100 dark:border-red-800">
                    <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">Despesas</span>
                    <p class="text-2xl font-black text-red-700 dark:text-red-300">R$ {{ number_format($summary['expense'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800">
                    <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Taxa de Poupança</span>
                    <p class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ number_format($summary['savings_rate'], 1) }}%</p>
                </div>
            </div>

            <!-- List -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-xs uppercase font-bold text-slate-500 dark:text-slate-300">
                        <tr>
                            <th class="px-6 py-4">Categoria</th>
                            <th class="px-6 py-4 text-center">Transações</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($ranking as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs" style="background-color: {{ $item['color'] }}">
                                        <x-icon :name="$item['icon'] ?? 'circle-dollar'" style="solid" class="text-xs text-white" />
                                    </div>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $item['category'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center font-medium">{{ $item['count'] }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-700 dark:text-slate-200">
                                    R$ {{ number_format($item['total'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const options = {
                    series: @json($ranking->pluck('total')->values()),
                    chart: {
                        type: 'donut',
                        height: 350,
                        fontFamily: 'Poppins, sans-serif'
                    },
                    labels: @json($ranking->pluck('category')->values()),
                    colors: @json($ranking->pluck('color')->values()),
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return "R$ " + w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: { enabled: false }
                };

                const chart = new ApexCharts(document.querySelector("#categoryChart"), options);
                chart.render();
            });
        </script>
    @endpush
</x-paneluser::layouts.master>
