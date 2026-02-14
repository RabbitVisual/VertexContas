<x-paneluser::layouts.master :title="'Configurações de Segurança'">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
                <x-icon name="circle-check" style="solid" class="text-emerald-600 dark:text-emerald-400 flex-shrink-0" />
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 flex items-center gap-3">
                <x-icon name="triangle-exclamation" style="solid" class="text-rose-600 dark:text-rose-400 flex-shrink-0" />
                <p class="text-sm font-medium text-rose-800 dark:text-rose-200">{{ session('error') }}</p>
            </div>
        @endif
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Segurança e Atividade</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie sua senha e visualize o histórico de acessos.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Password Change Column -->
            <div class="lg:col-span-1">
                @if(!session()->has('impersonate_inspection_id'))
                {{-- Acesso do Suporte (24h) --}}
                @php
                    $supportAccessActive = auth()->user()->support_access_expires_at && auth()->user()->support_access_expires_at->isFuture();
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center">
                        <div class="p-2 {{ $supportAccessActive ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-slate-100 dark:bg-slate-700' }} rounded-lg mr-3">
                            <x-icon name="headset" style="solid" class="{{ $supportAccessActive ? 'text-amber-600 dark:text-amber-400' : 'text-slate-600 dark:text-slate-400' }}" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Acesso do Suporte</h3>
                    </div>
                    <div class="p-6">
                        @if($supportAccessActive)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                A equipe de suporte está autorizada a realizar alterações em sua conta até
                                <strong class="text-gray-900 dark:text-white">{{ auth()->user()->support_access_expires_at->format('d/m/Y H:i') }}</strong>.
                            </p>
                            <form action="{{ route('user.security.support-access.revoke') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-rose-300 dark:border-rose-700 rounded-xl shadow-sm text-sm font-bold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/30 transition-all">
                                    Revogar acesso agora
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Autorize a equipe de suporte por <strong>24 horas</strong> para que possam realizar alterações em sua conta durante o atendimento.
                            </p>
                            <form action="{{ route('user.security.support-access.grant') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Autorizar suporte por 24 horas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg mr-3">
                            <x-icon name="key" style="solid" class="text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Alterar Senha</h3>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('user.security.password') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="space-y-5">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Senha Atual</label>
                                    <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
                                    @error('current_password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nova Senha</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
                                    @error('password') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-white sm:text-sm" required />
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
                <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center flex flex-col items-center justify-center h-full">
                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <x-icon name="lock" style="solid" class="text-gray-400 text-2xl" />
                    </div>
                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest leading-tight mb-2">Configurações Restritas</p>
                    <p class="text-xs text-gray-400 max-w-[200px] mx-auto">O suporte não pode alterar senhas ou permissões durante a inspeção.</p>
                </div>
                @endif

                {{-- Info: Modo Inspeção --}}
                <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="flex gap-3">
                        <div class="p-1.5 bg-amber-100 dark:bg-amber-900/30 rounded-lg h-fit">
                            <x-icon name="magnifying-glass-chart" style="solid" class="text-amber-600 dark:text-amber-400 text-sm" />
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-1">Modo Inspeção</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">Quando o suporte solicitar acesso remoto, um modal aparecerá para você autorizar ou recusar. Durante a inspeção, um banner vermelho será exibido e senhas/permissões ficarão bloqueadas.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log Column -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                             <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg mr-3">
                                <x-icon name="shield-halved" style="solid" class="text-emerald-600 dark:text-emerald-400" />
                            </div>
                            Histórico de Atividade
                        </h3>
                         <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                             Acessos Recentes
                         </span>
                    </div>

                    <div class="p-6">
                        <div class="relative border-l-2 border-gray-200 dark:border-gray-700 ml-3 space-y-8">
                            @php
                                $getBrowserIcon = function($ua) {
                                    $ua = strtolower($ua);
                                    if (str_contains($ua, 'chrome')) return ['name' => 'chrome', 'style' => 'brands'];
                                    if (str_contains($ua, 'firefox')) return ['name' => 'firefox', 'style' => 'brands'];
                                    if (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) return ['name' => 'safari', 'style' => 'brands'];
                                    if (str_contains($ua, 'edge')) return ['name' => 'edge', 'style' => 'brands'];
                                    if (str_contains($ua, 'opera')) return ['name' => 'opera', 'style' => 'brands'];
                                    return ['name' => 'globe', 'style' => 'solid'];
                                };
                                $getDeviceIcon = function($ua) {
                                     $ua = strtolower($ua);
                                     if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) return ['name' => 'mobile-screen', 'style' => 'solid'];
                                     return ['name' => 'desktop', 'style' => 'solid'];
                                };
                            @endphp

                            @forelse($logs as $log)
                                @php
                                    $deviceIcon = $getDeviceIcon($log->user_agent);
                                    $browserIcon = $getBrowserIcon($log->user_agent);
                                @endphp
                                <div class="relative pl-8 group">
                                    <!-- Dot -->
                                    <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 bg-emerald-500 shadow-sm z-10 transition-transform group-hover:scale-125"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                                Login Bem-sucedido
                                            </h4>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-col sm:flex-row gap-1 sm:gap-3 items-start sm:items-center">
                                                 <span class="flex items-center" title="{{ $log->user_agent }}">
                                                    <x-icon :name="$deviceIcon['name']" :style="$deviceIcon['style']" class="mr-1.5 text-gray-400" />
                                                    <x-icon :name="$browserIcon['name']" :style="$browserIcon['style']" class="mr-1.5 text-gray-400" />
                                                    <span class="truncate max-w-[200px]">{{ Str::limit($log->user_agent, 40) }}</span>
                                                </span>
                                                <span class="hidden sm:inline text-gray-300">•</span>
                                                <span class="flex items-center">
                                                    <x-icon name="network-wired" style="solid" class="mr-1.5 text-gray-400" />
                                                    {{ $log->ip_address }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right whitespace-nowrap">
                                            <time class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md block sm:inline-block mb-1 sm:mb-0">
                                                {{ $log->created_at->format('d/m/Y H:i') }}
                                            </time>
                                            <p class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="pl-8 text-gray-500 text-sm">
                                    Nenhuma atividade registrada recentemente.
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-700">
                             {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
