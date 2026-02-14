<x-paneladmin::layouts.master>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Painel Administrativo</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Bem-vindo de volta! Aqui está o que está acontecendo no sistema hoje.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-xs font-bold flex items-center">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                Sistema Online
            </span>
            <div class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <!-- Main Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <x-icon name="money-bill-trend-up" class="w-24 h-24 text-white" />
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-blue-100/80 font-semibold text-sm uppercase tracking-wider">Receita Total</p>
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <x-icon name="vault" class="w-5 h-5 text-white" />
                    </div>
                </div>
                <h3 class="text-3xl font-black mb-1">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                <div class="flex items-center text-xs text-blue-100/90 font-medium">
                    @php
                        $revenueDiff = $monthlyRevenue - $revenueLastMonth;
                        $revPercent = $revenueLastMonth > 0 ? ($revenueDiff / $revenueLastMonth) * 100 : 100;
                    @endphp
                    <x-icon name="{{ $revenueDiff >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}" class="w-3 h-3 mr-1" />
                    <span>{{ number_format(abs($revPercent), 1) }}% em relação ao mês anterior</span>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-gradient-to-br from-purple-600 to-pink-700 rounded-2xl p-6 text-white shadow-xl shadow-purple-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <x-icon name="users" class="w-24 h-24 text-white" />
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-purple-100/80 font-semibold text-sm uppercase tracking-wider">Total de Usuários</p>
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <x-icon name="users-gear" class="w-5 h-5 text-white" />
                    </div>
                </div>
                <h3 class="text-3xl font-black mb-1">{{ number_format($totalUsers, 0, ',', '.') }}</h3>
                <div class="flex items-center text-xs text-purple-100/90 font-medium">
                    <x-icon name="user-plus" class="w-3 h-3 mr-1" />
                    <span>+{{ $newUsersThisMonth }} novos este mês</span>
                </div>
            </div>
        </div>

        <!-- Pro Users Card -->
        <a href="{{ route('admin.subscriptions.index') }}" class="block bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl shadow-orange-500/20 relative overflow-hidden group hover:shadow-2xl hover:shadow-orange-500/30 transition-all">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <x-icon name="crown" class="w-24 h-24 text-white" />
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-amber-100/80 font-semibold text-sm uppercase tracking-wider">Assinantes PRO</p>
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <x-icon name="star" class="w-5 h-5 text-white" />
                    </div>
                </div>
                <h3 class="text-3xl font-black mb-1">{{ number_format($proUsersCount, 0, ',', '.') }}</h3>
                <div class="flex flex-col gap-1 text-xs text-amber-100/90 font-medium">
                    <span><x-icon name="chart-pie" class="w-3 h-3 mr-1 inline" />{{ $totalUsers > 0 ? number_format(($proUsersCount/$totalUsers)*100, 1) : 0 }}% da base total</span>
                    <span><x-icon name="arrows-rotate" class="w-3 h-3 mr-1 inline" />{{ $activeSubscriptionsCount ?? 0 }} assinaturas recorrentes ativas</span>
                </div>
            </div>
        </a>

        <!-- Support Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <x-icon name="headset" class="w-24 h-24 text-white" />
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-emerald-100/80 font-semibold text-sm uppercase tracking-wider">Tickets Abertos</p>
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <x-icon name="ticket" class="w-5 h-5 text-white" />
                    </div>
                </div>
                <h3 class="text-3xl font-black mb-1">{{ $openTicketsCount }}</h3>
                <div class="flex items-center text-xs text-emerald-100/90 font-medium">
                    <x-icon name="clock" class="w-3 h-3 mr-1" />
                    <span>Aguardando resposta</span>
                </div>
            </div>
        </div>
        <!-- Blog Conversion Rate -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <x-icon name="chart-simple" class="w-24 h-24 text-white" />
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-indigo-100/80 font-semibold text-sm uppercase tracking-wider">Conversão Blog</p>
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <x-icon name="arrow-right-arrow-left" class="w-5 h-5 text-white" />
                    </div>
                </div>
                <h3 class="text-3xl font-black mb-1">{{ number_format($blogConversionRate, 1) }}%</h3>
                <div class="flex items-center text-xs text-indigo-100/90 font-medium">
                    <x-icon name="users" class="w-3 h-3 mr-1" />
                    <span>Visitantes > Assinantes</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="chart-line" class="text-indigo-500" />
                    Evolução da Receita
                </h2>
                <span class="text-xs font-semibold text-slate-400 bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-700 px-2 py-1 rounded">Últimos 6 Meses</span>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Distribution Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                <x-icon name="chart-pie" class="text-purple-500" />
                Distribuição de Usuários
            </h2>
            <div class="h-48 flex items-center justify-center">
                <canvas id="userChart"></canvas>
            </div>
            <div class="mt-6 space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                        <span class="text-slate-600 dark:text-slate-400 font-medium">Usuários PRO</span>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $proUsersCount }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-slate-200 dark:bg-slate-600"></span>
                        <span class="text-slate-600 dark:text-slate-400 font-medium">Gratuitos</span>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $freeUsersCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Users -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="user-plus" class="text-blue-500" />
                    Últimos Cadastros
                </h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 transition-colors uppercase tracking-widest">Ver Todos</a>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700">
                @foreach($recentUsers as $user)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-900/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                                {{ substr($user->first_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ $user->full_name }}</h4>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-xs font-bold text-slate-400">{{ $user->created_at->format('d/m/Y') }}</span>
                            <span class="inline-block px-2 py-0.5 mt-1 rounded text-[10px] font-black uppercase {{ $user->hasRole('pro_user') ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30' : 'bg-slate-100 text-slate-600 dark:bg-slate-700' }}">
                                {{ $user->hasRole('pro_user') ? 'PRO' : 'FREE' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="credit-card" class="text-emerald-500" />
                    Pagamentos Recentes
                </h2>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-600 transition-colors uppercase tracking-widest">Ver Logs</a>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700">
                @foreach($recentPayments as $payment)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-900/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 flex items-center justify-center">
                                <x-icon name="receipt" class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ $payment->user->full_name ?? 'Usuário Desconhecido' }}</h4>
                                <p class="text-[10px] text-slate-400 font-mono uppercase">{{ $payment->gateway_slug }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-slate-900 dark:text-white">R$ {{ number_format($payment->amount, 2, ',', '.') }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[10px] font-bold {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                <span class="w-1 h-1 rounded-full bg-current mr-1"></span>
                                {{ strtoupper($payment->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Blog Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Most Read Posts -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="newspaper" class="text-indigo-500" />
                    Artigos Mais Lidos
                </h2>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700">
                @foreach($mostReadPosts as $post)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-900/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="text-sm font-bold text-slate-800 dark:text-white truncate max-w-[200px]">{{ $post->title }}</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-500">{{ $post->views }} views</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Authors -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-6 border-b border-slate-50 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <x-icon name="feather-pointed" class="text-amber-500" />
                    Top Autores
                </h2>
            </div>
            <div class="divide-y divide-slate-50 dark:divide-slate-700">
                @foreach($topAuthors as $stat)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-900/40 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="text-sm font-bold text-slate-800 dark:text-white">{{ $stat->author->name ?? "Unknown" }}</div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-500">{{ $stat->total }} posts</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
        <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
            <x-icon name="bolt" class="text-amber-500" />
            Atalhos do Administrador
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="users" class="text-2xl text-slate-400 group-hover:text-indigo-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Usuários</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="gears" class="text-2xl text-slate-400 group-hover:text-indigo-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Configurações</span>
            </a>
            <a href="{{ route('admin.gateways.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="credit-card" class="text-2xl text-slate-400 group-hover:text-indigo-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Gateways</span>
            </a>
            <a href="{{ route('admin.plans.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="crown" class="text-2xl text-slate-400 group-hover:text-amber-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Planos</span>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="shield-halved" class="text-2xl text-slate-400 group-hover:text-emerald-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Permissões</span>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all group">
                <x-icon name="bell" class="text-2xl text-slate-400 group-hover:text-pink-500 mb-2" />
                <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Notificações</span>
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new window.Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthLabels) !!},
                    datasets: [{
                        label: 'Receita (R$)',
                        data: {!! json_encode($revenueData) !!},
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderColor: '#4f46e5',
                        borderWidth: 4,
                        tension: 0.4,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold' } }
                        }
                    }
                }
            });

            // User Distribution Chart
            const ctxUser = document.getElementById('userChart').getContext('2d');
            new window.Chart(ctxUser, {
                type: 'doughnut',
                data: {
                    labels: ['PRO', 'FREE'],
                    datasets: [{
                        data: [{{ $proUsersCount }}, {{ $freeUsersCount }}],
                        backgroundColor: ['#6366f1', '#e2e8f0'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-paneladmin::layouts.master>
