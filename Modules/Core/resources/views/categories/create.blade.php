<x-paneluser::layouts.master :title="'Nova Categoria'">
    <div class="max-w-2xl mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500 pb-8" x-data="{ type: 'expense', selectedIcon: 'circle-dollar', selectedColor: '#64748b' }">
        {{-- Hero CBAV --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-950 border border-gray-200 dark:border-white/5 p-8 sm:p-12 shadow-sm dark:shadow-none">
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-indigo-600/5 dark:bg-indigo-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-violet-600/5 dark:bg-violet-600/10 rounded-full blur-[100px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <nav class="flex items-center gap-2 text-xs font-bold text-indigo-600 dark:text-indigo-500 uppercase tracking-widest mb-4">
                        <span>Organização</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <a href="{{ route('core.categories.index') }}" class="text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Categorias</a>
                        <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-800"></span>
                        <span class="text-gray-400 dark:text-gray-500">Nova categoria</span>
                    </nav>
                    <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-[1.1] mb-3">Nova <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400">Categoria</span></h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg max-w-md leading-relaxed">Crie uma categoria personalizada com nome, ícone e cor. Ela aparecerá no Extrato e nos orçamentos.</p>
                </div>
                <a href="{{ route('core.categories.index') }}" class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-white/10 transition-colors">
                    <x-icon name="arrow-left" style="solid" class="w-4 h-4" />
                    Voltar
                </a>
            </div>
        </div>

        {{-- Dica --}}
        <div class="rounded-3xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 p-5 shadow-sm">
            <div class="flex gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                    <x-icon name="circle-info" style="duotone" class="w-4 h-4" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-0.5">Dica de uso</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Escolha um nome claro (ex: Supermercado, Academia). O tipo define se a categoria será usada em receitas ou despesas. Ícone e cor ajudam a identificar rápido na lista e nos relatórios.</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-gray-900/50">
                <h2 class="font-bold text-gray-900 dark:text-white">Dados da categoria</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Preencha os campos para criar sua categoria personalizada</p>
            </div>
            <form action="{{ route('core.categories.store') }}" method="POST" class="p-6 lg:p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">Tipo *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="type = 'income'"
                                :class="type === 'income' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-white/10'"
                                class="px-6 py-4 rounded-2xl border-2 font-bold transition-all flex flex-col items-center gap-2">
                            <x-icon name="arrow-trend-up" style="duotone" class="w-6 h-6" />
                            Receita
                        </button>
                        <button type="button" @click="type = 'expense'"
                                :class="type === 'expense' ? 'bg-rose-600 text-white border-rose-600' : 'bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-white/10'"
                                class="px-6 py-4 rounded-2xl border-2 font-bold transition-all flex flex-col items-center gap-2">
                            <x-icon name="arrow-trend-down" style="duotone" class="w-6 h-6" />
                            Despesa
                        </button>
                    </div>
                    <input type="hidden" name="type" :value="type">
                </div>

                <div>
                    <label for="name" class="block text-sm font-bold text-gray-900 dark:text-white mb-2">Nome *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           class="w-full rounded-xl border-2 border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:border-indigo-500 outline-none transition-colors @error('name') border-red-500 @enderror"
                           placeholder="Ex: Supermercado, Academia" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 flex items-center gap-1"><x-icon name="circle-exclamation" style="solid" class="w-4 h-4" /> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">Ícone</label>
                    <div class="grid grid-cols-6 gap-3">
                        @php
                            $icons = ['utensils', 'house', 'car', 'heart-pulse', 'gamepad', 'graduation-cap', 'shirt', 'wrench', 'money-bill-wave', 'laptop-code', 'chart-line', 'shop', 'circle-dollar', 'shopping-cart', 'plane', 'book'];
                        @endphp
                        @foreach($icons as $icon)
                            <button type="button" @click="selectedIcon = '{{ $icon }}'"
                                    :class="selectedIcon === '{{ $icon }}' ? 'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900 bg-indigo-50 dark:bg-indigo-500/20' : 'bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10'"
                                    class="w-12 h-12 rounded-xl flex items-center justify-center text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                                <x-icon name="{{ $icon }}" style="duotone" class="w-5 h-5" />
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="icon" :value="selectedIcon">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">Cor</label>
                    <div class="grid grid-cols-8 gap-3">
                        @php
                            $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'];
                        @endphp
                        @foreach($colors as $color)
                            <button type="button" @click="selectedColor = '{{ $color }}'"
                                    :class="selectedColor === '{{ $color }}' ? 'ring-2 ring-offset-2 ring-gray-900 dark:ring-white' : ''"
                                    class="w-12 h-12 rounded-xl transition-all hover:scale-110 shadow-inner"
                                    style="background-color: {{ $color }}"></button>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" :value="selectedColor">
                </div>

                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-2">
                    <a href="{{ route('core.categories.index') }}" class="inline-flex items-center justify-center gap-2 py-3 px-5 rounded-2xl border-2 border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 font-bold text-sm hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-500/20">
                        <x-icon name="check" style="solid" class="w-5 h-5" />
                        Criar categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-paneluser::layouts.master>
