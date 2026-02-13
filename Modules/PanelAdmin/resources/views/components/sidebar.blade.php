<div class="w-64 bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-gray-800 min-h-screen flex flex-col transition-all duration-300" :class="{ '-ml-64': !sidebarOpen }">
    <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-800 px-4">
        <a href="{{ route('admin.index') }}" class="flex items-center gap-2 font-bold text-xl text-primary">
            <x-icon name="rocket" style="duotone" class="text-2xl" />
            <span>Vertex Admin</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-2">Principal</p>

        <a href="{{ route('admin.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.index') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="gauge" style="duotone" class="w-5" />
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.notifications.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="bullhorn" style="duotone" class="w-5" />
            <span>Central de Avisos</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Gestão</p>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="users-gear" style="duotone" class="w-5" />
            <span>Usuários</span>
        </a>

        <a href="{{ route('admin.plans.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.plans.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="sliders" style="duotone" class="w-5" />
            <span>Planos & Limites</span>
        </a>

        <a href="{{ route('admin.roles.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="shield-keyhole" style="duotone" class="w-5" />
            <span>Permissões</span>
        </a>

        <a href="{{ route('admin.payments.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.payments.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="receipt" style="duotone" class="w-5" />
            <span>Pagamentos</span>
        </a>

        <a href="{{ route('support.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('support.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
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
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.wiki.suggestions*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="lightbulb" style="duotone" class="w-5" />
            <span>Sugestões Wiki</span>
        </a>

        <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 mt-6">Configuração</p>

        <a href="{{ route('admin.settings.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="gears" style="duotone" class="w-5" />
            <span>Configurações</span>
        </a>

        <a href="{{ route('admin.gateways.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.gateways.*') ? 'bg-primary/10 text-primary font-medium' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="credit-card" style="duotone" class="w-5" />
            <span>Gateways</span>
        </a>

        <!-- Add more admin links here -->

    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-3 py-2 w-full rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                <x-icon name="right-from-bracket" style="duotone" class="w-5" />
                <span>Sair</span>
            </button>
        </form>
    </div>
</div>
