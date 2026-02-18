<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Meu Perfil</x-slot>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie suas informações e credenciais de administrador.</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <x-icon name="pen" style="duotone" class="w-4 h-4" />
                Editar Perfil
            </a>
        </div>

        {{-- Alerts Flowbite --}}
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0 text-green-500 dark:text-green-400" />
                <span class="sr-only">Info</span>
                <div class="ms-3 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0 text-red-500 dark:text-red-400" />
                <span class="sr-only">Info</span>
                <div class="ms-3 text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Card Avatar + Identificação - Flowbite User Profile Card --}}
            <div class="space-y-6">
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col items-center pb-10">
                        <form action="{{ route('admin.profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="contents">
                            @csrf
                            <label for="photo-upload" class="relative block cursor-pointer group">
                                @if($user->photo)
                                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg object-cover ring-4 ring-gray-200 dark:ring-gray-600 group-hover:ring-primary-500 transition-all" src="{{ $user->photo_url }}" alt="{{ $user->first_name }}">
                                @else
                                    <div class="w-24 h-24 mb-3 rounded-full shadow-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 group-hover:bg-primary-200 dark:group-hover:bg-primary-900/50 transition-colors">
                                        <span class="text-3xl font-bold">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center rounded-full bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-icon name="camera" style="duotone" class="w-6 h-6 text-white" />
                                </div>
                                <input type="file" name="photo" id="photo-upload" accept="image/*" class="sr-only" onchange="this.form.submit()">
                            </label>
                        </form>
                        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                        <div class="flex mt-4 space-x-3">
                            <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-primary-800 bg-primary-100 rounded-full dark:bg-primary-900/30 dark:text-primary-400">
                                <x-icon name="shield-keyhole" style="duotone" class="w-3.5 h-3.5 me-1.5 shrink-0" />
                                Administrador Master
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <x-icon name="fingerprint" style="duotone" class="w-4 h-4 text-gray-400" /> ID
                            </span>
                            <span class="font-medium text-gray-900 dark:text-white tabular-nums">{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <x-icon name="calendar-plus" style="duotone" class="w-4 h-4 text-gray-400" /> Cadastro
                            </span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <x-icon name="clock-rotate-left" style="duotone" class="w-4 h-4 text-gray-400" /> Último login
                            </span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '—' }}</span>
                        </div>
                        @if($user->last_login_ip)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <x-icon name="network-wired" style="duotone" class="w-4 h-4 text-gray-400" /> IP
                                </span>
                                <span class="font-mono text-xs text-gray-700 dark:text-gray-300">{{ $user->last_login_ip }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Vertex Solutions Branding --}}
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 text-center">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Desenvolvido por</p>
                    <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">Vertex Solutions LTDA</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">© 2026 · Reinan Rodrigues</p>
                </div>
            </div>

            {{-- Coluna direita: Informações + Segurança --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Card Informações Pessoais --}}
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                            <x-icon name="user" style="duotone" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Pessoais</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dados fundamentais da sua conta administrativa</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nome Completo</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">E-mail</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white break-all">{{ $user->email }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">CPF</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white font-mono">{{ lgpd_format_cpf($user->cpf ?? null) ?: '—' }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Data de Nascimento</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->birth_date?->format('d/m/Y') ?: '—' }}</p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 sm:col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telefone</label>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ lgpd_format_phone($user->phone ?? null) ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Segurança & Acesso --}}
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30">
                            <x-icon name="shield-check" style="duotone" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Segurança & Acesso</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status da conta e credenciais</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <span class="flex h-3 w-3 rounded-full bg-green-500 animate-pulse shrink-0" aria-hidden="true"></span>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</label>
                                <p class="text-sm font-semibold text-green-600 dark:text-green-400">Ativo & Verificado</p>
                            </div>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nível de Permissão</label>
                            <p class="text-sm font-semibold text-amber-600 dark:text-amber-400">Super Admin</p>
                        </div>
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600 sm:col-span-2">
                            <label class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Assistente Vertex Bot</label>
                            <p class="text-sm font-semibold {{ ($user->show_assistant ?? true) ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ ($user->show_assistant ?? true) ? 'Ativado' : 'Desativado' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 focus:ring-4 focus:ring-amber-200 dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-400 dark:hover:bg-amber-900/30 dark:focus:ring-amber-800">
                            <x-icon name="lock" style="duotone" class="w-4 h-4" />
                            Alterar senha
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
