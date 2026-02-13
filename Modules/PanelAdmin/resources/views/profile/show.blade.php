<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <!-- Profile Header Card -->
        <div class="relative bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-white/5 mb-8">
            <!-- Decorative Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#11C76F]/20 to-transparent opacity-50"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#11C76F]/5 rounded-full blur-3xl -mr-32 -mt-32"></div>

            <div class="relative p-10 flex flex-col md:flex-row items-center gap-10">
                <!-- Profile Photo with Upload Trigger -->
                <div class="relative group cursor-pointer" onclick="document.getElementById('photo-upload').click()">
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" class="w-40 h-40 rounded-[3rem] object-cover shadow-2xl border-4 border-white dark:border-slate-800 transition-transform group-hover:scale-105 duration-500">
                    @else
                        <div class="w-40 h-40 rounded-[3rem] bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center font-black text-6xl border-4 border-[#11C76F]/5 transition-transform group-hover:scale-105 duration-500">
                            {{ substr($user->first_name, 0, 1) }}
                        </div>
                    @endif

                    <div class="absolute inset-0 bg-black/40 rounded-[3rem] opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-sm">
                        <x-icon name="camera" class="text-white text-3xl" />
                    </div>

                    <div class="absolute -bottom-2 -right-2 bg-white dark:bg-[#111111] p-3 rounded-2xl shadow-xl border border-gray-100 dark:border-white/10 group-hover:rotate-12 transition-transform">
                        <x-icon name="pen" class="text-[#11C76F] text-lg" />
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left space-y-4">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-[#11C76F]/10 text-[#11C76F] text-[10px] font-black uppercase tracking-widest border border-[#11C76F]/20">
                        Administrador Master
                    </div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </h1>
                    <p class="text-lg text-slate-500 dark:text-slate-400 font-medium">
                        {{ $user->email }}
                    </p>

                    <div class="flex flex-wrap justify-center md:justify-start gap-4 pt-2">
                        <a href="{{ route('admin.profile.edit') }}" class="px-8 py-4 bg-[#11C76F] text-white font-black rounded-2xl shadow-xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-xs uppercase tracking-widest">
                            Editar Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hidden Photo Upload Form -->
            <form id="photo-form" action="{{ route('admin.profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="photo" id="photo-upload" accept="image/*" onchange="this.form.submit()">
            </form>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Pessoal -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl space-y-6">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <x-icon name="user" style="duotone" class="text-xl" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Informações Pessoais</h3>
                        <p class="text-xs text-slate-500 font-medium">Dados fundamentais da conta</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nome Completo</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->first_name }} {{ $user->last_name }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">E-mail</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->email }}</span>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Telefone</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $user->phone ?? 'Não informado' }}</span>
                    </div>
                </div>
            </div>

            <!-- Segurança & Acesso -->
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-xl space-y-6">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <x-icon name="shield-check" style="duotone" class="text-xl" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Segurança & Acesso</h3>
                        <p class="text-xs text-slate-500 font-medium">Mantenha sua conta protegida</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Status da Conta</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-sm font-black text-emerald-500 uppercase tracking-widest">Ativo & Verificado</span>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nível de Permissão</span>
                        <span class="text-sm font-black text-amber-500 uppercase tracking-widest">Super Admin</span>
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Último Login</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ now()->format('d/m/Y - H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-paneladmin::layouts.master>
