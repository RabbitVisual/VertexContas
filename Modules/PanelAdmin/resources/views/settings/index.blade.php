<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Configurações</x-slot>

    <x-paneladmin::page title="Configurações do Sistema" subtitle="Ajuste geral, marca, e-mail, blog e documentos.">
    <div x-data="{ activeTab: '{{ old('tab', $tab) }}' }" class="flex flex-col lg:flex-row gap-6 -mx-4 lg:mx-0">
    {{-- Sidebar vertical - navegação por aba --}}
    <aside class="w-full lg:w-56 shrink-0 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 p-2 h-fit">
        <nav class="space-y-0.5">
            <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" @click.prevent="activeTab = 'general'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'general' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="sliders" style="duotone" class="size-5 shrink-0" />
                <span>Geral</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'branding']) }}" @click.prevent="activeTab = 'branding'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'branding' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="palette" style="duotone" class="size-5 shrink-0" />
                <span>Marca</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'mail']) }}" @click.prevent="activeTab = 'mail'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'mail' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="envelope" style="duotone" class="size-5 shrink-0" />
                <span>E-mail (SMTP)</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'blog']) }}" @click.prevent="activeTab = 'blog'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'blog' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="newspaper" style="duotone" class="size-5 shrink-0" />
                <span>Blog</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'documents']) }}" @click.prevent="activeTab = 'documents'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'documents' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="file-lines" style="duotone" class="size-5 shrink-0" />
                <span>Documentos</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'security']) }}" @click.prevent="activeTab = 'security'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'security' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="shield-halved" style="duotone" class="size-5 shrink-0" />
                <span>Segurança</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'features']) }}" @click.prevent="activeTab = 'features'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'features' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="toggle-on" style="duotone" class="size-5 shrink-0" />
                <span>Recursos</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'pusher']) }}" @click.prevent="activeTab = 'pusher'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'pusher' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="satellite-dish" style="duotone" class="size-5 shrink-0" />
                <span>Pusher (Chat)</span>
            </a>
            <a href="{{ route('admin.settings.index', ['tab' => 'tools']) }}" @click.prevent="activeTab = 'tools'; history.replaceState(null, '', $event.currentTarget.href)" :class="activeTab === 'tools' ? 'bg-[#11C76F]/10 text-[#11C76F] dark:bg-[#11C76F]/15 border-l-[#11C76F]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700/50 border-l-transparent'" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium border-l-2 transition-colors">
                <x-icon name="screwdriver-wrench" style="duotone" class="size-5 shrink-0" />
                <span>Ferramentas</span>
            </a>
        </nav>
    </aside>

    {{-- Conteúdo das abas --}}
    <div class="flex-1 min-w-0 space-y-6">
    @if(session('success'))
        <div class="bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 dark:border-emerald-600 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- General Settings -->
    <div x-show="activeTab === 'general'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        <form action="{{ route('admin.settings.general') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="general">
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nome da Aplicação</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $general->get('app_name')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Descrição</label>
                    <textarea name="app_description" rows="3" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">{{ old('app_description', $general->get('app_description')) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">URL da Aplicação</label>
                    <input type="url" name="app_url" value="{{ old('app_url', $general->get('app_url')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Modo Manutenção</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Quando ativado, apenas administradores poderão acessar o sistema.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ $general->get('maintenance_mode') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#11C76F]/30 dark:peer-focus:ring-[#11C76F]/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#11C76F]"></div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Mensagem de Manutenção (opcional)</label>
                    <textarea name="maintenance_message" rows="3" placeholder="Ex: Voltar em breve! Estamos atualizando o sistema." class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">{{ old('maintenance_message', $general->get('maintenance_message')) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Exibida na página de manutenção quando ativada. Deixe vazio para mensagem padrão.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fuso Horário (GMT)</label>
                        <select name="app_timezone" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            <optgroup label="Brasil">
                                <option value="America/Sao_Paulo" {{ $general->get('app_timezone') == 'America/Sao_Paulo' ? 'selected' : '' }}>Brasília (GMT-3)</option>
                                <option value="America/Bahia" {{ $general->get('app_timezone') == 'America/Bahia' ? 'selected' : '' }}>Bahia (GMT-3)</option>
                                <option value="America/Fortaleza" {{ $general->get('app_timezone') == 'America/Fortaleza' ? 'selected' : '' }}>Fortaleza / Nordeste (GMT-3)</option>
                                <option value="America/Recife" {{ $general->get('app_timezone') == 'America/Recife' ? 'selected' : '' }}>Recife (GMT-3)</option>
                                <option value="America/Belem" {{ $general->get('app_timezone') == 'America/Belem' ? 'selected' : '' }}>Belém (GMT-3)</option>
                                <option value="America/Cuiaba" {{ $general->get('app_timezone') == 'America/Cuiaba' ? 'selected' : '' }}>Cuiabá (GMT-4)</option>
                                <option value="America/Campo_Grande" {{ $general->get('app_timezone') == 'America/Campo_Grande' ? 'selected' : '' }}>Campo Grande (GMT-4)</option>
                                <option value="America/Manaus" {{ $general->get('app_timezone') == 'America/Manaus' ? 'selected' : '' }}>Manaus (GMT-4)</option>
                                <option value="America/Porto_Velho" {{ $general->get('app_timezone') == 'America/Porto_Velho' ? 'selected' : '' }}>Porto Velho (GMT-4)</option>
                                <option value="America/Rio_Branco" {{ $general->get('app_timezone') == 'America/Rio_Branco' ? 'selected' : '' }}>Rio Branco (GMT-5)</option>
                            </optgroup>
                            <optgroup label="Outros">
                                <option value="UTC" {{ $general->get('app_timezone') == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                                <option value="America/New_York" {{ $general->get('app_timezone') == 'America/New_York' ? 'selected' : '' }}>Nova York (GMT-5/-4)</option>
                                <option value="Europe/Lisbon" {{ $general->get('app_timezone') == 'Europe/Lisbon' ? 'selected' : '' }}>Lisboa (GMT+0/+1)</option>
                                <option value="Europe/London" {{ $general->get('app_timezone') == 'Europe/London' ? 'selected' : '' }}>Londres (GMT+0/+1)</option>
                            </optgroup>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Usado globalmente em todo o sistema para datas e horários.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Idioma Padrão</label>
                        <select name="app_locale" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            <option value="pt_BR" {{ $general->get('app_locale') == 'pt_BR' ? 'selected' : '' }}>Português (Brasil)</option>
                            <option value="en" {{ $general->get('app_locale') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-slate-600 pt-6 mt-2">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Nomes dos Painéis</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Exibidos no cabeçalho de cada área do sistema. Deixe vazio para usar o nome da aplicação.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Painel do Usuário</label>
                            <input type="text" name="panel_user_name" value="{{ old('panel_user_name', $general->get('panel_user_name')) }}" placeholder="{{ $general->get('app_name') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Painel Admin</label>
                            <input type="text" name="panel_admin_name" value="{{ old('panel_admin_name', $general->get('panel_admin_name')) }}" placeholder="Administração" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Painel Suporte</label>
                            <input type="text" name="panel_suporte_name" value="{{ old('panel_suporte_name', $general->get('panel_suporte_name')) }}" placeholder="Suporte" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors font-bold flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Security Settings -->
    <div x-show="activeTab === 'security'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.security') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="security">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tentativas de Login (por minuto)</label>
                    <input type="number" name="max_login_attempts" value="{{ old('max_login_attempts', $security->get('max_login_attempts') ?? 5) }}" min="1" max="20" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Limite por IP antes de bloquear por 1 minuto.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Duração da Sessão (minutos)</label>
                    <input type="number" name="session_lifetime" value="{{ old('session_lifetime', $security->get('session_lifetime') ?? 120) }}" min="15" max="10080" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Tempo de inatividade até logout (15 min a 7 dias).</p>
                </div>
                <div class="col-span-1 md:col-span-2 flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Google reCAPTCHA v3</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Proteção contra bots no login e registro. <a href="https://www.google.com/recaptcha/admin" target="_blank" rel="noopener" class="text-[#11C76F] hover:underline">Obter chaves grátis</a>.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="recaptcha_enabled" value="1" class="sr-only peer" {{ $security->get('recaptcha_enabled') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#11C76F]/30 dark:peer-focus:ring-[#11C76F]/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#11C76F]"></div>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Site Key (pública)</label>
                    <input type="text" name="recaptcha_site_key" value="{{ old('recaptcha_site_key', $security->get('recaptcha_site_key')) }}" placeholder="6Lc..." class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Secret Key</label>
                    <input type="password" name="recaptcha_secret_key" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a atual.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Score mínimo (0 a 1)</label>
                    <input type="number" name="recaptcha_min_score" value="{{ old('recaptcha_min_score', $security->get('recaptcha_min_score') ?? 0.5) }}" step="0.1" min="0" max="1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">0.5 = equilibrado, 0.7+ = mais rigoroso.</p>
                </div>
                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Segurança
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Features Settings -->
    <div x-show="activeTab === 'features'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.features') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="features">
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Vertex Chat VIP</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Habilita o chat em tempo real para clientes PRO e suporte.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="vertex_chat_enabled" value="1" class="sr-only peer" {{ ($features->get('vertex_chat_enabled') ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#11C76F]/30 dark:peer-focus:ring-[#11C76F]/50 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-[#11C76F]"></div>
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Recursos
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Branding Settings -->
    <div x-show="activeTab === 'branding'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.branding') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tab" value="branding">
            <div class="space-y-8">
                <p class="text-sm text-slate-500 dark:text-slate-400">Configure logos por painel. Se não enviar, usa o padrão da aplicação. Variante clara = modo light, escura = modo dark.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Usuário / Homepage (claro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_user') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_user" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Usuário / Homepage (escuro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_user_dark') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_user_dark" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon Global</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $fav = $branding->get('favicon') ?? $branding->get('app_favicon'); @endphp
                            @if($fav)
                                <img src="{{ str_starts_with((string)$fav, 'storage/') ? asset($fav) : asset('storage/'.$fav) }}" alt="Favicon" class="h-8 w-8 mb-3 object-contain">
                            @else
                                <div class="h-8 w-8 bg-gray-200 dark:bg-gray-600 rounded mb-3"></div>
                            @endif
                            <input type="file" name="favicon" accept=".png,.ico" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                            <p class="text-xs text-gray-500 mt-1">PNG ou ICO (Máx. 512KB)</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Admin (claro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_admin') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_admin" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Admin (escuro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_admin_dark') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_admin_dark" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Suporte (claro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_suporte') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_suporte" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Painel Suporte (escuro)</label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                            @php $logo = $branding->get('logo_suporte_dark') ?? $branding->get('app_logo'); @endphp
                            @if($logo)
                                <img src="{{ str_starts_with((string)$logo, 'storage/') ? asset($logo) : asset('storage/'.$logo) }}" alt="Logo" class="h-12 mb-3 object-contain">
                            @else
                                <div class="h-12 flex items-center text-gray-400"><x-icon name="image" style="duotone" class="size-8" /></div>
                            @endif
                            <input type="file" name="logo_suporte_dark" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#11C76F] file:text-white">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors flex items-center">
                        <x-icon name="cloud-arrow-up" style="solid" class="mr-2" /> Upload & Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Mail Settings -->
    <div x-show="activeTab === 'mail'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.mail') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="mail">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Driver de E-mail</label>
                    <select name="mail_mailer" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="smtp" {{ $mail->get('mail_mailer') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="log" {{ $mail->get('mail_mailer') == 'log' ? 'selected' : '' }}>Log (Dev)</option>
                        <option value="mailgun" {{ $mail->get('mail_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Host SMTP</label>
                    <input type="text" name="mail_host" value="{{ old('mail_host', $mail->get('mail_host')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Porta</label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $mail->get('mail_port')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Criptografia</label>
                         <select name="mail_encryption" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            <option value="tls" {{ $mail->get('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $mail->get('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ $mail->get('mail_encryption') == 'null' ? 'selected' : '' }}>Nenhuma</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Usuário SMTP</label>
                    <input type="text" name="mail_username" value="{{ old('mail_username', $mail->get('mail_username')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Senha SMTP</label>
                    <input type="password" name="mail_password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a senha atual.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">E-mail Remetente</label>
                    <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $mail->get('mail_from_address')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nome Remetente</label>
                    <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $mail->get('mail_from_name')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors font-bold flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações de E-mail
                    </button>
                </div>
            </div>
        </form>
    </div>


    <!-- Document Templates Settings -->
    <div x-show="activeTab === 'documents'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.documents') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="documents">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nome da Empresa</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $documents->get('company_name') ?? $general->get('app_name')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                    <p class="text-xs text-gray-500 mt-1">Exibido em faturas e relatórios</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Razão Social / Nome Legal</label>
                    <input type="text" name="company_legal_name" value="{{ old('company_legal_name', $documents->get('company_legal_name')) }}" placeholder="Ex: Vertex Solutions LTDA" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Documentos legais, rodapés, aceite de termos</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Endereço</label>
                    <input type="text" name="company_address" value="{{ old('company_address', $documents->get('company_address')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">CNPJ</label>
                    <input type="text" name="company_cnpj" value="{{ old('company_cnpj', $documents->get('company_cnpj')) }}" x-mask="'cnpj'" placeholder="00.000.000/0000-00" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telefone</label>
                    <input type="text" name="company_phone" value="{{ old('company_phone', $documents->get('company_phone')) }}" x-mask="'phone'" placeholder="(00) 00000-0000" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">E-mail</label>
                    <input type="email" name="company_email" value="{{ old('company_email', $documents->get('company_email')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Texto do Rodapé</label>
                    <input type="text" name="document_footer_text" value="{{ old('document_footer_text', $documents->get('document_footer_text')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Limite de Faturas/Dia</label>
                    <input type="number" name="limit_download_invoice_per_day" value="{{ old('limit_download_invoice_per_day', $documents->get('limit_download_invoice_per_day') ?? 10) }}" min="0" max="999" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                    <p class="text-xs text-gray-500 mt-1">Máximo de visualizações de faturas por usuário por dia</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Limite de Relatórios/Dia</label>
                    <input type="number" name="limit_download_report_per_day" value="{{ old('limit_download_report_per_day', $documents->get('limit_download_report_per_day') ?? 5) }}" min="0" max="999" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                    <p class="text-xs text-gray-500 mt-1">Máximo de visualizações de relatórios por usuário por dia</p>
                </div>
                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors font-bold flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Pusher / Broadcasting Settings -->
    <div x-show="activeTab === 'pusher'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <form action="{{ route('admin.settings.pusher') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="pusher">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Driver de Broadcasting</label>
                    <select name="broadcast_connection" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        <option value="log" {{ ($pusher->get('broadcast_connection') ?? env('BROADCAST_CONNECTION', 'log')) == 'log' ? 'selected' : '' }}>Log (desenvolvimento)</option>
                        <option value="pusher" {{ ($pusher->get('broadcast_connection') ?? env('BROADCAST_CONNECTION')) == 'pusher' ? 'selected' : '' }}>Pusher (tempo real)</option>
                        <option value="null" {{ ($pusher->get('broadcast_connection') ?? env('BROADCAST_CONNECTION')) == 'null' ? 'selected' : '' }}>Desativado</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Use Pusher para Chat VIP em tempo real (Vertex Chat).</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">App ID</label>
                    <input type="text" name="pusher_app_id" value="{{ old('pusher_app_id', $pusher->get('pusher_app_id') ?? env('PUSHER_APP_ID')) }}" placeholder="2115877" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Key</label>
                    <input type="text" name="pusher_app_key" value="{{ old('pusher_app_key', $pusher->get('pusher_app_key') ?? env('PUSHER_APP_KEY')) }}" placeholder="fbed678feae0a33e789f" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Secret</label>
                    <input type="password" name="pusher_app_secret" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter o secret atual.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Cluster</label>
                    <input type="text" name="pusher_app_cluster" value="{{ old('pusher_app_cluster', $pusher->get('pusher_app_cluster') ?? env('PUSHER_APP_CLUSTER', 'mt1')) }}" placeholder="sa1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                    <p class="text-xs text-gray-500 mt-1">Ex: sa1 (Brasil), mt1 (EUA), eu, ap1 (Ásia).</p>
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors font-bold flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações do Pusher
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tools (Logs, Telescope, Pulse) -->
    <div x-show="activeTab === 'tools'" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6" x-cloak>
        <div class="space-y-6">
            <p class="text-sm text-slate-500 dark:text-slate-400">Links rápidos para ferramentas de diagnóstico. Instale os pacotes para habilitar.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(file_exists(base_path('vendor/laravel/telescope')))
                    <a href="{{ url('/telescope') }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                            <x-icon name="bug" style="duotone" class="text-indigo-600 dark:text-indigo-400 size-6" />
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 dark:text-white">Laravel Telescope</p>
                            <p class="text-xs text-slate-500">Debug, queries, jobs</p>
                        </div>
                    </a>
                @endif
                @if(file_exists(base_path('vendor/laravel/pulse')))
                    <a href="{{ url('/pulse') }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <x-icon name="heart-pulse" style="duotone" class="text-amber-600 dark:text-amber-400 size-6" />
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 dark:text-white">Laravel Pulse</p>
                            <p class="text-xs text-slate-500">Monitoramento em tempo real</p>
                        </div>
                    </a>
                @endif
                <a href="{{ url('/up') }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="duotone" class="text-emerald-600 dark:text-emerald-400 size-6" />
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 dark:text-white">Health Check</p>
                        <p class="text-xs text-slate-500">/up - Status da aplicação</p>
                    </div>
                </a>
                @if(config('logging.default') !== 'stack' || true)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/50">
                        <div class="w-12 h-12 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <x-icon name="terminal" style="duotone" class="text-slate-500 size-6" />
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 dark:text-white">Laravel Pail</p>
                            <p class="text-xs text-slate-500">Logs: <code class="bg-slate-200 dark:bg-slate-700 px-1 rounded">php artisan pail</code></p>
                        </div>
                    </div>
                @endif
            </div>
            @if(!file_exists(base_path('vendor/laravel/telescope')) && !file_exists(base_path('vendor/laravel/pulse')))
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-4">
                    <strong>Dica:</strong> Instale <code class="bg-slate-200 dark:bg-slate-700 px-1 rounded">laravel/telescope</code> ou <code class="bg-slate-200 dark:bg-slate-700 px-1 rounded">laravel/pulse</code> para monitoramento avançado.
                </p>
            @endif
        </div>
    </div>

    <!-- Blog Settings -->
        <form action="{{ route('admin.settings.blog') }}" method="POST">
            @csrf
            <input type="hidden" name="tab" value="blog">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comentários de Convidados</label>
                        <p class="text-sm text-gray-500">Permitir que usuários não registrados comentem?</p>
                    </div>
                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="enable_guest_comments" id="enable_guest_comments" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" {{ $blog->get('enable_guest_comments') ? 'checked' : '' }}/>
                        <label for="enable_guest_comments" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aprovação Automática</label>
                        <p class="text-sm text-gray-500">Comentários são publicados imediatamente?</p>
                    </div>
                    <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="auto_approve_comments" id="auto_approve_comments" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" {{ $blog->get('auto_approve_comments') ? 'checked' : '' }}/>
                        <label for="auto_approve_comments" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold py-2 px-4 rounded-xl transition-colors font-bold flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações do Blog
                    </button>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
