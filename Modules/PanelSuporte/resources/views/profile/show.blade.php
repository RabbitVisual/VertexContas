<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-500">

        <!-- Profile Header -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden mb-8">
            <div class="h-40 bg-gradient-to-r from-primary via-indigo-600 to-blue-700 relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-48 h-48 bg-primary-dark/20 rounded-full blur-3xl"></div>
            </div>

            <div class="px-8 pb-10 relative">
                <div class="relative -top-16 flex items-end justify-between flex-wrap gap-6">
                    <div class="flex items-end group">
                        <div class="h-32 w-32 bg-white dark:bg-slate-900 rounded-[2rem] p-1.5 shadow-2xl flex-shrink-0 relative overflow-hidden ring-4 ring-white dark:ring-slate-900">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" alt="Profile" class="h-full w-full rounded-[1.5rem] object-cover">
                            @else
                                <div class="h-full w-full bg-slate-100 dark:bg-slate-800 rounded-[1.5rem] flex items-center justify-center text-4xl font-black text-primary/30">
                                    {{ substr($user->first_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-6 mb-2">
                            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $user->first_name }} {{ $user->last_name }}</h1>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-lg">Agente de Suporte</span>
                                <span class="text-sm text-slate-400 font-bold">•</span>
                                <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">{{ $user->email }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route('support.profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all hover:scale-105 active:scale-95 uppercase tracking-widest">
                            <x-icon name="pen-to-square" class="w-4 h-4 mr-2" />
                            Editar Perfil
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-4">
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <x-icon name="user" class="text-primary" />
                                Informações do Agente
                            </h3>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nome</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->first_name }}</span>
                                    </div>
                                    <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Sobrenome</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->last_name }}</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">CPF (Documento)</span>
                                    <div class="flex items-center justify-between">
                                        <span class="text-base text-slate-900 dark:text-white font-black tracking-tighter">
                                            {{ $user->cpf ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $user->cpf) : 'Não informado' }}
                                        </span>
                                        @if($user->cpf)
                                            <span class="flex items-center gap-1 text-[10px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-500/10 px-2 py-1 rounded-lg">
                                                <x-icon name="shield-check" class="w-3 h-3" />
                                                Verificado
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Telefone</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->phone ?? '—' }}</span>
                                    </div>
                                    <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nascimento</span>
                                        <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <x-icon name="chart-mixed" class="text-indigo-500" />
                                Atividade & Status
                            </h3>

                            <div class="space-y-4">
                                <div class="bg-indigo-50/50 dark:bg-indigo-500/5 p-6 rounded-[2rem] border border-indigo-100/50 dark:border-indigo-500/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-indigo-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                                <x-icon name="id-badge" style="duotone" />
                                            </div>
                                            <div>
                                                <span class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Cargo Atual</span>
                                                <p class="text-lg font-black text-slate-900 dark:text-white leading-tight">Suporte Técnico</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                                        Você possui acesso às ferramentas de atendimento, tickets e base de conhecimento.
                                    </p>
                                </div>

                                <div class="bg-emerald-50/50 dark:bg-emerald-500/5 p-6 rounded-[2rem] border border-emerald-100/50 dark:border-emerald-500/10">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                            <x-icon name="calendar-check" style="duotone" />
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Tempo de Casa</span>
                                            <p class="text-lg font-black text-slate-900 dark:text-white leading-tight">Desde {{ $user->created_at->format('M Y') }}</p>
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
