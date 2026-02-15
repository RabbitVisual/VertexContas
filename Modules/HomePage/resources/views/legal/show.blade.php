<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-white dark:bg-slate-900 py-32 px-4 font-['Poppins']">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-2xl mb-4">
                    <x-icon name="file-contract" style="solid" class="text-primary text-2xl" />
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">{{ $document->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Vertex Solutions LTDA · Vertex Contas · Versão {{ $document->version }}</p>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="prose prose-slate dark:prose-invert max-w-none p-8 md:p-12">
                    {!! $document->content !!}
                </div>
                <div class="px-8 md:px-12 pb-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Documento emitido por <strong>Vertex Solutions LTDA</strong>. Jurisdição: Brasil.
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('homepage') }}" class="text-primary font-bold flex items-center gap-2 hover:underline underline-offset-4 decoration-2">
                    <x-icon name="arrow-left" />
                    Voltar para a Home
                </a>
                <a href="{{ route('homepage') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary/10 text-primary rounded-xl font-bold text-sm hover:bg-primary/20 transition-colors">
                    <x-icon name="download" />
                    Download PDF (em breve)
                </a>
            </div>
        </div>
    </main>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
