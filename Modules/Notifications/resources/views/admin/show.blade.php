<x-paneladmin::layouts.master>
    <div class="max-w-5xl mx-auto px-4 py-10">

        <!-- Header Section -->
        <div class="mb-10 flex items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.notifications.index') }}" class="text-[10px] font-black text-[#11C76F] uppercase tracking-[0.3em] hover:opacity-70 transition-opacity flex items-center gap-2">
                        <x-icon name="arrow-left" />
                        Voltar ao Histórico
                    </a>
                </div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none italic uppercase">
                    Detalhes do Disparo
                </h1>
                <p class="text-sm text-slate-500 font-medium italic">Análise técnica e alcance da notificação enviada.</p>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="inline-flex items-center gap-3 px-6 py-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-white/5 text-slate-600 dark:text-slate-300 font-black rounded-xl hover:border-[#11C76F] hover:text-[#11C76F] transition-all text-[10px] uppercase tracking-widest">
                    <x-icon name="copy" />
                    Usar como Template
                </a>
                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este histórico?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-3 bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                        <x-icon name="trash" />
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Details Card -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl">
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 rounded-[2rem] flex items-center justify-center
                                {{ match($data->type ?? 'info') {
                                    'success' => 'bg-emerald-500/10 text-emerald-500',
                                    'warning' => 'bg-amber-500/10 text-amber-500',
                                    'danger' => 'bg-red-500/10 text-red-500',
                                    default => 'bg-blue-500/10 text-blue-500'
                                } }}">
                                <x-icon :name="$data->icon ?? 'bell'" class="text-3xl" />
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight italic">{{ $data->title }}</h2>
                                <p class="text-xs text-slate-400 font-black uppercase tracking-widest">Enviado em {{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y \à\s H:i') }}</p>
                            </div>
                        </div>
                        <div class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest
                            {{ match($data->type ?? 'info') {
                                'success' => 'bg-emerald-500 text-white',
                                'warning' => 'bg-amber-500 text-white',
                                'danger' => 'bg-red-500 text-white',
                                default => 'bg-blue-500 text-white'
                            } }}">
                            {{ $data->type ?? 'Informativo' }}
                        </div>
                    </div>

                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-lg italic bg-gray-50/50 dark:bg-white/[0.01] p-8 rounded-3xl border border-dashed border-gray-200 dark:border-white/10">
                            "{{ $data->message }}"
                        </p>
                    </div>
                </div>

                <!-- Recipient List -->
                <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-white/5 shadow-2xl overflow-hidden">
                    <div class="p-10 border-b border-gray-100 dark:border-white/5">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight italic">Destinatários ({{ $blast->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/30 dark:bg-transparent text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                                    <th class="px-10 py-6">ID Usuário</th>
                                    <th class="px-10 py-6">Status Interno</th>
                                    <th class="px-10 py-6 text-right">Data de Recebimento</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                @foreach($blast as $notif)
                                    <tr>
                                        <td class="px-10 py-6">
                                            <span class="text-sm font-black text-slate-700 dark:text-slate-300">#{{ $notif->notifiable_id }}</span>
                                        </td>
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full {{ $notif->read_at ? 'bg-emerald-500 shadow-lg shadow-emerald-500/50' : 'bg-slate-300' }}"></div>
                                                <span class="text-xs font-black uppercase tracking-widest {{ $notif->read_at ? 'text-emerald-500' : 'text-slate-400' }}">
                                                    {{ $notif->read_at ? 'Lida' : 'Entregue' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <span class="text-xs font-medium text-slate-500 italic">
                                                {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Stats Sidebar -->
            <div class="space-y-8">
                <div class="bg-[#11C76F] p-8 rounded-[2.5rem] shadow-2xl shadow-[#11C76F]/20 text-white relative overflow-hidden">
                    <x-icon name="bolt" class="absolute -right-4 -bottom-4 text-9xl text-white/10" />
                    <div class="relative z-10">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-70">Desempenho</span>
                        <div class="mt-4 mb-8">
                            <span class="text-5xl font-black italic tracking-tighter">100%</span>
                            <p class="text-xs font-black uppercase tracking-widest mt-1">Taxa de Entrega</p>
                        </div>
                        <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                            <div class="bg-white h-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-6">
                    <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-[0.2em]">Metadados</h4>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-50 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ícone</span>
                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{ $data->icon ?? 'bell' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-50 dark:border-white/5">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transmissão Global</span>
                            <span class="text-xs font-black text-slate-700 dark:text-slate-300">Sim</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ID de Identificação</span>
                            <span class="text-[10px] font-mono text-slate-400">{{ substr(md5($notification->data), 0, 12) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
