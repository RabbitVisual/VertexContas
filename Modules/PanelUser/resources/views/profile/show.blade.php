@section('title', 'Meu Perfil')

<x-paneluser::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <!-- Profile Header -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-primary to-blue-600"></div>
            <div class="px-8 pb-8 relative">
                <div class="relative -top-12 flex items-end justify-between flex-wrap gap-4">
                    <div class="flex items-end">
                        <div class="h-24 w-24 bg-white dark:bg-slate-800 rounded-full p-1 shadow-lg flex-shrink-0">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" alt="Profile" class="h-full w-full rounded-full object-cover">
                            @else
                                <div class="h-full w-full bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-3xl font-bold text-gray-500 dark:text-gray-300">
                                    {{ substr($user->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->full_name }}</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="mb-2">
                        @if(!session()->has('impersonate_inspection_id'))
                            <a href="{{ route('user.profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                <x-icon name="pen" class="w-4 h-4 mr-2" />
                                Editar Perfil
                            </a>
                        @else
                            <div class="px-4 py-2 bg-slate-100 dark:bg-slate-700/50 text-slate-400 rounded-md text-xs font-black uppercase tracking-widest flex items-center gap-2">
                                <x-icon name="lock" /> SESSÃO DE LEITURA
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Informações Pessoais</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-gray-400 block">Nome Completo</span>
                                <span class="text-base text-gray-900 dark:text-white">{{ $user->full_name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">E-mail</span>
                                <span class="text-base text-gray-900 dark:text-white">{{ $user->email }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">CPF</span>
                                <span class="text-base text-gray-900 dark:text-white font-mono">
                                    {{ $user->cpf ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $user->cpf) : 'Não informado' }}
                                    @if($user->cpf)
                                        <x-icon name="shield-check" class="ml-1 text-emerald-500 w-3 h-3 inline" title="Verificado" />
                                    @endif
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-400 block">Telefone</span>
                                    <span class="text-base text-gray-900 dark:text-white">{{ $user->phone ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Nascimento</span>
                                    <span class="text-base text-gray-900 dark:text-white">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 block">Membro desde</span>
                                <span class="text-base text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Plano Atual</h3>
                        <div class="bg-gray-50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-gray-900 dark:text-white">
                                    @if($user->hasRole('pro_user'))
                                        <span class="text-amber-500 flex items-center">
                                            <x-icon name="crown" style="solid" class="w-4 h-4 mr-1" />
                                            Vertex PRO
                                        </span>
                                    @else
                                        <span class="text-slate-500">Vertex Grátis</span>
                                    @endif
                                </span>
                                @if($user->hasRole('pro_user'))
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded-full dark:bg-amber-900/30 dark:text-amber-400">Ativo</span>
                                @else
                                    <a href="{{ route('user.subscription.index') }}" class="text-xs text-primary hover:underline">Fazer Upgrade</a>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if($user->hasRole('pro_user'))
                                    Você tem acesso ilimitado a todos os recursos. Aproveite!
                                @else
                                    Você está usando a versão limitada. Atualize para desbloquear todo o potencial.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strategic CTA for Free Users -->
        @unless($user->hasRole('pro_user'))
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl shadow-xl overflow-hidden relative">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>

                <div class="relative px-8 py-10 sm:px-12 lg:px-16 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl font-extrabold text-white sm:text-3xl mb-2">
                            Leve seu controle financeiro para o próximo nível
                        </h2>
                        <p class="text-slate-300 max-w-lg mx-auto md:mx-0">
                            Desbloqueie relatórios avançados, contas ilimitadas e suporte prioritário com o Vertex PRO.
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-slate-900 bg-white hover:bg-gray-50 transition-all shadow-lg transform hover:-translate-y-1">
                            <x-icon name="rocket" class="w-5 h-5 mr-2 text-primary" />
                            Quero Ser PRO
                        </a>
                    </div>
                </div>
            </div>
        @endunless

    </div>
</x-paneluser::layouts.master>
