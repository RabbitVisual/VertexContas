<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Assinaturas</x-slot>

    <x-paneladmin::page title="Assinaturas" subtitle="Assinaturas recorrentes e status por gateway.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="arrows-rotate" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Ativas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <x-icon name="clock" style="duotone" class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Pagamento pendente</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $pastDueCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="ban" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Canceladas</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $canceledCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative inline-block">
                        <select name="gateway" class="subscriptions-select pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] min-w-[160px] appearance-none [&::-ms-expand]:hidden">
                            <option value="">Todos os gateways</option>
                            <option value="stripe" {{ request('gateway') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="mercadopago" {{ request('gateway') == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                        </span>
                    </div>
                    <div class="relative inline-block">
                        <select name="status" class="subscriptions-select pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] min-w-[160px] appearance-none [&::-ms-expand]:hidden">
                            <option value="">Todos os status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativa</option>
                            <option value="past_due" {{ request('status') == 'past_due' ? 'selected' : '' }}>Pagamento pendente</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" />
                        </span>
                    </div>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="filter" style="duotone" class="w-4 h-4" />
                        Filtrar
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
                                <div class="flex items-center gap-2">
                                    <x-icon name="user" style="duotone" class="w-4 h-4 text-slate-400 shrink-0" />
                                    <div>
                                        <div class="font-medium text-slate-900 dark:text-white">{{ $sub->user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $sub->user->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                                    <x-icon name="user-xmark" style="duotone" class="w-3.5 h-3.5" />
                                    Usuário removido
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 uppercase font-bold text-xs text-slate-700 dark:text-slate-300">
                                <x-icon name="credit-card" style="duotone" class="w-3.5 h-3.5" />
                                {{ $sub->gateway_slug }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 dark:text-white tabular-nums">
                            {{ format_currency((float) $sub->amount, $sub->currency ?? 'R$') }}/mês
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = match($sub->status) {
                                    'active' => ['class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400', 'icon' => 'circle-check', 'label' => 'Ativa'],
                                    'past_due' => ['class' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'icon' => 'clock', 'label' => 'Pagamento pendente'],
                                    'canceled' => ['class' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-400', 'icon' => 'ban', 'label' => 'Cancelada'],
                                    default => ['class' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300', 'icon' => 'circle-question', 'label' => ucfirst($sub->status)]
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $statusConfig['class'] }}">
                                <x-icon name="{{ $statusConfig['icon'] }}" style="duotone" class="w-3.5 h-3.5" />
                                {{ $statusConfig['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm tabular-nums">
                            @if($sub->current_period_end)
                                <span class="inline-flex items-center gap-1.5">
                                    <x-icon name="calendar-days" style="duotone" class="w-3.5 h-3.5 text-slate-400" />
                                    {{ $sub->current_period_end->format('d/m/Y') }}
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400 truncate max-w-[140px]" title="{{ $sub->external_subscription_id }}">{{ $sub->external_subscription_id ? Str::limit($sub->external_subscription_id, 20) : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="arrows-rotate" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma assinatura encontrada.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Ajuste os filtros ou aguarde novas assinaturas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            @if($subscriptions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $subscriptions->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>

    @push('styles')
    <style>
        .subscriptions-select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: none !important;
        }
    </style>
    @endpush
</x-paneladmin::layouts.master>
