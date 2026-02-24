<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Usuários</x-slot>

    <x-paneladmin::page title="Usuários do Sistema" subtitle="Gerencie contas, planos, permissões e assinaturas.">
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

        <x-paneladmin::card>
            <x-slot name="header">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative min-w-[160px]">
                        <select name="role" class="users-select w-full pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] appearance-none [&::-ms-expand]:hidden">
                            <option value="">Todas as funções</option>
                            <option value="free_user" {{ request('role') == 'free_user' ? 'selected' : '' }}>Free User</option>
                            <option value="pro_user" {{ request('role') == 'pro_user' ? 'selected' : '' }}>Pro User</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="support" {{ request('role') == 'support' ? 'selected' : '' }}>Suporte</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400 shrink-0" />
                        </span>
                    </div>
                    <div class="relative flex-1 min-w-[200px]">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4 text-slate-400 shrink-0" />
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou e-mail..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] placeholder-slate-400">
                    </div>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#11C76F] text-white text-sm font-bold hover:bg-[#0EA85A] transition-colors inline-flex items-center justify-center gap-2 shrink-0">
                        <x-icon name="magnifying-glass" style="duotone" class="w-4 h-4 shrink-0" />
                        Filtrar
                    </button>
                </form>
            </x-slot>

            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Usuário</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Função</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Plano</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Assinatura</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Renda decl.</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Cadastro</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($users as $user)
                    @php
                        $plan = $user->plan ?? \Modules\Core\Models\Plan::getDefaultFree();
                        $hasActiveSub = isset($activeSubscriptionByUser[$user->id]);
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5 align-middle">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($user->photo)
                                    <img src="{{ $user->photo_url }}" alt="" class="h-10 w-10 rounded-xl object-cover shrink-0 border border-slate-200 dark:border-slate-600">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center shrink-0">
                                        <x-icon name="user" style="duotone" class="w-5 h-5 shrink-0" />
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle">
                            <div class="flex flex-wrap items-center gap-1.5">
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
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border shrink-0 {{ $color }}">
                                    <x-icon name="{{ $icon }}" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </span>
                            @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-5 align-middle">
                            @if($plan)
                                <span class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $plan->name }}</span>
                                @if($plan->is_free)
                                    <span class="ml-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">(Grátis)</span>
                                @endif
                            @else
                                <span class="text-slate-400 dark:text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 align-middle">
                            @if($hasActiveSub)
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-1 rounded-lg border border-emerald-200 dark:border-emerald-700">
                                    <x-icon name="circle-check" style="duotone" class="w-3.5 h-3.5 shrink-0" />
                                    Ativa
                                </span>
                            @else
                                <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 align-middle">
                            <span class="font-bold text-slate-900 dark:text-white tabular-nums">{{ format_currency($monthlyIncomeByUser[$user->id] ?? 0) }}</span>
                        </td>
                        <td class="px-6 py-5 align-middle text-slate-600 dark:text-slate-400 text-sm">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-5 align-middle text-right">
                            <div class="flex items-center justify-end">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 transition-colors" title="Ver detalhes">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4 shrink-0" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0">
                                    <x-icon name="users" style="duotone" class="w-8 h-8 text-slate-400 shrink-0" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum usuário encontrado.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">Ajuste os filtros de busca.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $users->links() }}
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>

    @push('styles')
    <style>.users-select{appearance:none;-webkit-appearance:none;-moz-appearance:none;background-image:none}</style>
    @endpush
</x-paneladmin::layouts.master>
