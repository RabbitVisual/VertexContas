<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar versão PWA</x-slot>

    <x-paneladmin::page title="Editar versão" subtitle="Alterar dados da release.">
        <x-slot name="header">
            <a href="{{ route('admin.pwa.versions.index') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <x-paneladmin::card>
            <div class="p-6">
                <form action="{{ route('admin.pwa.versions.update', $version) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('paneladmin::pwa.versions._form', ['version' => $version])
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                            <x-icon name="check" style="duotone" class="w-4 h-4" /> Salvar
                        </button>
                        <a href="{{ route('admin.pwa.versions.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 font-medium">Cancelar</a>
                    </div>
                </form>
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
