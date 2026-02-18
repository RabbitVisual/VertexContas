<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Detalhes do Usuário</x-slot>

    <x-paneladmin::page title="Detalhes do Usuário" subtitle="{{ $user->name }} — {{ $user->email }}">
        <x-slot name="header">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" />
                Voltar
            </a>
        </x-slot>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Coluna esquerda: Perfil + Gerenciar --}}
            <div class="space-y-6">
                <x-paneladmin::card>
                    <div class="p-6">
                        <div class="flex flex-col items-center text-center">
                            @if($user->photo)
                                <img src="{{ $user->photo_url }}" alt="" class="h-24 w-24 rounded-xl object-cover border border-slate-200 dark:border-slate-600 mb-4">
                            @else
                                <div class="h-24 w-24 rounded-xl bg-[#11C76F]/10 flex items-center justify-center text-[#11C76F] mb-4">
                                    <x-icon name="user" style="duotone" class="w-12 h-12" />
                                </div>
                            @endif
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $user->name }}</h2>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5">{{ $user->email }}</p>
                            <div class="flex flex-wrap justify-center gap-2 mt-4">
                                @foreach($user->roles as $role)
                                    @php
                                        $color = match($role->name) {
                                            'admin' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-700',
                                            'pro_user' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-700',
                                            'free_user' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-700',
                                            'support' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-700',
                                            default => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600'
                                        };
                                        $icon = match($role->name) {
                                            'admin' => 'shield-keyhole',
                                            'pro_user' => 'crown',
                                            'free_user' => 'user',
                                            'support' => 'headset',
                                            default => 'user'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $color }}">
                                        <x-icon name="{{ $icon }}" style="duotone" class="w-3.5 h-3.5 mr-1.5" />
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="border-t border-slate-100 dark:border-slate-700 mt-6 pt-6 space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <x-icon name="fingerprint" style="duotone" class="w-4 h-4" /> ID
                                </span>
                                <span class="font-medium text-slate-900 dark:text-white tabular-nums">{{ $user->id }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <x-icon name="calendar-plus" style="duotone" class="w-4 h-4" /> Cadastro
                                </span>
                                <span class="font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <x-icon name="clock-rotate-left" style="duotone" class="w-4 h-4" /> Último login
                                </span>
                                <span class="font-medium text-slate-900 dark:text-white">
                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                </span>
                            </div>
                            @if($user->last_login_ip)
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                        <x-icon name="network-wired" style="duotone" class="w-4 h-4" /> Último IP
                                    </span>
                                    <span class="font-medium text-slate-900 dark:text-white font-mono text-xs">{{ $user->last_login_ip }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-paneladmin::card>

                <x-paneladmin::card title="Gerenciar função" subtitle="Altere o papel do usuário no sistema.">
                    <div class="p-6">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Função</label>
                                <select name="role" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F] py-2.5 px-4">
                                    <option value="free_user" {{ $user->hasRole('free_user') ? 'selected' : '' }}>Free User</option>
                                    <option value="pro_user" {{ $user->hasRole('pro_user') ? 'selected' : '' }}>Pro User</option>
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                    <option value="support" {{ $user->hasRole('support') ? 'selected' : '' }}>Suporte</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-2.5 bg-[#11C76F] text-white rounded-xl hover:bg-[#0EA85A] transition-colors text-sm font-bold flex items-center justify-center gap-2">
                                <x-icon name="floppy-disk" style="duotone" class="w-4 h-4" />
                                Atualizar função
                            </button>
                        </form>
                    </div>
                </x-paneladmin::card>
            </div>

            {{-- Coluna direita: Plano, Permissões, Assinatura, Financeiro, Suporte --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Plano e limites --}}
                <x-paneladmin::card title="Plano atual" subtitle="Limites do plano vinculado ao usuário.">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-[#11C76F]/10 flex items-center justify-center">
                                <x-icon name="layer-group" style="duotone" class="w-6 h-6 text-[#11C76F]" />
                            </div>
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-lg">{{ $plan->name }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $plan->is_free ? 'Plano gratuito' : 'Plano pago' }}
                                    · {{ $plan->billing_interval === 'yearly' ? 'Cobrança anual' : 'Cobrança mensal' }}
                                    @if(!$plan->is_free && $plan->amount)
                                        · {{ format_currency($plan->amount, $plan->currency ?? 'R$') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                            @foreach(['account' => 'Contas', 'income' => 'Receitas', 'expense' => 'Despesas', 'goal' => 'Metas', 'budget' => 'Orçamentos', 'category' => 'Categorias'] as $entity => $label)
                                @php $lim = $plan->getLimit($entity); @endphp
                                <div class="px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600">
                                    <span class="text-slate-500 dark:text-slate-400 block text-xs font-bold uppercase">{{ $label }}</span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $lim === 'unlimited' ? 'Ilimitado' : $lim }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-paneladmin::card>

                {{-- Permissões --}}
                <x-paneladmin::card title="Permissões" subtitle="Permissões efetivas (via funções).">
                    <div class="p-6">
                        @if($permissions->isNotEmpty())
                            <ul class="flex flex-wrap gap-2">
                                @foreach($permissions->take(24) as $perm)
                                    <li class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                        <x-icon name="key" style="duotone" class="w-3 h-3 mr-1.5 text-slate-500" />
                                        {{ $perm->name }}
                                    </li>
                                @endforeach
                            </ul>
                            @if($permissions->count() > 24)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">+ {{ $permissions->count() - 24 }} outras.</p>
                            @endif
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <x-icon name="key" style="duotone" class="w-4 h-4" />
                                Nenhuma permissão direta além da função.
                            </p>
                        @endif
                    </div>
                </x-paneladmin::card>

                {{-- Assinatura --}}
                <x-paneladmin::card title="Assinatura" subtitle="Status da assinatura recorrente (gateway).">
                    <div class="p-6">
                        @if($activeSubscription)
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700">
                                <x-icon name="circle-check" style="duotone" class="w-8 h-8 text-emerald-600 dark:text-emerald-400 shrink-0" />
                                <div>
                                    <div class="font-bold text-emerald-800 dark:text-emerald-200">Ativa</div>
                                    <div class="text-sm text-emerald-600 dark:text-emerald-400">
                                        {{ format_currency($activeSubscription->amount, $activeSubscription->currency ?? 'R$') }}/mês
                                        · Gateway: {{ $activeSubscription->gateway_slug }}
                                    </div>
                                    @if($activeSubscription->current_period_end)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                            Próxima cobrança: {{ $activeSubscription->current_period_end->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                <x-icon name="rectangle-xmark" style="duotone" class="w-4 h-4" />
                                Sem assinatura ativa.
                            </p>
                        @endif
                    </div>
                </x-paneladmin::card>

                {{-- Resumo financeiro --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <x-paneladmin::card>
                        <div class="p-6 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <x-icon name="wallet" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Saldo total</p>
                                <p class="text-xl font-bold text-slate-900 dark:text-white tabular-nums">{{ format_currency($financialSnapshot['account_balance'] ?? 0) }}</p>
                            </div>
                        </div>
                    </x-paneladmin::card>
                    <x-paneladmin::card>
                        <div class="p-6 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <x-icon name="building-columns" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Contas</p>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $accountCount }}</p>
                            </div>
                        </div>
                    </x-paneladmin::card>
                    <x-paneladmin::card>
                        <div class="p-6 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                <x-icon name="arrows-left-right" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Transações</p>
                                <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $transactionCount }}</p>
                            </div>
                        </div>
                    </x-paneladmin::card>
                </div>

                {{-- Saúde financeira --}}
                <x-paneladmin::card title="Saúde financeira" subtitle="Renda e saldo declarados.">
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Renda mensal declarada</span>
                                <div class="text-xl font-bold text-slate-900 dark:text-white tabular-nums mt-1">{{ format_currency($financialSnapshot['monthly_income'] ?? 0) }}</div>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Saldo em contas</span>
                                <div class="text-xl font-bold text-slate-900 dark:text-white tabular-nums mt-1">{{ format_currency($financialSnapshot['account_balance'] ?? 0) }}</div>
                            </div>
                        </div>
                        @if(($financialSnapshot['monthly_income'] ?? 0) > 0)
                            <span class="inline-flex items-center mt-4 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700">
                                <x-icon name="chart-line" style="duotone" class="w-3.5 h-3.5 mr-2" />
                                Renda ativa
                            </span>
                        @endif
                    </div>
                </x-paneladmin::card>

                @if($user->hasRole('support') && isset($supportStats))
                    <x-paneladmin::card title="Performance de suporte" subtitle="Chamados e avaliações.">
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 flex items-center justify-between">
                                    <div>
                                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Chamados encerrados</div>
                                        <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $supportStats['closed_tickets'] }}</div>
                                    </div>
                                    <x-icon name="circle-check" style="duotone" class="w-10 h-10 text-emerald-500" />
                                </div>
                                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-600 flex items-center justify-between">
                                    <div>
                                        <div class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Avaliação média</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl font-bold text-amber-500">{{ $supportStats['avg_rating'] }}</span>
                                            <x-icon name="star" style="duotone" class="w-6 h-6 text-amber-400" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Últimas avaliações</h4>
                            <div class="space-y-3">
                                @forelse($supportStats['recent_ratings'] as $rating)
                                    <div class="p-3 rounded-lg border border-slate-100 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <x-icon name="star" style="duotone" class="w-3.5 h-3.5 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" />
                                                @endfor
                                                <span class="text-xs font-bold ml-2 text-slate-900 dark:text-white">Ticket #{{ $rating->id }}</span>
                                            </div>
                                            <span class="text-[10px] text-slate-400">{{ $rating->updated_at->diffForHumans() }}</span>
                                        </div>
                                        @if($rating->rating_comment)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 italic">"{{ $rating->rating_comment }}"</p>
                                        @else
                                            <p class="text-xs text-slate-400 italic">Sem comentário.</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Nenhuma avaliação registrada.</p>
                                @endforelse
                            </div>
                        </div>
                    </x-paneladmin::card>
                @endif

                {{-- Atividade (placeholder) --}}
                <x-paneladmin::card title="Histórico de atividades" subtitle="Implementação futura.">
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-700 mb-4">
                            <x-icon name="clock-rotate-left" style="duotone" class="w-7 h-7 text-slate-400" />
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 text-sm">Listagem detalhada de ações do usuário em desenvolvimento.</p>
                    </div>
                </x-paneladmin::card>
            </div>
        </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
