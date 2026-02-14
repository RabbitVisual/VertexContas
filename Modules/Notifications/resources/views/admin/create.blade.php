<x-paneladmin::layouts.master>
    <div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="notificationForm()">

        <!-- Header Section -->
        <div class="mb-10 flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none italic uppercase">
                    Administrar Disparos
                </h1>
                <p class="text-sm text-slate-500 font-medium italic">Configure e envie alertas inteligentes para sua base de usuários.</p>
            </div>
            <a href="{{ route('admin.notifications.index') }}" class="w-12 h-12 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-white/5 text-slate-400 hover:text-[#11C76F] hover:shadow-lg transition-all flex items-center justify-center group active:scale-95 shadow-sm">
                <x-icon name="arrow-left" class="text-xl group-hover:-translate-x-1 transition-transform" />
            </a>
        </div>

        <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Main Content (Left: 8 cols) -->
                <div class="lg:col-span-8 space-y-8">

                    <!-- Content Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#11C76F]/10 text-[#11C76F] flex items-center justify-center">
                                <x-icon name="pen-to-square" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic uppercase">Mensagem do Alerta</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Defina o título e o corpo do aviso</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Título Principal</label>
                                <input type="text" name="title" x-model="title" placeholder="Ex: Manutenção agendada"
                                    class="w-full px-6 py-4 bg-gray-50/50 dark:bg-white/[0.02] border border-transparent focus:border-[#11C76F]/20 rounded-2xl focus:ring-0 text-slate-800 dark:text-white font-bold text-base transition-all" required>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] ml-4">Conteúdo Detalhado</label>
                                <textarea name="message" x-model="message" rows="5" placeholder="Digite aqui o texto que o usuário irá ler..."
                                    class="w-full px-6 py-4 bg-gray-50/50 dark:bg-white/[0.02] border border-transparent focus:border-[#11C76F]/20 rounded-2xl focus:ring-0 text-slate-800 dark:text-white font-medium text-sm transition-all leading-relaxed" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Type Selector Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                                <x-icon name="palette" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic uppercase">Estilo da Prioridade</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Como a notificação aparecerá visualmente</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <template x-for="t in types" :key="t.value">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" :value="t.value" x-model="type" class="peer sr-only">
                                    <div class="p-5 rounded-[2rem] border-2 border-gray-50 dark:border-white/5 bg-gray-50/30 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-all group-hover:scale-105 active:scale-95">
                                        <div class="w-12 h-12 rounded-xl mx-auto mb-3 flex items-center justify-center transition-transform shadow-sm" :class="t.bg">
                                            <i class="fa-duotone text-xl" :class="'fa-' + t.icon + ' ' + t.color"></i>
                                        </div>
                                        <span class="text-[9px] font-black text-slate-500 dark:text-slate-300 uppercase tracking-[0.1em] block" x-text="t.label"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Settings Sidebar (Right: 4 cols) -->
                <div class="lg:col-span-4 space-y-8 sticky top-8">

                    <!-- Audience Selection Card -->
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-2xl space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                                <x-icon name="bullseye" style="duotone" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-tight italic uppercase">Público Alvo</h3>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mt-0.5">Quem deve receber?</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 gap-3">
                                @php
                                    $audiences = [
                                        ['val' => 'all', 'label' => 'Geral', 'icon' => 'bullhorn', 'color' => 'blue'],
                                        ['val' => 'role', 'label' => 'Grupos', 'icon' => 'users', 'color' => 'amber'],
                                        ['val' => 'user', 'label' => 'Individual', 'icon' => 'user', 'color' => 'emerald'],
                                    ];
                                @endphp
                                @foreach($audiences as $aud)
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="audience" value="{{ $aud['val'] }}" x-model="audience" class="peer sr-only">
                                        <div class="px-5 py-4 rounded-2xl border-2 border-gray-50 dark:border-white/5 bg-gray-50/20 dark:bg-transparent peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 transition-all flex items-center gap-4 group-hover:bg-gray-50 dark:group-hover:bg-white/5">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-{{ $aud['color'] }}-500/10 text-{{ $aud['color'] }}-500">
                                                <x-icon name="{{ $aud['icon'] }}" class="text-lg" />
                                            </div>
                                            <span class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">{{ $aud['label'] }}</span>
                                            <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <x-icon name="circle-check" class="text-[#11C76F]" />
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Special Selectors (Conditional) -->
                            <div x-show="audience === 'role'" class="space-y-3 pt-4 border-t border-gray-50 dark:border-white/5" x-transition>
                                <label class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Selecione o Grupo</label>
                                <select name="role" class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl text-slate-800 dark:text-white font-bold text-xs cursor-pointer focus:ring-2 focus:ring-[#11C76F]/20">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ match($role->name) {
                                                'free_user', 'user' => 'Usuário Comum',
                                                'pro_user', 'pro' => 'Usuário VIP / Pro',
                                                'suporte' => 'Equipe de Suporte',
                                                'financeiro' => 'Setor Financeiro',
                                                default => ucfirst(str_replace(['_', '-'], ' ', $role->name))
                                            } }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="audience === 'user'" class="space-y-4 pt-4 border-t border-gray-50 dark:border-white/5" x-transition>
                                <div class="relative">
                                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsers()"
                                        class="w-full px-5 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-2xl text-slate-800 dark:text-white font-bold text-xs"
                                        placeholder="Pesquise por Nome/Email...">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2" x-show="loading">
                                        <x-icon name="spinner" class="text-[#11C76F] animate-spin text-sm" />
                                    </div>
                                </div>
                                <div x-show="users.length > 0" class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl overflow-hidden max-h-48 overflow-y-auto">
                                    <template x-for="user in users" :key="user.id">
                                        <div @click="selectUser(user)" class="p-3 hover:bg-[#11C76F]/5 cursor-pointer border-b border-gray-50 dark:border-white/5 last:border-0 transition-colors">
                                            <p class="text-[11px] font-black text-slate-900 dark:text-white" x-text="user.name"></p>
                                            <p class="text-[9px] text-slate-500" x-text="user.email"></p>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="selectedUser" class="p-4 bg-[#11C76F]/5 border border-[#11C76F]/20 rounded-2xl flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] font-black text-[#11C76F] uppercase tracking-widest">Selecionado</span>
                                        <span class="text-[11px] font-black text-slate-800 dark:text-white mt-1" x-text="selectedUser?.name"></span>
                                    </div>
                                    <button type="button" @click="selectedUser = null; userId = ''" class="text-red-500 hover:scale-110 px-2 transition-transform">
                                        <x-icon name="xmark" />
                                    </button>
                                </div>
                                <input type="hidden" name="user_id" x-model="userId">
                            </div>
                        </div>
                    </div>

                    <!-- Final Action Card -->
                    <div class="bg-slate-900 dark:bg-[#11C76F]/5 p-8 rounded-[2.5rem] border border-[#11C76F]/20 shadow-2xl space-y-6">
                        <button type="submit" class="w-full py-5 bg-[#11C76F] text-white font-black rounded-2xl shadow-xl shadow-[#11C76F]/20 hover:bg-[#0EA85A] hover:-translate-y-1 active:scale-95 transition-all text-sm uppercase tracking-[0.2em] flex items-center justify-center gap-3 group">
                            <x-icon name="paper-plane" class="text-lg group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
                            Disparar Agora
                        </button>
                        <p class="text-[9px] text-center text-slate-500 font-bold uppercase tracking-widest">Confirme os dados antes de enviar</p>
                    </div>
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
