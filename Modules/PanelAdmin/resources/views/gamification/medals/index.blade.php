<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Medalhas</x-slot>

    <x-paneladmin::page title="Medalhas" subtitle="Gerencie as medalhas e conquistas do sistema de gamificação.">
        <x-slot name="header">
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.gamification.medals.index') }}" method="GET" class="flex items-center gap-2">
                    <select name="difficulty" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">
                        <option value="">Todas dificuldades</option>
                        <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Fácil</option>
                        <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Médio</option>
                        <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Difícil</option>
                        <option value="advanced" {{ request('difficulty') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                    </select>
                    <select name="is_pro_only" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium">
                        <option value="">Todas</option>
                        <option value="0" {{ request('is_pro_only') === '0' ? 'selected' : '' }}>FREE</option>
                        <option value="1" {{ request('is_pro_only') === '1' ? 'selected' : '' }}>PRO</option>
                    </select>
                </form>
                <a href="{{ route('admin.gamification.medals.create') }}" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white px-4 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                    <x-icon name="plus" style="duotone" class="w-4 h-4" /> Nova Medalha
                </a>
            </div>
        </x-slot>

        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Medalha</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Trigger</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Dificuldade</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Raridade</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Ativo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">PRO</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($medals as $medal)
                    <tr class="group hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                                    <x-icon name="{{ $medal->icon_name ?? 'medal' }}" style="duotone" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white">{{ $medal->title }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">{{ Str::limit($medal->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <code class="text-xs font-mono text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">{{ $medal->trigger_key }}</code>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $diffLabels = ['easy' => 'Fácil', 'medium' => 'Médio', 'hard' => 'Difícil', 'advanced' => 'Avançado'];
                                $diffStyles = [
                                    'easy' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
                                    'medium' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
                                    'hard' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                    'advanced' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $diffStyles[$medal->difficulty ?? 'medium'] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $diffLabels[$medal->difficulty ?? 'medium'] ?? $medal->difficulty }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase capitalize bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $medal->rarity ?? 'silver' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($medal->is_active)
                                <span class="text-emerald-600 dark:text-emerald-400 text-sm font-bold">Sim</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-sm">Não</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($medal->is_pro_only)
                                <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black rounded uppercase">PRO</span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.gamification.medals.edit', $medal) }}" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:text-[#11C76F] transition-colors">
                                    <x-icon name="pen" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.gamification.medals.destroy', $medal) }}" method="POST" onsubmit="return confirm('Remover esta medalha? Regras vinculadas podem ser afetadas.')">
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
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <x-icon name="medal" style="duotone" class="text-6xl text-slate-300 dark:text-slate-600 mb-6" />
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Nenhuma medalha cadastrada.</p>
                                <a href="{{ route('admin.gamification.medals.create') }}" class="mt-6 text-[#11C76F] font-black text-sm hover:underline">Criar primeira medalha</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>
            @if($medals->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $medals->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
