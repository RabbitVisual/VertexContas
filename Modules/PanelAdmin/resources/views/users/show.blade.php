@section('title', 'Detalhes do Usuário')

<x-paneladmin::layouts.master>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- User Profile Card -->
        <div class="col-span-1">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 text-center">
                <div class="h-24 w-24 bg-primary/10 rounded-full flex items-center justify-center text-3xl font-bold text-primary mx-auto mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $user->email }}</p>

                <div class="flex justify-center gap-2 mb-6">
                    @foreach($user->roles as $role)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-300">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 dark:border-gray-700 pt-6 text-left space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">ID:</span>
                        <span class="font-medium dark:text-white">{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Cadastro:</span>
                        <span class="font-medium dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Último Login:</span>
                        <span class="font-medium dark:text-white">
                            {{ $lastLogin ? $lastLogin->created_at->format('d/m/Y H:i') : 'Nunca' }}
                        </span>
                    </div>
                    @if($lastLogin)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Último IP:</span>
                            <span class="font-medium dark:text-white">{{ $lastLogin->ip_address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Manage Role -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mt-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Gerenciar Papel/Plano</h3>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role do Usuário</label>
                        <select name="role" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white">
                            <option value="free_user" {{ $user->hasRole('free_user') ? 'selected' : '' }}>Free User</option>
                            <option value="pro_user" {{ $user->hasRole('pro_user') ? 'selected' : '' }}>Pro User</option>
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                            <option value="support" {{ $user->hasRole('support') ? 'selected' : '' }}>Support</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium">
                        Atualizar Role
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats & Activity -->
        <div class="col-span-2 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-1">Saldo Total</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($totalBalance, 2, ',', '.') }}</div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-1">Contas</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $accountCount }}</div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="text-gray-500 text-xs uppercase font-bold tracking-wider mb-1">Transações</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $transactionCount }}</div>
                </div>
            </div>

            @if(isset($supportStats))
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <x-icon name="headset" class="text-primary" />
                        Performance Técnico de Suporte
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div>
                                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Chamados Encerrados</div>
                                <div class="text-3xl font-black text-gray-900 dark:text-white">{{ $supportStats['closed_tickets'] }}</div>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                                <x-icon name="check-circle" style="solid" class="text-xl" />
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div>
                                <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Avaliação Média</div>
                                <div class="flex items-center gap-2">
                                    <span class="text-3xl font-black text-amber-500">{{ $supportStats['avg_rating'] }}</span>
                                    <x-icon name="star" style="solid" class="text-amber-400 text-xl" />
                                </div>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center">
                                <x-icon name="chart-simple" style="solid" class="text-xl" />
                            </div>
                        </div>
                    </div>

                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-widest">Últimas Avaliações</h4>
                    <div class="space-y-3">
                        @forelse($supportStats['recent_ratings'] as $rating)
                            <div class="p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="flex items-center gap-1">
                                        @for($i=1; $i<=5; $i++)
                                            <x-icon name="star" style="solid" class="w-3 h-3 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600' }}" />
                                        @endfor
                                        <span class="text-xs font-bold ml-2 text-gray-900 dark:text-white">Ticket #{{ $rating->id }}</span>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $rating->updated_at->diffForHumans() }}</span>
                                </div>
                                @if($rating->rating_comment)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">"{{ $rating->rating_comment }}"</p>
                                @else
                                    <p class="text-xs text-gray-400 italic">Sem comentário escrito.</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">Nenhuma avaliação registrada ainda.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Future: Activity Log Table or Detailed List could go here -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 dark:bg-slate-700 rounded-full mb-3">
                    <x-icon name="chart-line" class="w-6 h-6 text-gray-400" />
                </div>
                <h3 class="text-gray-900 dark:text-white font-medium mb-1">Histórico de Atividades</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Implementação futura: listagem detalhada de ações do usuário.
                </p>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
