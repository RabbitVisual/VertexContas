<x-paneladmin::layouts.master>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="notificationForm()">

        <!-- Header Section -->
        <div class="mb-10 flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none italic">
                    Nova Notificação
                </h1>
                <p class="text-sm text-slate-500 font-medium italic">Configure e dispare um alerta para seus usuários.</p>
            </div>
            <a href="{{ route('admin.notifications.index') }}" class="p-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-[#11C76F] transition-all shadow-sm">
                <x-icon name="arrow-left" class="text-xl" />
            </a>
        </div>

        <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-10">
            @csrf

            <!-- Form Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                <!-- Main Content (Left) -->
                <div class="lg:col-span-2 space-y-10">

                    <!-- Content Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center">
                                <x-icon name="pen-to-square" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic">Conteúdo do Alerta</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">O que o usuário vai visualizar</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Título da Mensagem</label>
                                <input type="text" name="title" x-model="title" placeholder="Ex: Manutenção no Sistema"
                                    class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all" required>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Mensagem Detalhada</label>
                                <textarea name="message" x-model="message" rows="4" placeholder="Escreva aqui o conteúdo da sua notificação..."
                                    class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Type Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                <x-icon name="palette" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic">Estilo & Urgência</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Defina a prioridade visual</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="t in types" :key="t.value">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" :value="t.value" x-model="type" class="peer sr-only">
                                    <div class="p-6 rounded-[2rem] border-2 border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-all group-hover:scale-105">
                                        <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center" :class="t.bg">
                                            <i class="fa-duotone text-xl" :class="'fa-' + t.icon + ' ' + t.color"></i>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest block" x-text="t.label"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (Audience & Preview) -->
                <div class="space-y-10">

                    <!-- Audience Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                <x-icon name="users" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic">Destinatários</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Quem receberá o aviso</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-2">Público do Disparo</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <!-- Todos -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="audience" value="all" x-model="audience" class="peer sr-only">
                                        <div class="p-6 rounded-[2rem] border-2 border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-all group-hover:scale-105">
                                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center bg-blue-500/10 text-blue-500">
                                                <x-icon name="bullhorn" class="text-xl" />
                                            </div>
                                            <span class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Geral</span>
                                        </div>
                                    </label>

                                    <!-- Grupos -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="audience" value="role" x-model="audience" class="peer sr-only">
                                        <div class="p-6 rounded-[2rem] border-2 border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-all group-hover:scale-105">
                                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center bg-amber-500/10 text-amber-500">
                                                <x-icon name="users" class="text-xl" />
                                            </div>
                                            <span class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Grupos</span>
                                        </div>
                                    </label>

                                    <!-- Individual -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="audience" value="user" x-model="audience" class="peer sr-only">
                                        <div class="p-6 rounded-[2rem] border-2 border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-all group-hover:scale-105">
                                            <div class="w-10 h-10 rounded-xl mx-auto mb-3 flex items-center justify-center bg-emerald-500/10 text-emerald-500">
                                                <x-icon name="user" class="text-xl" />
                                            </div>
                                            <span class="text-[10px] font-black text-slate-400 dark:text-slate-300 uppercase tracking-widest block">Individual</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Role Selector -->
                            <div x-show="audience === 'role'" class="space-y-2 py-4 bg-gray-50/50 dark:bg-white/[0.02] p-6 rounded-3xl border border-gray-100 dark:border-white/5" x-transition>
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-2">Escolha o Grupo</label>
                                <select name="role" class="w-full px-6 py-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-white/5 rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all appearance-none cursor-pointer">
                                    @foreach($roles as $role)
                                        @php
                                            $translatedRole = match($role->name) {
                                                'free_user', 'user' => 'Usuário Comum',
                                                'pro_user', 'pro' => 'Usuário VIP / Pro',
                                                'suporte' => 'Equipe de Suporte',
                                                'financeiro' => 'Setor Financeiro',
                                                default => ucfirst(str_replace(['_', '-'], ' ', $role->name))
                                            };
                                        @endphp
                                        <option value="{{ $role->name }}">{{ $translatedRole }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- User Search -->
                            <div x-show="audience === 'user'" class="space-y-4" x-transition>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-2">Buscar Usuário</label>
                                    <div class="relative">
                                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsers()"
                                            class="w-full px-6 py-4 bg-gray-50 dark:bg-white/5 border-none rounded-2xl focus:ring-4 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-black text-sm transition-all"
                                            placeholder="Nome, E-mail ou CPF...">
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2" x-show="loading">
                                            <x-icon name="spinner" class="text-slate-400 animate-spin" />
                                        </div>
                                    </div>
                                </div>

                                <div x-show="users.length > 0" class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl overflow-hidden">
                                    <template x-for="user in users" :key="user.id">
                                        <div @click="selectUser(user)" class="p-4 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer border-b border-gray-100 dark:border-white/5 last:border-0">
                                            <p class="text-xs font-black text-slate-900 dark:text-white" x-text="user.name"></p>
                                            <p class="text-[10px] text-slate-500" x-text="user.email"></p>
                                        </div>
                                    </template>
                                </div>

                                <div x-show="selectedUser" class="p-4 bg-[#11C76F]/5 border border-[#11C76F]/10 rounded-2xl flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[9px] font-black text-[#11C76F] uppercase tracking-widest leading-none">Selecionado</span>
                                        <span class="text-xs font-black text-slate-900 dark:text-white leading-none mt-1" x-text="selectedUser?.name"></span>
                                    </div>
                                    <button type="button" @click="selectedUser = null; userId = ''" class="text-slate-400 hover:text-red-500 transition-colors">
                                        <x-icon name="xmark" />
                                    </button>
                                </div>
                                <input type="hidden" name="user_id" x-model="userId">
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" class="w-full py-8 bg-[#11C76F] text-white font-black rounded-[2.5rem] shadow-2xl shadow-[#11C76F]/30 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-sm uppercase tracking-[0.3em] flex items-center justify-center gap-3">
                        <x-icon name="paper-plane" class="text-xl" />
                        Disparar Alerta
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function notificationForm() {
            return {
                title: @json($template->title ?? ''),
                message: @json($template->message ?? ''),
                type: @json($template->type ?? 'info'),
                audience: 'all',
                searchQuery: '',
                users: [],
                selectedUser: null,
                userId: '',
                loading: false,

                types: [
                    { value: 'info', label: 'Informativo', icon: 'circle-info', bg: 'bg-blue-500/10', color: 'text-blue-500' },
                    { value: 'success', label: 'Sucesso', icon: 'circle-check', bg: 'bg-emerald-500/10', color: 'text-emerald-500' },
                    { value: 'warning', label: 'Atenção', icon: 'triangle-exclamation', bg: 'bg-amber-500/10', color: 'text-amber-500' },
                    { value: 'danger', label: 'Crítico', icon: 'circle-xmark', bg: 'bg-red-500/10', color: 'text-red-500' }
                ],

                searchUsers() {
                    if (this.searchQuery.length < 2) {
                        this.users = [];
                        return;
                    }
                    this.loading = true;
                    fetch(`{{ route('admin.notifications.search') }}?term=${this.searchQuery}`)
                        .then(res => res.json())
                        .then(data => {
                            this.users = data;
                            this.loading = false;
                        });
                },

                selectUser(user) {
                    this.selectedUser = user;
                    this.userId = user.id;
                    this.users = [];
                    this.searchQuery = '';
                }
            }
        }
    </script>
</x-paneladmin::layouts.master>
