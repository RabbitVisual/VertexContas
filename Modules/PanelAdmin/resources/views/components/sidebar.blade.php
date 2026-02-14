<div class="w-64 bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-gray-800 min-h-screen flex flex-col transition-all duration-300" :class="{ '-ml-64': !sidebarOpen }">
    <div class="h-24 flex items-center px-6 border-b border-gray-100 dark:border-white/5">
        <a href="{{ route('admin.index') }}" class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#11C76F] to-[#0EA85A] flex items-center justify-center shadow-lg shadow-[#11C76F]/20">
                <x-icon name="rocket" style="solid" class="text-white text-xl" />
            </div>
            <div class="flex flex-col">
                <span class="text-slate-900 dark:text-white font-black text-lg tracking-tight leading-none italic">Vertex</span>
                <span class="text-[10px] font-black text-[#11C76F] uppercase tracking-widest leading-none mt-1">Admin Panel</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-2">Principal</p>

        <a href="{{ route('admin.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.index') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="gauge" style="duotone" class="w-5" />
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.notifications.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-primary/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="bullhorn" style="duotone" class="w-5" />
            <span>Central de Avisos</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Gestão</p>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="users-gear" style="duotone" class="w-5" />
            <span>Usuários</span>
        </a>

        <a href="{{ route('admin.plans.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.plans.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="sliders" style="duotone" class="w-5" />
            <span>Planos & Limites</span>
        </a>

        <a href="{{ route('admin.roles.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="shield-keyhole" style="duotone" class="w-5" />
            <span>Permissões</span>
        </a>

        <a href="{{ route('admin.payments.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="receipt" style="duotone" class="w-5" />
            <span>Pagamentos</span>
        </a>

        <a href="{{ route('admin.subscriptions.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.subscriptions.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="arrows-rotate" style="duotone" class="w-5" />
            <span>Assinaturas</span>
        </a>

        <a href="{{ route('admin.support.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.support.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="headset" style="duotone" class="w-5" />
            <span>Central de Suporte</span>
        </a>
        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Base de Conhecimento</p>

        <a href="{{ route('admin.wiki.categories') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wiki.categories*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="folder-tree" style="duotone" class="w-5" />
            <span>Categorias Wiki</span>
        </a>

        <a href="{{ route('admin.wiki.articles') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wiki.articles*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="file-pen" style="duotone" class="w-5" />
            <span>Artigos Wiki</span>
        </a>

        <a href="{{ route('admin.wiki.suggestions') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wiki.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="book" style="duotone" class="w-5" />
            <span>Gestão Wiki</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Gestão do Blog</p>

        <a href="{{ route('admin.blog.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.blog.index') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="newspaper" style="duotone" class="w-5" />
            <span>Todos os Posts</span>
        </a>

        <a href="{{ route('admin.blog.create') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.blog.create') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="plus" style="duotone" class="w-5" />
             <span>Novo Post</span>
        </a>

        <a href="{{ route('admin.blog.categories') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.blog.categories') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="tags" style="duotone" class="w-5" />
            <span>Categorias</span>
        </a>

        <a href="{{ route('admin.blog.comments') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.blog.comments') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="comments" style="duotone" class="w-5" />
            <span>Comentários</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Configuração</p>

        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="gears" style="duotone" class="w-5" />
            <span>Configurações</span>
        </a>

        <a href="{{ route('admin.gateways.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.gateways.*') ? 'bg-[#11C76F]/10 text-[#11C76F] font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
            <x-icon name="credit-card" style="duotone" class="w-5" />
            <span>Gateways</span>
        </a>

        <!-- Add more admin links here -->

    </nav>

    <div class="p-6 border-t border-gray-100 dark:border-white/5 space-y-4">
        <!-- Profile Link -->
        <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-4 p-4 rounded-3xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-white/5 hover:border-[#11C76F]/30 transition-all group">
            <div class="relative">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-12 h-12 rounded-2xl object-cover shadow-md border-2 border-white dark:border-slate-800">
                @else
                    <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-xl border-2 border-[#11C76F]/5">
                        {{ substr(Auth::user()->first_name, 0, 1) }}
                    </div>
                @endif
                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-slate-900 dark:text-white truncate leading-none mb-1">{{ Auth::user()->first_name }}</p>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest truncate leading-none">Ver Perfil</p>
            </div>
            <x-icon name="chevron-right" class="text-slate-300 group-hover:text-[#11C76F] transition-colors text-xs" />
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-3 px-3 py-4 w-full rounded-2xl transition-all text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 group font-black uppercase tracking-[0.2em] text-[10px]">
                <x-icon name="right-from-bracket" style="duotone" class="text-lg transition-transform group-hover:translate-x-1" />
                <span>Sair do Sistema</span>
            </button>
        </form>
    </div>
</div>
