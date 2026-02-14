@php
    $isPro = auth()->user()?->isPro() ?? false;
    $balance = $totalBalance ?? 0;
    $totalIncome = $monthlyIncome ?? 0;
    $totalExpense = $monthlyExpense ?? 0;
@endphp
<x-paneluser::layouts.master :title="'Dashboard'">
    {{-- 3 cards principais - Flowbite básico --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Saldo Atual</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                </div>
                <x-icon name="wallet" style="duotone" class="w-10 h-10 text-primary-500 opacity-80" />
            </div>
            <span class="inline-flex items-center mt-2 text-xs font-medium {{ $balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                <x-icon name="{{ $balance >= 0 ? 'arrow-trend-up' : 'arrow-trend-down' }}" style="solid" class="w-3 h-3 mr-1" />
                Total Disponível
            </span>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Receitas (mês)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
                </div>
                <x-icon name="arrow-down" style="duotone" class="w-10 h-10 text-emerald-500 opacity-80" />
            </div>
            <span class="inline-flex items-center mt-2 text-xs font-medium text-emerald-600 dark:text-emerald-400">Recebido este mês</span>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Despesas (mês)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">R$ {{ number_format($totalExpense, 2, ',', '.') }}</p>
                </div>
                <x-icon name="arrow-up" style="duotone" class="w-10 h-10 text-rose-500 opacity-80" />
            </div>
            <span class="inline-flex items-center mt-2 text-xs font-medium text-rose-600 dark:text-rose-400">Gasto este mês</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Transações recentes - lista simples --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="clock-rotate-left" style="duotone" class="w-5 h-5 text-primary-500" />
                    Transações Recentes
                </h3>
                <a href="{{ route('core.transactions.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentTransactions as $transaction)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $transaction->description }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->category->name ?? 'Geral' }} · {{ $transaction->date->format('d/m/Y') }}</p>
                        </div>
                        <span class="font-semibold {{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-600 dark:text-gray-400">
                        <x-icon name="receipt" style="duotone" class="w-12 h-12 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                        <p class="text-sm font-medium">Nenhuma transação recente</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Metas - resumo breve --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <x-icon name="bullseye" style="duotone" class="w-5 h-5 text-primary-500" />
                    Metas
                </h3>
                <a href="{{ route('core.goals.index') }}" class="text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">Ver todas</a>
            </div>
            <div class="p-4 space-y-4">
                @forelse($goals as $goal)
                    @php
                        $pct = $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $goal->name }}</span>
                            <span class="text-gray-500 dark:text-gray-400">{{ number_format($pct, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-primary-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-600 dark:text-gray-400">
                        <x-icon name="bullseye" style="duotone" class="w-12 h-12 mx-auto mb-2 text-gray-400 dark:text-gray-500" />
                        <p class="text-sm font-medium">Nenhuma meta cadastrada</p>
                        <a href="{{ route('core.goals.create') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium mt-2 inline-block transition-colors">Criar primeira meta</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CTA para FREE - card em destaque, botão atrativo, legível em claro e escuro --}}
    @if(!$isPro)
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-800 shadow-xl p-8 text-center">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
            <div class="relative z-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm mb-4">
                    <x-icon name="crown" style="solid" class="w-8 h-8 text-white" />
                </div>
                <h4 class="font-bold text-white text-xl mb-2">Desbloqueie o Dashboard Financeiro Pro</h4>
                <p class="text-blue-100 dark:text-blue-200 text-sm mb-6 max-w-md mx-auto leading-relaxed">Gráficos avançados, contas ilimitadas, metas e orçamentos completos. Assine o Vertex Pro e tenha controle total das suas finanças.</p>
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-blue-700 dark:bg-white dark:text-blue-800 font-bold rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-50 dark:hover:bg-blue-50 transition-all hover:scale-105 active:scale-100">
                    <x-icon name="bolt" style="solid" class="w-5 h-5" />
                    Ver Planos e Assinar
                </a>
            </div>
        </div>
    @else
        <div class="flex justify-center">
            <a href="{{ route('core.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 font-semibold rounded-lg border border-gray-200 dark:border-gray-600 transition-colors">
                <x-icon name="chart-line" style="duotone" class="w-5 h-5" />
                Ver Dashboard Financeiro Pro
            </a>
        </div>
    @endif
</x-paneluser::layouts.master>
