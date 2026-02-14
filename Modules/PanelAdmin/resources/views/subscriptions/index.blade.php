@section('title', 'Assinaturas')

<x-paneladmin::layouts.master>
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <x-icon name="check-circle" style="solid" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Assinaturas Ativas</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <x-icon name="clock" style="solid" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pagamento Pendente</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pastDueCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <x-icon name="ban" style="solid" class="w-6 h-6 text-gray-600 dark:text-gray-400" />
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Canceladas</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $canceledCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Assinaturas (Recorrentes)</h2>
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex items-center gap-2">
                <select name="gateway" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">Todos os gateways</option>
                    <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                    <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                </select>
                <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativa</option>
                    <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Pagamento pendente</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelada</option>
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
                        <th class="px-6 py-4">Usuário</th>
                        <th class="px-6 py-4">Gateway</th>
                        <th class="px-6 py-4">Valor</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Próxima cobrança</th>
                        <th class="px-6 py-4">ID Externo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($subscriptions as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                @if($sub->user)
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $sub->user->name ?? $sub->user->email }}</div>
                                    <div class="text-xs">{{ $sub->user->email }}</div>
                                @else
                                    <span class="text-xs text-red-500">Usuário deletado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 uppercase font-bold text-xs">{{ $sub->gateway_slug }}</td>
                            <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">
                                {{ $sub->currency }} {{ number_format((float) $sub->amount, 2, ',', '.') }}/mês
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($sub->status) {
                                        'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        'past_due' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'canceled' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                        default => 'bg-gray-100 text-gray-700'
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 rounded-md text-xs font-bold {{ $statusColor }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $sub->current_period_end?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ Str::limit($sub->external_subscription_id, 20) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Nenhuma assinatura encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $subscriptions->links() }}
        </div>
    </div>
</x-paneladmin::layouts.master>
