<div x-data="notificationSystem()" x-init="init()" class="relative">
    <!-- Bell Icon -->
    <button @click.stop="toggleDropdown()" class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-slate-800 transition-colors focus:outline-none">
        <x-icon name="bell" style="duotone" class="w-6 h-6" />

        <!-- Badge -->
        <span x-show="count > 0"
              x-transition.scale
              class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-900"
              x-text="count > 9 ? '9+' : count">
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="isOpen"
         @click.away="isOpen = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute right-0 mt-2 w-80 md:w-96 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden origin-top-right">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white">Notificações</h3>
            <button @click="markAllRead()" x-show="count > 0" class="text-xs text-primary hover:text-primary-dark font-medium transition-colors">
                Marcar todas como lidas
            </button>
        </div>

        <!-- List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="loading && notifications.length === 0">
                <div class="p-4 text-center text-gray-400 text-sm">
                    <x-icon name="spinner" class="w-5 h-5 animate-spin mx-auto mb-2" />
                    Carregando...
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center text-gray-400">
                    <x-icon name="bell-slash" class="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p class="text-sm">Nenhuma nuva notificação.</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group relative">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <i class="fa-duotone w-5 h-5" :class="['fa-' + notification.icon, notification.color]" aria-hidden="true"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white" x-text="notification.title"></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2" x-text="notification.message"></p>
                            <p class="text-xs text-gray-400 mt-1" x-text="notification.created_at_human"></p>

                            <template x-if="notification.action_url">
                                <a :href="notification.action_url" class="mt-2 inline-block text-xs font-bold text-primary hover:underline">
                                    Ver Detalhes &rarr;
                                </a>
                            </template>
                        </div>

                        <!-- Mark as Read Button (Show on Hover) -->
                        <button @click.stop="markAsRead(notification.id)" class="absolute top-2 right-2 text-gray-300 hover:text-primary opacity-0 group-hover:opacity-100 transition-opacity" title="Marcar como lida">
                            <x-icon name="check" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-2 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-slate-700/50 text-center">
            @php
                $user = auth()->user();
                $viewAllRoute = 'user.notifications.index';

                if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
                    $viewAllRoute = 'admin.notifications.index';
                } elseif ($user->hasRole('suporte')) {
                    $viewAllRoute = 'support.notifications.index';
                }
            @endphp
            <a href="{{ route($viewAllRoute) }}" class="text-xs font-bold text-primary hover:text-primary-dark transition-colors uppercase tracking-widest px-4 py-2 block">
                Ver Central de Notificações
            </a>
        </div>
    </div>
</div>

<script>
    function notificationSystem() {
        return {
            isOpen: false,
            count: 0,
            notifications: [],
            loading: false,

            init() {
                this.fetchNotifications();
                // Polling every 15 seconds
                setInterval(() => {
                    this.fetchNotifications(true); // silent update
                }, 15000);
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.fetchNotifications();
                }
            },

            fetchNotifications(silent = false) {
                if (!silent) this.loading = true;

                fetch('/notifications/unread')
                    .then(response => response.json())
                    .then(data => {
                        // Check for new notifications
                        if (silent && data.notifications.length > 0) {
                            let currentLatestId = this.notifications.length > 0 ? this.notifications[0].id : null;
                            let newLatestId = data.notifications[0].id;

                            if (newLatestId !== currentLatestId) {
                                // Dispatch event for Toast
                                window.dispatchEvent(new CustomEvent('new-notification', { detail: data.notifications[0] }));

                                // Play sound (optional, kept simple for now)
                            }
                        }

                        this.count = data.count;
                        this.notifications = data.notifications;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Error fetching notifications:', error);
                        this.loading = false;
                    });
            },

            markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from list locally
                        this.notifications = this.notifications.filter(n => n.id !== id);
                        this.count = Math.max(0, this.count - 1);
                    }
                });
            },

            markAllRead() {
                fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.notifications = [];
                        this.count = 0;
                        this.isOpen = false;
                    }
                });
            }
        }
    }
</script>
