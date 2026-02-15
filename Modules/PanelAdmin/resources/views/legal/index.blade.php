<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Central Legal</x-slot>

    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Central Legal</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Vertex Solutions LTDA — Gerencie Termos de Uso, Privacidade e Contratos.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($documents as $document)
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:border-primary/30 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                @if($document->slug === 'privacidade')
                                    <x-icon name="shield-halved" style="solid" class="w-6" />
                                @elseif($document->slug === 'politica-cookies')
                                    <x-icon name="cookie-bite" style="solid" class="w-6" />
                                @else
                                    <x-icon name="file-contract" style="solid" class="w-6" />
                                @endif
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 dark:text-white">{{ $document->title }}</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Versão {{ $document->version }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    @if($document->is_active)
                                        <span class="flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-lg uppercase">
                                            <div class="w-1 h-1 rounded-full bg-emerald-500"></div> Ativo
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1.5 px-2 py-0.5 bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-lg uppercase">
                                            <div class="w-1 h-1 rounded-full bg-amber-500"></div> Inativo
                                        </span>
                                    @endif
                                    @if($document->requires_acceptance)
                                        <span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-black rounded-lg uppercase">Exige aceite</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.legal.edit', $document) }}" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors flex-shrink-0">
                            <x-icon name="pen" style="duotone" class="text-sm" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-paneladmin::layouts.master>
