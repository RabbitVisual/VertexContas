<div x-data="{
        show: false,
        notification: null,
        timeout: null,
        init() {
            window.addEventListener('new-notification', (event) => {
                this.notification = event.detail;
                this.show = true;

                // Play a subtle sound
                const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                audio.volume = 0.5;
                audio.play().catch(e => console.log('Audio play failed', e));

                // Auto hide after 5 seconds
                if (this.timeout) clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.show = false;
                }, 5000);
            });
        }
    }"
    class="fixed bottom-4 right-4 z-[99999]"
    x-cloak>

    <div x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="max-w-md w-full pointer-events-auto">

        <div class="rounded-xl border p-4 shadow-xl pointer-events-auto backdrop-blur-sm"
            :class="{
                'bg-blue-50/95 border-blue-200 shadow-blue-500/10': notification && notification.type === 'info',
                'bg-emerald-50/95 border-emerald-200 shadow-emerald-500/10': notification && notification.type === 'success',
                'bg-amber-50/95 border-amber-200 shadow-amber-500/10': notification && notification.type === 'warning',
                'bg-rose-50/95 border-rose-200 shadow-rose-500/10': notification && notification.type === 'danger',
                'bg-violet-50/95 border-violet-200 shadow-violet-500/10': notification && notification.type === 'pro'
            }">

            <div class="flex items-start gap-4">
                <!-- Dynamic Icon -->
                <div class="flex-shrink-0 mt-0.5">
                    <template x-if="notification">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             :class="{
                                'bg-blue-100 text-blue-600': notification.type === 'info',
                                'bg-emerald-100 text-emerald-600': notification.type === 'success',
                                'bg-amber-100 text-amber-600': notification.type === 'warning',
                                'bg-rose-100 text-rose-600': notification.type === 'danger',
                                'bg-violet-100 text-violet-600': notification.type === 'pro'
                             }">
                            <x-icon ::name="notification.icon" style="solid" class="w-4 h-4" />
                        </div>
                    </template>
                </div>

                <div class="flex-1 min-w-0">
                    <strong class="block leading-tight font-bold text-sm mb-1"
                        :class="{
                            'text-blue-900': notification && notification.type === 'info',
                            'text-emerald-900': notification && notification.type === 'success',
                            'text-amber-900': notification && notification.type === 'warning',
                            'text-rose-900': notification && notification.type === 'danger',
                            'text-violet-900': notification && notification.type === 'pro'
                        }"
                        x-text="notification ? notification.title : ''">
                    </strong>

                    <p class="text-xs leading-relaxed font-medium"
                        :class="{
                            'text-blue-700': notification && notification.type === 'info',
                            'text-emerald-700': notification && notification.type === 'success',
                            'text-amber-700': notification && notification.type === 'warning',
                            'text-rose-700': notification && notification.type === 'danger',
                            'text-violet-700': notification && notification.type === 'pro'
                        }"
                        x-text="notification ? notification.message : ''">
                    </p>

                    <!-- Optional Action Button -->
                    <template x-if="notification && notification.action_url">
                         <a :href="notification.action_url"
                            class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg border transition-all active:scale-95"
                            :class="{
                                'bg-white border-blue-200 text-blue-700 hover:bg-blue-100 hover:border-blue-300': notification && notification.type === 'info',
                                'bg-white border-emerald-200 text-emerald-700 hover:bg-emerald-100 hover:border-emerald-300': notification && notification.type === 'success',
                                'bg-white border-amber-200 text-amber-700 hover:bg-amber-100 hover:border-amber-300': notification && notification.type === 'warning',
                                'bg-white border-rose-200 text-rose-700 hover:bg-rose-100 hover:border-rose-300': notification && notification.type === 'danger',
                                'bg-white border-violet-200 text-violet-700 hover:bg-violet-100 hover:border-violet-300': notification && notification.type === 'pro'
                            }">
                             Ver Detalhes <x-icon name="arrow-right" class="ml-1 w-3 h-3" />
                         </a>
                    </template>
                </div>

                <!-- Close Button -->
                <div class="flex-shrink-0 ml-2">
                    <button @click="show = false" class="rounded-lg p-1.5 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-offset-transparent focus:ring-current opacity-60 hover:opacity-100"
                        :class="{
                            'text-blue-500 hover:bg-blue-100': notification && notification.type === 'info',
                            'text-emerald-500 hover:bg-emerald-100': notification && notification.type === 'success',
                            'text-amber-500 hover:bg-amber-100': notification && notification.type === 'warning',
                            'text-rose-500 hover:bg-rose-100': notification && notification.type === 'danger',
                            'text-violet-500 hover:bg-violet-100': notification && notification.type === 'pro'
                        }">
                        <span class="sr-only">Fechar</span>
                        <x-icon name="xmark" class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
