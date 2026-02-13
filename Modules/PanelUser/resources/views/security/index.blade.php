<x-paneluser::layouts.master>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Segurança da Conta</h1>
            <p class="mt-2 text-lg text-slate-600 dark:text-slate-400">Gerencie suas credenciais e monitore o acesso à sua conta.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Support Access & Password Column -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Support Access Card -->
                @if(!session()->has('impersonate_inspection_id'))
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center">
                            <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg mr-3">
                                <x-icon name="headset" class="text-amber-600 dark:text-amber-400" />
                            </div>
                            Acesso para Suporte
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Ao habilitar, você autoriza nossa equipe de suporte a visualizar e editar seus dados cadastrais por <strong>24 horas</strong> para auxiliar em chamados técnicos.
                        </p>

                        @if(auth()->user()->support_access_expires_at && auth()->user()->support_access_expires_at->isFuture())
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl border border-emerald-100 dark:border-emerald-500/20 mb-4">
                                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold text-xs uppercase tracking-widest">
                                    <x-icon name="shield-check" class="w-4 h-4" />
                                    Acesso Autorizado
                                </div>
                                <p class="text-[10px] text-emerald-600/70 dark:text-emerald-400/60 mt-1 uppercase tracking-tighter">
                                    Expira em: {{ auth()->user()->support_access_expires_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <form action="{{ route('user.security.support-access.revoke') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm text-sm font-bold text-red-600 bg-white dark:bg-slate-900 hover:bg-red-50 transition-all">
                                    Revogar Acesso Agora
                                </button>
                            </form>
                        @else
                            <form action="{{ route('user.security.support-access.grant') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 transition-all hover:shadow-lg transform hover:-translate-y-0.5">
                                    Autorizar Acesso (24h)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endif

                @if(!session()->has('impersonate_inspection_id'))
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                                <x-icon name="key" class="text-indigo-600 dark:text-indigo-400" />
                            </div>
                            Alterar Senha
                        </h3>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('user.security.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                <div class="relative z-0 w-full group">
                                    <input type="password" name="current_password" id="current_password" class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600 peer" placeholder=" " required />
                                    <label for="current_password" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Senha Atual</label>
                                    @error('current_password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative z-0 w-full group">
                                    <input type="password" name="password" id="password" class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600 peer" placeholder=" " required />
                                    <label for="password" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nova Senha</label>
                                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative z-0 w-full group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-indigo-600 peer" placeholder=" " required />
                                    <label for="password_confirmation" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-indigo-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirmar Nova Senha</label>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all hover:shadow-lg transform hover:-translate-y-0.5">
                                    Atualizar Segurança
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="p-8 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 text-center">
                    <x-icon name="lock" class="text-3xl text-slate-300 dark:text-slate-600 mb-3 mx-auto" />
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Configurações Restritas</p>
                    <p class="text-[9px] text-slate-400/60 mt-1 uppercase tracking-tighter">O suporte não pode alterar senhas ou permissões de acesso durante a inspeção.</p>
                </div>
                @endif
            </div>

            <!-- Activity Log Column -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center">
                             <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg mr-3">
                                <x-icon name="shield-halved" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            Registro de Atividades
                        </h3>
                         <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                             Últimos acessos
                         </span>
                    </div>

                    <div class="p-6">
                        <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8">
                            @forelse($logs as $log)
                                <div class="relative pl-8 group">
                                    <!-- Dot -->
                                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-2 border-white dark:border-slate-800 bg-emerald-500 shadow-sm z-10 transition-transform group-hover:scale-125"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-slate-800 dark:text-white text-sm">
                                                Login Realizado com Sucesso
                                            </h4>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex flex-col sm:flex-row gap-1 sm:gap-3">
                                                 <span class="flex items-center">
                                                    <x-icon name="{{ Str::contains(strtolower($log->user_agent), 'mobile') ? 'mobile-screen' : 'desktop' }}" class="mr-1.5 text-slate-400" />
                                                    <span class="truncate max-w-[200px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span>
                                                </span>
                                                <span class="hidden sm:inline">•</span>
                                                <span class="flex items-center">
                                                    <x-icon name="globe" class="mr-1.5 text-slate-400" />
                                                    {{ $log->ip_address }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <time class="text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md">
                                                {{ $log->created_at->format('d/m/Y \à\s H:i') }}
                                            </time>
                                            <p class="text-[10px] text-slate-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                    <!-- Location Badge (Mockup/Optional) -->
                                    @if($log->location)
                                        <div class="mt-2 text-xs text-slate-500 flex items-center">
                                            <x-icon name="location-dot" class="mr-1 text-slate-400" />
                                            {{ $log->location }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="pl-8 text-slate-500 text-sm">
                                    Nenhuma atividade registrada recentemente.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-700">
                             {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
