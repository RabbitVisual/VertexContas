<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Editar Regra</x-slot>

    <div class="max-w-3xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.gamification.rules.index') }}" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-900 border border-gray-100 dark:border-gray-800 flex items-center justify-center text-slate-400 hover:text-[#11C76F] transition-all">
                <x-icon name="arrow-left" style="duotone" />
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Editar Regra</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">{{ $rule->trigger_key }}</p>
            </div>
        </div>

        <form action="{{ route('admin.gamification.rules.update', $rule) }}" method="POST" class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 border border-gray-100 dark:border-gray-800 shadow-sm space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="trigger_key" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Chave do gatilho</label>
                    <input type="text" name="trigger_key" id="trigger_key" required value="{{ old('trigger_key', $rule->trigger_key) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-mono text-sm">
                    @error('trigger_key') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="condition_type" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Tipo de condição</label>
                    <select name="condition_type" id="condition_type" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                        <option value="pillar_threshold" {{ old('condition_type', $rule->condition_type) === 'pillar_threshold' ? 'selected' : '' }}>Pilar 50/30/20</option>
                        <option value="reserve_months" {{ old('condition_type', $rule->condition_type) === 'reserve_months' ? 'selected' : '' }}>Reserva (meses)</option>
                        <option value="consecutive_days" {{ old('condition_type', $rule->condition_type) === 'consecutive_days' ? 'selected' : '' }}>Dias consecutivos</option>
                        <option value="savings_threshold" {{ old('condition_type', $rule->condition_type) === 'savings_threshold' ? 'selected' : '' }}>Poupança %</option>
                        <option value="pro_subscription" {{ old('condition_type', $rule->condition_type) === 'pro_subscription' ? 'selected' : '' }}>Assinante PRO</option>
                    </select>
                    @error('condition_type') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="medal_id" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Medalha vinculada</label>
                    <select name="medal_id" id="medal_id" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                        <option value="">Nenhuma</option>
                        @foreach($medals as $medal)
                            <option value="{{ $medal->id }}" {{ old('medal_id', $rule->medal_id) == $medal->id ? 'selected' : '' }}>{{ $medal->title }} ({{ $medal->trigger_key }})</option>
                        @endforeach
                    </select>
                    @error('medal_id') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="priority" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Prioridade</label>
                    <input type="number" name="priority" id="priority" required min="0" max="999" value="{{ old('priority', $rule->priority) }}" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                    @error('priority') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="level" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Nível</label>
                    <select name="level" id="level" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm">
                        <option value="info" {{ old('level', $rule->level) === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ old('level', $rule->level) === 'success' ? 'selected' : '' }}>Sucesso</option>
                        <option value="warning" {{ old('level', $rule->level) === 'warning' ? 'selected' : '' }}>Aviso</option>
                        <option value="danger" {{ old('level', $rule->level) === 'danger' ? 'selected' : '' }}>Perigo</option>
                    </select>
                    @error('level') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="condition_params" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Parâmetros JSON</label>
                <textarea name="condition_params" id="condition_params" rows="6" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-mono text-xs resize-none">{{ old('condition_params', json_encode($rule->condition_params ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
                @error('condition_params') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="message_override" class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Mensagem (Vertex Bot)</label>
                <textarea name="message_override" id="message_override" rows="2" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-[#11C76F]/20 text-slate-800 dark:text-white font-medium text-sm resize-none">{{ old('message_override', $rule->message_override) }}</textarea>
                @error('message_override') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-slate-800 rounded-2xl">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $rule->is_active) ? 'checked' : '' }} class="rounded border-slate-300 dark:border-slate-600 text-[#11C76F] focus:ring-[#11C76F]/20">
                <label for="is_active" class="text-sm font-bold text-slate-700 dark:text-slate-300">Regra ativa</label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-8 py-3 bg-[#11C76F] hover:bg-[#0EA85A] text-white font-bold rounded-xl transition-colors">
                    Atualizar Regra
                </button>
                <a href="{{ route('admin.gamification.rules.index') }}" class="px-8 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-medium hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-paneladmin::layouts.master>
