@php
    $user = auth()->user();
    $userName = $user?->full_name ?? $user?->first_name ?? 'Usuário';
    $userEmail = $user?->email ?? '';
    $photoUrl = $user?->photo_url ?? asset('assets/images/default-avatar.png');
    $hasPhoto = !empty($user?->photo);
@endphp
<nav class="fixed top-0 left-0 right-0 z-50 w-full bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
            {{-- Logo + Mobile Toggle --}}
            <div class="flex items-center gap-2 md:gap-4">
                <button id="drawer-toggle-btn" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center justify-center p-2.5 text-gray-500 rounded-xl hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200 dark:focus:ring-gray-700 md:hidden">
                    <span class="sr-only">Abrir menu lateral</span>
                    <x-icon name="bars" style="solid" class="w-5 h-5" />
                </button>
                <a href="{{ route('paneluser.index') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('storage/logos/logo.svg') }}" class="h-8 dark:hidden transition-transform group-hover:scale-105" alt="Vertex Contas" />
                    <img src="{{ asset('storage/logos/logo-white.svg') }}" class="h-8 hidden dark:block transition-transform group-hover:scale-105" alt="Vertex Contas" />
                    <span class="self-center text-lg font-bold whitespace-nowrap text-gray-900 dark:text-white hidden sm:inline">Vertex Contas</span>
                </a>
            </div>

            {{-- Right: Theme, Notifications, Profile --}}
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Theme Toggle (liga/desliga com ícones sol e lua - Font Awesome local) --}}
                <label for="theme-toggle-checkbox" class="relative block h-8 w-14 rounded-full bg-gray-300 transition-colors cursor-pointer [-webkit-tap-highlight-color:transparent] has-checked:bg-primary-500 dark:has-checked:bg-primary-600" aria-label="Alternar tema claro/escuro">
                    <input type="checkbox" id="theme-toggle-checkbox" class="peer sr-only">
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full bg-white shadow-sm ring-4 ring-white ring-inset transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none"></span>
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full flex items-center justify-center text-amber-500 transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none opacity-100 peer-checked:opacity-0 peer-checked:invisible">
                        <x-icon name="sun" style="solid" class="w-3.5 h-3.5" />
                    </span>
                    <span class="absolute inset-y-0 start-0 m-1 size-6 rounded-full flex items-center justify-center text-indigo-200 transition-all duration-200 ease-out peer-checked:start-7 pointer-events-none opacity-0 invisible peer-checked:opacity-100 peer-checked:visible">
                        <x-icon name="moon" style="solid" class="w-3.5 h-3.5" />
                    </span>
                </label>

                {{-- Notifications Bell (full component with dropdown) --}}
                <div class="relative">
                    <x-notifications::bell />
                </div>

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 transition-all"
                        aria-expanded="false">
                        <span class="sr-only">Abrir menu do usuário</span>
                        <div class="relative w-9 h-9 rounded-full overflow-hidden ring-2 ring-gray-200 dark:ring-gray-600 shrink-0">
                            @if($hasPhoto)
                                <img src="{{ $photoUrl }}" alt="{{ $userName }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-primary-600 text-white text-sm font-bold">
                                    {{ strtoupper(mb_substr($userName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="hidden lg:flex flex-col items-start text-left">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">{{ $userName }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[140px]">{{ $userEmail }}</span>
                        </div>
                        <span class="hidden sm:block transition-transform duration-200" :class="{ 'rotate-180': open }"><x-icon name="chevron-down" style="solid" class="w-4 h-4 text-gray-400" /></span>
                    </button>

                    {{-- Dropdown Panel --}}
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                         x-cloak>
                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $userName }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $userEmail }}</p>
                        </div>
                        <ul class="py-2">
                            <li>
                                <a href="{{ route('paneluser.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="gauge" style="solid" class="w-4 h-4 text-gray-400" />
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile.show') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="user" style="solid" class="w-4 h-4 text-gray-400" />
                                    Perfil
                                </a>
                            </li>
                            @if(Route::has('user.notifications.index'))
                            <li>
                                <a href="{{ route('user.notifications.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="bell" style="solid" class="w-4 h-4 text-gray-400" />
                                    Notificações
                                    @if($user && $user->unreadNotifications->count() > 0)
                                        <span class="ml-auto px-2 py-0.5 text-[10px] font-bold bg-rose-500 text-white rounded-full">{{ $user->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('user.subscription.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="credit-card" style="solid" class="w-4 h-4 text-gray-400" />
                                    Assinatura
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('user.security.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <x-icon name="user-shield" style="solid" class="w-4 h-4 text-gray-400" />
                                    Segurança
                                </a>
                            </li>
                        </ul>
                        <div class="border-t border-gray-100 dark:border-gray-700 py-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-colors">
                                    <x-icon name="right-from-bracket" style="solid" class="w-4 h-4" />
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<script>
(function(){
    var cb = document.getElementById('theme-toggle-checkbox');
    if (!cb) return;
    cb.checked = document.documentElement.classList.contains('dark');
    cb.addEventListener('change', function(){
        var isDark = cb.checked;
        document.documentElement.classList.toggle('dark', isDark);
        localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
    });
})();
</script>
