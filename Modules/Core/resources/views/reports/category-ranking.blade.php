<x-paneluser::layouts.master :title="'Ranking de Categorias'">
<div class="max-w-6xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-purple-600/5 dark:bg-purple-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-violet-600/5 dark:bg-violet-600/10 rounded-full blur-[100px]" aria-hidden="true"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold text-purple-600 dark:text-purple-500 uppercase tracking-widest mb-4">
                    <a href="{{ route('core.reports.index') }}" class="hover:underline">Relatórios</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Ranking de Categorias</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Ranking de <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-violet-600 dark:from-purple-400 dark:to-violet-400">Categorias</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Descubra para onde seu dinheiro está indo e priorize onde ajustar.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Foque nas 3 categorias com maior gasto para obter o maior impacto.</p>
            </div>

            @php $isPro = auth()->user()->hasRole('pro_user') || auth()->user()->hasRole('admin'); @endphp
            <div class="flex flex-wrap gap-2">
                @if($isPro)
                    <a href="{{ route('core.reports.categories.view', request()->only(['start_date','end_date','account_id'])) }}" target="_blank" rel="noopener noreferrer" class="flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-bold text-sm" title="Abre em nova aba. Use Ctrl+P para imprimir ou salvar como PDF.">
                        <x-icon name="print" style="solid" class="mr-2" /> Ver / Imprimir
                    </a>
                    <a href="{{ route('core.reports.export.categories.csv', request()->only(['start_date','end_date','account_id'])) }}" class="flex items-center px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 font-bold text-sm">
                        <x-icon name="file-csv" style="solid" class="mr-2" /> CSV
                    </a>
                @else
                    <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded-lg cursor-not-allowed font-bold text-sm" title="Disponível no plano PRO">
                        <x-icon name="lock" style="solid" class="mr-2" /> Ver / Imprimir
                    </button>
                    <button onclick="alert('Recurso exclusivo para assinantes PRO!')" class="flex items-center px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-500 rounded-lg cursor-not-allowed font-bold text-sm" title="Disponível no plano PRO">
                        <x-icon name="lock" style="solid" class="mr-2" /> CSV
                    </button>
                @endif
                <a href="{{ route('core.reports.index') }}" class="flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-sm">
                    <x-icon name="arrow-left" style="solid" class="mr-2" /> Voltar
                </a>
            </div>
        </div>
    </div>

    {{-- Filtros PRO --}}
    @if($isPro)
    <div class="bg-white dark:bg-gray-900/50 rounded-3xl border border-gray-200 dark:border-white/5 p-6 shadow-sm">
        <h2 class="font-bold text-lg text-slate-800 dark:text-white mb-4 flex items-center gap-2">
            <x-icon name="filter" style="duotone" class="w-5 h-5 text-purple-600" />
            Filtros
        </h2>
        <form method="GET" action="{{ route('core.reports.categories') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conta</label>
                <select name="account_id" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-800 dark:text-white shadow-sm">
                    <option value="">Todas</option>
                    @foreach($accounts ?? [] as $acc)
                        <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg flex items-center gap-2">
                <x-icon name="magnifying-glass" style="solid" class="w-4 h-4" /> Filtrar
            </button>
        </form>
    </div>
    @endif

    @php
        $catBalance = ($summary['income'] ?? 0) - ($summary['expense'] ?? 0);
        $firstCat = $ranking->first();
        $topCatPct = ($firstCat && ($summary['expense'] ?? 0) > 0) ? (($firstCat['total'] / $summary['expense']) * 100) : null;
    @endphp

    {{-- Dicas + O que fazer --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-core::report-tips variant="categories" />
        <x-core::report-actions
            :balance="$catBalance"
            :savings-rate="$summary['savings_rate'] ?? 0"
            :top-category-percent="$topCatPct"
        />
    </div>

    {{-- Referência 50-30-20 (quando houver despesas) --}}
    @if(($summary['expense'] ?? 0) > 0)
    <div class="rounded-2xl border border-purple-200 dark:border-purple-800/50 bg-purple-50/30 dark:bg-purple-900/10 p-6">
        <h4 class="font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
            <x-icon name="scale-balanced" style="duotone" class="w-5 h-5 text-purple-600" />
            Regra 50-30-20 — referência
        </h4>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Divida suas despesas em: 50% necessidades (moradia, alimentação), 30% desejos (lazer), 20% poupança. Compare com suas categorias acima.</p>
        <div class="grid grid-cols-3 gap-3">
            <div class="text-center p-3 rounded-xl bg-white dark:bg-slate-800/50 border border-purple-100 dark:border-purple-800/30">
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400">50%</span>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Necessidades</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-white dark:bg-slate-800/50 border border-purple-100 dark:border-purple-800/30">
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400">30%</span>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Desejos</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-white dark:bg-slate-800/50 border border-purple-100 dark:border-purple-800/30">
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">20%</span>
                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mt-1">Poupança</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-lg border border-slate-100 dark:border-slate-700">
            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-6 text-center">Distribuição de Despesas</h3>
            <div id="categoryChart" class="sensitive-value flex justify-center"></div>
        </div>

        <!-- Summary & Stats -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800">
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Receitas</span>
                    <p class="sensitive-value text-2xl font-black text-emerald-700 dark:text-emerald-300"><x-core::financial-value :value="$summary['income']" /></p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-100 dark:border-red-800">
                    <span class="text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wider">Despesas</span>
                    <p class="sensitive-value text-2xl font-black text-red-700 dark:text-red-300"><x-core::financial-value :value="$summary['expense']" /></p>
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
                            <th class="px-6 py-4 text-right">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @php $totalExp = $summary['expense'] ?? 0; @endphp
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
                                    <span class="sensitive-value"><x-core::financial-value :value="$item['total']" /></span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-500 font-medium">{{ $totalExp > 0 ? number_format(($item['total'] / $totalExp) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
