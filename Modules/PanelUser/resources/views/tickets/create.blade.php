@php
    $isPro = $isPro ?? auth()->user()?->isPro() ?? false;
@endphp
<x-paneluser::layouts.master :title="'Novo Chamado'">
    <div class="max-w-3xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 px-4 pb-12">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-primary-500/5 dark:bg-primary-500/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-emerald-600/5 dark:bg-emerald-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10">
                <nav class="flex items-center gap-2 text-xs font-bold text-primary-600 dark:text-primary-500 uppercase tracking-widest mb-4" aria-label="Navegação">
                    <a href="{{ route('user.tickets.index') }}" class="hover:underline">Central de Ajuda</a>
                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                    <span class="text-gray-400 dark:text-gray-500">Novo Chamado</span>
                </nav>
                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Como podemos <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-600 dark:from-primary-400 dark:to-emerald-400">ajudar?</span></h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">
                    @if($isPro)
                        Seus chamados têm prioridade. Descreva o problema e nossa equipe responderá o mais rápido possível.
                    @else
                        Descreva seu problema para que nossa equipe técnica possa resolver o mais rápido possível.
                    @endif
                </p>
            </div>
        </div>

        @if(!$isPro)
            <div class="rounded-3xl border border-amber-200 dark:border-amber-500/20 bg-amber-50/50 dark:bg-amber-950/20 p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0">
                    <x-icon name="crown" style="duotone" class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Vertex PRO oferece suporte prioritário</p>
                    <p class="text-xs text-amber-700/80 dark:text-amber-400/80">Atendimento VIP e exportação do histórico.</p>
                </div>
                <a href="{{ route('user.subscription.index') }}" class="shrink-0 text-sm font-bold text-amber-600 dark:text-amber-400 hover:underline">
                    Conhecer PRO →
                </a>
            </div>
        @endif

        {{-- Formulário --}}
        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 flex items-center gap-3 bg-gray-50/50 dark:bg-gray-950/50">
                <div class="w-10 h-10 rounded-xl bg-primary-500/10 dark:bg-primary-500/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <x-icon name="headset" style="duotone" class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Abrir Solicitação</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Preencha os campos abaixo para iniciar o atendimento</p>
                </div>
            </div>

            <form action="{{ route('user.tickets.store') }}" method="POST" class="p-6 lg:p-8 space-y-8" x-data="{ priority: 'medium' }">
                @csrf

                <div class="space-y-2">
                    <label for="subject" class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Assunto do Chamado</label>
                    <input type="text" name="subject" id="subject"
                        class="w-full px-4 py-3.5 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-medium placeholder:text-gray-400 focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                        placeholder="Ex: Erro ao processar pagamento" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Nível de Urgência</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="priority" value="low" class="peer sr-only" x-model="priority">
                            <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/5 hover:border-emerald-500/50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2">
                                    <x-icon name="circle-check" style="duotone" class="w-5 h-5" />
                                </div>
                                <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Baixa</span>
                                <span class="block text-[10px] text-gray-400">Dúvidas gerais</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="priority" value="medium" class="peer sr-only" checked x-model="priority">
                            <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/5 hover:border-amber-500/50 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2">
                                    <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5" />
                                </div>
                                <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Média</span>
                                <span class="block text-[10px] text-gray-400">Problemas</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" name="priority" value="high" class="peer sr-only" x-model="priority">
                            <div class="p-4 rounded-2xl border-2 border-gray-200 dark:border-white/5 hover:border-rose-500/50 peer-checked:border-rose-500 peer-checked:bg-rose-50 dark:peer-checked:bg-rose-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-2">
                                    <x-icon name="circle-exclamation" style="duotone" class="w-5 h-5" />
                                </div>
                                <span class="block text-sm font-bold text-gray-700 dark:text-gray-300">Alta</span>
                                <span class="block text-[10px] text-gray-400">Sistema off</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="message" class="block text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Detalhamento</label>
                    <div class="relative">
                        <textarea name="message" id="message" rows="5"
                            class="w-full rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-4 text-sm font-medium text-gray-900 dark:text-gray-200 placeholder:text-gray-400 focus:ring-4 focus:ring-primary-500/20 focus:border-primary-500 transition-all resize-none"
                            placeholder="Descreva o que aconteceu, passos para reproduzir e o que esperava..." required></textarea>
                        <div class="absolute bottom-4 right-4 text-gray-300 dark:text-gray-600 pointer-events-none">
                            <x-icon name="pen-nib" style="solid" class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center gap-4 pt-4">
                    <a href="{{ route('user.tickets.index') }}"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 py-3 px-6 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-all">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="flex-1 w-full sm:max-w-sm flex items-center justify-center gap-3 py-3.5 px-6 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm transition-all shadow-lg shadow-primary-500/20 hover:scale-[1.02] active:scale-95">
                        <x-icon name="paper-plane-top" style="solid" class="w-5 h-5" />
                        Abrir Solicitação
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
