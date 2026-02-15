<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Usuários</x-slot>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        {{-- Header & Filters (HyperUI style) --}}
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Usuários do Sistema</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Gerencie contas, planos e permissões.</p>
                </div>

                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative">
                        <select name="role" class="pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] appearance-none">
                            <option value="">Todas as Roles</option>
                            <option value="free_user" {{ request('role') == 'free_user' ? 'selected' : '' }}>Free User</option>
                            <option value="pro_user" {{ request('role') == 'pro_user' ? 'selected' : '' }}>Pro User</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <x-icon name="chevron-down" style="duotone" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" />
                    </div>
                    <div class="relative flex-1 min-w-[200px]">
                        <x-icon name="magnifying-glass" style="duotone" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou E-mail..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] placeholder-slate-400">
                    </div>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors flex items-center gap-2">
                        <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4" />
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        {{-- Table (HyperUI: sticky header, zebra) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="sticky top-0 z-10 bg-slate-50 dark:bg-slate-800/95 backdrop-blur border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Plano/Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Renda Decl.</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Cadastro</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-bold shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    @php
                                        $color = match($role->name) {
                                            'admin' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-700',
                                            'pro_user' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-700',
                                            'free_user' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-700',
                                            'support' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-700',
                                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600'
                                        };
                                        $icon = match($role->name) {
                                            'admin' => 'shield-keyhole',
                                            'pro_user' => 'crown',
                                            'free_user' => 'user',
                                            'support' => 'headset',
                                            default => 'user'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $color }}">
                                        <x-icon name="{{ $icon }}" style="duotone" class="w-3.5 h-3.5 mr-1.5" />
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900 dark:text-white tabular-nums">{{ format_currency($monthlyIncomeByUser[$user->id] ?? 0) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-1 rounded-lg">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Ativo
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 transition-colors" title="Ver Detalhes">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                        <x-icon name="users" style="duotone" class="w-8 h-8 text-slate-400" />
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum usuário encontrado.</p>
                                    <p class="text-sm text-slate-400 dark:text-slate-500">Tente ajustar os filtros de busca.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-paneladmin::layouts.master>
