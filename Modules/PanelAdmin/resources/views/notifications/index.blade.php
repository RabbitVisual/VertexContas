@section('title', 'Central de Notificações - Admin')

<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notificações do Sistema</h2>
                <p class="text-gray-500 dark:text-gray-400">Alertas de segurança, logs de auditoria e atualizações administrativas.</p>
            </div>

             <form id="readAllForm" action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center shadow-sm">
                    <x-icon name="check-double" class="w-4 h-4 mr-2" />
                    Limpar Todas as Notificações
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            @php
                $notifications = auth()->user()->notifications()->paginate(15);
            @endphp

            @forelse($notifications as $notification)
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors {{ $notification->read_at ? 'opacity-70' : 'bg-slate-50 dark:bg-slate-900/40 border-l-4 border-l-slate-800 dark:border-l-primary' }}">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 mt-1">
                            @php
                                $type = $notification->data['type'] ?? 'info';
                                $icon = $notification->data['icon'] ?? 'bell';

                                $iconClass = match($type) {
                                    'danger' => 'bg-red-500/10 text-red-500',
                                    'warning' => 'bg-amber-500/10 text-amber-500',
                                    'success' => 'bg-emerald-500/10 text-emerald-500',
                                    default => 'bg-slate-500/10 text-slate-500'
                                };
                            @endphp
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center {{ $iconClass }}">
                                <x-icon :name="$icon" class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                    {{ $notification->data['title'] ?? 'Notificação de Sistema' }}
                                </h3>
                                <span class="text-xs font-medium text-gray-400 whitespace-nowrap ml-2">
                                    {{ $notification->created_at->toFormattedDateString() }}
                                </span>
                            </div>

                            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm leading-relaxed">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            @if(isset($notification->data['action_url']) && $notification->data['action_url'])
                                <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center mt-3 text-xs font-bold text-primary hover:text-primary-dark transition-colors">
                                    Acessar Recurso <x-icon name="arrow-right" class="ml-1 w-3 h-3" />
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-20 text-center">
                    <x-icon name="cloud-check" style="duotone" class="w-16 h-16 text-slate-200 dark:text-slate-800 mx-auto mb-4" />
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Tudo em ordem!</h3>
                    <p class="text-gray-500 text-sm">Nenhum alerta administrativo pendente para sua conta.</p>
                </div>
            @endforelse

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-800/50">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
