<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Gateways</x-slot>

    <x-paneladmin::page title="Gateways de Pagamento" subtitle="Configure e ative Stripe, Mercado Pago e outros meios de pagamento.">
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

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <x-icon name="credit-card" style="duotone" class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Total de Gateways</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <x-icon name="circle-check" style="duotone" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Ativos</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <x-icon name="bolt" style="duotone" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Em Produção (Live)</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $liveCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gateway Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($gateways as $gateway)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden transition-all hover:shadow-md hover:border-slate-200 dark:hover:border-slate-600">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 text-2xl">
                                @if($gateway->slug === 'stripe')
                                    <x-icon name="stripe" style="brands" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                                @elseif($gateway->slug === 'mercadopago')
                                    <x-icon name="credit-card" style="duotone" class="w-8 h-8 text-sky-500 dark:text-sky-400" />
                                @else
                                    <x-icon name="credit-card" style="duotone" class="w-8 h-8 text-[#11C76F]" />
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-black text-slate-900 dark:text-white truncate">{{ $gateway->name }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $gateway->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400' }}">
                                        <x-icon name="{{ $gateway->is_active ? 'circle-check' : 'circle-xmark' }}" style="duotone" class="w-3.5 h-3.5" />
                                        {{ $gateway->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $gateway->mode === 'live' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        <x-icon name="{{ $gateway->mode === 'live' ? 'bolt' : 'flask' }}" style="duotone" class="w-3.5 h-3.5" />
                                        {{ $gateway->mode === 'live' ? 'Produção' : 'Sandbox' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between gap-3">
                        <form action="{{ route('admin.gateways.toggle', $gateway) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold py-2 px-3 rounded-lg transition-colors {{ $gateway->is_active ? 'text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20' : 'text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/20' }}">
                                <x-icon name="{{ $gateway->is_active ? 'power-off' : 'circle-play' }}" style="duotone" class="w-4 h-4" />
                                {{ $gateway->is_active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.gateways.edit', $gateway) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] hover:bg-[#0EA85A] text-white rounded-xl text-sm font-bold transition-colors shrink-0">
                            <x-icon name="gears" style="duotone" class="w-4 h-4" />
                            Configurar
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-paneladmin::card>
                        <div class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mx-auto mb-4">
                                <x-icon name="credit-card" style="duotone" class="w-8 h-8 text-slate-400" />
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum gateway configurado.</p>
                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Execute o seeder de gateways para exibir Stripe e Mercado Pago.</p>
                        </div>
                    </x-paneladmin::card>
                </div>
            @endforelse
        </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
