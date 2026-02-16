<x-panelsuporte::layouts.master>
    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 animate-in fade-in duration-500">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('support.tickets.index') }}" class="p-3 bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 rounded-2xl text-gray-400 hover:text-primary transition-all">
                    <x-icon name="arrow-left" style="duotone" />
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">User X-Ray</h1>
                    <p class="text-sm text-slate-500 font-medium tracking-tight">Visualização detalhada do usuário sob autorização temporária.</p>
                </div>
            </div>

            <a href="{{ route('support.users.edit', $user) }}" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all uppercase tracking-widest gap-2">
                <x-icon name="user-pen" style="duotone" class="w-4 h-4" />
                Editar Dados
            </a>
        </div>

        {{-- User Card Header --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden mb-6">
            <div class="h-32 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 relative">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>
            <div class="px-8 pb-6 relative -mt-16">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
                    <div class="h-28 w-28 bg-white dark:bg-slate-900 rounded-2xl p-2 shadow-2xl ring-4 ring-white dark:ring-slate-900 flex-shrink-0 overflow-hidden">
                        @if($user->photo)
                            <img src="{{ asset('storage/'.$user->photo) }}" class="h-full w-full rounded-xl object-cover">
                        @else
                            <div class="h-full w-full bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-3xl font-black text-primary/30 uppercase">
                                {{ substr($user->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="md:mb-2 text-center md:text-left">
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2">
                            @if($user->hasRole('pro_user'))
                                <span class="px-3 py-1 bg-amber-400 text-slate-900 text-[10px] font-black uppercase tracking-widest rounded-lg">Membro PRO</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-lg">Membro Free</span>
                            @endif
                            <span class="text-sm text-slate-500 font-bold">{{ lgpd_mask_email($user->email) }}</span>
                            @if(($financialSnapshot['free_cashflow'] ?? 0) < 0)
                                <span class="px-2 py-0.5 bg-rose-500/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-lg">Risco Financeiro</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flowbite Tabs --}}
        <div class="mb-4">
            <div class="border-b border-gray-200 dark:border-gray-700" data-tabs-toggle="#user-xray-tab-contents">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                    <li class="me-2" role="presentation">
                        <button id="tab-overview-trigger" class="inline-block p-4 border-b-2 rounded-t-lg text-primary border-primary" type="button" role="tab" data-tabs-target="#tab-overview" aria-controls="tab-overview" aria-selected="true"
                                data-tabs-active-classes="text-primary border-primary dark:text-primary dark:border-primary"
                                data-tabs-inactive-classes="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 border-transparent hover:border-gray-300 dark:hover:border-gray-600">
                            <span class="flex items-center gap-2">
                                <x-icon name="circle-info" style="duotone" class="w-4 h-4" />
                                Visão Geral
                            </span>
                        </button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" type="button" role="tab" data-tabs-target="#tab-activities" aria-controls="tab-activities" aria-selected="false"
                                data-tabs-active-classes="text-primary border-primary dark:text-primary dark:border-primary"
                                data-tabs-inactive-classes="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 border-transparent hover:border-gray-300 dark:hover:border-gray-600">
                            <span class="flex items-center gap-2">
                                <x-icon name="clock-rotate-left" style="duotone" class="w-4 h-4" />
                                Log de Atividades
                            </span>
                        </button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" type="button" role="tab" data-tabs-target="#tab-security" aria-controls="tab-security" aria-selected="false"
                                data-tabs-active-classes="text-primary border-primary dark:text-primary dark:border-primary"
                                data-tabs-inactive-classes="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 border-transparent hover:border-gray-300 dark:hover:border-gray-600">
                            <span class="flex items-center gap-2">
                                <x-icon name="shield-keyhole" style="duotone" class="w-4 h-4" />
                                Segurança
                            </span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div id="user-xray-tab-contents" class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-gray-800 overflow-hidden">
            {{-- Tab 1: Visão Geral --}}
            <div id="tab-overview" role="tabpanel" class="p-8 rounded-lg" aria-labelledby="tab-overview-trigger">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="id-card" style="duotone" class="text-primary" /> Informações Básicas
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nome Completo</span>
                                <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                            <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">E-mail</span>
                                <span class="text-base text-slate-900 dark:text-white font-bold">{{ lgpd_mask_email($user->email) }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">CPF</span>
                                    <span class="text-base text-slate-900 dark:text-white font-bold">{{ lgpd_mask_cpf($user->cpf ?? null) }}</span>
                                </div>
                                <div class="bg-gray-50/50 dark:bg-slate-800/30 p-5 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nascimento</span>
                                    <span class="text-base text-slate-900 dark:text-white font-bold">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '—' }}</span>
                                </div>
                            </div>
                        </div>

                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="wallet" style="duotone" class="text-primary" /> Conexões Ativas
                        </h3>
                        @forelse($accounts ?? [] as $account)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $account->name }}</span>
                                    <span class="text-xs text-slate-500 block">{{ ucfirst($account->type ?? 'conta') }}</span>
                                </div>
                                <span class="font-black text-slate-900 dark:text-white">{{ format_currency($account->balance ?? 0) }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 p-4 rounded-xl bg-gray-50 dark:bg-slate-800/30">Nenhuma conta vinculada</p>
                        @endforelse
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="chart-pie" style="duotone" class="text-indigo-500" /> Snapshot Financeiro
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-500/5 border border-indigo-100 dark:border-indigo-500/10">
                                <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase block mb-1">Renda Mensal</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['monthly_income'] ?? 0) }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                                <span class="text-[10px] font-black text-slate-500 uppercase block mb-1">Saldo Total</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['account_balance'] ?? 0) }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                                <span class="text-[10px] font-black text-slate-500 uppercase block mb-1">Despesas (mês)</span>
                                <span class="text-lg font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['monthly_expenses'] ?? 0) }}</span>
                            </div>
                            <div class="p-4 rounded-2xl {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/5 border-emerald-200' : 'bg-rose-50 dark:bg-rose-500/5 border-rose-200' }} border">
                                <span class="text-[10px] font-black uppercase block mb-1">Fluxo Livre</span>
                                <span class="text-lg font-black {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ format_currency($financialSnapshot['free_cashflow'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10">
                            <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase block mb-1">Reserva (meses)</span>
                            <span class="text-lg font-black text-slate-900 dark:text-white">{{ format_number($reserveMonths ?? 0, 1) }} meses</span>
                        </div>

                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="file-contract" style="duotone" class="text-primary" /> Compliance
                        </h3>
                        <div class="space-y-2">
                            @forelse($complianceStatus ?? [] as $item)
                                <div class="flex items-center justify-between p-3 rounded-xl border {{ $item['is_up_to_date'] ? 'border-emerald-200 dark:border-emerald-500/30 bg-emerald-50/50 dark:bg-emerald-900/20' : 'border-rose-200 dark:border-rose-500/30 bg-rose-50/50 dark:bg-rose-900/20' }}">
                                    <div class="flex items-center gap-3">
                                        @if($item['is_up_to_date'])
                                            <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-[10px] font-black rounded-lg uppercase">
                                                <x-icon name="check" style="solid" class="w-3 h-3 inline" /> Em dia
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-rose-500/20 text-rose-700 dark:text-rose-400 text-[10px] font-black rounded-lg uppercase">
                                                <x-icon name="xmark" style="solid" class="w-3 h-3 inline" /> Desatualizado
                                            </span>
                                        @endif
                                        <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $item['document']->title }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500">Nenhum documento com exigência de aceite.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 2: Log de Atividades --}}
            <div id="tab-activities" role="tabpanel" class="p-8 rounded-lg hidden">
                <div class="space-y-8">
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="right-to-bracket" style="duotone" class="text-primary" /> Último Login
                        </h3>
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-4">
                                <x-icon name="clock" style="duotone" class="text-slate-400" />
                                <div>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '—' }}</span>
                                    <span class="text-xs text-slate-500 block">IP: {{ $user->last_login_ip ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="repeat" style="duotone" class="text-primary" /> Transações Recentes
                        </h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-slate-800/50 text-[10px] font-black text-slate-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Data</th>
                                        <th class="px-4 py-3 text-left">Descrição</th>
                                        <th class="px-4 py-3 text-right">Valor</th>
                                        <th class="px-4 py-3 text-center">Tipo</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @forelse($recentTransactions ?? [] as $tx)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/30">
                                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $tx->date?->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 font-medium text-slate-800 dark:text-white truncate max-w-[200px]">{{ $tx->description ?: '—' }}</td>
                                            <td class="px-4 py-3 text-right font-bold {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-slate-800 dark:text-white' }}">{{ format_currency($tx->amount) }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $tx->type === 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">{{ $tx->type === 'income' ? 'Receita' : 'Despesa' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Nenhuma transação</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="clipboard-list" style="duotone" class="text-primary" /> Histórico de Auditoria
                        </h3>
                        <div class="space-y-2">
                            @forelse($recentAuditForUser ?? [] as $audit)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                        <x-icon name="user-shield" style="duotone" class="w-4 h-4" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="font-bold text-slate-800 dark:text-white">{{ $audit->action }}</span>
                                        <span class="text-xs text-slate-500 block">Por {{ $audit->agent?->first_name ?? $audit->agent?->name ?? '-' }} · {{ isset($audit->created_at) ? \Carbon\Carbon::parse($audit->created_at)->diffForHumans() : '—' }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 p-4">Nenhum registro de auditoria.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab 3: Segurança --}}
            <div id="tab-security" role="tabpanel" class="p-8 rounded-lg hidden">
                <div class="space-y-8">
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="key" style="duotone" class="text-primary" /> Ações de Segurança
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            <form action="{{ route('support.users.send-password-reset', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary-dark transition-all">
                                    <x-icon name="envelope" style="duotone" class="w-4 h-4" />
                                    Enviar link de redefinição de senha
                                </button>
                            </form>
                            <form action="{{ route('support.users.logout-all', $user) }}" method="POST" class="inline" onsubmit="return confirm('Deslogar o usuário de todos os dispositivos?');">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-500 text-white font-bold text-sm rounded-xl hover:bg-rose-600 transition-all">
                                    <x-icon name="right-from-bracket" style="duotone" class="w-4 h-4" />
                                    Deslogar de todos os dispositivos
                                </button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <x-icon name="network-wired" style="duotone" class="text-primary" /> Log de IP
                        </h3>
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-slate-800/30 border border-gray-100 dark:border-gray-800">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-[10px] font-black text-slate-500 uppercase block mb-1">Último IP</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->last_login_ip ?? '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-slate-500 uppercase block mb-1">Data do último login</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-panelsuporte::layouts.master>
