<x-paneluser::layouts.master :title="'Segurança e Atividade'">
    @php
        $user = auth()->user();
        $isPro = $user->isPro();
        $supportAccessActive = $user->support_access_expires_at && $user->support_access_expires_at->isFuture();
    @endphp

    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex mb-2" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <li>Painel</li>
                            <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                            <li class="text-primary">Segurança e Atividade</li>
                        </ol>
                    </nav>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Segurança e Atividade</h1>
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">Gerencie sua senha, autorize o suporte e visualize o histórico de acessos.</p>
                </div>
                <a href="{{ route('user.profile.show') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white transition-colors group">
                    <x-icon name="chevron-left" style="solid" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                    Voltar ao Perfil
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Support Access + Password --}}
                <div class="lg:col-span-1 space-y-8">
                    @if(!session()->has('impersonate_inspection_id'))

                        {{-- Acesso do Suporte --}}
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="p-6 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3 {{ $supportAccessActive ? 'bg-amber-50/50 dark:bg-amber-900/10' : 'bg-gray-50/50 dark:bg-slate-900/50' }}">
                                <div class="p-2.5 {{ $supportAccessActive ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-slate-100 dark:bg-slate-800' }} rounded-xl">
                                    <x-icon name="headset" style="solid" class="{{ $supportAccessActive ? 'text-amber-600 dark:text-amber-400' : 'text-slate-600 dark:text-slate-400' }} w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Acesso do Suporte</h3>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">{{ $supportAccessActive ? 'Autorizado' : 'Não autorizado' }}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                @if($supportAccessActive)
                                    <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">
                                        A equipe de suporte está autorizada a realizar alterações até
                                        <strong class="text-gray-900 dark:text-white">{{ $user->support_access_expires_at->format('d/m/Y H:i') }}</strong>.
                                        Durante esse período, o admin ou suporte poderá, <strong>se você solicitar</strong>, alterar seu e-mail e CPF.
                                    </p>
                                    <form action="{{ route('user.security.support-access.revoke') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-rose-300 dark:border-rose-700 rounded-xl text-sm font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                            <x-icon name="xmark" style="solid" class="w-4 h-4" />
                                            Revogar acesso agora
                                        </button>
                                    </form>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">
                                        Autorize a equipe de suporte por <strong>24 horas</strong> para que possam realizar alterações durante o atendimento. Com sua permissão, eles poderão alterar e-mail e CPF se necessário.
                                    </p>
                                    <form action="{{ route('user.security.support-access.grant') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl text-sm font-bold text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-lg shadow-primary/20">
                                            <x-icon name="check" style="solid" class="w-4 h-4" />
                                            Autorizar suporte por 24 horas
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Alterar Senha --}}
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                                <div class="p-2.5 bg-primary/10 rounded-xl">
                                    <x-icon name="key" style="solid" class="text-primary w-5 h-5" />
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white">Alterar Senha</h3>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('user.security.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-5">
                                        <div class="space-y-1">
                                            <label for="current_password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Senha Atual</label>
                                            <input type="password" name="current_password" id="current_password" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                            @error('current_password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Nova Senha</label>
                                            <input type="password" name="password" id="password" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                            @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="password_confirmation" class="block text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-slate-500">Confirmar Nova Senha</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all">
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-6 w-full flex justify-center items-center gap-2 py-3 px-4 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-[0.98]">
                                        <x-icon name="shield-check" style="solid" class="w-4 h-4" />
                                        Atualizar Senha
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="p-8 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                                <x-icon name="lock" style="solid" class="text-slate-400 w-8 h-8" />
                            </div>
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest leading-tight mb-2">Configurações Restritas</p>
                            <p class="text-xs text-slate-400 max-w-[220px] mx-auto">O suporte não pode alterar senhas ou permissões durante a inspeção.</p>
                        </div>
                    @endif

                    {{-- Modo Inspeção --}}
                    <div class="p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700">
                        <div class="flex gap-3 items-start">
                            <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                                <x-icon name="magnifying-glass-chart" style="solid" class="text-amber-600 dark:text-amber-400 w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-900 dark:text-white uppercase tracking-widest mb-1">Modo Inspeção</p>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 leading-relaxed">Quando o suporte solicitar acesso remoto, um modal aparecerá para você autorizar ou recusar. Durante a inspeção, um banner será exibido e senhas/permissões ficarão bloqueadas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Activity Log --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                        <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                    <x-icon name="shield-halved" style="solid" class="text-emerald-600 dark:text-emerald-400 w-5 h-5" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Histórico de Atividade</h3>
                                    <p class="text-xs text-gray-500 dark:text-slate-400">Acessos recentes à sua conta</p>
                                </div>
                            </div>
                            @if($isPro)
                                <a href="{{ route('user.security.export-logs') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all shadow-lg shadow-emerald-500/20">
                                    <x-icon name="file-arrow-down" style="solid" class="w-4 h-4" />
                                    Exportar CSV
                                </a>
                            @else
                                <div class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-slate-500 dark:text-slate-400">
                                    <x-icon name="lock" style="solid" class="w-4 h-4" />
                                    <span class="text-xs font-bold">Exportar é PRO</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6">
                            @php
                                $getBrowserIcon = function($ua) {
                                    $ua = strtolower($ua ?? '');
                                    if (str_contains($ua, 'chrome')) return ['name' => 'chrome', 'style' => 'brands'];
                                    if (str_contains($ua, 'firefox')) return ['name' => 'firefox', 'style' => 'brands'];
                                    if (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) return ['name' => 'safari', 'style' => 'brands'];
                                    if (str_contains($ua, 'edge')) return ['name' => 'edge', 'style' => 'brands'];
                                    if (str_contains($ua, 'opera')) return ['name' => 'opera', 'style' => 'brands'];
                                    return ['name' => 'globe', 'style' => 'solid'];
                                };
                                $getDeviceIcon = function($ua) {
                                    $ua = strtolower($ua ?? '');
                                    if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) return ['name' => 'mobile-screen', 'style' => 'solid'];
                                    return ['name' => 'desktop', 'style' => 'solid'];
                                };
                            @endphp

                            <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3 space-y-8">
                                @forelse($logs as $log)
                                    @php
                                        $deviceIcon = $getDeviceIcon($log->user_agent);
                                        $browserIcon = $getBrowserIcon($log->user_agent);
                                    @endphp
                                    <div class="relative pl-8 group">
                                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-2 border-white dark:border-slate-800 bg-emerald-500 shadow-sm z-10 transition-transform group-hover:scale-125"></div>
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                            <div>
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Login Bem-sucedido</h4>
                                                <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 flex flex-col sm:flex-row gap-1 sm:gap-3 items-start sm:items-center">
                                                    <span class="flex items-center gap-1.5" title="{{ $log->user_agent }}">
                                                        <x-icon :name="$deviceIcon['name']" :style="$deviceIcon['style']" class="text-gray-400 w-4 h-4" />
                                                        <x-icon :name="$browserIcon['name']" :style="$browserIcon['style']" class="text-gray-400 w-4 h-4" />
                                                        <span class="truncate max-w-[200px]">{{ Str::limit($log->user_agent, 40) }}</span>
                                                    </span>
                                                    <span class="hidden sm:inline text-gray-300">•</span>
                                                    <span class="flex items-center gap-1.5">
                                                        <x-icon name="network-wired" style="solid" class="text-gray-400 w-4 h-4" />
                                                        {{ $log->ip_address }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-right whitespace-nowrap">
                                                <time class="text-xs font-medium text-gray-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
                                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                                </time>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="pl-8 py-4 text-gray-500 dark:text-slate-400 text-sm">
                                        Nenhuma atividade registrada recentemente.
                                    </div>
                                @endforelse
                            </div>

                            <div class="mt-8 pt-4 border-t border-gray-100 dark:border-slate-700">
                                {{ $logs->links() }}
                            </div>
                        </div>
                    </div>

                    {{-- PRO CTA for FREE users --}}
                    @unless($isPro)
                        <div class="mt-8 p-6 bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-900 rounded-3xl border border-slate-700/50 relative overflow-hidden">
                            <div class="absolute -right-16 -top-16 w-40 h-40 bg-amber-500/20 rounded-full blur-3xl"></div>
                            <div class="relative flex items-center gap-4">
                                <div class="p-3 bg-amber-500/20 rounded-xl">
                                    <x-icon name="crown" style="solid" class="text-amber-400 w-8 h-8" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-white text-sm">Exporte seu histórico de acessos</h4>
                                    <p class="text-slate-400 text-xs mt-0.5">Vertex PRO permite exportar o histórico completo em CSV para auditoria e controle.</p>
                                </div>
                                <a href="{{ route('user.subscription.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold rounded-xl text-sm transition-all">
                                    <x-icon name="rocket" style="solid" class="w-4 h-4" />
                                    Fazer Upgrade
                                </a>
                            </div>
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
