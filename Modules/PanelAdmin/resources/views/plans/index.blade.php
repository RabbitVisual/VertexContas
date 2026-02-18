<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Planos</x-slot>

    <x-paneladmin::page title="Planos" subtitle="Gerencie planos e limites de forma centralizada.">
        <x-slot name="header">
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                <x-icon name="plus" style="duotone" class="w-4 h-4" /> Criar plano
            </a>
        </x-slot>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-paneladmin::card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700 sticky top-0">
                        <tr>
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">Slug</th>
                            <th class="px-6 py-4">Recorrência</th>
                            <th class="px-6 py-4">Limites</th>
                            <th class="px-6 py-4">Ativo</th>
                            <th class="px-6 py-4">Ordem</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ $plan->name }}
                                    @if($plan->is_free)
                                        <span class="ml-2 px-2 py-0.5 text-[10px] font-bold bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-300 rounded">Gratuito</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4"><code class="text-xs">{{ $plan->slug }}</code></td>
                                <td class="px-6 py-4">{{ $plan->billing_interval === 'yearly' ? 'Anual' : 'Mensal' }}</td>
                                <td class="px-6 py-4 text-xs">
                                    @php
                                        $entities = ['account' => 'contas', 'income' => 'rec.', 'expense' => 'desp.', 'goal' => 'metas', 'budget' => 'orç.', 'category' => 'cat.'];
                                        $parts = [];
                                        foreach ($entities as $key => $label) {
                                            $l = $plan->getLimit($key);
                                            $parts[] = $l === 'unlimited' ? $label . ': ∞' : $label . ': ' . $l;
                                        }
                                    @endphp
                                    {{ implode(' · ', $parts) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($plan->is_active)
                                        <span class="px-2 py-1 text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-500/20 rounded">Ativo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-bold text-slate-500 bg-slate-100 dark:bg-slate-600 rounded">Inativo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $plan->sort_order }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.plans.edit', $plan) }}" class="inline-flex items-center gap-1 text-[#11C76F] hover:underline font-medium">
                                        <x-icon name="pencil" style="duotone" class="w-4 h-4" /> Editar
                                    </a>
                                    @if(!$plan->is_free && $plan->users()->count() === 0)
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline-block ml-3" onsubmit="return confirm('Excluir este plano?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline font-medium text-sm">Excluir</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Nenhum plano cadastrado. Execute as migrations ou crie um plano.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-paneladmin::card>

        @php
            $previewFree = $plans->firstWhere('is_free', true) ?? \Modules\Core\Models\Plan::getDefaultFree();
            $previewPaid = $plans->firstWhere('is_free', false) ?? \Modules\Core\Models\Plan::getDefaultPaid();
        @endphp
        @if($previewFree || $previewPaid)
            <div class="mt-8">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Preview: como aparece ao público</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Resumo do que o usuário vê na página Planos e Assinatura.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($previewFree)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900/50 p-6 shadow-sm">
                            <h3 class="font-bold text-slate-900 dark:text-white">{{ $previewFree->name }}</h3>
                            <p class="mt-2 text-2xl font-black text-slate-900 dark:text-white">Grátis</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">/sempre</p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                @php
                                    $acc = $previewFree->getLimit('account');
                                    $li = $previewFree->getLimit('income');
                                    $le = $previewFree->getLimit('expense');
                                    $go = $previewFree->getLimit('goal');
                                    $bu = $previewFree->getLimit('budget');
                                @endphp
                                <li>{{ $acc === 'unlimited' ? 'Contas ilimitadas' : $acc . ' ' . ($acc === 1 ? 'conta' : 'contas') }}</li>
                                <li>{{ ($li === 'unlimited' || $le === 'unlimited') ? 'Transações ilimitadas' : 'Até ' . ((int)$li + (int)$le) . ' transações (rec.+desp.)' }}</li>
                                <li>{{ $go === 'unlimited' && $bu === 'unlimited' ? 'Metas e orçamentos ilimitados' : (($go === 'unlimited' ? 'Metas ilimitadas' : $go . ' meta(s)') . ', ' . ($bu === 'unlimited' ? 'orç. ilimitados' : $bu . ' orç.')) }}</li>
                                <li>Suporte via Ticket</li>
                            </ul>
                        </div>
                    @endif
                    @if($previewPaid)
                        <div class="rounded-2xl border-2 border-amber-500/30 bg-slate-900 dark:bg-slate-950 p-6 shadow-lg">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <x-icon name="crown" style="solid" class="text-amber-400 w-5 h-5" />
                                {{ $previewPaid->name }}
                            </h3>
                            <p class="mt-2 text-2xl font-black text-white">R$ {{ $previewPaid->amount ? number_format((float) $previewPaid->amount, 2, ',', '.') : '29,90' }}</p>
                            <p class="mt-1 text-xs text-slate-400">/mês</p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-300">
                                @php
                                    $acc = $previewPaid->getLimit('account');
                                    $li = $previewPaid->getLimit('income');
                                    $le = $previewPaid->getLimit('expense');
                                    $go = $previewPaid->getLimit('goal');
                                    $bu = $previewPaid->getLimit('budget');
                                @endphp
                                <li>{{ $acc === 'unlimited' ? 'Contas ilimitadas' : 'Até ' . $acc . ' contas' }}</li>
                                <li>{{ ($li === 'unlimited' || $le === 'unlimited') ? 'Transações ilimitadas' : 'Até ' . ((int)$li + (int)$le) . ' transações' }}</li>
                                <li>{{ $go === 'unlimited' && $bu === 'unlimited' ? 'Metas e orçamentos ilimitados' : (($go === 'unlimited' ? 'Metas ilimitadas' : 'Até ' . $go . ' metas') . ', ' . ($bu === 'unlimited' ? 'orç. ilimitados' : 'até ' . $bu . ' orç.')) }}</li>
                                <li>Suporte Prioritário</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
