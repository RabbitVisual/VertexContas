<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Permissões</x-slot>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Matriz de Permissões</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Defina o que cada papel pode fazer no sistema.</p>
            </div>
            <div class="text-sm text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-lg">
                <x-icon name="shield-check" style="duotone" class="w-4 h-4 inline mr-1" />
                Admin tem acesso total (Oculto)
            </div>
        </div>

        <form action="{{ route('admin.roles.update') }}" method="POST">
            @csrf

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-semibold text-gray-500 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-4">Permissão</th>
                            @foreach($roles as $role)
                                <th class="px-6 py-4 text-center min-w-[120px]">
                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $permission->name }}
                                </td>
                                @foreach($roles as $role)
                                    <td class="px-6 py-4 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   name="permissions[{{ $role->id }}][]"
                                                   value="{{ $permission->name }}"
                                                   class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 dark:bg-slate-800 dark:border-gray-600"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors font-medium flex items-center">
                    <x-icon name="save" style="duotone" class="w-4 h-4 mr-2" />
                    Salvar Permissões
                </button>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
