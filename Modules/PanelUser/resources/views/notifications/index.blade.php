<x-paneluser::layouts.master :title="'Central de Notificações'">
    @php
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(15);
        $unreadCount = $user->unreadNotifications()->count();
        $readCount = $user->notifications()->whereNotNull('read_at')->count();
    @endphp

    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Central de Notificações</li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Central de Notificações</h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">Acompanhe todas as atualizações e alertas da sua conta.</p>
                </div>
                <div class="flex flex-wrap gap-2" x-data="{ clearAllModal: false }">
                    @if($unreadCount > 0)
                        <form id="readAllForm" action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95">
                                <x-icon name="check-double" style="solid" class="w-5 h-5" />
                                Marcar Todas como Lidas
                            </button>
                        </form>
                    @endif
                    @if($readCount > 0)
                        <form action="{{ route('notifications.clear-read') }}" method="POST" class="inline" onsubmit="return confirm('Remover {{ $readCount }} notificação(ões) lida(s)?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition-all">
                                <x-icon name="broom" style="solid" class="w-5 h-5" />
                                Limpar Lidas
                            </button>
                        </form>
                    @endif
                    @if($notifications->total() > 0)
                        <button @click="clearAllModal = true" type="button" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 rounded-xl font-bold text-sm hover:bg-rose-200 dark:hover:bg-rose-900/50 transition-all">
                            <x-icon name="trash" style="solid" class="w-5 h-5" />
                            Limpar Todas
                        </button>
                        <div x-show="clearAllModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @keydown.escape.window="clearAllModal = false">
                            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-md w-full p-6" @click.away="clearAllModal = false">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Limpar todas as notificações?</h3>
                                <p class="text-gray-600 dark:text-slate-300 text-sm mb-6">Esta ação não pode ser desfeita. Todas as {{ $notifications->total() }} notificação(ões) serão removidas.</p>
                                <div class="flex gap-3">
                                    <form action="{{ route('notifications.clear-all') }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white transition-colors">Sim, remover todas</button>
                                    </form>
                                    <button @click="clearAllModal = false" type="button" class="flex-1 py-2.5 rounded-xl font-bold text-sm bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stats Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center bg-primary/10 rounded-xl">
                        <x-icon name="bell" style="solid" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $notifications->total() }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 shrink-0 flex items-center justify-center {{ $unreadCount > 0 ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-slate-100 dark:bg-slate-800' }} rounded-xl">
                        <x-icon name="envelope" style="solid" class="{{ $unreadCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400' }} w-6 h-6" />
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Não Lidas</p>
                        <p class="text-xl font-bold {{ $unreadCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-white' }}">{{ $unreadCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Notifications List Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden transition-all hover:shadow-md">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2.5 bg-primary/10 rounded-xl">
                        <x-icon name="bell" style="solid" class="text-primary w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Suas Notificações</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Alertas e atualizações em tempo real</p>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($notifications as $notification)
                        @php
                            $type = $notification->data['type'] ?? 'info';
                            $icon = $notification->data['icon'] ?? 'bell';
                            $iconClass = match($type) {
                                'success' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                                'warning' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                                'danger' => 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400',
                                'pro' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
                                default => 'bg-primary/10 text-primary'
                            };
                        @endphp
                        <div class="p-6 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors {{ $notification->read_at ? 'opacity-80' : 'bg-primary/5 dark:bg-primary/5' }} group relative">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 shrink-0 flex items-center justify-center rounded-xl {{ $iconClass }}">
                                    <x-icon :name="$icon" style="solid" class="w-5 h-5" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                        <h4 class="text-base font-bold text-gray-900 dark:text-white">
                                            {{ $notification->data['title'] ?? 'Notificação' }}
                                        </h4>
                                        <span class="text-xs text-gray-500 dark:text-slate-400 whitespace-nowrap">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 dark:text-slate-300 mt-1 text-sm leading-relaxed">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    @if(isset($notification->data['action_url']) && $notification->data['action_url'])
                                        <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center gap-2 mt-3 text-sm font-bold text-primary hover:underline">
                                            Ver Detalhes
                                            <x-icon name="chevron-right" style="solid" class="w-4 h-4" />
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
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                <x-icon name="bell-slash" style="solid" class="w-10 h-10 text-slate-400 dark:text-slate-500" />
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sem notificações</h3>
                            <p class="text-gray-500 dark:text-slate-400 text-sm max-w-sm mx-auto">Você não tem nenhuma notificação no momento. Novos alertas aparecerão aqui.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
