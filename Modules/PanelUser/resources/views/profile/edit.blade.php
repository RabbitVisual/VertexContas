<x-paneluser::layouts.master :title="'Configurações do Perfil'">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Perfil</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Mantenha seus dados pessoais atualizados. Informações sensíveis como CPF são protegidas.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="shadow sm:rounded-md sm:overflow-hidden bg-white dark:bg-gray-800">
                        <div class="px-4 py-5 space-y-6 sm:p-6">

                            <!-- Profile Photo Section -->
                            <div class="col-span-6 sm:col-span-4" x-data="{ uploading: false }">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto</label>
                                <div class="mt-2 flex items-center space-x-6">
                                    <div class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 dark:border-gray-700 shadow-sm">
                                         @if(auth()->user()->profile_photo_path)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-primary-100 text-primary-600 font-bold text-2xl uppercase">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                        @endif

                                        <!-- Upload Overlay -->
                                        <div x-show="uploading" class="absolute inset-0 bg-black/50 flex items-center justify-center text-white" x-transition>
                                            <x-icon name="spinner" style="solid" class="fa-spin text-xl" />
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-2">
                                        <button type="button"
                                                class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors"
                                                @click="document.getElementById('photo-upload').click()">
                                            Selecionar Nova Foto
                                        </button>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            JPG, GIF ou PNG. Máx. 1MB.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white sm:text-sm transition-colors">
                                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white sm:text-sm transition-colors" {{ session()->has('impersonate_inspection_id') ? 'disabled' : '' }}>
                                    @error('email') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- CPF -->
                                <div>
                                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('CPF') }}
                                        @if(!empty($user->cpf)) <x-icon name="lock" style="solid" class="text-gray-400 text-xs ml-1" /> @endif
                                    </label>
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white sm:text-sm disabled:opacity-60 disabled:bg-gray-100 dark:disabled:bg-gray-800 cursor-not-allowed transition-colors"
                                           {{ !empty($user->cpf) ? 'readonly' : '' }}>
                                    @if(!empty($user->cpf))
                                        <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400 flex items-center">
                                            <x-icon name="shield-check" style="solid" class="mr-1" />
                                            Documento verificado. Entre em contato com o suporte para alterar.
                                        </p>
                                    @endif
                                    @error('cpf') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                </div>

                                <!-- Phone & Birth Date -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone</label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white sm:text-sm transition-colors">
                                        @error('phone') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Nascimento</label>
                                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-900 dark:text-white sm:text-sm transition-colors">
                                        @error('birth_date') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 text-right sm:px-6 rounded-b-md">
                             @if(session()->has('impersonate_inspection_id'))
                                <span class="text-xs font-bold text-amber-500 uppercase tracking-widest italic mr-4">Modo Somente Leitura</span>
                                <button type="button" disabled class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                                    Ação Bloqueada
                                </button>
                            @else
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors shadow-primary-500/30 shadow-lg">
                                    Salvar Alterações
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hidden Forms -->
        <form id="photo-upload-form" action="{{ route('user.profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" name="photos[]" id="photo-upload" accept="image/*" onchange="if(this.files.length > 0) { this.form.submit(); }" />
        </form>

        <div class="hidden sm:block" aria-hidden="true">
            <div class="py-5">
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
            </div>
        </div>

        <!-- Security Section Link -->
        <div class="mt-10 sm:mt-0 md:grid md:grid-cols-3 md:gap-6">
             <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Segurança</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Atualize sua senha e revise suas sessões ativas.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                 <div class="shadow sm:rounded-md sm:overflow-hidden bg-white dark:bg-gray-800">
                    <div class="px-4 py-5 space-y-6 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Senha</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Recomendamos usar uma senha forte e única.</p>
                            </div>
                            <a href="{{ route('user.security.index') }}" class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 font-medium py-2 px-4 rounded-lg text-sm transition-colors shadow-sm inline-flex items-center gap-2">
                                <x-icon name="key" style="solid" class="w-4 h-4" /> Alterar Senha
                            </a>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
