@if(config('pwa.enabled', true))
<div id="pwa-install-banner" class="hidden fixed bottom-0 left-0 right-0 z-40 p-4 md:left-64" style="padding-left: max(1rem, env(safe-area-inset-left)); padding-right: max(1rem, env(safe-area-inset-right)); padding-bottom: max(1rem, env(safe-area-inset-bottom));">
    <div class="max-w-4xl mx-auto rounded-3xl border border-slate-200 dark:border-slate-700 bg-linear-to-r from-slate-900 via-slate-950 to-slate-900 shadow-2xl px-4 py-4 sm:px-6 sm:py-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <span class="flex items-center justify-center w-11 h-11 min-w-[44px] min-h-[44px] rounded-2xl bg-emerald-500/15 text-emerald-400 shrink-0">
                <i class="fa-pro fa-solid fa-mobile-screen"></i>
            </span>
            <div class="min-w-0">
                <p class="font-semibold text-slate-50 text-sm">
                    📱 <span class="font-bold">Leve seu Mentor no Bolso!</span>
                </p>
                <p id="pwa-install-hint" class="text-xs text-slate-300">
                    Instale o aplicativo Vertex para registrar seus gastos na hora da compra, sem esquecer de nada.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" id="pwa-install-btn" class="hidden min-h-[44px] min-w-[44px] inline-flex items-center justify-center px-4 py-2.5 bg-emerald-500 text-slate-950 font-bold rounded-2xl hover:bg-emerald-400 transition-colors text-sm shadow-lg shadow-emerald-500/30">
                Instalar App
            </button>
            <button type="button" id="pwa-install-dismiss" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center px-3 py-2 text-slate-400 hover:text-slate-200 rounded-xl transition-colors text-xs font-medium" aria-label="Agora não">
                Agora não
            </button>
        </div>
    </div>
</div>
@endif

