<x-paneluser::layouts.master :title="'Meu Perfil'">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary dark:text-primary-400">Meu Perfil</li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Meu Perfil</h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">Gerencie suas informações cadastrais, fotos e plano em um só lugar.</p>
                </div>
                <div class="flex items-center gap-3">
                    @if(!session()->has('impersonate_inspection_id'))
                        <a href="{{ route('user.profile.edit') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 dark:hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-95 group">
                            <x-icon name="pen-to-square" style="solid" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                            Editar Cadastro
                        </a>
                    @else
                        <div class="inline-flex items-center gap-2 px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-xl text-sm font-bold uppercase tracking-wider">
                            <x-icon name="lock" style="solid" class="w-4 h-4" />
                            Sessão de Leitura
                        </div>
                    @endif
                </div>
            </div>

            {{-- Hero Card: Profile Overview --}}
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl shadow-xl dark:shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors duration-200">
                {{-- Decorative Mesh Gradient Background --}}
                <div class="absolute inset-0 opacity-20 dark:opacity-40 pointer-events-none">
                    <div class="absolute -top-24 -left-20 w-96 h-96 bg-primary dark:bg-primary/60 rounded-full blur-[100px]"></div>
                    <div class="absolute top-1/2 -right-20 w-80 h-80 bg-blue-400 dark:bg-blue-600 rounded-full blur-[100px]"></div>
                    <div class="absolute bottom-0 left-1/2 w-64 h-64 bg-indigo-300 dark:bg-indigo-500 rounded-full blur-[80px]"></div>
                </div>

                <div class="relative px-8 py-10 flex flex-col lg:flex-row items-center gap-10">
                    {{-- Avatar Section with Radial Progress --}}
                    <div class="relative group">
                        @php
                            $fields = [$user->first_name, $user->last_name, $user->email, $user->cpf, $user->phone, $user->birth_date, $user->photo];
                            $filled = collect($fields)->filter(fn($v) => !empty($v))->count();
                            $profileCompletion = min(100, (int) round(($filled / 7) * 100));
                        @endphp
                        <div class="relative w-44 h-44 flex items-center justify-center">
                            {{-- Progress Ring --}}
                            <svg class="absolute inset-0 w-full h-full -rotate-90 transform" viewBox="0 0 100 100">
                                <circle class="text-gray-200 dark:text-slate-800" stroke-width="6" stroke="currentColor" fill="transparent" r="44" cx="50" cy="50"/>
                                <circle class="text-primary transition-all duration-1000 ease-out"
                                    stroke-width="6"
                                    stroke-dasharray="{{ 2 * pi() * 44 }}"
                                    stroke-dashoffset="{{ (2 * pi() * 44) * (1 - $profileCompletion / 100) }}"
                                    stroke-linecap="round"
                                    stroke="currentColor"
                                    fill="transparent" r="44" cx="50" cy="50"/>
                            </svg>

                            {{-- Avatar --}}
                            <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white dark:border-slate-800 shadow-xl bg-gray-100 dark:bg-slate-800 relative z-10">
                                @if ($user->photo)
                                    <img src="{{ asset('storage/'.$user->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl font-black text-gray-300 dark:text-slate-600 bg-gray-50 dark:bg-slate-900">
                                        {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Completion Badge --}}
                            <div class="absolute -bottom-2 right-2 bg-primary text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border-2 border-white dark:border-slate-900 shadow-xl z-20">
                                {{ $profileCompletion }}% Completo
                            </div>
                        </div>

                        {{-- Compact Gallery Overlay --}}
                        @if($user->photos->count() > 0)
                            <div class="mt-6 flex justify-center gap-2 flex-wrap">
                                @foreach($user->photos as $photo)
                                    <form action="{{ route('user.profile.photo.active', $photo->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-10 h-10 rounded-full border-2 {{ $user->photo === $photo->path ? 'border-primary scale-110 ring-2 ring-primary/30' : 'border-gray-200 dark:border-slate-700 opacity-50 hover:opacity-100' }} transition-all overflow-hidden shadow-lg bg-gray-100 dark:bg-slate-800">
                                            <img src="{{ asset('storage/'.$photo->path) }}" alt="Foto" class="w-full h-full object-cover">
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Identity info --}}
                    <div class="flex-1 text-center lg:text-left space-y-4 relative z-10">
                        <div>
                            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-2">
                                <h2 class="text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $user->full_name }}</h2>
                                @if($user->hasRole('pro_user'))
                                    <span class="bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 text-[10px] font-black tracking-widest uppercase px-3 py-1 rounded-full border border-amber-200 dark:border-amber-500/30 backdrop-blur-md flex items-center gap-1.5">
                                        <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Vertex PRO
                                    </span>
                                @else
                                    <span class="bg-slate-100 text-slate-600 dark:bg-slate-500/20 dark:text-slate-400 text-[10px] font-black tracking-widest uppercase px-3 py-1 rounded-full border border-slate-200 dark:border-slate-600/30">Vertex Grátis</span>
                                @endif
                            </div>
                            <p class="text-primary/80 dark:text-primary-400/80 font-medium text-lg tracking-wide">{{ $user->email }}</p>
                        </div>

                        {{-- Stats Cards --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl">
                            <div class="bg-white/60 dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                                <div class="p-3 bg-primary/10 dark:bg-primary/20 rounded-xl text-primary">
                                    <x-icon name="crown" style="solid" class="w-8 h-8" />
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 dark:text-white/40 font-black uppercase tracking-widest">Plano Atual</p>
                                    <p class="text-gray-900 dark:text-white font-bold text-xl">{{ $user->hasRole('pro_user') ? 'Vertex PRO' : 'Vertex Grátis' }}</p>
                                </div>
                            </div>
                            <div class="bg-white/60 dark:bg-slate-800/50 backdrop-blur-xl border border-gray-200 dark:border-white/10 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                                <div class="p-3 bg-purple-100 dark:bg-purple-500/20 rounded-xl text-purple-600 dark:text-purple-400">
                                    <x-icon name="calendar-check" style="solid" class="w-8 h-8" />
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-500 dark:text-white/40 font-black uppercase tracking-widest">Membro desde</p>
                                    <p class="text-gray-900 dark:text-white font-bold text-xl">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Profile Completion Progress Bar --}}
                        <div class="max-w-xl mt-6 space-y-2">
                            <div class="flex justify-between items-end px-1">
                                <span class="text-[10px] font-black text-gray-500 dark:text-white/40 uppercase tracking-widest">Completude do perfil</span>
                                <span class="text-xs font-bold text-gray-700 dark:text-white/80">{{ $profileCompletion }}%</span>
                            </div>
                            <div class="h-2 w-full bg-gray-200 dark:bg-white/10 rounded-full overflow-hidden p-0.5 border border-transparent dark:border-white/5 backdrop-blur-sm">
                                <div class="h-full bg-gradient-to-r from-primary via-blue-500 to-indigo-500 rounded-full transition-all duration-1000 shadow-lg shadow-primary/30" style="width: {{ $profileCompletion }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Guided Information Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Details Cards --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Category: Personal & Contact --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                                    <x-icon name="user" style="solid" class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Informações Pessoais</h3>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">Seus dados cadastrais e meios de contato.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                                @php
                                    $personalFields = [
                                        ['label' => 'Nome Completo', 'value' => $user->full_name, 'icon' => 'user'],
                                        ['label' => 'E-mail', 'value' => $user->email, 'icon' => 'envelope'],
                                        ['label' => 'CPF', 'value' => $user->cpf ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $user->cpf) : null, 'icon' => 'id-card', 'verified' => !empty($user->cpf)],
                                        ['label' => 'Telefone', 'value' => $user->phone, 'icon' => 'phone'],
                                        ['label' => 'Data de Nascimento', 'value' => $user->birth_date?->format('d/m/Y'), 'icon' => 'cake-candles'],
                                        ['label' => 'Fotos de Perfil', 'value' => $user->photos->count() . ' de 3', 'icon' => 'images'],
                                    ];
                                @endphp

                                @foreach($personalFields as $field)
                                    <div class="flex items-start gap-4 group">
                                        <div class="mt-1 text-gray-400 dark:text-slate-600 group-hover:text-primary transition-colors">
                                            <x-icon name="{{ $field['icon'] }}" style="solid" class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500 mb-0.5">{{ $field['label'] }}</dt>
                                            <dd class="text-sm font-semibold text-gray-900 dark:text-slate-200 flex items-center gap-2">
                                                {{ $field['value'] ?? 'Não informado' }}
                                                @if(!empty($field['verified']))
                                                    <x-icon name="shield-check" style="solid" class="text-emerald-500 w-4 h-4" title="Verificado" />
                                                @endif
                                            </dd>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- CTA PRO for Free Users (inline) --}}
                    @unless($user->hasRole('pro_user'))
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 rounded-3xl shadow-xl overflow-hidden relative">
                            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 bg-primary/20 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>
                            <div class="relative px-8 py-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                                <div>
                                    <h3 class="text-xl font-extrabold text-white sm:text-2xl mb-1">Leve seu controle financeiro para o próximo nível</h3>
                                    <p class="text-slate-300 text-sm">Relatórios avançados, contas ilimitadas e suporte prioritário com o Vertex PRO.</p>
                                </div>
                                <a href="{{ route('user.subscription.index') }}" class="shrink-0 inline-flex items-center justify-center gap-2 px-6 py-3 border border-transparent text-base font-bold rounded-xl text-slate-900 bg-white hover:bg-gray-50 transition-all shadow-lg active:scale-95">
                                    <x-icon name="rocket" style="solid" class="w-5 h-5 text-primary" />
                                    Quero Ser PRO
                                </a>
                            </div>
                        </div>
                    @endunless
                </div>

                {{-- Right Column: Plan & Security --}}
                <div class="space-y-8">
                    {{-- Plan Card --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between {{ $user->hasRole('pro_user') ? 'bg-amber-50/50 dark:bg-amber-900/10' : 'bg-gray-50/50 dark:bg-slate-900/50' }}">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 {{ $user->hasRole('pro_user') ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }} rounded-xl">
                                    <x-icon name="{{ $user->hasRole('pro_user') ? 'crown' : 'sparkles' }}" style="solid" class="w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $user->hasRole('pro_user') ? 'Vertex PRO' : 'Vertex Grátis' }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">{{ $user->hasRole('pro_user') ? 'Plano ativo' : 'Versão limitada' }}</p>
                                </div>
                            </div>
                            @if($user->hasRole('pro_user'))
                                <span class="bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 text-[10px] font-black px-2 py-0.5 rounded-full">Ativo</span>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($user->hasRole('pro_user'))
                                <p class="text-sm text-gray-600 dark:text-slate-400">Acesso ilimitado a todos os recursos. Aproveite!</p>
                            @else
                                <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">Desbloqueie todo o potencial do Vertex.</p>
                                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all text-sm w-full justify-center">
                                    <x-icon name="rocket" style="solid" class="w-4 h-4" />
                                    Fazer Upgrade
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Security Card --}}
                    <a href="{{ route('user.security.index') }}" class="block bg-slate-900 dark:bg-black rounded-3xl p-6 text-white shadow-xl relative overflow-hidden group hover:shadow-2xl transition-all">
                        <div class="absolute -right-4 -bottom-4 text-white/5 group-hover:text-primary/10 transition-colors">
                            <x-icon name="shield-halved" style="solid" class="w-24 h-24" />
                        </div>

                        <div class="flex items-center gap-3 mb-4 relative">
                            <div class="p-2 bg-white/10 rounded-xl"><x-icon name="shield-halved" style="solid" class="w-5 h-5" /></div>
                            <h3 class="font-bold text-sm tracking-tight text-white">Segurança e Atividade</h3>
                        </div>

                        <div class="space-y-2 relative">
                            <p class="text-[11px] text-white/70 leading-relaxed">Alterar senha, autorizar acesso do suporte (incl. alteração de e-mail/CPF) e histórico de acessos.</p>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary/90">
                                Acessar <x-icon name="chevron-right" style="solid" class="w-4 h-4" />
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
