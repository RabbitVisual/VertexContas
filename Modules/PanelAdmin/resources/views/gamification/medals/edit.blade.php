<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Medalha</x-slot>

    <div class="max-w-3xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.gamification.medals.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-[#11C76F] transition-all">
                <x-icon name="arrow-left" style="duotone" />
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Editar Medalha</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">{{ $medal->title }}</p>
            </div>
        </div>

        <form action="{{ route('admin.gamification.medals.update', $medal) }}" method="POST" class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 space-y-2">
                    <label for="title" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Título</label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $medal->title) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    @error('title') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="description" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Descrição curta</label>
                    <textarea name="description" id="description" rows="2" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('description', $medal->description) }}</textarea>
                    @error('description') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="trigger_key" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Chave do gatilho</label>
                    <input type="text" name="trigger_key" id="trigger_key" required value="{{ old('trigger_key', $medal->trigger_key) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-mono text-sm">
                    @error('trigger_key') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="icon_name" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Ícone (FontAwesome)</label>
                    <input type="text" name="icon_name" id="icon_name" required value="{{ old('icon_name', $medal->icon_name) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    @error('icon_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="color" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Cor</label>
                    <input type="text" name="color" id="color" required value="{{ old('color', $medal->color) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    @error('color') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="rarity" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Raridade</label>
                    <select name="rarity" id="rarity" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                        <option value="bronze" {{ old('rarity', $medal->rarity) === 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="silver" {{ old('rarity', $medal->rarity) === 'silver' ? 'selected' : '' }}>Prata</option>
                        <option value="gold" {{ old('rarity', $medal->rarity) === 'gold' ? 'selected' : '' }}>Ouro</option>
                        <option value="platinum" {{ old('rarity', $medal->rarity) === 'platinum' ? 'selected' : '' }}>Platina</option>
                    </select>
                    @error('rarity') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="difficulty" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Dificuldade</label>
                    <select name="difficulty" id="difficulty" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                        <option value="easy" {{ old('difficulty', $medal->difficulty ?? 'medium') === 'easy' ? 'selected' : '' }}>Fácil</option>
                        <option value="medium" {{ old('difficulty', $medal->difficulty ?? 'medium') === 'medium' ? 'selected' : '' }}>Médio</option>
                        <option value="hard" {{ old('difficulty', $medal->difficulty ?? 'medium') === 'hard' ? 'selected' : '' }}>Difícil</option>
                        <option value="advanced" {{ old('difficulty', $medal->difficulty ?? 'medium') === 'advanced' ? 'selected' : '' }}>Avançado</option>
                    </select>
                    @error('difficulty') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="explanation" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Explicação</label>
                <textarea name="explanation" id="explanation" rows="3" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('explanation', $medal->explanation) }}</textarea>
                @error('explanation') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="tips" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Dicas</label>
                <textarea name="tips" id="tips" rows="2" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('tips', $medal->tips) }}</textarea>
                @error('tips') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="incentive_message" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Mensagem de incentivo</label>
                <textarea name="incentive_message" id="incentive_message" rows="2" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('incentive_message', $medal->incentive_message) }}</textarea>
                @error('incentive_message') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-wrap gap-6 p-4 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $medal->is_active) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Ativo</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_pro_only" value="1" {{ old('is_pro_only', $medal->is_pro_only) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Exclusivo PRO</span>
                </label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold rounded-xl transition-colors">
                    Atualizar Medalha
                </button>
                <a href="{{ route('admin.gamification.medals.index') }}" class="px-8 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
