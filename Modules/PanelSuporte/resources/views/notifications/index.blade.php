<x-panelsuporte::layouts.master title="Central de Notificações - Suporte">
    <div class="space-y-8 animate-in fade-in duration-500 max-w-4xl mx-auto">
        @php
            $user = auth()->user();
            $notifications = $user->notifications()->paginate(15);
            $readCount = $user->notifications()->whereNotNull('read_at')->count();
        @endphp

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Central de Notificações</h2>
                <p class="text-gray-500 dark:text-gray-400">Acompanhe seus alertas técnicos e atualizações do sistema.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <form id="readAllForm" action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors shadow-sm">
                        <x-icon name="check-double" style="duotone" class="w-4 h-4" />
                        Marcar Todas como Lidas
                    </button>
                </form>
                @if($readCount > 0)
                    <form action="{{ route('notifications.clear-read') }}" method="POST" class="inline" onsubmit="return confirm('Remover {{ $readCount }} notificação(ões) lida(s)?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">
                            <x-icon name="broom" style="duotone" class="w-4 h-4" />
                            Limpar Lidas
                        </button>
                    </form>
                @endif
                @if($notifications->total() > 0)
                    <form action="{{ route('notifications.clear-all') }}" method="POST" class="inline" onsubmit="return confirm('Remover todas as {{ $notifications->total() }} notificação(ões)?');">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 rounded-xl text-sm font-bold hover:bg-rose-200 dark:hover:bg-rose-900/50 transition-colors">
                            <x-icon name="trash" style="duotone" class="w-4 h-4" />
                            Limpar Todas
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">

            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-primary/5 dark:bg-primary/10 border-l-4 border-l-primary' }} group relative">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'info';
                                $icon = $notification->data['icon'] ?? 'bell';
                                $color = $notification->data['color'] ?? 'text-blue-500';

                                // UI Map
                                $iconClass = match($type) {
                                    'success' => 'text-emerald-500 bg-emerald-50 dark:bg-emerald-900/20',
                                    'warning' => 'text-amber-500 bg-amber-50 dark:bg-amber-900/20',
                                    'danger' => 'text-red-500 bg-red-50 dark:bg-red-900/20',
                                    'info' => 'text-primary bg-primary/10 dark:bg-primary/20',
                                    default => 'text-blue-500 bg-blue-50 dark:bg-blue-900/20'
                                };
                            @endphp
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center {{ $iconClass }}">
                                <x-icon :name="$icon" style="duotone" class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Notificação' }}
                                </h3>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 whitespace-nowrap ml-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm leading-relaxed">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            @if(isset($notification->data['action_url']) && $notification->data['action_url'])
                                <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center mt-3 text-xs font-black uppercase tracking-widest text-primary hover:underline">
                                    Ver Detalhes &rarr;
                                </a>
                            @endif
                        </div>
                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                            @csrf
                            <button type="submit" class="w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors" title="Remover notificação">
                                <x-icon name="trash" style="solid" class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4 border border-gray-100 dark:border-gray-800">
                        <x-icon name="bell-slash" style="duotone" class="w-8 h-8 opacity-50" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sem atualizações</h3>
                    <p class="text-sm">Você está em dia com todas as suas notificações técnicos.</p>
                </div>
            @endforelse

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/30">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
