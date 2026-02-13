<x-paneladmin::layouts.master>
<div class="container mx-auto px-4 py-6" x-data="{ activeTab: 'general' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
            <x-icon name="gears" style="solid" class="mr-2 text-primary" />
            Configurações do Sistema
        </h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
        <button @click="activeTab = 'general'" :class="{ 'border-primary text-primary': activeTab === 'general', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'general' }" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
            <x-icon name="sliders" style="solid" class="mr-2" /> Geral
        </button>
        <button @click="activeTab = 'branding'" :class="{ 'border-primary text-primary': activeTab === 'branding', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'branding' }" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
            <x-icon name="palette" style="solid" class="mr-2" /> Marca
        </button>
        <button @click="activeTab = 'mail'" :class="{ 'border-primary text-primary': activeTab === 'mail', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'mail' }" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
            <x-icon name="envelope" style="solid" class="mr-2" /> E-mail (SMTP)
        </button>
        <button @click="activeTab = 'notifications'" :class="{ 'border-primary text-primary': activeTab === 'notifications', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'notifications' }" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
            <x-icon name="bell" style="solid" class="mr-2" /> Notificações
        </button>
        <button @click="activeTab = 'blog'" :class="{ 'border-primary text-primary': activeTab === 'blog', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'blog' }" class="py-2 px-4 border-b-2 font-medium text-sm focus:outline-none transition-colors">
            <x-icon name="newspaper" style="solid" class="mr-2" /> Blog
        </button>
    </div>

    <!-- General Settings -->
    <div x-show="activeTab === 'general'" class="bg-white dark:bg-slate-800 shadow rounded-lg p-6">
        <form action="{{ route('admin.settings.general') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome da Aplicação</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $general->get('app_name')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                    <textarea name="app_description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('app_description', $general->get('app_description')) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL da Aplicação</label>
                    <input type="url" name="app_url" value="{{ old('app_url', $general->get('app_url')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Modo Manutenção</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Quando ativado, apenas administradores poderão acessar o sistema.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ $general->get('maintenance_mode') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/30 dark:peer-focus:ring-primary/80 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fuso Horário</label>
                        <select name="app_timezone" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="America/Sao_Paulo" {{ $general->get('app_timezone') == 'America/Sao_Paulo' ? 'selected' : '' }}>America/Sao_Paulo</option>
                            <option value="UTC" {{ $general->get('app_timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Idioma Padrão</label>
                        <select name="app_locale" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="pt_BR" {{ $general->get('app_locale') == 'pt_BR' ? 'selected' : '' }}>Português (Brasil)</option>
                            <option value="en" {{ $general->get('app_locale') == 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Branding Settings -->
    <div x-show="activeTab === 'branding'" class="bg-white dark:bg-slate-800 shadow rounded-lg p-6" x-cloak>
        <form action="{{ route('admin.settings.branding') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Logo da Aplicação</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                        @if($branding->get('app_logo'))
                            <img src="{{ Storage::url($branding->get('app_logo')) }}" alt="Logo" class="h-16 mb-4 object-contain">
                        @else
                            <x-logo class="h-16 w-auto mb-4 text-gray-400" />
                        @endif
                        <input type="file" name="app_logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary sm:file:bg-primary/10 file:text-white sm:file:text-primary hover:file:bg-primary-dark">
                        <p class="text-xs text-gray-500 mt-2">PNG, JPG ou SVG (Máx. 2MB)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Favicon</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 flex flex-col items-center justify-center bg-gray-50 dark:bg-slate-900">
                         @if($branding->get('app_favicon'))
                            <img src="{{ Storage::url($branding->get('app_favicon')) }}" alt="Favicon" class="h-8 w-8 mb-4 object-contain">
                        @else
                            <div class="h-8 w-8 bg-gray-200 rounded mb-4"></div>
                        @endif
                        <input type="file" name="app_favicon" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary sm:file:bg-primary/10 file:text-white sm:file:text-primary hover:file:bg-primary-dark">
                        <p class="text-xs text-gray-500 mt-2">PNG ou ICO (Máx. 512KB)</p>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="cloud-arrow-up" style="solid" class="mr-2" /> Upload & Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Mail Settings -->
    <div x-show="activeTab === 'mail'" class="bg-white dark:bg-slate-800 shadow rounded-lg p-6" x-cloak>
        <form action="{{ route('admin.settings.mail') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Driver de E-mail</label>
                    <select name="mail_mailer" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="smtp" {{ $mail->get('mail_mailer') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="log" {{ $mail->get('mail_mailer') == 'log' ? 'selected' : '' }}>Log (Dev)</option>
                        <option value="mailgun" {{ $mail->get('mail_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Host SMTP</label>
                    <input type="text" name="mail_host" value="{{ old('mail_host', $mail->get('mail_host')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Porta</label>
                        <input type="number" name="mail_port" value="{{ old('mail_port', $mail->get('mail_port')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Criptografia</label>
                         <select name="mail_encryption" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="tls" {{ $mail->get('mail_encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $mail->get('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ $mail->get('mail_encryption') == 'null' ? 'selected' : '' }}>Nenhuma</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usuário SMTP</label>
                    <input type="text" name="mail_username" value="{{ old('mail_username', $mail->get('mail_username')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Senha SMTP</label>
                    <input type="password" name="mail_password" placeholder="••••••••" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Deixe em branco para manter a senha atual.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail Remetente</label>
                    <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $mail->get('mail_from_address')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Remetente</label>
                    <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $mail->get('mail_from_name')) }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end mt-4">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações de E-mail
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Notification Settings -->
    <div x-show="activeTab === 'notifications'" class="bg-white dark:bg-slate-800 shadow rounded-lg p-6" x-cloak>
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Enviar Notificação Manual</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Envie alertas em tempo real para os usuários do sistema.</p>
        </div>

        <form action="{{ route('admin.notifications.send') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                    <input type="text" name="title" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensagem</label>
                    <textarea name="message" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Alerta</label>
                        <select name="type" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="info">Informação (Azul)</option>
                            <option value="success">Sucesso (Verde)</option>
                            <option value="warning">Aviso (Amarelo)</option>
                            <option value="danger">Erro/Perigo (Vermelho)</option>
                        </select>
                    </div>

                    <div x-data="{ audience: 'all' }">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Público Alvo</label>
                        <select name="audience" x-model="audience" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 mb-3">
                            <option value="all">Todos os Usuários</option>
                            <option value="role">Por Função (Role)</option>
                        </select>

                        <div x-show="audience === 'role'" x-transition>
                             <select name="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-gray-100 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option value="free_user">Usuários Gratuitos</option>
                                <option value="pro_user">Usuários PRO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="paper-plane" style="solid" class="mr-2" /> Enviar Notificação
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Blog Settings -->
    <div x-show="activeTab === 'blog'" class="bg-white dark:bg-slate-800 shadow rounded-lg p-6" x-cloak>
        <form action="{{ route('admin.settings.blog') }}" method="POST">
            @csrf
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
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition-colors flex items-center">
                        <x-icon name="save" style="solid" class="mr-2" /> Salvar Configurações do Blog
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</x-paneladmin::layouts.master>
