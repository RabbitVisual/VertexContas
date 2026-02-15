<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Nova Dica</x-slot>

    <div class="max-w-3xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.insights.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-[#11C76F] transition-all">
                <x-icon name="arrow-left" style="duotone" />
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Nova Dica do Vertex Bot</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Cadastre uma nova mensagem para o assistente virtual.</p>
            </div>
        </div>

        <form action="{{ route('admin.insights.store') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="trigger_event" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Gatilho</label>
                <select name="trigger_event" id="trigger_event" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    <option value="">Selecione...</option>
                    <option value="low_balance" {{ old('trigger_event') === 'low_balance' ? 'selected' : '' }}>Saldo baixo</option>
                    <option value="budget_reached" {{ old('trigger_event') === 'budget_reached' ? 'selected' : '' }}>Orçamento atingido</option>
                    <option value="savings_milestone" {{ old('trigger_event') === 'savings_milestone' ? 'selected' : '' }}>Marco de economia</option>
                    <option value="daily_tip" {{ old('trigger_event') === 'daily_tip' ? 'selected' : '' }}>Dica do dia</option>
                </select>
                @error('trigger_event') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="content" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Conteúdo da mensagem</label>
                <textarea name="content" id="content" rows="4" required placeholder="Ex: Parabéns! Você gastou menos de 50% da sua renda este mês! Use {{ category }} e {{ percent }} para orçamentos." class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('content') }}</textarea>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Placeholders: &#123;&#123; category &#125;&#125;, &#123;&#123; percent &#125;&#125;</p>
                @error('content') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="level" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nível</label>
                <select name="level" id="level" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    <option value="info" {{ old('level') === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="success" {{ old('level') === 'success' ? 'selected' : '' }}>Sucesso</option>
                    <option value="warning" {{ old('level') === 'warning' ? 'selected' : '' }}>Aviso</option>
                    <option value="danger" {{ old('level') === 'danger' ? 'selected' : '' }}>Perigo</option>
                </select>
                @error('level') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                <label for="is_active" class="text-sm font-bold text-slate-700 dark:text-slate-300">Ativo (visível para o robô)</label>
            </div>

            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                <input type="checkbox" name="is_pro_only" id="is_pro_only" value="1" {{ old('is_pro_only') ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                <label for="is_pro_only" class="text-sm font-bold text-slate-700 dark:text-slate-300">Exclusivo PRO</label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold rounded-xl transition-colors">
                    Salvar Dica
                </button>
                <a href="{{ route('admin.insights.index') }}" class="px-8 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
