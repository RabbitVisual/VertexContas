<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-500">

        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <div class="mb-6">
                        <a href="{{ route('support.profile.show') }}" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-primary transition-colors uppercase tracking-widest gap-2">
                            <x-icon name="arrow-left" />
                            Voltar ao Perfil
                        </a>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Meus Dados</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 font-medium">
                        Mantenha seus dados de agente atualizados. Isso ajuda na identificação durante os atendimentos.
                    </p>
                </div>
            </div>

            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('support.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="shadow-2xl rounded-[2.5rem] overflow-hidden border border-gray-100 dark:border-gray-800 bg-white dark:bg-slate-900">
                        <div class="px-6 py-8 space-y-8">

                            <!-- Photo Gallery Section -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Galeria de Fotos (Agente)</label>
                                    @if($user->photos->count() < 3)
                                        <button type="button" onclick="document.getElementById('photo-upload').click()" class="inline-flex items-center px-4 py-2 bg-primary/10 text-primary text-xs font-black rounded-xl hover:bg-primary hover:text-white transition-all active:scale-95">
                                            <x-icon name="plus" class="w-4 h-4 mr-2" />
                                            Nova Foto
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    @foreach($user->photos as $photo)
                                        <div class="relative group rounded-2xl overflow-hidden border-2 {{ $user->photo === $photo->path ? 'border-primary ring-4 ring-primary/10' : 'border-gray-100 dark:border-gray-800' }} transition-all">
                                            <img src="{{ asset('storage/'.$photo->path) }}" alt="User Photo" class="w-full h-32 object-cover">

                                            @if($user->photo === $photo->path)
                                                <div class="absolute top-2 right-2 bg-primary text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg uppercase tracking-wider">
                                                    Ativa
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                @if($user->photo !== $photo->path)
                                                    <button type="button" onclick="document.getElementById('activate-photo-{{ $photo->id }}').submit()" class="p-2.5 bg-white text-slate-900 rounded-xl hover:bg-primary hover:text-white transition-all transform hover:scale-110" title="Usar como perfil">
                                                        <x-icon name="check" class="w-4 h-4" />
                                                    </button>
                                                @endif

                                                <button type="button" onclick="if(confirm('Excluir esta foto?')) document.getElementById('delete-photo-{{ $photo->id }}').submit()" class="p-2.5 bg-white text-slate-900 rounded-xl hover:bg-red-500 hover:text-white transition-all transform hover:scale-110" title="Excluir">
                                                    <x-icon name="trash" class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($user->photos->count() === 0)
                                        <div class="col-span-3 text-center py-10 border-2 border-dashed border-gray-100 dark:border-gray-800 rounded-[2rem]">
                                            <div class="mx-auto h-12 w-12 text-slate-300">
                                                <x-icon name="image" class="w-12 h-12" />
                                            </div>
                                            <p class="mt-4 text-sm font-bold text-slate-400">Nenhuma foto carregada</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                <!-- First Name -->
                                <div class="space-y-2">
                                    <label for="first_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Nome</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}"
                                        class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                                    @error('first_name') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="space-y-2">
                                    <label for="last_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Sobrenome</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                                    @error('last_name') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">E-mail Corporativo</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                                @error('email') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                            </div>

                            <!-- CPF Section -->
                            <div class="space-y-2">
                                <label for="cpf" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">CPF (Documento)</label>
                                <div class="relative">
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf', $user->cpf) }}"
                                        class="w-full pl-6 pr-12 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all {{ Auth::user()->hasRole('admin') ? '' : 'opacity-60 cursor-not-allowed' }}"
                                        @if(!Auth::user()->hasRole('admin')) readonly disabled @endif
                                        maxlength="14"
                                    />
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2">
                                        @if(Auth::user()->hasRole('admin'))
                                            <x-icon name="shield-keyhole" class="text-primary text-xl" />
                                        @else
                                            <x-icon name="lock" class="text-slate-400" />
                                        @endif
                                    </div>
                                </div>
                                @if(!Auth::user()->hasRole('admin'))
                                    <p class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-2 flex items-center gap-2">
                                        <x-icon name="triangle-exclamation" class="text-amber-500" />
                                        Apenas administradores podem editar o CPF.
                                    </p>
                                @endif
                                @error('cpf') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label for="phone" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Telefone/WhatsApp</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                                    @error('phone') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                                </div>

                                <!-- Birth Date -->
                                <div class="space-y-2">
                                    <label for="birth_date" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Data de Nascimento</label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                        class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                                    @error('birth_date') <span class="text-xs text-red-500 mt-1 block px-2">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Password Update -->
                            <div class="pt-6 border-t border-gray-100 dark:border-gray-800 space-y-6">
                                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider ml-2 text-primary">Alterar Senha</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Nova Senha</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                                    </div>
                                    <div class="space-y-2">
                                        <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Confirmar Senha</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="px-8 py-6 bg-gray-50/50 dark:bg-slate-800/50 text-right">
                            <button type="submit" class="px-10 py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all hover:scale-[1.02] active:scale-95 text-sm uppercase tracking-widest">
                                Salvar Minhas Alterações
                            </button>
                        </div>
                    </div>

                </form>

                <!-- Hidden Photo Upload Form -->
                <form id="photo-upload-form" action="{{ route('support.profile.photo.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="photos[]" id="photo-upload" accept="image/*" multiple onchange="if(this.files.length > 0) document.getElementById('photo-upload-form').submit()">
                </form>

                <!-- Hidden Action Forms -->
                @foreach($user->photos as $photo)
                    <form id="activate-photo-{{ $photo->id }}" action="{{ route('support.profile.photo.active', $photo->id) }}" method="POST" class="hidden">
                        @csrf @method('PATCH')
                    </form>
                    <form id="delete-photo-{{ $photo->id }}" action="{{ route('support.profile.photo.delete', $photo->id) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>

    </div>
</x-panelsuporte::layouts.master>
