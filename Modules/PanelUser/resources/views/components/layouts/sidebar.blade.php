<div class="h-full flex flex-col bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-gray-800 w-64 transition-all duration-300">
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-800">
        <a href="{{ route('paneluser.index') }}" class="flex items-center gap-2">
            <x-logo class="h-8 w-auto" />
            <span class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">Vertex</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('paneluser.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('paneluser.index') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="house" style="{{ request()->routeIs('paneluser.index') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('paneluser.index') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Dashboard
        </a>

        <!-- Financeiro Section -->
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Financeiro
            </p>
        </div>

        <a href="{{ route('core.accounts.index') }}" id="sidebar-accounts-link"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('core.accounts.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="wallet" style="{{ request()->routeIs('core.accounts.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('core.accounts.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Minhas Contas
        </a>

        <a href="{{ route('core.transactions.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('core.transactions.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="money-bill-transfer" style="{{ request()->routeIs('core.transactions.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('core.transactions.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Transações
        </a>

        <a href="{{ route('core.categories.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('core.categories.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="tags" style="{{ request()->routeIs('core.categories.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('core.categories.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Categorias
        </a>

        <a href="{{ route('core.goals.index') }}" id="sidebar-goals-link"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('core.goals.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="bullseye" style="{{ request()->routeIs('core.goals.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('core.goals.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Metas & Objetivos
        </a>

        <a href="{{ route('core.reports.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('core.reports.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="chart-simple" style="{{ request()->routeIs('core.reports.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('core.reports.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Relatórios
        </a>

        <!-- Premium -->
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Premium
            </p>
        </div>

        <a href="{{ route('user.subscription.index') }}" id="sidebar-subscription-link"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.subscription.*') ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="star" style="solid" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('user.subscription.*') ? 'text-amber-500' : 'text-gray-400 group-hover:text-amber-500' }}" />
            Minha Assinatura
        </a>

        <!-- Settings -->
        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Configurações
            </p>
        </div>

        <a href="{{ route('user.profile.edit') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.profile.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="user" style="{{ request()->routeIs('user.profile.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('user.profile.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Meu Perfil
        </a>

        <a href="{{ route('user.tickets.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.tickets.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="headset" style="{{ request()->routeIs('user.tickets.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('user.tickets.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Central de Ajuda
        </a>

        <a href="{{ route('user.security.index') }}"
           class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.security.*') ? 'bg-primary/10 text-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white' }}">
            <x-icon name="user-shield" style="{{ request()->routeIs('user.security.*') ? 'solid' : 'regular' }}" class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('user.security.*') ? 'text-primary' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" />
            Segurança
        </a>
    </nav>

    <!-- User & Logout -->
    <div class="border-t border-gray-200 dark:border-gray-800 p-4">
        <div class="flex items-center gap-3 mb-3">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/'.auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-full object-cover">
            @else
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(auth()->user()->first_name, 0, 1) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ auth()->user()->email }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-slate-800 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                <x-icon name="right-from-bracket" class="w-4 h-4 mr-2" />
                Sair
            </button>
        </form>
    </div>
</div>
