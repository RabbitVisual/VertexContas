<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Central de Avisos</x-slot>

    <x-paneladmin::page title="Central de Avisos" subtitle="Gerencie e envie notificações para toda a plataforma.">
        <x-slot name="header">
            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                <x-icon name="plus" style="duotone" class="w-4 h-4" />
                Nova Notificação
            </a>
        </x-slot>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center">
                    <x-icon name="check" style="duotone" class="w-5 h-5" />
                </div>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Total de Disparos</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $recentNotifications->sum('count') }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                        <x-icon name="paper-plane" style="duotone" class="w-6 h-6 text-blue-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Alcance Médio</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">98%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                        <x-icon name="users" style="duotone" class="w-6 h-6 text-emerald-500" />
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Taxa de Leitura</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">74%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center">
                        <x-icon name="eye" style="duotone" class="w-6 h-6 text-amber-500" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico -->
        <x-paneladmin::card title="Histórico de Disparos" subtitle="Últimas notificações enviadas.">
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notificação</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Tipo</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Alcance</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Data</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($recentNotifications as $notification)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $notification->data->title ?? '—' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 max-w-md">{{ $notification->data->message ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center
                                    {{ match($notification->data->type ?? 'info') {
                                        'success' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                        'warning' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                        'danger' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                                        default => 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                    } }}">
                                    <x-icon :name="$notification->data->icon ?? 'bell'" style="duotone" class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-medium text-slate-600 dark:text-slate-400">
                                    {{ match($notification->data->type ?? 'info') {
                                        'success' => 'Sucesso',
                                        'warning' => 'Atenção',
                                        'danger' => 'Crítico',
                                        default => 'Informativo'
                                    } }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $notification->count }} usuários
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm">
                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.notifications.show', $notification->id) }}" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 flex items-center justify-center transition-colors" title="Ver Detalhes">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-blue-500 hover:bg-blue-500/10 flex items-center justify-center transition-colors" title="Usar como Template">
                                    <x-icon name="copy" style="duotone" class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Apagar este histórico?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:text-red-500 hover:bg-red-500/10 flex items-center justify-center transition-colors" title="Excluir">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="bullhorn" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="font-medium text-slate-900 dark:text-white">Nenhuma notificação enviada</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Crie sua primeira notificação para os usuários.</p>
                                <a href="{{ route('admin.notifications.create') }}" class="text-sm font-bold text-[#11C76F] hover:underline">Nova Notificação</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>

            @if($recentNotifications->isNotEmpty())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">
                    Mostrando últimos {{ $recentNotifications->count() }} registros
                </div>
            @endif
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
