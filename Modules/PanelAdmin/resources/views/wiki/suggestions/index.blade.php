<x-paneladmin::layouts.master>
    <div x-data="{
        statusModal: false,
        selectedSuggestion: null,
        status: 'pending',
        notes: '',
        openStatusModal(suggestion) {
            this.selectedSuggestion = suggestion;
            this.status = suggestion.status;
            this.notes = suggestion.admin_notes || '';
            this.statusModal = true;
        }
    }" class="space-y-8 animate-in fade-in duration-500">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Sugestões da Wiki</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-1">Gerencie as ideias e solicitações enviadas pelo time de suporte.</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Colaborador</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sugestão</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Data</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($suggestions as $suggestion)
                            <tr class="group hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-all">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs">
                                            {{ substr($suggestion->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 dark:text-white text-sm">{{ $suggestion->user->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $suggestion->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col max-w-md">
                                        <span class="font-black text-slate-800 dark:text-white text-sm leading-tight mb-1">{{ $suggestion->title }}</span>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 italic font-medium">"{{ $suggestion->description }}"</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusThemes = [
                                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                                            'reviewed' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                            'implementing' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
                                            'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $statusThemes[$suggestion->status] }}">
                                        {{ $suggestion->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $suggestion->created_at->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="openStatusModal({{ json_encode($suggestion) }})" class="p-2 rounded-xl bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-primary transition-colors">
                                            <x-icon name="pen-to-square" class="text-xs" />
                                        </button>
                                        <form action="{{ route('admin.wiki.suggestions.destroy', $suggestion) }}" method="POST" onsubmit="return confirm('Apagar sugestão permanentemente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 hover:bg-red-100 transition-colors">
                                                <x-icon name="trash" class="text-xs" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($suggestions->hasPages())
                <div class="p-6 border-t border-gray-50 dark:border-gray-800">
                    {{ $suggestions->links() }}
                </div>
            @endif
        </div>

        <!-- Status Modal -->
        <div x-show="statusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="statusModal = false" class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                <div class="p-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-800 dark:text-white">Atualizar Sugestão</h3>
                        <button @click="statusModal = false" class="text-slate-400 hover:text-red-500 transition-colors">
                            <x-icon name="xmark" class="text-xl" />
                        </button>
                    </div>

                    <form :action="'{{ route('admin.wiki.suggestions.update', '') }}/' + selectedSuggestion?.id" method="POST" class="space-y-4">
                        @csrf @method('PUT')

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Status do Processamento</label>
                            <select name="status" x-model="status" required class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-bold text-sm">
                                <option value="pending">Pendente (Novo)</option>
                                <option value="reviewed">Revisado</option>
                                <option value="implementing">Em Escrita</option>
                                <option value="completed">Concluído / Publicado</option>
                                <option value="rejected">Rejeitado / Duplicado</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-1">Notas Administrativas (Interno)</label>
                            <textarea name="admin_notes" x-model="notes" rows="4" class="w-full px-6 py-4 bg-gray-50 dark:bg-slate-800 border-none rounded-[1.5rem] focus:ring-2 focus:ring-primary/20 text-slate-800 dark:text-white font-medium text-sm resize-none" placeholder="Ex: Ótima ideia, agendado para semana que vem."></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-primary-dark transition-all">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-paneladmin::layouts.master>
