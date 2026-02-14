@section('title', 'Gerenciamento de Usuários')

<x-paneladmin::layouts.master>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

        <!-- Header & Filters -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Usuários do Sistema</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gerencie contas, planos e permissões.</p>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                <select name="role" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-primary focus:border-primary">
                    <option value="">Todas as Roles</option>
                    <option value="free_user" {{ request('role') == 'free_user' ? 'selected' : '' }}>Free User</option>
                    <option value="pro_user" {{ request('role') == 'pro_user' ? 'selected' : '' }}>Pro User</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome ou E-mail..." class="pl-3 pr-10 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white text-sm focus:ring-primary focus:border-primary">
                    <button type="submit" class="absolute right-0 top-0 h-full px-3 text-gray-500 hover:text-primary">
                        <x-icon name="magnifying-glass" class="w-4 h-4" />
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-slate-700/50 uppercase text-xs font-semibold text-gray-500 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Usuário</th>
                        <th class="px-6 py-4">Plano/Role</th>
                        <th class="px-6 py-4">Renda Decl.</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Cadastro</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    @php
                                        $color = match($role->name) {
                                            'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'pro_user' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'free_user' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'support' => 'bg-green-100 text-green-700 border-green-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                        };
                                        $icon = match($role->name) {
                                            'admin' => 'shield-keyhole',
                                            'pro_user' => 'crown',
                                            'free_user' => 'user',
                                            'support' => 'headset',
                                            default => 'user'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }} dark:bg-opacity-20">
                                        <x-icon name="{{ $icon }}" class="w-3 h-3 mr-1" />
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 dark:text-white tabular-nums">R$ {{ number_format($monthlyIncomeByUser[$user->id] ?? 0, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Ativo
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-gray-400 hover:text-primary transition-colors inline-block" title="Ver Detalhes">
                                    <x-icon name="eye" class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</x-paneladmin::layouts.master>
