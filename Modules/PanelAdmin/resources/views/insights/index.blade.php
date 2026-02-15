<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Central de Insights</x-slot>

    <x-paneladmin::page title="Central de Insights" subtitle="Gerencie as dicas e mensagens do assistente Vertex Bot.">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.insights.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="trigger_event" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">
                        <option value="">Todos os gatilhos</option>
                        <option value="low_balance" {{ request('trigger_event') === 'low_balance' ? 'selected' : '' }}>Saldo baixo</option>
                        <option value="budget_reached" {{ request('trigger_event') === 'budget_reached' ? 'selected' : '' }}>Orçamento atingido</option>
                        <option value="savings_milestone" {{ request('trigger_event') === 'savings_milestone' ? 'selected' : '' }}>Marco de economia</option>
                        <option value="daily_tip" {{ request('trigger_event') === 'daily_tip' ? 'selected' : '' }}>Dica do dia</option>
                    </select>
                </form>
                <a href="{{ route('admin.insights.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Dica
                </a>
            </div>
        </x-slot>

        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Gatilho</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Conteúdo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nível</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Ativo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">PRO</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($insights as $insight)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            @php
                                $triggerLabels = [
                                    'low_balance' => 'Saldo baixo',
                                    'budget_reached' => 'Orçamento atingido',
                                    'savings_milestone' => 'Marco de economia',
                                    'daily_tip' => 'Dica do dia',
                                ];
                            @endphp
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black rounded-lg uppercase tracking-wider">
                                {{ $triggerLabels[$insight->trigger_event] ?? $insight->trigger_event }}
                            </span>
                        </td>
                        <td class="px-6 py-5 max-w-md">
                            <span class="text-sm text-slate-700 dark:text-slate-300 line-clamp-2">{{ Str::limit($insight->content, 80) }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $levelStyles = [
                                    'info' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                    'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    'warning' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                    'danger' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $levelStyles[$insight->level] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $insight->level }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($insight->is_active)
                                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold">Sim</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-sm">Não</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($insight->is_pro_only ?? false)
                                <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black rounded uppercase">PRO</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.insights.edit', $insight) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.insights.destroy', $insight) }}" method="POST" onsubmit="return confirm('Remover esta dica?')">
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
                                <x-icon name="lightbulb" style="duotone" class="text-6xl text-slate-300 dark:text-slate-600 mb-6" />
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhum insight cadastrado.</p>
                                <a href="{{ route('admin.insights.create') }}" class="mt-6 text-[#11C76F] font-black text-sm hover:underline">Criar primeira dica</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>
            @if($insights->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $insights->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
