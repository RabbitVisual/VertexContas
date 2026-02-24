@php
    $version = $version ?? new \Modules\PWA\Models\PwaVersion();
@endphp
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Versão <span class="text-red-500">*</span></label>
        <input type="text" name="version" value="{{ old('version', $version->version) }}" required maxlength="32" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="1.2.0">
        @error('version')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data de publicação</label>
        <input type="datetime-local" name="released_at" value="{{ old('released_at', $version->released_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
        @error('released_at')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>
<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas da versão</label>
    <textarea name="release_notes" rows="4" maxlength="2000" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]" placeholder="Correções e melhorias...">{{ old('release_notes', $version->release_notes) }}</textarea>
    @error('release_notes')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
<div class="mt-4 flex items-center gap-3">
    <input type="hidden" name="is_force_update" value="0">
    <input type="checkbox" name="is_force_update" value="1" id="is_force_update" {{ old('is_force_update', $version->is_force_update) ? 'checked' : '' }} class="rounded border-gray-300 text-[#11C76F] focus:ring-[#11C76F]/20">
    <label for="is_force_update" class="text-sm font-medium text-gray-700 dark:text-gray-300">Exigir atualização (usuários verão aviso para recarregar o app)</label>
</div>
