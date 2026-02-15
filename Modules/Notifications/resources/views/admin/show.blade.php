<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Detalhes do Disparo</x-slot>

    <x-paneladmin::page title="Detalhes do Disparo" subtitle="Análise e alcance da notificação enviada.">
        <x-slot name="header">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
                </a>
                <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 hover:border-[#11C76F] hover:text-[#11C76F] font-medium text-sm transition-colors">
                    <x-icon name="copy" style="duotone" class="w-4 h-4" /> Usar como Template
                </a>
                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Remover este histórico?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2.5 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-colors">
                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <x-paneladmin::card>
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center
                                    {{ match($data->type ?? 'info') {
                                        'success' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                        'warning' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                        'danger' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                                        default => 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
                                    } }}">
                                    <x-icon :name="$data->icon ?? 'bell'" style="duotone" class="w-7 h-7" />
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $data->title ?? '—' }}</h2>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Enviado em {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y \à\s H:i') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase
                                {{ match($data->type ?? 'info') {
                                    'success' => 'bg-emerald-500/20 text-emerald-700 dark:text-emerald-400',
                                    'warning' => 'bg-amber-500/20 text-amber-700 dark:text-amber-400',
                                    'danger' => 'bg-red-500/20 text-red-700 dark:text-red-400',
                                    default => 'bg-blue-500/20 text-blue-700 dark:text-blue-400'
                                } }}">
                                {{ $data->type ?? 'Informativo' }}
                            </span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700">
                            <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ $data->message ?? '—' }}</p>
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card :title="'Destinatários (' . $blast->count() . ')'">
                    <x-paneladmin::table-wrapper>
                        <x-slot name="thead">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">ID Usuário</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Recebimento</th>
                            </tr>
                        </x-slot>
                        @foreach($blast as $notif)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 even:bg-slate-50/50 dark:even:bg-slate-800/30">
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">#{{ $notif->notifiable_id }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $notif->read_at ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                                        <span class="w-2 h-2 rounded-full {{ $notif->read_at ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $notif->read_at ? 'Lida' : 'Entregue' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-slate-500 dark:text-slate-400">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </x-paneladmin::table-wrapper>
                </x-paneladmin::card>
            </div>

            <div class="space-y-6">
                <x-paneladmin::card title="Desempenho">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 flex items-center justify-center">
                                <x-icon name="bolt" style="duotone" class="w-6 h-6 text-[#11C76F]" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">100%</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Taxa de entrega</p>
                            </div>
                        </div>
                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-[#11C76F] rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card title="Metadados">
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Ícone</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $data->icon ?? 'bell' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Transmissão</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">Global</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-500 dark:text-slate-400">ID</span>
                            <span class="text-xs font-mono text-slate-500 dark:text-slate-400">{{ substr(md5($notification->data), 0, 12) }}</span>
                        </div>
                    </div>
                </x-paneladmin::card>
            </div>
        </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
