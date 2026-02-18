<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Sugestões Wiki</x-slot>

    <x-paneladmin::page title="Sugestões da Wiki" subtitle="Ideias e solicitações enviadas pelo time de suporte.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <x-slot name="header">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.wiki.articles') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="file-lines" style="duotone" class="w-4 h-4" /> Artigos
                </a>
                <a href="{{ route('admin.wiki.categories') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2">
                    <x-icon name="folder" style="duotone" class="w-4 h-4" /> Categorias
                </a>
            </div>
        </x-slot>

        <div x-data="wikiSuggestionModal()">
        <x-paneladmin::card>
            <x-paneladmin::table-wrapper>
                <x-slot name="thead">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Colaborador</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sugestão</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Data</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Ações</th>
                    </tr>
                </x-slot>
                @forelse($suggestions as $suggestion)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors even:bg-slate-50/50 dark:even:bg-slate-800/30">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-600 dark:text-slate-400 text-sm shrink-0">
                                    <x-icon name="user" style="duotone" class="w-5 h-5" />
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm block truncate">{{ $suggestion->user?->name ?? '—' }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 truncate block">{{ $suggestion->user?->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 max-w-sm">
                            <span class="font-bold text-slate-900 dark:text-white text-sm block truncate">{{ $suggestion->title }}</span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mt-0.5">"{{ Str::limit($suggestion->description, 60) }}"</p>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $statusThemes = [
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'reviewed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'implementing' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendente',
                                    'reviewed' => 'Revisado',
                                    'implementing' => 'Em escrita',
                                    'completed' => 'Concluído',
                                    'rejected' => 'Rejeitado',
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $statusThemes[$suggestion->status] ?? 'bg-slate-100 text-slate-700' }}">
                                <x-icon name="circle-dot" style="duotone" class="w-3.5 h-3.5" />
                                {{ $statusLabels[$suggestion->status] ?? $suggestion->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center text-sm text-slate-600 dark:text-slate-400 tabular-nums">
                            {{ $suggestion->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                    data-id="{{ $suggestion->id }}"
                                    data-status="{{ $suggestion->status }}"
                                    data-notes="{{ e($suggestion->admin_notes ?? '') }}"
                                    @click="openStatusModal($el.dataset.id, $el.dataset.status, $el.dataset.notes || '')"
                                    class="p-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 hover:bg-[#11C76F]/10 hover:text-[#11C76F] transition-colors"
                                    title="Atualizar status">
                                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4" />
                                </button>
                                <form action="{{ route('admin.wiki.suggestions.destroy', $suggestion) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta sugestão permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors" title="Excluir">
                                        <x-icon name="trash" style="duotone" class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                    <x-icon name="lightbulb" style="duotone" class="w-8 h-8 text-slate-400" />
                                </div>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Nenhuma sugestão no momento.</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500">As sugestões enviadas pelo time de suporte aparecerão aqui.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-paneladmin::table-wrapper>
            @if($suggestions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $suggestions->links() }}
                </div>
            @endif
        </x-paneladmin::card>

        {{-- Modal Atualizar Status --}}
        <div x-cloak>
            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div @click.away="open = false" class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-black text-slate-900 dark:text-white">Atualizar Sugestão</h3>
                            <button type="button" @click="open = false" class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-600 dark:hover:text-slate-300">
                                <x-icon name="xmark" style="duotone" class="w-5 h-5" />
                            </button>
                        </div>
                        <form :action="'{{ url('admin/wiki/suggestions') }}/' + suggestionId" method="POST" class="space-y-6" x-ref="form">
                            @csrf
                            @method('PUT')
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</label>
                                <div class="relative">
                                    <select name="status" x-model="status" required class="wiki-select w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                                        <option value="pending">Pendente (Novo)</option>
                                        <option value="reviewed">Revisado</option>
                                        <option value="implementing">Em Escrita</option>
                                        <option value="completed">Concluído / Publicado</option>
                                        <option value="rejected">Rejeitado / Duplicado</option>
                                    </select>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"><x-icon name="chevron-down" style="duotone" class="w-4 h-4 text-slate-400" /></span>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notas administrativas (interno)</label>
                                <textarea name="admin_notes" x-model="notes" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium text-sm resize-none placeholder:text-slate-400" placeholder="Ex: Ótima ideia, agendado para próxima sprint."></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-400 font-bold text-sm">Cancelar</button>
                                <button type="submit" class="flex-1 py-3 rounded-xl bg-[#11C76F] text-white font-bold text-sm hover:bg-[#0EA85A]">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </x-paneladmin::page>

    @push('styles')
    <style>.wiki-select{appearance:none!important;-webkit-appearance:none!important;-moz-appearance:none!important;background-image:none!important}</style>
    [x-cloak]{display:none!important}
    @endpush
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', function() {
            Alpine.data('wikiSuggestionModal', function() {
                return {
                    open: false,
                    suggestionId: null,
                    status: 'pending',
                    notes: '',
                    openStatusModal(id, currentStatus, adminNotes) {
                        this.suggestionId = id;
                        this.status = currentStatus || 'pending';
                        this.notes = adminNotes || '';
                        this.open = true;
                    }
                };
            });
        });
    </script>
    @endpush
</x-paneladmin::layouts.master>
