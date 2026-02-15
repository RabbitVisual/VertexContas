<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Nova Notificação</x-slot>

    <div x-data="notificationForm()">
        <x-paneladmin::page title="Nova Notificação" subtitle="Configure e envie alertas para a base de usuários.">
            <x-slot name="header">
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white">
                    <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
                </a>
            </x-slot>

            <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    <div class="lg:col-span-8 space-y-6">
                        <x-paneladmin::card title="Mensagem do Alerta" subtitle="Título e corpo do aviso.">
                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Título</label>
                                    <input type="text" name="title" x-model="title" placeholder="Ex: Manutenção agendada" required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Conteúdo</label>
                                    <textarea name="message" x-model="message" rows="5" placeholder="Texto que o usuário irá ler..." required
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]"></textarea>
                                </div>
                            </div>
                        </x-paneladmin::card>

                        <x-paneladmin::card title="Estilo da Notificação" subtitle="Tipo visual e prioridade.">
                            <div class="p-6">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    @php
                                        $typeOptions = [
                                            ['value' => 'info', 'label' => 'Informativo', 'icon' => 'circle-info', 'bg' => 'bg-blue-500/10', 'color' => 'text-blue-500'],
                                            ['value' => 'success', 'label' => 'Sucesso', 'icon' => 'circle-check', 'bg' => 'bg-emerald-500/10', 'color' => 'text-emerald-500'],
                                            ['value' => 'warning', 'label' => 'Atenção', 'icon' => 'triangle-exclamation', 'bg' => 'bg-amber-500/10', 'color' => 'text-amber-500'],
                                            ['value' => 'danger', 'label' => 'Crítico', 'icon' => 'circle-xmark', 'bg' => 'bg-red-500/10', 'color' => 'text-red-500'],
                                        ];
                                    @endphp
                                    @foreach($typeOptions as $t)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type" value="{{ $t['value'] }}" x-model="type" class="peer sr-only">
                                            <div class="p-4 rounded-xl border-2 border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30 peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 text-center transition-colors hover:border-slate-200 dark:hover:border-slate-600">
                                                <div class="w-10 h-10 rounded-xl mx-auto mb-2 flex items-center justify-center {{ $t['bg'] }}">
                                                    <x-icon name="{{ $t['icon'] }}" style="duotone" class="w-5 h-5 {{ $t['color'] }}" />
                                                </div>
                                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 block">{{ $t['label'] }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </x-paneladmin::card>
                    </div>

                    <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-8">
                        <x-paneladmin::card title="Público Alvo" subtitle="Quem deve receber a notificação.">
                            <div class="p-6 space-y-4">
                                @php
                                    $audiences = [
                                        ['val' => 'all', 'label' => 'Geral', 'icon' => 'bullhorn', 'box' => 'bg-blue-500/10 text-blue-500'],
                                        ['val' => 'role', 'label' => 'Grupos', 'icon' => 'users', 'box' => 'bg-amber-500/10 text-amber-500'],
                                        ['val' => 'user', 'label' => 'Individual', 'icon' => 'user', 'box' => 'bg-emerald-500/10 text-emerald-500'],
                                    ];
                                @endphp
                                @foreach($audiences as $aud)
                                    <label class="cursor-pointer block">
                                        <input type="radio" name="audience" value="{{ $aud['val'] }}" x-model="audience" class="peer sr-only">
                                        <div class="px-4 py-3 rounded-xl border-2 border-slate-100 dark:border-slate-700 peer-checked:border-[#11C76F] peer-checked:bg-[#11C76F]/5 transition-all flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $aud['box'] }}">
                                                <x-icon name="{{ $aud['icon'] }}" style="duotone" class="w-4 h-4" />
                                            </div>
                                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $aud['label'] }}</span>
                                            <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <x-icon name="circle-check" style="duotone" class="w-5 h-5 text-[#11C76F]" />
                                            </div>
                                        </div>
                                    </label>
                                @endforeach

                                <div x-show="audience === 'role'" class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-2" x-transition>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Grupo</label>
                                    <select name="role" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
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

                                <div x-show="audience === 'user'" class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-3" x-transition>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Buscar usuário</label>
                                    <div class="relative">
                                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsers()"
                                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]"
                                            placeholder="Nome ou e-mail...">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2" x-show="loading">
                                            <x-icon name="spinner" style="duotone" class="text-[#11C76F] animate-spin w-4 h-4" />
                                        </div>
                                    </div>
                                    <div x-show="users.length > 0" class="rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 overflow-hidden max-h-48 overflow-y-auto">
                                        <template x-for="user in users" :key="user.id">
                                            <div @click="selectUser(user)" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer border-b border-slate-100 dark:border-slate-700 last:border-0">
                                                <p class="text-sm font-medium text-slate-900 dark:text-white" x-text="user.name"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="user.email"></p>
                                            </div>
                                        </template>
                                    </div>
                                    <div x-show="selectedUser" class="p-3 bg-[#11C76F]/10 border border-[#11C76F]/20 rounded-xl flex items-center justify-between">
                                        <div>
                                            <span class="text-xs font-bold text-[#11C76F] uppercase">Selecionado</span>
                                            <p class="text-sm font-medium text-slate-900 dark:text-white mt-0.5" x-text="selectedUser?.name"></p>
                                        </div>
                                        <button type="button" @click="selectedUser = null; userId = ''" class="p-2 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                                            <x-icon name="xmark" style="duotone" class="w-4 h-4" />
                                        </button>
                                    </div>
                                    <input type="hidden" name="user_id" x-model="userId">
                                </div>
                            </div>
                        </x-paneladmin::card>

                        <x-paneladmin::card>
                            <div class="p-6 space-y-4">
                                <button type="submit" class="w-full py-3 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors flex items-center justify-center gap-2">
                                    <x-icon name="paper-plane" style="duotone" class="w-5 h-5" />
                                    Disparar Agora
                                </button>
                                <p class="text-xs text-center text-slate-500 dark:text-slate-400">Confirme os dados antes de enviar.</p>
                            </div>
                        </x-paneladmin::card>
                    </div>
                </div>
            </form>
        </x-paneladmin::page>
    </div>

    <script>
        function notificationForm() {
            return {
                title: @json(optional($template ?? null)->title ?? ''),
                message: @json(optional($template ?? null)->message ?? ''),
                type: @json(optional($template ?? null)->type ?? 'info'),
                audience: 'all',
                searchQuery: '',
                users: [],
                selectedUser: null,
                userId: '',
                loading: false,
                searchUsers() {
                    if (this.searchQuery.length < 2) { this.users = []; return; }
                    this.loading = true;
                    fetch(`{{ route('admin.notifications.search') }}?term=${this.searchQuery}`)
                        .then(res => res.json())
                        .then(data => { this.users = data; this.loading = false; });
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
