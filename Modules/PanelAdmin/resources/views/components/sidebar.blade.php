@php
    $logoLight = branding_logo_url('admin', false);
    $logoDark = branding_logo_url('admin', true);
    $panelName = branding_panel_name('admin');
    $linkActive = 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15';
    $linkInactive = 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800';
@endphp
<aside
    class="flex h-screen flex-col shrink-0 border-r border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 z-20"
    :class="sidebarOpen ? 'w-64' : 'w-16'"
>
    <div class="flex flex-1 min-h-0 overflow-hidden">
        {{-- Icon bar (sempre visível) --}}
        <div class="w-16 flex flex-col shrink-0 border-r border-gray-100 dark:border-white/5">
            {{-- Logo --}}
            <div class="inline-flex size-16 items-center justify-center shrink-0 border-b border-gray-100 dark:border-white/5">
                <a href="{{ route('admin.index') }}" class="flex size-full items-center justify-center overflow-hidden px-2 transition-opacity hover:opacity-90" title="{{ $panelName }}">
                    <img src="{{ $logoLight }}" alt="{{ $panelName }}" class="h-9 w-auto max-w-full object-contain dark:hidden" loading="eager" />
                    <img src="{{ $logoDark }}" alt="{{ $panelName }}" class="hidden h-9 w-auto max-w-full object-contain dark:block" loading="eager" />
                </a>
            </div>

            <div class="border-t border-gray-100 dark:border-white/5 flex-1 min-h-0 overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                <div class="px-2 py-4 space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.index') && !request()->routeIs('admin.notifications.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="gauge" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Dashboard
                        </span>
                    </a>

                    {{-- Central de Avisos --}}
                    <a href="{{ route('admin.notifications.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="bullhorn" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Central de Avisos
                        </span>
                    </a>

                    {{-- Central Legal --}}
                    <a href="{{ route('admin.legal.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.legal.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="file-contract" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Central Legal
                        </span>
                    </a>

                    {{-- Gestão --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.plans.*') || request()->routeIs('admin.plan.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.subscriptions.*') || request()->routeIs('admin.support.*') || request()->routeIs('admin.chat.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="users-gear" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Gestão
                        </span>
                    </a>

                    {{-- Base de Conhecimento --}}
                    <a href="{{ route('admin.wiki.categories') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.wiki.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="folder-tree" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Base de Conhecimento
                        </span>
                    </a>

                    {{-- Central de Insights --}}
                    <a href="{{ route('admin.insights.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.insights.*') || request()->routeIs('admin.gamification.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="robot" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Gamificação
                        </span>
                    </a>

                    {{-- Blog --}}
                    <a href="{{ route('admin.blog.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.blog.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="newspaper" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Blog
                        </span>
                    </a>

                    {{-- Mail Central --}}
                    <a href="{{ route('admin.mail.templates.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.mail.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="envelope" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Mail Central
                        </span>
                    </a>

                    {{-- Configuração --}}
                    <a href="{{ route('admin.settings.index') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.gateways.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="gears" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Configuração
                        </span>
                    </a>

                    @if(Route::has('admin.pwa.dashboard'))
                    {{-- PWA --}}
                    <a href="{{ route('admin.pwa.dashboard') }}"
                        class="group relative flex justify-center rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('admin.pwa.*') ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15' : 'text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F]' }}">
                        <x-icon name="mobile-screen" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            PWA
                        </span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Footer icon bar: Perfil + Logout --}}
            <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 dark:border-white/5 bg-white dark:bg-slate-900 p-2 space-y-1">
                <a href="{{ route('admin.profile.show') }}"
                    class="group relative flex w-full justify-center rounded-lg px-2 py-2 text-slate-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-[#11C76F] transition-colors">
                    <x-icon name="user" style="duotone" class="size-5 shrink-0" />
                    <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                        Ver Perfil
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="group relative flex w-full justify-center rounded-lg px-2 py-2 text-slate-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-500 transition-colors">
                        <x-icon name="right-from-bracket" style="duotone" class="size-5 shrink-0" />
                        <span class="invisible absolute start-full top-1/2 z-50 ms-4 -translate-y-1/2 rounded-lg bg-slate-900 dark:bg-slate-700 px-3 py-2 text-xs font-medium text-white shadow-xl group-hover:visible">
                            Sair
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Painel expandido (flat: links + seções) --}}
        <div x-show="sidebarOpen" x-collapse
            class="flex-1 min-w-0 flex flex-col overflow-hidden border-r border-gray-100 dark:border-white/5">
            <nav class="flex-1 overflow-y-auto px-3 py-5">
                {{-- Links principais --}}
                <div class="space-y-0.5">
                    <a href="{{ route('admin.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.index') && !request()->routeIs('admin.notifications.*') ? $linkActive : $linkInactive }}">
                        <x-icon name="gauge" style="duotone" class="size-5 shrink-0" />
                        Dashboard
                    </a>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.notifications.*') ? $linkActive : $linkInactive }}">
                        <x-icon name="bullhorn" style="duotone" class="size-5 shrink-0" />
                        Central de Avisos
                    </a>
                    <a href="{{ route('admin.legal.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.legal.*') ? $linkActive : $linkInactive }}">
                        <x-icon name="file-contract" style="duotone" class="size-5 shrink-0" />
                        Central Legal
                    </a>
                </div>

                {{-- Categoria: Gestão --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="users-gear" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Gestão</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? $linkActive : $linkInactive }}"><x-icon name="users" style="duotone" class="size-4 shrink-0" />Usuários</a>
                        <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.plans.*') ? $linkActive : $linkInactive }}"><x-icon name="sliders" style="duotone" class="size-4 shrink-0" />Planos & Limites</a>
                        <a href="{{ route('admin.plan.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.plan.*') ? $linkActive : $linkInactive }}"><x-icon name="file-lines" style="duotone" class="size-4 shrink-0" />Página de Planos</a>
                        <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.roles.*') ? $linkActive : $linkInactive }}"><x-icon name="shield-keyhole" style="duotone" class="size-4 shrink-0" />Permissões</a>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.payments.*') ? $linkActive : $linkInactive }}"><x-icon name="receipt" style="duotone" class="size-4 shrink-0" />Pagamentos</a>
                        <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.subscriptions.*') ? $linkActive : $linkInactive }}"><x-icon name="arrows-rotate" style="duotone" class="size-4 shrink-0" />Assinaturas</a>
                        <a href="{{ route('admin.support.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.support.*') ? $linkActive : $linkInactive }}"><x-icon name="headset" style="duotone" class="size-4 shrink-0" />Central de Suporte</a>
                        @if(Route::has('admin.chat.index'))
                            <a href="{{ route('admin.chat.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.chat.*') ? $linkActive : $linkInactive }}"><x-icon name="comments" style="duotone" class="size-4 shrink-0" />Chat VIP</a>
                        @endif
                    </div>
                </div>

                {{-- Categoria: Base de Conhecimento --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="folder-tree" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Base de Conhecimento</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.wiki.categories') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.wiki.categories*') ? $linkActive : $linkInactive }}"><x-icon name="folder" style="duotone" class="size-4 shrink-0" />Categorias Wiki</a>
                        <a href="{{ route('admin.wiki.articles') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.wiki.articles*') ? $linkActive : $linkInactive }}"><x-icon name="file-pen" style="duotone" class="size-4 shrink-0" />Artigos Wiki</a>
                        <a href="{{ route('admin.wiki.suggestions') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.wiki.suggestions*') ? $linkActive : $linkInactive }}"><x-icon name="book" style="duotone" class="size-4 shrink-0" />Gestão Wiki</a>
                    </div>
                </div>

                {{-- Categoria: Gamificação --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="robot" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Gamificação</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.insights.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.insights.*') ? $linkActive : $linkInactive }}"><x-icon name="lightbulb" style="duotone" class="size-4 shrink-0" />Central de Insights</a>
                        <a href="{{ route('admin.gamification.medals.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.gamification.medals.*') ? $linkActive : $linkInactive }}"><x-icon name="medal" style="duotone" class="size-4 shrink-0" />Medalhas</a>
                        <a href="{{ route('admin.gamification.rules.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.gamification.rules.*') ? $linkActive : $linkInactive }}"><x-icon name="list-check" style="duotone" class="size-4 shrink-0" />Regras de Conquistas</a>
                    </div>
                </div>

                {{-- Categoria: Gestão do Blog --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="newspaper" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Gestão do Blog</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog.index') ? $linkActive : $linkInactive }}"><x-icon name="newspaper" style="duotone" class="size-4 shrink-0" />Todos os Posts</a>
                        <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog.create') ? $linkActive : $linkInactive }}"><x-icon name="plus" style="duotone" class="size-4 shrink-0" />Novo Post</a>
                        <a href="{{ route('admin.blog.categories') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog.categories') ? $linkActive : $linkInactive }}"><x-icon name="tags" style="duotone" class="size-4 shrink-0" />Categorias</a>
                        <a href="{{ route('admin.blog.comments') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog.comments') ? $linkActive : $linkInactive }}"><x-icon name="comments" style="duotone" class="size-4 shrink-0" />Comentários</a>
                    </div>
                </div>

                {{-- Categoria: Mail Central --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="envelope" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Mail Central</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.mail.templates.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.mail.templates.*') ? $linkActive : $linkInactive }}"><x-icon name="file-lines" style="duotone" class="size-4 shrink-0" />Templates</a>
                        <a href="{{ route('admin.mail.logs.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.mail.logs.*') ? $linkActive : $linkInactive }}"><x-icon name="inbox" style="duotone" class="size-4 shrink-0" />Logs de Mensageria</a>
                    </div>
                </div>

                @if(Route::has('admin.pwa.dashboard'))
                {{-- Categoria: PWA --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="mobile-screen" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">PWA</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.pwa.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.pwa.dashboard') ? $linkActive : $linkInactive }}"><x-icon name="gauge" style="duotone" class="size-4 shrink-0" />Dashboard</a>
                        <a href="{{ route('admin.pwa.installs') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.pwa.installs') ? $linkActive : $linkInactive }}"><x-icon name="mobile-screen" style="duotone" class="size-4 shrink-0" />Instalações</a>
                        <a href="{{ route('admin.pwa.versions.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.pwa.versions.*') ? $linkActive : $linkInactive }}"><x-icon name="code-branch" style="duotone" class="size-4 shrink-0" />Versões</a>
                    </div>
                </div>
                @endif

                {{-- Categoria: Configuração --}}
                <div class="mt-5">
                    <div class="flex items-center gap-2 px-3 py-2">
                        <x-icon name="gears" style="duotone" class="size-4 shrink-0 text-slate-400 dark:text-slate-500" />
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Configuração</span>
                    </div>
                    <div class="mt-0.5 space-y-0.5">
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? $linkActive : $linkInactive }}"><x-icon name="gears" style="duotone" class="size-4 shrink-0" />Configurações</a>
                        <a href="{{ route('admin.gateways.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-5 text-sm font-medium transition-colors {{ request()->routeIs('admin.gateways.*') ? $linkActive : $linkInactive }}"><x-icon name="credit-card" style="duotone" class="size-4 shrink-0" />Gateways</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</aside>
