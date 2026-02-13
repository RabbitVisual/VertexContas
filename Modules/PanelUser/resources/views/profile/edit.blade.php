<x-paneluser::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white">Perfil</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                        Mantenha seus dados pessoais atualizados. Informações sensíveis como CPF são protegidas.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white dark:bg-slate-800 space-y-6 sm:p-6">

                            <!-- Photo Gallery Section -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Galeria de Fotos (Máx. 3)</label>
                                    @if($user->photos->count() < 3)
                                        <button type="button" onclick="document.getElementById('photo-upload').click()" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <x-icon name="plus" class="w-4 h-4 mr-2" />
                                            Nova Foto
                                        </button>
                                    @else
                                        <span class="text-xs text-amber-500">Limite de 3 fotos atingido</span>
                                    @endif
                                </div>

                                <!-- Hidden Upload Form -->
                                <!-- Hidden Upload Form will be moved outside -->
                                <button type="button" onclick="document.getElementById('photo-upload').click()" class="hidden"></button>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @foreach($user->photos as $photo)
                                        <div class="relative group rounded-lg overflow-hidden border-2 {{ $user->photo === $photo->path ? 'border-primary' : 'border-gray-200 dark:border-gray-700' }}">
                                            <img src="{{ asset('storage/'.$photo->path) }}" alt="User Photo" class="w-full h-32 object-cover">

                                            <!-- Active Indicator -->
                                            @if($user->photo === $photo->path)
                                                <div class="absolute top-2 right-2 bg-primary text-white text-xs px-2 py-1 rounded-full shadow-sm">
                                                    Em uso
                                                </div>
                                            @endif

                                            <!-- Actions Overlay -->
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                                                @if($user->photo !== $photo->path)
                                                    <button type="button" onclick="document.getElementById('activate-photo-{{ $photo->id }}').submit()" class="p-2 bg-white rounded-full text-slate-700 hover:text-primary transition-colors" title="Usar esta foto">
                                                        <x-icon name="check" class="w-4 h-4" />
                                                    </button>
                                                @endif

                                                <button type="button" onclick="if(confirm('Tem certeza que deseja excluir esta foto?')) document.getElementById('delete-photo-{{ $photo->id }}').submit()" class="p-2 bg-white rounded-full text-slate-700 hover:text-red-500 transition-colors" title="Excluir">
                                                    <x-icon name="trash" class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Placeholder if empty -->
                                    @if($user->photos->count() === 0)
                                        <div class="col-span-3 text-center py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg">
                                            <div class="mx-auto h-12 w-12 text-slate-400">
                                                <x-icon name="image" class="w-12 h-12" />
                                            </div>
                                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">Nenhuma foto</h3>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Carregue uma foto para personalizar seu perfil.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div class="relative z-0 w-full group">
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}"
                                        class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer disabled:opacity-50" placeholder=" " required {{ session()->has('impersonate_inspection_id') ? 'disabled' : '' }} />
                                    <label for="first_name" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Nome
                                    </label>
                                    @error('first_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="relative z-0 w-full group">
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer disabled:opacity-50" placeholder=" " required {{ session()->has('impersonate_inspection_id') ? 'disabled' : '' }} />
                                    <label for="last_name" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Sobrenome
                                    </label>
                                    @error('last_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="relative z-0 w-full group">
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer disabled:opacity-50" placeholder=" " required {{ session()->has('impersonate_inspection_id') ? 'disabled' : '' }} />
                                <label for="email" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                    Endereço de E-mail
                                </label>
                                @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- CPF -->
                            <div class="relative z-0 w-full group">
                                <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf) }}"
                                    class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer disabled:opacity-50 disabled:cursor-not-allowed"
                                    placeholder=" "
                                    {{ !empty($user->cpf) ? 'disabled readonly' : '' }}
                                    maxlength="14"
                                />
                                <label for="cpf" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 flex items-center">
                                    CPF
                                    @if(!empty($user->cpf))
                                        <x-icon name="lock" class="ml-1 text-slate-400 text-xs" />
                                    @endif
                                </label>
                                @if(!empty($user->cpf))
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 flex items-center">
                                        <x-icon name="shield-check" class="mr-1 text-emerald-500" />
                                        Documento verificado. Contate o suporte para alterar.
                                    </p>
                                @endif
                                @error('cpf') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div class="relative z-0 w-full group">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                    <label for="phone" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Telefone
                                    </label>
                                    @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Birth Date -->
                                <div class="relative z-0 w-full group">
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                        class="block py-2.5 px-0 w-full text-sm text-slate-900 dark:text-white bg-transparent border-0 border-b-2 border-slate-300 dark:border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer" placeholder=" " />
                                    <label for="birth_date" class="peer-focus:font-medium absolute text-sm text-slate-500 dark:text-slate-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        Data de Nascimento
                                    </label>
                                    @error('birth_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                        <div class="px-4 py-3 bg-slate-50 dark:bg-slate-900/50 text-right sm:px-6">
                            @if(session()->has('impersonate_inspection_id'))
                                <span class="text-xs font-black text-amber-500 uppercase tracking-widest italic mr-4">Modo Leitura Ativo</span>
                                <button type="button" disabled class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-400 cursor-not-allowed">
                                    Ação Bloqueada
                                </button>
                            @else
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    Salvar Alterações
                                </button>
                            @endif
                        </div>
                    </div>

                </form>

    <!-- Hidden Photo Upload Form (Moved outside main form to prevent nesting issues) -->
    <form id="photo-upload-form" action="{{ route('user.profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" name="photos[]" id="photo-upload" accept="image/*" multiple onchange="if(this.files.length > 0) document.getElementById('photo-upload-form').submit()">
    </form>

    <!-- Hidden Action Forms -->
    @foreach($user->photos as $photo)
        <form id="activate-photo-{{ $photo->id }}" action="{{ route('user.profile.photo.active', $photo->id) }}" method="POST" class="hidden">
            @csrf
            @method('PATCH')
        </form>
        <form id="delete-photo-{{ $photo->id }}" action="{{ route('user.profile.photo.delete', $photo->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
            </div>
        </div>

        <div class="hidden sm:block" aria-hidden="true">
            <div class="py-5">
                <div class="border-t border-slate-200 dark:border-slate-700"></div>
            </div>
        </div>

        <!-- Security Section (Unchanged logic, just keeping structure) -->
        <div class="mt-10 sm:mt-0 md:grid md:grid-cols-3 md:gap-6">
             <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-slate-900 dark:text-white">Segurança</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                        Atualize sua senha e revise suas sessões ativas.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                 <div class="shadow sm:rounded-md sm:overflow-hidden bg-white dark:bg-slate-800">
                    <div class="px-4 py-5 space-y-6 sm:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-slate-900 dark:text-white">Senha</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Recomendamos usar uma senha forte e única.</p>
                            </div>
                            <a href="{{ route('user.security.index') }}" class="bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 font-medium py-2 px-4 rounded-md text-sm transition-colors shadow-sm">
                                Alterar Senha
                            </a>
                        </div>
                         <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-700 pt-4">
                            <div>
                                <h4 class="text-sm font-medium text-slate-900 dark:text-white">Histórico de Acesso</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Veja onde você está conectado.</p>
                            </div>
                            <a href="{{ route('user.security.index') }}" class="text-primary hover:text-primary-dark font-medium text-sm hover:underline">
                                Ver Histórico &rarr;
                            </a>
                        </div>
                    </div>
                 </div>
            </div>
        </div>

    </div>
</x-paneluser::layouts.master>
