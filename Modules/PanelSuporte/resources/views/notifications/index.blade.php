@section('title', 'Central de Notificações - Suporte')

<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Central de Notificações</h2>
                <p class="text-gray-500 dark:text-gray-400">Acompanhe seus alertas técnicos e atualizações do sistema.</p>
            </div>

             <form id="readAllForm" action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center shadow-sm">
                    <x-icon name="check-double" class="w-4 h-4 mr-2" />
                    Marcar Todas como Lidas
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            @php
                $notifications = auth()->user()->notifications()->paginate(15);
            @endphp

            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-primary/5 dark:bg-primary/10 border-l-4 border-l-primary' }}">
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
                                <x-icon :name="$icon" class="w-5 h-5" />
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
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400">
                    <div class="w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-900 flex items-center justify-center mx-auto mb-4 border border-gray-100 dark:border-gray-800">
                        <x-icon name="bell-slash" class="w-8 h-8 opacity-50" />
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sem atualizações</h3>
                    <p class="text-sm">Você está em dia com todas as suas notificações técnicos.</p>
                </div>
            @endforelse

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/50">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
