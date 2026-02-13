<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-500">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('support.tickets.index') }}" class="p-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-gray-400 hover:text-primary transition-all">
                    <x-icon name="arrow-left" />
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Perfil do Usuário</h1>
                    <p class="text-sm text-slate-500 font-medium tracking-tight">Visualizando dados completos do solicitante sob autorização temporária.</p>
                </div>
            </div>

            <a href="{{ route('support.users.edit', $user) }}" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all uppercase tracking-widest gap-2">
                <x-icon name="user-pen" class="w-4 h-4" />
                Editar Dados
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="h-40 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 relative">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>

            <div class="px-10 pb-12 relative">
                <div class="relative -top-20 flex flex-col md:flex-row items-center md:items-end gap-8">
                     <div class="h-40 w-40 bg-white dark:bg-slate-900 rounded-[2.5rem] p-2 shadow-2xl ring-8 ring-white dark:ring-slate-900 flex-shrink-0 overflow-hidden">
                        @if($user->photo)
                            <img src="{{ asset('storage/'.$user->photo) }}" class="h-full w-full rounded-[2rem] object-cover">
                        @else
                            <div class="h-full w-full bg-slate-100 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center text-4xl font-black text-primary/30 uppercase">
                                {{ substr($user->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="md:mb-4 text-center md:text-left">
                        <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter italic-none">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <div class="flex items-center justify-center md:justify-start gap-3 mt-2">
                             @if($user->hasRole('pro_user'))
                                <span class="px-3 py-1 bg-amber-400 text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg shadow-amber-400/20">👑 Membro PRO</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-lg">Membro Free</span>
                            @endif
                            <span class="text-gray-300">•</span>
                            <span class="text-sm text-slate-500 font-bold tracking-tight">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-4">
                    <div class="space-y-10">
                        <div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <x-icon name="id-card" class="text-primary" /> Informações Básicas
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nome Completo</span>
                                    <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                                </div>
                                <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">E-mail</span>
                                    <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->email }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">CPF</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold tracking-tighter">{{ $user->cpf ?? '—' }}</span>
                                    </div>
                                    <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nascimento</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-10">
                         <div>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <x-icon name="shield-check" class="text-indigo-500" /> Detalhes da Conta
                            </h3>
                            <div class="space-y-4">
                                <div class="bg-indigo-50/30 dark:bg-indigo-500/5 p-6 rounded-[2rem] border border-indigo-100/50 dark:border-indigo-500/10">
                                    <div class="flex items-center gap-4 mb-4">
                                        <x-icon name="calendar-clock" class="text-indigo-500 text-xl" />
                                        <div>
                                            <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest block">Membro desde</span>
                                            <span class="text-lg font-black text-slate-900 dark:text-white">{{ $user->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed">
                                        Atualmente utiliza o plano <strong>{{ $user->hasRole('pro_user') ? 'PRO' : 'Gratuito' }}</strong>.
                                    </p>
                                </div>

                                <div class="bg-emerald-50/30 dark:bg-emerald-500/5 p-6 rounded-[2rem] border border-emerald-100/50 dark:border-emerald-500/10">
                                     <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                            <x-icon name="lock-open" style="duotone" />
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Autorização de Suporte</span>
                                            <span class="text-xs text-slate-900 dark:text-white font-bold">Expira em {{ $user->support_access_expires_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-panelsuporte::layouts.master>
