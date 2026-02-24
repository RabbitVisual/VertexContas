<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Nova versão PWA</x-slot>

    <x-paneladmin::page title="Nova versão" subtitle="Publicar uma nova release do app.">
        <x-slot name="header">
            <a href="{{ route('admin.pwa.versions.index') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white font-medium">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4" /> Voltar
            </a>
        </x-slot>

        <x-paneladmin::card>
            <div class="p-6">
                <form action="{{ route('admin.pwa.versions.store') }}" method="POST">
                    @csrf
                    @include('paneladmin::pwa.versions._form', ['version' => $version])
                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-colors">
                            <x-icon name="check" style="duotone" class="w-4 h-4" /> Publicar versão
                        </button>
                        <a href="{{ route('admin.pwa.versions.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 font-medium">Cancelar</a>
                    </div>
                </form>
            </div>
        </x-paneladmin::card>
    </x-paneladmin::page>
</x-paneladmin::layouts.master>
