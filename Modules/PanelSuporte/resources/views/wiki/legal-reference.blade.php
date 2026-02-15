<x-panelsuporte::layouts.master>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('support.wiki.index') }}" class="p-3 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-gray-800 text-slate-500 hover:text-primary transition-all">
                <x-icon name="arrow-left" />
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Referência Legal</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Vertex Solutions LTDA — Documentos para consulta e citação em atendimentos.</p>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($documents as $document)
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <x-icon name="file-contract" style="solid" class="w-6" />
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-800 dark:text-white">{{ $document->title }}</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Versão {{ $document->version }}</p>
                            </div>
                        </div>
                        <a href="{{ route('public.legal.show', $document->slug) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary font-bold rounded-xl hover:bg-primary/20 transition-colors text-sm">
                            <x-icon name="envelope" style="solid" class="w-4 h-4" />
                            Enviar por E-mail
                        </a>
                    </div>
                    <div class="p-6 prose prose-slate dark:prose-invert prose-sm max-w-none max-h-[400px] overflow-y-auto">
                        {!! $document->content !!}
                    </div>
                </div>
            @endforeach
        </div>

        @if($documents->isEmpty())
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-12 text-center border border-dashed border-gray-200 dark:border-gray-800">
                <x-icon name="file-contract" style="duotone" class="w-16 h-16 text-slate-300 mx-auto mb-4" />
                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhum documento legal ativo no momento.</p>
            </div>
        @endif
    </div>
</x-panelsuporte::layouts.master>
