<x-paneluser::layouts.master :title="'Novo Chamado'">
    <div class="min-h-[calc(100vh-6rem)] bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
        <div class="max-w-2xl mx-auto space-y-8 px-6 pt-8">
            {{-- Dashboard Header --}}
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <li><a href="{{ route('user.tickets.index') }}" class="hover:text-primary transition-colors">Central de Ajuda</a></li>
                        <li><x-icon name="chevron-right" style="solid" class="w-3 h-3" /></li>
                        <li class="text-primary">Novo Chamado</li>
                    </ol>
                </nav>
                <div class="flex flex-wrap items-center gap-3 mb-1">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Como podemos ajudar?</h1>
                    @if($isPro ?? false)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 border border-amber-200 dark:border-amber-500/30">
                            <x-icon name="crown" style="solid" class="w-3.5 h-3.5" /> Suporte VIP
                        </span>
                    @endif
                </div>
                <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-md">
                    @if($isPro ?? false)
                        Seus chamados têm prioridade no atendimento. Descreva seu problema e nossa equipe responderá o mais rápido possível.
                    @else
                        Descreva seu problema para que nossa equipe técnica possa resolver o mais rápido possível.
                    @endif
                </p>
            </div>

            @if(!($isPro ?? false))
                <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-2xl border border-amber-100 dark:border-amber-500/20 flex items-center gap-3">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                        <x-icon name="crown" style="solid" class="text-amber-600 dark:text-amber-400 w-5 h-5" />
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Vertex PRO oferece suporte prioritário</p>
                        <p class="text-xs text-amber-700/80 dark:text-amber-400/80">Atendimento VIP e exportação do histórico de chamados.</p>
                    </div>
                    <a href="{{ route('user.subscription.index') }}" class="shrink-0 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                        Conhecer PRO →
                    </a>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all hover:shadow-md">
                <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                    <div class="p-2.5 bg-primary/10 rounded-xl">
                        <x-icon name="headset" style="solid" class="text-primary w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Abrir Solicitação</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Preencha os campos abaixo para iniciar o atendimento</p>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <form action="{{ route('user.tickets.store') }}" method="POST" class="space-y-8" x-data="{ priority: 'medium' }">
                        @csrf

                        <div class="space-y-3">
                            <label for="subject" class="block text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Assunto do Chamado</label>
                            <input type="text" name="subject" id="subject"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 text-gray-900 dark:text-white font-medium placeholder:text-gray-400 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all"
                                placeholder="Ex: Erro ao processar pagamento" required>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Nível de Urgência</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="priority" value="low" class="peer sr-only" @click="priority = 'low'">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 hover:border-emerald-200 dark:hover:border-emerald-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all text-center">
                                        <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                            <x-icon name="coffee" style="duotone" class="w-5 h-5" />
                                        </div>
                                        <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Baixa</span>
                                        <span class="block text-[10px] text-gray-400 mt-1">Dúvidas gerais</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer group">
                                    <input type="radio" name="priority" value="medium" class="peer sr-only" checked @click="priority = 'medium'">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 hover:border-amber-200 dark:hover:border-amber-800 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 transition-all text-center">
                                        <div class="w-10 h-10 mx-auto rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                            <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5" />
                                        </div>
                                        <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Média</span>
                                        <span class="block text-[10px] text-gray-400 mt-1">Problemas</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer group">
                                    <input type="radio" name="priority" value="high" class="peer sr-only" @click="priority = 'high'">
                                    <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-slate-700 hover:border-red-200 dark:hover:border-red-800 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all text-center">
                                        <div class="w-10 h-10 mx-auto rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                            <x-icon name="siren-on" style="duotone" class="w-5 h-5 animate-pulse" />
                                        </div>
                                        <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Alta</span>
                                        <span class="block text-[10px] text-gray-400 mt-1">Sistema off</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="message" class="block text-[10px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest">Detalhamento</label>
                            <div class="relative">
                                <textarea name="message" id="message" rows="5"
                                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 dark:bg-slate-800 p-4 text-sm font-medium text-slate-700 dark:text-gray-300 placeholder:text-gray-400 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all resize-none"
                                    placeholder="Descreva o que aconteceu..." required></textarea>
                                <div class="absolute bottom-3 right-3 text-gray-300 dark:text-slate-600 pointer-events-none">
                                    <x-icon name="pen-nib" style="solid" class="w-4 h-4" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <a href="{{ route('user.tickets.index') }}"
                                class="text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-white transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="flex-1 flex items-center justify-center gap-3 py-3 px-6 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 active:scale-[0.98]">
                                <x-icon name="paper-plane-top" style="solid" class="w-5 h-5" />
                                Abrir Solicitação
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-paneluser::layouts.master>
