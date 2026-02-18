@extends('paneluser::components.layouts.legal-wall')

@section('content')
    <div class="w-full max-w-2xl" x-data="{
        scrolledToBottom: false,
        accepted: false,
        checkScroll() {
            const el = this.$refs.content;
            if (!el) return;
            const threshold = Math.max(0, el.scrollHeight - el.clientHeight - 20);
            this.scrolledToBottom = el.scrollTop >= threshold;
        },
        canSubmit() { return this.scrolledToBottom && this.accepted; }
    }" x-init="checkScroll()">
        <div class="text-center mb-8" data-tour="legal-intro">
            <a href="{{ route('homepage') }}" class="inline-block mb-4">
                <img src="{{ branding_logo_url('user', false) }}" alt="{{ config('app.name') }}" class="h-10 block dark:hidden" />
                <img src="{{ branding_logo_url('user', true) }}" alt="{{ config('app.name') }}" class="h-10 hidden dark:block" />
            </a>
            <h1 class="text-xl font-black text-slate-800 dark:text-white">{{ branding_company_legal_name() }}</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Aceite os termos para continuar</p>
        </div>

        @if($pendingDocuments->isEmpty())
            @php
                $dashboardRoute = auth()->user()->hasRole('admin') ? route('admin.index') : route('paneluser.index');
            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg border border-slate-200 dark:border-slate-700 text-center">
                <x-icon name="shield-check" style="solid" class="w-16 h-16 text-emerald-500 mx-auto mb-4" />
                <p class="text-slate-700 dark:text-slate-300 font-medium">Você já aceitou todos os termos necessários.</p>
                <a href="{{ $dashboardRoute }}" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors">
                    <x-icon name="arrow-right" />
                    Ir para o Painel
                </a>
            </div>
        @else
            @php $document = $pendingDocuments->first(); @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <x-icon name="file-contract" style="solid" class="w-6" />
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 dark:text-white">{{ $document->title }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Versão {{ $document->version }}</p>
                        </div>
                    </div>
                </div>
                <div x-ref="content"
                     @scroll.debounce.50ms="checkScroll()"
                     class="max-h-[50vh] overflow-y-auto p-6 prose prose-slate dark:prose-invert prose-sm max-w-none">
                    {!! $document->content !!}
                </div>
                <form action="{{ route('paneluser.legal.store') }}" method="POST" class="p-6 border-t border-slate-200 dark:border-slate-700">
                    @csrf
                    <input type="hidden" name="legal_document_id" value="{{ $document->id }}">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <label class="flex items-center gap-3 cursor-pointer flex-1" :class="{ 'cursor-not-allowed opacity-60': !scrolledToBottom }">
                            <input type="checkbox"
                                   x-model="accepted"
                                   name="accepted"
                                   value="1"
                                   :disabled="!scrolledToBottom"
                                   class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Eu li e aceito os Termos da Vertex Solutions LTDA
                            </span>
                        </label>
                        <button type="submit"
                                :disabled="!canSubmit()"
                                class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-colors disabled:opacity-50 disabled:cursor-not-allowed shrink-0">
                            Aceitar
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400" x-show="!scrolledToBottom">
                        Role até o final do documento para habilitar a confirmação.
                    </p>
                </form>
            </div>
        @endif
    </div>
@endsection

@if(!empty($pageTourId) && !empty($pageTourSteps))
@push('scripts')
<script>
(function() {
    var tourId = @json($pageTourId);
    var steps = @json($pageTourSteps);
    function register() {
        if (window.registerVertexTourSteps && steps && steps.length) {
            window.registerVertexTourSteps(tourId, steps);
            return;
        }
        setTimeout(register, 50);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', register);
    } else {
        register();
    }
})();
</script>
@endpush
@endif
