<x-paneladmin::layouts.master>
    <div class="max-w-5xl mx-auto px-4 py-10">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none italic uppercase">
                    Central de Avisos
                </h1>
                <p class="text-sm text-slate-500 font-medium italic">Gerencie e envie notificações inteligentes para toda a plataforma.</p>
            </div>

            <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center gap-3 px-6 py-4 bg-[#11C76F] text-white font-black rounded-2xl shadow-lg shadow-[#11C76F]/20 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-[10px] uppercase tracking-[0.2em]">
                <x-icon name="plus" class="text-lg" />
                Nova Notificação
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-[2rem] flex items-center gap-4 text-emerald-600 dark:text-emerald-400">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <x-icon name="check" class="text-xl" />
                </div>
                <p class="font-black uppercase tracking-widest text-xs">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Stats Grid (Aesthetic) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full blur-3xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Total de Disparos</span>
                    <div class="flex items-end gap-3">
                        <span class="text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tight italic">{{ $recentNotifications->sum('count') }}</span>
                        <x-icon name="paper-plane" class="text-xl text-blue-500 mb-1" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Alcance Médio</span>
                    <div class="flex items-end gap-3">
                        <span class="text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tight italic">98%</span>
                        <x-icon name="users" class="text-xl text-emerald-500 mb-1" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full blur-3xl -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10 flex flex-col">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Taxa de Leitura</span>
                    <div class="flex items-end gap-3">
                        <span class="text-4xl font-black text-slate-900 dark:text-white leading-none tracking-tight italic">74%</span>
                        <x-icon name="eye" class="text-xl text-amber-500 mb-1" />
                    </div>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden">
            <div class="p-10 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.01]">
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight italic">Histórico de Disparos</h3>
                <p class="text-xs text-slate-400 font-black uppercase tracking-widest mt-1">Últimas 10 notificações enviadas</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-gray-50/30 dark:bg-transparent">
                            <th class="px-10 py-6">Notificação</th>
                            <th class="px-10 py-6">Tipo / Ícone</th>
                            <th class="px-10 py-6">Alcance</th>
                            <th class="px-10 py-6">Data de Envio</th>
                            <th class="px-10 py-6 text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @forelse($recentNotifications as $notification)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                <td class="px-10 py-8">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-slate-900 dark:text-white tracking-tight">{{ $notification->data->title }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-1 max-w-md italic">{{ $notification->data->message }}</p>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center
                                            {{ match($notification->data->type ?? 'info') {
                                                'success' => 'bg-emerald-500/10 text-emerald-500',
                                                'warning' => 'bg-amber-500/10 text-amber-500',
                                                'danger' => 'bg-red-500/10 text-red-500',
                                                default => 'bg-blue-500/10 text-blue-500'
                                            } }}">
                                            <x-icon :name="$notification->data->icon ?? 'bell'" class="text-lg" />
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                            {{ match($notification->data->type ?? 'info') {
                                                'success' => 'Sucesso',
                                                'warning' => 'Atenção',
                                                'danger' => 'Crítico',
                                                default => 'Informativo'
                                            } }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-2">
                                        <div class="px-3 py-1 bg-gray-100 dark:bg-white/5 rounded-full text-[10px] font-black text-slate-600 dark:text-slate-300 uppercase tracking-widest border border-gray-100 dark:border-white/5">
                                            {{ $notification->count }} Usuários
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic leading-none">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y - H:i') }}
                                    </span>
                                </td>
                                <td class="px-10 py-8 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.notifications.show', $notification->id) }}" class="p-3 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-[#11C76F] hover:bg-[#11C76F]/10 transition-all active:scale-90" title="Ver Detalhes">
                                            <x-icon name="eye" class="text-lg" />
                                        </a>
                                        <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="p-3 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-blue-500 hover:bg-blue-500/10 transition-all active:scale-90" title="Usar como Template">
                                            <x-icon name="copy" class="text-lg" />
                                        </a>
                                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Apagar este histórico?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-3 bg-gray-50 dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition-all active:scale-90" title="Excluir">
                                                <x-icon name="trash" class="text-lg" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 rounded-[2rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-slate-300">
                                            <x-icon name="bullhorn" class="text-4xl" />
                                        </div>
                                        <div>
                                            <p class="text-base font-black text-slate-900 dark:text-white uppercase tracking-widest">Nenhuma notificação enviada</p>
                                            <p class="text-xs text-slate-400 font-medium italic mt-1">Comece criando sua primeira mensagem para os usuários.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-10 bg-gray-50/50 dark:bg-white/[0.01] border-t border-gray-100 dark:border-white/5 flex items-center justify-between">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Mostrando últimos registros</span>
                <div class="flex gap-2">
                    <button class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-white/5 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all shadow-sm">
                        <x-icon name="chevron-left" class="text-xs" />
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-white/5 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all shadow-sm">
                        <x-icon name="chevron-right" class="text-xs" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
