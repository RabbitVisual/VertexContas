<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Criar plano</x-slot>

    <x-paneladmin::page title="Criar plano" subtitle="Adicione um novo plano com limites e integração a gateways.">
        <x-slot name="header">
            <a href="{{ route('admin.plans.index') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

        <x-paneladmin::card>
            <div class="p-6">
                <form action="{{ route('admin.plans.store') }}" method="POST">
                    @csrf
                    @include('paneladmin::plans._form')
                </form>
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
