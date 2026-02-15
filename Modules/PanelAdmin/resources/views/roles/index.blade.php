<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Permissões</x-slot>

    <x-paneladmin::page title="Matriz de Permissões" subtitle="Defina o que cada papel pode fazer no sistema.">
        <x-paneladmin::card>
            <x-slot name="header">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-lg font-medium">
                        <x-icon name="shield-check" style="duotone" class="w-4 h-4 inline mr-1" />
                        Admin tem acesso total (Oculto)
                    </span>
                </div>
            </x-slot>

        <form action="{{ route('admin.roles.update') }}" method="POST">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="sticky top-0 z-10 bg-slate-50 dark:bg-slate-800/95 backdrop-blur border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Permissão</th>
                            @foreach($roles as $role)
                                <th class="px-6 py-4 text-center min-w-[120px] text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ $permission->name }}
                                </td>
                                @foreach($roles as $role)
                                    <td class="px-6 py-4 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="permissions[{{ $role->id }}][]"
                                                   value="{{ $permission->name }}"
                                                   class="rounded border-slate-200 dark:border-slate-600 text-[#11C76F] focus:ring-2 focus:ring-[#11C76F]/20 dark:bg-slate-700"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-[#11C76F] text-white rounded-xl hover:bg-[#0EA85A] transition-colors font-bold flex items-center">
                    <x-icon name="save" style="duotone" class="w-4 h-4 mr-2" />
                    Salvar Permissões
                </button>
            </div>
        </form>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
