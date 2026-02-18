<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Perfil</x-slot>

    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configurações do Perfil</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atualize suas informações pessoais e credenciais de acesso.</p>
            </div>
            <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-600 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:hover:text-primary-500 dark:focus:ring-gray-700">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" />
                Voltar
            </a>
        </div>

        {{-- Alerts Flowbite --}}
        @if(session('success'))
            <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0 text-green-500 dark:text-green-400" />
                <div class="ms-3 text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0 text-red-500 dark:text-red-400" />
                <div class="ms-3 text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Coluna esquerda: Formulários --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Card Dados Pessoais - Flowbite Card with form inputs --}}
                    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/30">
                                <x-icon name="user-gear" style="duotone" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Dados Pessoais</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Informações básicas da conta administrativa</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" required placeholder="Primeiro nome"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('first_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sobrenome <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required placeholder="Sobrenome"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('last_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data de Nascimento</label>
                                <input type="text" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('d/m/Y')) }}" x-mask="'date'" placeholder="dd/mm/aaaa"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('birth_date') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="cpf" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CPF</label>
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf', lgpd_format_cpf($user->cpf ?? null)) }}" x-mask="'cpf'" placeholder="000.000.000-00"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('cpf') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">E-mail <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required placeholder="seu@email.com"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('email') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Telefone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" x-mask="'phone'" placeholder="(00) 00000-0000"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('phone') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Card Preferências --}}
                    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                                <x-icon name="sliders" style="duotone" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Preferências</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Configurações do assistente e exibição</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600">
                            <input type="checkbox" name="show_assistant" id="show_assistant" value="1" {{ old('show_assistant', $user->show_assistant ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                            <label for="show_assistant" class="flex-1">
                                <span class="block text-sm font-semibold text-gray-900 dark:text-white">Exibir Assistente Vertex Bot</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">Mostrar dicas e insights baseados nas suas atividades.</span>
                            </label>
                        </div>
                    </div>

                    {{-- Card Alterar Senha --}}
                    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30">
                                <x-icon name="lock" style="duotone" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Alterar Senha</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Deixe em branco se não quiser alterar</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Senha Atual</label>
                                <input type="password" name="current_password" id="current_password" autocomplete="current-password" placeholder="••••••••"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                @error('current_password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nova Senha</label>
                                    <input type="password" name="new_password" id="new_password" autocomplete="new-password" placeholder="••••••••"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    @error('new_password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="new_password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar Nova Senha</label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" autocomplete="new-password" placeholder="••••••••"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botões --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            <x-icon name="floppy-disk" style="duotone" class="w-4 h-4" />
                            Salvar Alterações
                        </button>
                        <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-700 text-center">
                            Cancelar
                        </a>
                    </div>
                </div>

                {{-- Coluna direita: Resumo + Branding --}}
                <div class="space-y-6">
                    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-4 mb-4">
                            @if($user->photo)
                                <img src="{{ $user->photo_url }}" alt="" class="w-14 h-14 rounded-lg object-cover ring-2 ring-gray-200 dark:ring-gray-600">
                            @else
                                <div class="flex items-center justify-center w-14 h-14 rounded-lg bg-primary-100 dark:bg-primary-900/30">
                                    <span class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ substr($user->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            As alterações serão aplicadas imediatamente após salvar. Mantenha seus dados atualizados para garantir o acesso ao painel.
                        </p>
                    </div>

                    {{-- Vertex Solutions Branding --}}
                    <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 text-center">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Desenvolvido por</p>
                        <p class="mt-1 text-sm font-bold text-gray-900 dark:text-white">Vertex Solutions LTDA</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">© 2026 · Reinan Rodrigues</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
