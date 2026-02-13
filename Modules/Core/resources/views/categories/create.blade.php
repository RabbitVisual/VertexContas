<x-paneluser::layouts.master>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-3xl text-slate-800 dark:text-white">
                <x-icon name="plus-circle" style="solid" class="text-primary" /> Nova Categoria
            </h2>
            <a href="{{ route('core.categories.index') }}"
               class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                <x-icon name="arrow-left" style="solid" /> Voltar
            </a>
        </div>
    </div>

    <div class="py-12 font-['Poppins']" x-data="{ type: 'expense', selectedIcon: 'circle-dollar', selectedColor: '#64748b' }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-xl border border-slate-100 dark:border-slate-700">
                <form action="{{ route('core.categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Tipo *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" @click="type = 'income'" :class="type === 'income' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="px-6 py-4 rounded-xl font-bold transition-all">
                                <x-icon name="arrow-up" style="solid" class="text-2xl mb-2" />
                                <p>Receita</p>
                            </button>
                            <button type="button" @click="type = 'expense'" :class="type === 'expense' ? 'bg-red-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="px-6 py-4 rounded-xl font-bold transition-all">
                                <x-icon name="arrow-down" style="solid" class="text-2xl mb-2" />
                                <p>Despesa</p>
                            </button>
                        </div>
                        <input type="hidden" name="type" :value="type">
                    </div>

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nome *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-lg border-2 border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" placeholder="Ex: Supermercado, Academia" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-2"><x-icon name="exclamation-circle" style="solid" /> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Ícone</label>
                        <div class="grid grid-cols-6 gap-3 mb-3">
                            @php
                                $icons = ['utensils', 'house', 'car', 'heart-pulse', 'gamepad', 'graduation-cap', 'shirt', 'wrench', 'money-bill-wave', 'laptop-code', 'chart-line', 'shop', 'circle-dollar', 'shopping-cart', 'plane', 'book'];
                            @endphp
                            @foreach($icons as $icon)
                                <button type="button" @click="selectedIcon = '{{ $icon }}'" :class="selectedIcon === '{{ $icon }}' ? 'bg-primary text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="w-12 h-12 rounded-lg flex items-center justify-center hover:scale-110 transition-all">
                                    <i class="fa-solid fa-{{ $icon }} text-xl"></i>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="icon" :value="selectedIcon">
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Cor</label>
                        <div class="grid grid-cols-8 gap-3">
                            @php
                                $colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#64748b'];
                            @endphp
                            @foreach($colors as $color)
                                <button type="button" @click="selectedColor = '{{ $color }}'" :class="selectedColor === '{{ $color }}' ? 'ring-4 ring-offset-2 ring-primary' : ''" class="w-12 h-12 rounded-lg transition-all hover:scale-110" style="background-color: {{ $color }}"></button>
                            @endforeach
                        </div>
                        <input type="hidden" name="color" :value="selectedColor">
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full text-sm font-bold shadow-lg shadow-primary/25 transform hover:-translate-y-0.5 transition-all">
                            <x-icon name="check" style="solid" /> Criar Categoria
                        </button>
                        <a href="{{ route('core.categories.index') }}" class="bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 px-6 py-3 rounded-full text-sm font-bold transition-all">
                            <x-icon name="times" style="solid" /> Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-paneluser::layouts.master>
