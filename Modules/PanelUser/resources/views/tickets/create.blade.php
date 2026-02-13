@section('title', 'Novo Chamado')

<x-paneluser::layouts.master>
    <div class="max-w-2xl mx-auto py-8 animate-in slide-in-from-bottom-4 duration-700">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Como podemos ajudar?</h1>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Descreva seu problema para que nossa equipe técnica possa resolver o mais rápido possível.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] shadow-xl shadow-slate-200/50 dark:shadow-none border border-gray-100 dark:border-gray-700 p-8 md:p-12 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-primary via-purple-500 to-indigo-600"></div>

            <form action="{{ route('user.tickets.store') }}" method="POST" class="space-y-8" x-data="{ priority: 'medium' }">
                @csrf

                <!-- Subject Input -->
                <div class="space-y-3">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest pl-1">Assunto do Chamado</label>
                    <input type="text" name="subject" class="w-full text-xl font-bold border-0 border-b-2 border-gray-100 dark:border-gray-700 bg-transparent px-0 py-3 text-slate-900 dark:text-white focus:ring-0 focus:border-primary placeholder:text-gray-300 dark:placeholder:text-gray-600 transition-colors" placeholder="Ex: Erro ao processar pagamento" required>
                </div>

                <!-- Priority Selection (Visual Cards) -->
                <div class="space-y-3">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest pl-1">Nível de Urgência</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer group relative">
                            <input type="radio" name="priority" value="low" class="peer sr-only" @click="priority = 'low'">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 hover:border-emerald-200 dark:hover:border-emerald-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 dark:peer-checked:bg-emerald-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="coffee" style="duotone" class="text-lg" />
                                </div>
                                <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Baixa</span>
                                <span class="block text-[10px] text-gray-400 mt-1">Dúvidas gerais</span>
                            </div>
                        </label>

                        <label class="cursor-pointer group relative">
                            <input type="radio" name="priority" value="medium" class="peer sr-only" checked @click="priority = 'medium'">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 hover:border-amber-200 dark:hover:border-amber-800 peer-checked:border-amber-500 peer-checked:bg-amber-50 dark:peer-checked:bg-amber-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="triangle-exclamation" style="duotone" class="text-lg" />
                                </div>
                                <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Média</span>
                                <span class="block text-[10px] text-gray-400 mt-1">Problemas</span>
                            </div>
                        </label>

                        <label class="cursor-pointer group relative">
                            <input type="radio" name="priority" value="high" class="peer sr-only" @click="priority = 'high'">
                            <div class="p-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 hover:border-red-200 dark:hover:border-red-800 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all text-center">
                                <div class="w-10 h-10 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                    <x-icon name="siren-on" style="duotone" class="text-lg animate-pulse" />
                                </div>
                                <span class="block text-sm font-bold text-slate-700 dark:text-gray-300">Alta</span>
                                <span class="block text-[10px] text-gray-400 mt-1">Sistema off</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="space-y-3">
                     <label class="block text-xs font-black text-gray-400 uppercase tracking-widest pl-1">Detalhamento</label>
                     <div class="relative">
                        <textarea name="message" rows="5" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-slate-900/50 p-4 text-sm font-medium text-slate-700 dark:text-gray-300 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none" placeholder="Descreva o que aconteceu..." required></textarea>
                        <div class="absolute bottom-3 right-3 text-gray-300">
                             <x-icon name="pen-nib" class="text-sm" />
                        </div>
                     </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4 pt-4">
                    <a href="{{ route('user.tickets.index') }}" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">Cancelar</a>
                    <button type="submit" class="flex-1 py-4 bg-primary hover:bg-primary-dark text-white font-black rounded-2xl shadow-lg shadow-primary/30 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                        <x-icon name="paper-plane-top" style="solid" />
                        Abrir Solicitação
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
