<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Assinaturas</x-slot>

    <x-paneladmin::page title="Assinaturas" subtitle="Assinaturas recorrentes e status.">
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="check-circle" style="solid" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Assinaturas Ativas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="clock" style="solid" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Pagamento Pendente</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $pastDueCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="ban" style="solid" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Canceladas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $canceledCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <select name="gateway" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="">Todos os gateways</option>
                        <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                        <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                    </select>
                    <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="">Todos os status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativa</option>
                        <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Pagamento pendente</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="filter" style="duotone" class="w-4 h-4" /> Filtrar
                    </button>
                </form>
            </x-slot>

            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuário</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Gateway</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Valor</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Próxima cobrança</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">ID Externo</th>
                    </tr>
                </x-slot>
                @forelse($subscriptions as $sub)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-4">
                            @if($sub->user)
                                <div class="font-medium text-slate-900 dark:text-white">{{ $sub->user->name ?? $sub->user->email }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $sub->user->email }}</div>
                            @else
                                <span class="text-xs text-red-500">Usuário deletado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 uppercase font-bold text-xs text-slate-700 dark:text-slate-300">{{ $sub->gateway_slug }}</td>
                        <td class="px-6 py-4 font-mono font-medium text-slate-900 dark:text-white">
                            {{ format_currency((float) $sub->amount, $sub->currency ?? 'R$') }}/mês
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($sub->status) {
                                    'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'past_due' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'canceled' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400',
                                    default => 'bg-slate-100 text-slate-700'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-bold {{ $statusColor }}">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                            {{ $sub->current_period_end?->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">{{ Str::limit($sub->external_subscription_id, 20) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            Nenhuma assinatura encontrada.
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                {{ $subscriptions->links() }}
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
