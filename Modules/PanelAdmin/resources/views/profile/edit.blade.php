<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <div class="mb-10 flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none italic">
                    Configurações do Perfil
                </h1>
                <p class="text-sm text-slate-500 font-medium">Atualize suas informações e senha de acesso.</p>
            </div>
            <a href="{{ route('admin.profile.show') }}" class="p-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-[#11C76F] transition-all shadow-sm">
                <x-icon name="arrow-left" class="text-xl" />
            </a>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Pessoal Section -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center">
                        <x-icon name="user-gear" style="duotone" class="text-xl" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Dados Pessoais</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Informações básicas da conta</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Nome</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all" required>
                        @error('first_name') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Sobrenome</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all" required>
                        @error('last_name') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all" required>
                        @error('email') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Telefone (Opcional)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all">
                        @error('phone') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Segurança Section -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <x-icon name="lock" style="duotone" class="text-xl" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Alterar Senha</h3>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Deixe em branco se não quiser alterar</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Senha Atual</label>
                        <input type="password" name="current_password"
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all">
                        @error('current_password') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Nova Senha</label>
                            <input type="password" name="new_password"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all">
                            @error('new_password') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest ml-4 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Confirmar Nova Senha</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 py-6 bg-[#11C76F] text-white font-black rounded-[2rem] shadow-2xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-xs uppercase tracking-[0.2em]">
                    Salvar Todas as Alterações
                </button>
                <a href="{{ route('admin.profile.show') }}" class="py-6 px-10 bg-white dark:bg-slate-900 text-slate-400 font-black rounded-[2rem] border border-gray-100 dark:border-white/5 hover:text-slate-600 dark:hover:text-white transition-all text-xs uppercase tracking-[0.2em] text-center">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
