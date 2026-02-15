<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Perfil</x-slot>

    <x-paneladmin::page title="Configurações do Perfil" subtitle="Atualize suas informações e senha de acesso.">
        <x-slot name="header">
            <a href="{{ route('admin.profile.show') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Pessoal Section -->
            <x-paneladmin::card>
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center">
                        <x-icon name="user-gear" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Dados Pessoais</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Informações básicas da conta</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nome</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                        @error('first_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Sobrenome</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                        @error('last_name') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" required>
                        @error('email') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Telefone (Opcional)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            x-mask="'phone'" placeholder="(00) 00000-0000"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        @error('phone') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            </x-paneladmin::card>

            <!-- Segurança Section -->
            <x-paneladmin::card>
            <div class="p-6 space-y-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <x-icon name="lock" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Alterar Senha</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Deixe em branco se não quiser alterar</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Senha Atual</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        @error('current_password') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nova Senha</label>
                            <input type="password" name="new_password"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                            @error('new_password') <p class="text-[10px] text-red-500 font-black uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Confirmar Nova Senha</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                        </div>
                    </div>
                </div>
            </div>
            </x-paneladmin::card>

            <div class="flex flex-col md:flex-row gap-4">
                <button type="submit" class="px-6 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                    Salvar Alterações
                </button>
                <a href="{{ route('admin.profile.show') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-center font-medium">
                    Cancelar
                </a>
            </div>
        </form>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
