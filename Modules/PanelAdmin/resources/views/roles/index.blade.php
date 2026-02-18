<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Permissões</x-slot>

    <x-paneladmin::page title="Matriz de Permissões" subtitle="Defina o que cada função pode fazer no sistema. A função Admin tem acesso total e não é editável aqui.">
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

        {{-- Resumo: funções editáveis e aviso Admin --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach($roles as $role)
                @php
                    $roleStyle = match($role->name) {
                        'free_user' => 'bg-blue-100 dark:bg-blue-900/30 border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-400',
                        'pro_user' => 'bg-amber-100 dark:bg-amber-900/30 border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400',
                        'support' => 'bg-emerald-100 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400',
                        default => 'bg-slate-100 dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300'
                    };
                    $icon = match($role->name) {
                        'pro_user' => 'crown',
                        'support' => 'headset',
                        default => 'user'
                    };
                    $userCount = $userCountByRole[$role->id] ?? 0;
                @endphp
                <div class="rounded-xl border p-4 {{ $roleStyle }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white/50 dark:bg-black/20 flex items-center justify-center">
                            <x-icon name="{{ $icon }}" style="duotone" class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="font-bold">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                            <div class="text-xs opacity-90">{{ $userCount }} {{ $userCount === 1 ? 'usuário' : 'usuários' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50 p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <x-icon name="shield-keyhole" style="duotone" class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <div class="font-bold text-slate-900 dark:text-white">Admin</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Acesso total · não editável</div>
                </div>
            </div>
        </div>

        <x-paneladmin::card>
            <x-slot name="header">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400 text-sm">
                        <x-icon name="key" style="duotone" class="w-4 h-4" />
                        <span><strong>{{ $permissions->count() }}</strong> permissões · marque as caixas para conceder à função</span>
                    </div>
                </div>
            </x-slot>

            <form action="{{ route('admin.roles.update') }}" method="POST">
                @csrf

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                        <thead class="bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left min-w-[220px]">Permissão</th>
                                @foreach($roles as $role)
                                    @php $count = $userCountByRole[$role->id] ?? 0; @endphp
                                    <th class="px-6 py-4 text-center min-w-[120px]">
                                        <div>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                                        <div class="text-[9px] font-normal normal-case text-slate-400 dark:text-slate-500 mt-0.5">{{ $count }} {{ $count === 1 ? 'usuário' : 'usuários' }}</div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($permissions as $permission)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                    <td class="px-6 py-3 font-medium text-slate-900 dark:text-white">
                                        <span class="inline-flex items-center gap-2">
                                            <x-icon name="key" style="duotone" class="w-3.5 h-3.5 text-slate-400" />
                                            {{ $permission->name }}
                                        </span>
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="px-6 py-3 text-center">
                                            <label class="inline-flex items-center justify-center cursor-pointer">
                                                <input type="checkbox"
                                                    name="permissions[{{ $role->id }}][]"
                                                    value="{{ $permission->name }}"
                                                    class="rounded border-slate-200 dark:border-slate-600 text-[#11C76F] focus:ring-2 focus:ring-[#11C76F]/20 dark:bg-slate-700 w-4 h-4"
                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                <x-icon name="key" style="duotone" class="w-7 h-7 text-slate-400" />
                                            </div>
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma permissão cadastrada.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($permissions->isNotEmpty())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#11C76F] text-white rounded-xl hover:bg-[#0EA85A] transition-colors font-bold text-sm">
                            <x-icon name="floppy-disk" style="duotone" class="w-4 h-4" />
                            Salvar permissões
                        </button>
                    </div>
                @endif
            </form>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
