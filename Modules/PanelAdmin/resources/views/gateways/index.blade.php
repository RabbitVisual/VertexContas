<x-paneladmin::layouts.master>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">
            <x-icon name="credit-card" style="solid" class="mr-2 text-primary" />
            Gateways de Pagamento
        </h1>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gateways as $gateway)
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-shadow hover:shadow-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="h-12 w-12 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-primary text-xl">
                                @if($gateway->slug === 'stripe')
                                    <x-icon name="stripe" style="brands" />
                                @else
                                    <x-icon name="{{ $gateway->icon }}" style="solid" />
                                @endif
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $gateway->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $gateway->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $gateway->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $gateway->mode === 'live' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($gateway->mode) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-900 px-6 py-4 flex justify-between items-center">
                    <form action="{{ route('admin.gateways.toggle', $gateway->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-medium {{ $gateway->is_active ? 'text-red-600 hover:text-red-900' : 'text-emerald-600 hover:text-emerald-900' }}">
                            {{ $gateway->is_active ? 'Desativar' : 'Ativar' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.gateways.edit', $gateway->id) }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded text-sm font-medium transition-colors">
                        <x-icon name="gears" style="solid" class="mr-2" /> Configurar
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
</x-paneladmin::layouts.master>
