<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Regras de Conquistas</x-slot>

    <x-paneladmin::page title="Regras de Conquistas" subtitle="Configure as condições e guardas para desbloquear medalhas.">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.gamification.rules.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="condition_type" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">
                        <option value="">Todos os tipos</option>
                        <option value="pillar_threshold" {{ request('condition_type') === 'pillar_threshold' ? 'selected' : '' }}>Pilar 50/30/20</option>
                        <option value="reserve_months" {{ request('condition_type') === 'reserve_months' ? 'selected' : '' }}>Reserva (meses)</option>
                        <option value="consecutive_days" {{ request('condition_type') === 'consecutive_days' ? 'selected' : '' }}>Dias consecutivos</option>
                        <option value="savings_threshold" {{ request('condition_type') === 'savings_threshold' ? 'selected' : '' }}>Poupança %</option>
                        <option value="pro_subscription" {{ request('condition_type') === 'pro_subscription' ? 'selected' : '' }}>Assinante PRO</option>
                    </select>
                </form>
                <a href="{{ route('admin.gamification.rules.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Regra
                </a>
            </div>
        </x-slot>

        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Trigger</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Medalha</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Prioridade</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Ativo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($rules as $rule)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            <code class="text-xs font-mono text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $rule->trigger_key }}</code>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $typeLabels = [
                                    'pillar_threshold' => 'Pilar 50/30/20',
                                    'reserve_months' => 'Reserva (meses)',
                                    'consecutive_days' => 'Dias consecutivos',
                                    'savings_threshold' => 'Poupança %',
                                    'pro_subscription' => 'Assinante PRO',
                                ];
                            @endphp
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
                                {{ $typeLabels[$rule->condition_type] ?? $rule->condition_type }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($rule->medal)
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $rule->medal->title }}</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <span class="font-bold text-slate-800 dark:text-white">{{ $rule->priority }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($rule->is_active)
                                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold">Sim</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-sm">Não</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.gamification.rules.edit', $rule) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.gamification.rules.destroy', $rule) }}" method="POST" onsubmit="return confirm('Remover esta regra?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <x-icon name="list-check" style="duotone" class="text-6xl text-slate-300 dark:text-slate-600 mb-6" />
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhuma regra cadastrada.</p>
                                <a href="{{ route('admin.gamification.rules.create') }}" class="mt-6 text-[#11C76F] font-black text-sm hover:underline">Criar primeira regra</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>
            @if($rules->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $rules->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
