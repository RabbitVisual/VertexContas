@php
    $navBase = 'flex items-center gap-3 px-3 py-2 rounded-lg transition-colors group';
    $navActive = 'bg-[#11C76F]/10 text-[#11C76F] font-bold dark:bg-[#11C76F]/15';
    $navInactive = 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white';
    $iconActive = 'text-[#11C76F]';
    $iconInactive = 'text-slate-400 dark:text-slate-500 group-hover:text-[#11C76F]';
    $accordionOpen = [];
    $accordionOpen['principal'] = request()->routeIs('admin.index') || request()->routeIs('admin.notifications.*');
    $accordionOpen['gestao'] = request()->routeIs('admin.users.*') || request()->routeIs('admin.plans.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.subscriptions.*') || request()->routeIs('admin.support.*');
    $accordionOpen['wiki'] = request()->routeIs('admin.wiki.*');
    $accordionOpen['blog'] = request()->routeIs('admin.blog.*');
    $accordionOpen['config'] = request()->routeIs('admin.settings.*') || request()->routeIs('admin.gateways.*');
@endphp
<aside
    :class="sidebarOpen ? 'w-64' : 'w-16'"
    class="bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-slate-800 min-h-screen flex flex-col shrink-0 transition-all duration-300 z-20"
>
    {{-- Logo --}}
    <div class="h-16 flex items-center border-b border-gray-100 dark:border-white/5 shrink-0">
        <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 w-full min-w-0" :class="sidebarOpen ? 'justify-start' : 'justify-center px-0'">
            <div class="w-10 h-10 shrink-0 rounded-xl bg-gradient-to-br from-[#11C76F] to-[#0EA85A] flex items-center justify-center shadow-lg shadow-[#11C76F]/20">
                <x-icon name="rocket" style="solid" class="text-white text-lg" />
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="flex flex-col min-w-0 overflow-hidden">
                <span class="text-slate-900 dark:text-white font-black text-base tracking-tight leading-none">Vertex</span>
                <span class="text-[10px] font-black text-[#11C76F] uppercase tracking-widest leading-none mt-0.5">Admin</span>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        {{-- Principal (accordion) --}}
        <div x-data="{ open: {{ json_encode($accordionOpen['principal']) }} }" class="space-y-0.5">
            <button @click="open = !open" type="button" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarOpen ? null : 'Principal'">
                <x-icon name="squares-four" style="duotone" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-transition class="text-xs font-semibold uppercase tracking-wider">Principal</span>
                <x-icon x-show="sidebarOpen" name="chevron-down" class="w-3.5 h-3.5 ml-auto transition-transform" :class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pl-1">
                <a href="{{ route('admin.index') }}" :title="sidebarOpen ? null : 'Dashboard'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.index') ? $navActive : $navInactive }}">
                    <x-icon name="gauge" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.index') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Dashboard</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" :title="sidebarOpen ? null : 'Central de Avisos'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.notifications.*') ? $navActive : $navInactive }}">
                    <x-icon name="bullhorn" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.notifications.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Central de Avisos</span>
                </a>
            </div>
        </div>

        {{-- Gestão (accordion) --}}
        <div x-data="{ open: {{ json_encode($accordionOpen['gestao']) }} }" class="space-y-0.5 mt-4">
            <button @click="open = !open" type="button" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarOpen ? null : 'Gestão'">
                <x-icon name="users-gear" style="duotone" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-transition class="text-xs font-semibold uppercase tracking-wider">Gestão</span>
                <x-icon x-show="sidebarOpen" name="chevron-down" class="w-3.5 h-3.5 ml-auto transition-transform" :class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pl-1">
                <a href="{{ route('admin.users.index') }}" :title="sidebarOpen ? null : 'Usuários'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.users.*') ? $navActive : $navInactive }}">
                    <x-icon name="users" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.users.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Usuários</span>
                </a>
                <a href="{{ route('admin.plans.index') }}" :title="sidebarOpen ? null : 'Planos e Limites'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.plans.*') ? $navActive : $navInactive }}">
                    <x-icon name="sliders" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.plans.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Planos & Limites</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" :title="sidebarOpen ? null : 'Permissões'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.roles.*') ? $navActive : $navInactive }}">
                    <x-icon name="shield-keyhole" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.roles.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Permissões</span>
                </a>
                <a href="{{ route('admin.payments.index') }}" :title="sidebarOpen ? null : 'Pagamentos'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.payments.*') ? $navActive : $navInactive }}">
                    <x-icon name="receipt" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.payments.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Pagamentos</span>
                </a>
                <a href="{{ route('admin.subscriptions.index') }}" :title="sidebarOpen ? null : 'Assinaturas'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.subscriptions.*') ? $navActive : $navInactive }}">
                    <x-icon name="arrows-rotate" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.subscriptions.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Assinaturas</span>
                </a>
                <a href="{{ route('admin.support.index') }}" :title="sidebarOpen ? null : 'Central de Suporte'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.support.*') ? $navActive : $navInactive }}">
                    <x-icon name="headset" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.support.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Central de Suporte</span>
                </a>
            </div>
        </div>

        {{-- Base de Conhecimento (accordion) --}}
        <div x-data="{ open: {{ json_encode($accordionOpen['wiki']) }} }" class="space-y-0.5 mt-4">
            <button @click="open = !open" type="button" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarOpen ? null : 'Base de Conhecimento'">
                <x-icon name="folder-tree" style="duotone" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-transition class="text-xs font-semibold uppercase tracking-wider">Base de Conhecimento</span>
                <x-icon x-show="sidebarOpen" name="chevron-down" class="w-3.5 h-3.5 ml-auto transition-transform" :class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pl-1">
                <a href="{{ route('admin.wiki.categories') }}" :title="sidebarOpen ? null : 'Categorias Wiki'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.wiki.categories*') ? $navActive : $navInactive }}">
                    <x-icon name="folder-tree" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.wiki.categories*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Categorias Wiki</span>
                </a>
                <a href="{{ route('admin.wiki.articles') }}" :title="sidebarOpen ? null : 'Artigos Wiki'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.wiki.articles*') ? $navActive : $navInactive }}">
                    <x-icon name="file-pen" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.wiki.articles*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Artigos Wiki</span>
                </a>
                <a href="{{ route('admin.wiki.suggestions') }}" :title="sidebarOpen ? null : 'Gestão Wiki'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.wiki.suggestions*') ? $navActive : $navInactive }}">
                    <x-icon name="book" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.wiki.suggestions*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Gestão Wiki</span>
                </a>
            </div>
        </div>

        {{-- Gestão do Blog (accordion) --}}
        <div x-data="{ open: {{ json_encode($accordionOpen['blog']) }} }" class="space-y-0.5 mt-4">
            <button @click="open = !open" type="button" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarOpen ? null : 'Gestão do Blog'">
                <x-icon name="newspaper" style="duotone" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-transition class="text-xs font-semibold uppercase tracking-wider">Gestão do Blog</span>
                <x-icon x-show="sidebarOpen" name="chevron-down" class="w-3.5 h-3.5 ml-auto transition-transform" :class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pl-1">
                <a href="{{ route('admin.blog.index') }}" :title="sidebarOpen ? null : 'Todos os Posts'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.blog.index') ? $navActive : $navInactive }}">
                    <x-icon name="newspaper" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.blog.index') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Todos os Posts</span>
                </a>
                <a href="{{ route('admin.blog.create') }}" :title="sidebarOpen ? null : 'Novo Post'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.blog.create') ? $navActive : $navInactive }}">
                    <x-icon name="plus" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.blog.create') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Novo Post</span>
                </a>
                <a href="{{ route('admin.blog.categories') }}" :title="sidebarOpen ? null : 'Categorias'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.blog.categories') ? $navActive : $navInactive }}">
                    <x-icon name="tags" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.blog.categories') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Categorias</span>
                </a>
                <a href="{{ route('admin.blog.comments') }}" :title="sidebarOpen ? null : 'Comentários'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.blog.comments') ? $navActive : $navInactive }}">
                    <x-icon name="comments" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.blog.comments') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Comentários</span>
                </a>
            </div>
        </div>

        {{-- Jurídico (single link) --}}
        <div class="space-y-0.5 mt-4">
            <a href="{{ route('admin.legal.index') }}" :title="sidebarOpen ? null : 'Central Legal'"
               class="{{ $navBase }} {{ request()->routeIs('admin.legal.*') ? $navActive : $navInactive }}">
                <x-icon name="file-contract" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.legal.*') ? $iconActive : $iconInactive }}" />
                <span x-show="sidebarOpen" x-transition>Central Legal</span>
            </a>
        </div>

        {{-- Configuração (accordion) --}}
        <div x-data="{ open: {{ json_encode($accordionOpen['config']) }} }" class="space-y-0.5 mt-4">
            <button @click="open = !open" type="button" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors"
                :title="sidebarOpen ? null : 'Configuração'">
                <x-icon name="gears" style="duotone" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-transition class="text-xs font-semibold uppercase tracking-wider">Configuração</span>
                <x-icon x-show="sidebarOpen" name="chevron-down" class="w-3.5 h-3.5 ml-auto transition-transform" :class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="space-y-0.5 pl-1">
                <a href="{{ route('admin.settings.index') }}" :title="sidebarOpen ? null : 'Configurações'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.settings.*') ? $navActive : $navInactive }}">
                    <x-icon name="gears" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.settings.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Configurações</span>
                </a>
                <a href="{{ route('admin.gateways.index') }}" :title="sidebarOpen ? null : 'Gateways'"
                   class="{{ $navBase }} {{ request()->routeIs('admin.gateways.*') ? $navActive : $navInactive }}">
                    <x-icon name="credit-card" style="duotone" class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.gateways.*') ? $iconActive : $iconInactive }}" />
                    <span x-show="sidebarOpen" x-transition>Gateways</span>
                </a>
            </div>
        </div>
    </nav>

    {{-- Footer: Profile + Logout --}}
    <div class="p-4 border-t border-gray-100 dark:border-white/5 space-y-3 shrink-0">
        <a href="{{ route('admin.profile.show') }}" :title="sidebarOpen ? null : 'Ver Perfil'"
           class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 hover:border-[#11C76F]/30 transition-all group">
            <div class="relative shrink-0">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" alt="" class="w-10 h-10 rounded-xl object-cover shadow-md border-2 border-white dark:border-slate-800">
                @else
                    <div class="w-10 h-10 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-lg border-2 border-[#11C76F]/5">
                        {{ substr(Auth::user()->first_name ?? Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900"></div>
            </div>
            <div x-show="sidebarOpen" x-transition class="flex-1 min-w-0 overflow-hidden">
                <p class="text-sm font-bold text-slate-900 dark:text-white truncate leading-none">{{ Auth::user()->first_name ?? Auth::user()->name }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate leading-none">Ver Perfil</p>
            </div>
            <x-icon x-show="sidebarOpen" name="chevron-right" class="text-slate-300 group-hover:text-[#11C76F] transition-colors text-xs shrink-0" />
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" :title="sidebarOpen ? null : 'Sair do Sistema'"
                class="flex items-center justify-center gap-3 w-full px-3 py-3 rounded-xl transition-all text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 group font-bold text-sm">
                <x-icon name="right-from-bracket" style="duotone" class="text-lg shrink-0 transition-transform group-hover:translate-x-0.5" />
                <span x-show="sidebarOpen" x-transition>Sair do Sistema</span>
            </button>
        </form>
    </div>
</aside>
