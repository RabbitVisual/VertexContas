<x-panelsuporte::layouts.master>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-500">

        <!-- Header -->
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('support.users.show', $user) }}" class="p-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-gray-400 hover:text-primary transition-all">
                <x-icon name="arrow-left" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Editar Usuário</h1>
                <p class="text-sm text-slate-500 font-medium tracking-tight">Alterando dados do perfil — <span class="text-primary font-bold">Ações rastreadas pelo Log de Auditoria.</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            <form action="{{ route('support.users.update', $user) }}" method="POST" class="divide-y divide-gray-100 dark:divide-gray-800">
                @csrf
                @method('PUT')

                <div class="p-8 md:p-12 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label for="first_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Nome</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                            @error('first_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label for="last_name" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Sobrenome</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                            @error('last_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                         <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">E-mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" required />
                            @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                         <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Telefone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                            @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Birth Date -->
                        <div class="space-y-2">
                            <label for="birth_date" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Data de Nascimento</label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold placeholder-slate-400 transition-all" />
                            @error('birth_date') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Account Status -->
                        <div class="space-y-2">
                            <label for="status" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Status da Conta</label>
                            <select name="status" id="status" class="w-full pl-6 pr-10 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold transition-all appearance-none cursor-pointer">
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inativo</option>
                                <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Bloqueado</option>
                            </select>
                            @error('status') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-500/5 p-6 rounded-[2rem] border border-amber-100 dark:border-amber-500/20">
                        <div class="flex items-start gap-4">
                            <x-icon name="circle-info" class="text-amber-500 text-xl mt-1" />
                            <div>
                                <h4 class="text-sm font-black text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-1">Rastreabilidade Total</h4>
                                <p class="text-[13px] text-amber-700/70 dark:text-amber-400/50 font-medium leading-relaxed">
                                    Ao salvar, uma entrada será criada no log de auditoria vinculando seu ID de agente às mudanças realizadas. <strong>Não altere o perfil do usuário sem autorização prévia via ticket.</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-8 bg-gray-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic-none">VERTEX CONTAS • PRIVILEGED ACCESS</p>
                    <button type="submit" class="w-full md:w-auto px-12 py-4 bg-primary hover:bg-primary-dark text-white font-black text-sm rounded-2xl shadow-xl shadow-primary/20 transition-all hover:scale-105 active:scale-95 uppercase tracking-widest">
                        Gravar Alterações & Logar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-panelsuporte::layouts.master>
