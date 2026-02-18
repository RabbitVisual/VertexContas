<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Dashboard</x-slot>

    <x-paneladmin::page title="Painel Administrativo" subtitle="Visão geral do sistema, métricas e atalhos rápidos.">
        <x-slot name="header">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                    Sistema online
                </span>
                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium tabular-nums">
                    {{ now()->format('d/m/Y · H:i') }}
                </span>
                @if(($wikiSuggestionsPending ?? 0) > 0)
                    <a href="{{ route('admin.wiki.suggestions') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 hover:bg-amber-500/20 transition-colors">
                        <x-icon name="lightbulb" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                        {{ $wikiSuggestionsPending }} sugestão(ões) Wiki
                    </a>
                @endif
            </div>
        </x-slot>

        {{-- KPIs --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
            {{-- Receita Total --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="vault" style="duotone" class="w-20 h-20 text-indigo-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Receita total</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="money-bill-trend-up" style="duotone" class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ format_currency($totalRevenue) }}</p>
                    @php
                        $revenueDiff = $monthlyRevenue - $revenueLastMonth;
                        $revPercent = $revenueLastMonth > 0 ? ($revenueDiff / $revenueLastMonth) * 100 : ($revenueDiff >= 0 ? 100 : 0);
                    @endphp
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400 inline-flex items-center gap-1">
                        <x-icon name="{{ $revenueDiff >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}" style="duotone" class="w-3.5 h-3.5 shrink-0 {{ $revenueDiff >= 0 ? 'text-emerald-500' : 'text-red-500' }}" />
                        {{ format_percent(abs($revPercent), 1) }} vs mês anterior
                    </p>
                </div>
            </div>

            {{-- Total Usuários --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="users" style="duotone" class="w-20 h-20 text-purple-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuários</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="users-gear" style="duotone" class="w-4 h-4 text-purple-600 dark:text-purple-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ format_number($totalUsers, 0) }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400 inline-flex items-center gap-1">
                        <x-icon name="user-plus" style="duotone" class="w-3.5 h-3.5 shrink-0 text-emerald-500" />
                        +{{ $newUsersThisMonth }} este mês
                    </p>
                </div>
            </div>

            {{-- Assinantes PRO (link) --}}
            <a href="{{ route('admin.subscriptions.index') }}" class="block bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group hover:border-[#11C76F]/30 hover:shadow-md transition-all">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="crown" style="duotone" class="w-20 h-20 text-amber-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Assinantes PRO</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="star" style="duotone" class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ format_number($proUsersCount, 0) }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ $totalUsers > 0 ? format_percent(($proUsersCount / $totalUsers) * 100, 1) : '0%' }} da base · {{ $activeSubscriptionsCount ?? 0 }} assinaturas ativas
                    </p>
                </div>
            </a>

            {{-- Tickets (link) --}}
            <a href="{{ route('admin.support.index') }}" class="block bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group hover:border-emerald-500/30 hover:shadow-md transition-all">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="headset" style="duotone" class="w-20 h-20 text-emerald-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tickets abertos</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="ticket" style="duotone" class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $openTicketsCount }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400 inline-flex items-center gap-1">
                        <x-icon name="clock" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                        Aguardando resposta
                    </p>
                </div>
            </a>

            {{-- Score financeiro --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="chart-pie" style="duotone" class="w-20 h-20 text-cyan-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Score financeiro</span>
                        <div class="w-9 h-9 rounded-xl bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="wallet" style="duotone" class="w-4 h-4 text-cyan-600 dark:text-cyan-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $avgFinancialScore ?? 0 }}/100</p>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Média (economia, orçamento, consistência)</p>
                </div>
            </div>

            {{-- Conversão Blog --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="absolute -right-2 -bottom-2 opacity-10 group-hover:opacity-20 transition-opacity">
                    <x-icon name="chart-simple" style="duotone" class="w-20 h-20 text-indigo-500 shrink-0" />
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Conversão blog</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                            <x-icon name="arrow-right-arrow-left" style="duotone" class="w-4 h-4 text-indigo-600 dark:text-indigo-400 shrink-0" />
                        </div>
                    </div>
                    <p class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ format_percent($blogConversionRate, 1) }}</p>
                    <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Visitantes → Assinantes</p>
                </div>
            </div>
        </section>

        {{-- Gráficos --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="chart-line" style="duotone" class="w-5 h-5 text-indigo-500 shrink-0" />
                        Evolução da receita
                    </h2>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">Últimos 6 meses</span>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <x-icon name="chart-pie" style="duotone" class="w-5 h-5 text-purple-500 shrink-0" />
                    Distribuição de usuários
                </h2>
                <div class="h-48 flex items-center justify-center">
                    <canvas id="userChart"></canvas>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 shrink-0"></span>
                            <span class="text-slate-600 dark:text-slate-400 font-medium">PRO</span>
                        </div>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $proUsersCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 shrink-0"></span>
                            <span class="text-slate-600 dark:text-slate-400 font-medium">Gratuitos</span>
                        </div>
                        <span class="font-bold text-slate-900 dark:text-white">{{ $freeUsersCount }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Atividade recente --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="user-plus" style="duotone" class="w-5 h-5 text-blue-500 shrink-0" />
                        Últimos cadastros
                    </h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-[#11C76F] hover:text-[#0EA85A] uppercase tracking-wider transition-colors">Ver todos</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($recentUsers as $user)
                        <a href="{{ route('admin.users.show', $user) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($user->photo)
                                    <img src="{{ $user->photo_url }}" alt="" class="h-10 w-10 rounded-xl object-cover shrink-0 border border-slate-200 dark:border-slate-600">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center shrink-0">
                                        <x-icon name="user" style="duotone" class="w-5 h-5 shrink-0" />
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-3">
                                <span class="block text-xs font-medium text-slate-500 dark:text-slate-400">{{ $user->created_at->format('d/m/Y') }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-lg text-[10px] font-bold uppercase {{ $user->hasRole('pro_user') ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                                    {{ $user->hasRole('pro_user') ? 'PRO' : 'FREE' }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 shrink-0">
                                <x-icon name="users" style="duotone" class="w-6 h-6 text-slate-400 shrink-0" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum cadastro recente.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="credit-card" style="duotone" class="w-5 h-5 text-emerald-500 shrink-0" />
                        Pagamentos recentes
                    </h2>
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-[#11C76F] hover:text-[#0EA85A] uppercase tracking-wider transition-colors">Ver logs</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($recentPayments as $payment)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center shrink-0">
                                    <x-icon name="receipt" style="duotone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $payment->user->name ?? 'Usuário' }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-mono uppercase">{{ $payment->gateway_slug ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 ml-3">
                                <p class="text-sm font-black text-slate-900 dark:text-white">{{ format_currency($payment->amount) }}</p>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 mt-1 rounded-lg text-[10px] font-bold {{ $payment->status === 'succeeded' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                    {{ strtoupper($payment->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 shrink-0">
                                <x-icon name="credit-card" style="duotone" class="w-6 h-6 text-slate-400 shrink-0" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum pagamento recente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Blog --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="newspaper" style="duotone" class="w-5 h-5 text-indigo-500 shrink-0" />
                        Artigos mais lidos
                    </h2>
                    <a href="{{ route('admin.blog.index') }}" class="text-xs font-bold text-[#11C76F] hover:text-[#0EA85A] uppercase tracking-wider transition-colors">Ver blog</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($mostReadPosts as $post)
                        <a href="{{ route('admin.blog.show', $post) }}" class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30 group">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate flex-1 min-w-0">{{ $post->title }}</p>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 shrink-0 ml-2">{{ $post->views }} views</span>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 shrink-0">
                                <x-icon name="newspaper" style="duotone" class="w-6 h-6 text-slate-400 shrink-0" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum artigo publicado.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="feather-pointed" style="duotone" class="w-5 h-5 text-amber-500 shrink-0" />
                        Top autores
                    </h2>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($topAuthors as $stat)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $stat->author->name ?? '—' }}</p>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 shrink-0 ml-2">{{ $stat->total }} {{ $stat->total === 1 ? 'post' : 'posts' }}</span>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 shrink-0">
                                <x-icon name="feather-pointed" style="duotone" class="w-6 h-6 text-slate-400 shrink-0" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum autor com publicações.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <x-icon name="comments" style="duotone" class="w-5 h-5 text-amber-500 shrink-0" />
                        Comentários recentes
                    </h2>
                    <a href="{{ route('admin.blog.comments') }}" class="text-xs font-bold text-[#11C76F] hover:text-[#0EA85A] uppercase tracking-wider transition-colors">Moderar</a>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($recentComments as $comment)
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                            <p class="text-sm text-slate-700 dark:text-slate-300 line-clamp-2">{{ $comment->content }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $comment->user->name ?? '—' }} · {{ $comment->created_at->diffForHumans() }}</p>
                            <a href="{{ route('admin.blog.show', $comment->post) }}" class="text-xs font-bold text-[#11C76F] hover:underline mt-0.5 inline-block truncate max-w-full">{{ $comment->post->title }}</a>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-3 shrink-0">
                                <x-icon name="comments" style="duotone" class="w-6 h-6 text-slate-400 shrink-0" />
                            </div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Nenhum comentário recente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Atalhos --}}
        <section class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <x-icon name="bolt" style="duotone" class="w-5 h-5 text-amber-500 shrink-0" />
                Atalhos do administrador
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="users" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Usuários</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="gears" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Configurações</span>
                </a>
                <a href="{{ route('admin.gateways.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="credit-card" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Gateways</span>
                </a>
                <a href="{{ route('admin.plans.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="crown" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-amber-500 mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Planos</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="shield-halved" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Permissões</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="bell" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Notificações</span>
                </a>
                <a href="{{ route('admin.support.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="headset" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Suporte</span>
                </a>
                <a href="{{ route('admin.blog.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="newspaper" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Blog</span>
                </a>
                <a href="{{ route('admin.wiki.suggestions') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="book" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Wiki</span>
                </a>
                <a href="{{ route('admin.legal.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="scale-balanced" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Legal</span>
                </a>
                <a href="{{ route('admin.insights.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-[#11C76F]/50 hover:bg-[#11C76F]/5 transition-all group">
                    <x-icon name="lightbulb" style="duotone" class="w-7 h-7 text-slate-400 group-hover:text-[#11C76F] mb-2 shrink-0" />
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 group-hover:text-[#11C76F] text-center">Insights</span>
                </a>
            </div>
        </section>
    </x-paneladmin::page>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctxRevenue = document.getElementById('revenueChart');
            if (ctxRevenue) {
                new window.Chart(ctxRevenue.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthLabels) !!},
                        datasets: [{
                            label: 'Receita (R$)',
                            data: {!! json_encode($revenueData) !!},
                            fill: true,
                            backgroundColor: 'rgba(17, 199, 111, 0.08)',
                            borderColor: '#11C76F',
                            borderWidth: 2,
                            tension: 0.4,
                            pointBackgroundColor: '#11C76F',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(148, 163, 184, 0.15)' },
                                ticks: { font: { size: 11, weight: '600' } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11, weight: '600' } }
                            }
                        }
                    }
                });
            }
            const ctxUser = document.getElementById('userChart');
            if (ctxUser) {
                new window.Chart(ctxUser.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['PRO', 'FREE'],
                        datasets: [{
                            data: [{{ $proUsersCount }}, {{ $freeUsersCount }}],
                            backgroundColor: ['#11C76F', '#cbd5e1'],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-paneladmin::layouts.master>
