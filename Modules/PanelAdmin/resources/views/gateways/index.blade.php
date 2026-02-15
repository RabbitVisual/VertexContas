<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Gateways</x-slot>

    <x-paneladmin::page title="Gateways de Pagamento" subtitle="Configure e ative os gateways de pagamento.">
    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gateways as $gateway)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden transition-shadow hover:shadow-md">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-[#11C76F] text-xl">
                                @if($gateway->slug === 'stripe')
                                    <x-icon name="stripe" style="brands" />
                                @else
                                    <x-icon name="{{ $gateway->icon }}" style="solid" />
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $gateway->name }}</h3>
                                <div class="flex items-center mt-1 gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold {{ $gateway->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $gateway->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold {{ $gateway->mode === 'live' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ ucfirst($gateway->mode) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/30 px-6 py-4 flex justify-between items-center border-t border-slate-100 dark:border-slate-700">
                    <form action="{{ route('admin.gateways.toggle', $gateway->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-bold {{ $gateway->is_active ? 'text-red-600 hover:text-red-700 dark:text-red-400' : 'text-emerald-600 hover:text-emerald-700 dark:text-emerald-400' }}">
                            {{ $gateway->is_active ? 'Desativar' : 'Ativar' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.gateways.edit', $gateway->id) }}" class="inline-flex items-center px-4 py-2.5 bg-[#11C76F] hover:bg-[#0EA85A] text-white rounded-xl text-sm font-bold transition-colors">
                        <x-icon name="gears" style="duotone" class="mr-2" /> Configurar
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
