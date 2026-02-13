@extends('paneladmin::layouts.master')

@section('title', 'Central de Avisos')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
            <x-icon name="bullhorn" style="solid" class="text-primary" />
            Central de Avisos
        </h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl p-6 border border-gray-100 dark:border-gray-700">
        <form action="{{ route('admin.notifications.send') }}" method="POST" x-data="notificationForm()">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Content Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        Conteúdo da Mensagem
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" name="title" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary" placeholder="Ex: Manutenção Programada" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensagem</label>
                        <textarea name="message" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary" placeholder="Digite sua mensagem aqui..." required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Alerta</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="info" class="peer sr-only" checked>
                                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 text-center hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                                    <x-icon name="circle-info" class="w-6 h-6 mx-auto mb-1 text-blue-500" />
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Info</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="success" class="peer sr-only">
                                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 text-center hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                                    <x-icon name="circle-check" class="w-6 h-6 mx-auto mb-1 text-emerald-500" />
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Sucesso</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="warning" class="peer sr-only">
                                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 text-center hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                                    <x-icon name="triangle-exclamation" class="w-6 h-6 mx-auto mb-1 text-amber-500" />
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Aviso</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="danger" class="peer sr-only">
                                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 text-center hover:bg-gray-50 dark:hover:bg-slate-700 transition-all">
                                    <x-icon name="circle-xmark" class="w-6 h-6 mx-auto mb-1 text-red-500" />
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Erro</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Audience Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        Público Alvo
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quem deve receber?</label>
                        <select name="audience" x-model="audience" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white focus:ring-primary focus:border-primary">
                            <option value="all">📢 Todos os Usuários do Sistema</option>
                            <option value="role">👥 Grupo Específico (Role)</option>
                            <option value="user">👤 Usuário Individual</option>
                        </select>
                    </div>

                    <!-- Role Selector -->
                    <div x-show="audience === 'role'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Selecione o Grupo</label>
                        <select name="role" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User Search -->
                    <div x-show="audience === 'user'" x-transition class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar Usuário</label>
                        <div class="relative">
                            <input type="text"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="searchUsers()"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white pl-10 focus:ring-primary focus:border-primary"
                                   placeholder="Nome, E-mail ou CPF...">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                <x-icon name="magnifying-glass" class="w-4 h-4" />
                            </span>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div x-show="users.length > 0" @click.away="users = []" class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="user in users" :key="user.id">
                                <div @click="selectUser(user)" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="user.name"></p>
                                    <p class="text-xs text-gray-500" x-text="user.email"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Selected User Display -->
                        <div x-show="selectedUser" class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-blue-900 dark:text-blue-100">Usuário Selecionado:</p>
                                <p class="text-sm text-blue-800 dark:text-blue-200" x-text="selectedUser ? selectedUser.name : ''"></p>
                            </div>
                            <button type="button" @click="selectedUser = null; userId = ''" class="text-blue-500 hover:text-blue-700">
                                <x-icon name="xmark" class="w-4 h-4" />
                            </button>
                        </div>

                        <input type="hidden" name="user_id" x-model="userId">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-lg shadow transition-all transform hover:scale-105 flex items-center gap-2">
                    <x-icon name="paper-plane" class="w-4 h-4" />
                    Enviar Notificação Agora
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function notificationForm() {
        return {
            audience: 'all',
            searchQuery: '',
            users: [],
            selectedUser: null,
            userId: '',

            searchUsers() {
                if (this.searchQuery.length < 2) {
                    this.users = [];
                    return;
                }

                fetch(`{{ route('admin.notifications.search') }}?term=${this.searchQuery}`)
                    .then(res => res.json())
                    .then(data => {
                        this.users = data;
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
@endsection
