@php
    $iconName = match ($document->slug) {
        'privacidade' => 'shield-halved',
        'politica-cookies' => 'cookie-bite',
        default => 'file-contract',
    };
    $lastUpdated = $document->updated_at ?? $document->created_at;
@endphp
<x-homepage::layouts.master>
    <x-homepage::layouts.navbar />

    <main class="min-h-screen bg-slate-50 dark:bg-slate-900 py-32 px-4 font-['Poppins']">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-primary/10 rounded-3xl mb-6">
                    <x-icon name="{{ $iconName }}" style="duotone" class="text-primary text-4xl" />
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">{{ $document->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">
                    {{ branding_company_legal_name() }} · Versão {{ $document->version }}
                    @if($lastUpdated)
                        · Última atualização: {{ $lastUpdated->format('d/m/Y') }}
                    @endif
                </p>
            </div>

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-[40px] p-8 md:p-16 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-white dark:border-slate-700">
                <div class="prose prose-slate dark:prose-invert prose-headings:font-black prose-p:text-slate-600 dark:prose-p:text-slate-300 prose-li:text-slate-600 dark:prose-li:text-slate-300 max-w-none space-y-6">
                    {!! $document->content !!}
                </div>
                <div class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-700">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Documento emitido por <strong>{{ branding_company_legal_name() }}</strong>. Jurisdição: Brasil.
                    </p>
                </div>
            </div>

            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('homepage') }}" class="text-primary font-black flex items-center gap-2 hover:underline underline-offset-4 decoration-2">
                    <x-icon name="arrow-left" style="solid" />
                    Voltar para a Home
                </a>
            </div>
        </div>
    </main>

    <x-homepage::layouts.footer />
</x-homepage::layouts.master>
