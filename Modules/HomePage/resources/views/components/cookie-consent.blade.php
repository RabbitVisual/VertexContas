@php
    $enabled = \Illuminate\Support\Facades\Schema::hasTable('settings')
        ? (bool) setting('homepage_cookie_consent_enabled', false)
        : false;
    $message = setting('homepage_cookie_consent_message', 'Utilizamos cookies para garantir a melhor experiência em nosso site. Ao continuar, você concorda com nossa Política de Cookies.');
@endphp
@if($enabled)
<div x-data="{
    accepted: localStorage.getItem('cookie_consent') !== null,
    accept() {
        localStorage.setItem('cookie_consent', 'accepted');
        this.accepted = true;
    },
    reject() {
        localStorage.setItem('cookie_consent', 'rejected');
        this.accepted = true;
    }
}"
x-show="!accepted"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-8"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-8"
class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6"
aria-live="polite">
    <div class="max-w-4xl mx-auto bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 p-4 md:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3 flex-1">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                <x-icon name="cookie-bite" style="duotone" class="text-primary w-5 h-5" />
            </div>
            <div>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    {{ $message }}
                </p>
                <a href="{{ route('public.legal.show', 'politica-cookies') }}" class="text-xs font-bold text-primary hover:underline mt-1 inline-block">
                    Saber mais
                </a>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <button @click="reject()" type="button" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                Rejeitar
            </button>
            <button @click="accept()" type="button" class="px-6 py-2 rounded-xl bg-primary hover:bg-primary-dark text-white text-sm font-bold shadow-lg shadow-primary/25 transition-all">
                Aceitar
            </button>
        </div>
    </div>
</div>
@endif
