<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Logs de Mensageria</x-slot>

    <x-paneladmin::page title="Logs de Mensageria" subtitle="Auditoria de todos os disparos de e-mail. Filtre por destinatário ou status. Visualize o conteúdo enviado ou reenvie em caso de falha.">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400 mb-6" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-4 text-red-600 dark:text-red-400 mb-6" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="p-4 bg-sky-500/10 border border-sky-500/20 rounded-xl flex items-center gap-4 text-sky-600 dark:text-sky-400 mb-6" role="alert">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('info') }}</p>
            </div>
        @endif

        {{-- Filtros --}}
        <form action="{{ route('admin.mail.logs.index') }}" method="GET" class="flex flex-wrap items-center gap-3 mb-6">
            <input type="text" name="recipient_email" value="{{ request('recipient_email') }}" placeholder="E-mail do destinatário"
                class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white text-sm min-w-[200px]">
            <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 min-w-[140px]">
                <option value="">Todos os status</option>
                <option value="queued" {{ request('status') === 'queued' ? 'selected' : '' }}>Na fila</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviado</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Falha</option>
            </select>
            <select name="template_key" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white text-sm font-medium focus:ring-2 focus:ring-[#11C76F]/20 min-w-[180px]">
                <option value="">Todos os templates</option>
                <option value="welcome_user" {{ request('template_key') === 'welcome_user' ? 'selected' : '' }}>welcome_user</option>
                <option value="password_reset" {{ request('template_key') === 'password_reset' ? 'selected' : '' }}>password_reset</option>
                <option value="pro_activated" {{ request('template_key') === 'pro_activated' ? 'selected' : '' }}>pro_activated</option>
                <option value="ticket_replied" {{ request('template_key') === 'ticket_replied' ? 'selected' : '' }}>ticket_replied</option>
                <option value="monthly_report_ready" {{ request('template_key') === 'monthly_report_ready' ? 'selected' : '' }}>monthly_report_ready</option>
            </select>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white text-sm">
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white text-sm">
            <button type="submit" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Filtrar</button>
        </form>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 uppercase tracking-wider text-xs font-bold sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-4">Data</th>
                            <th scope="col" class="px-6 py-4">Destinatário</th>
                            <th scope="col" class="px-6 py-4">Template</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4">Resposta SMTP</th>
                            <th scope="col" class="px-6 py-4">Erro</th>
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $log->sent_at?->format('d/m/Y H:i') ?? $log->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $log->recipient_email }}</td>
                                <td class="px-6 py-4 font-mono text-xs">{{ $log->template_key }}</td>
                                <td class="px-6 py-4">
                                    @if($log->status === 'sent')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-bold">Enviado</span>
                                    @elseif($log->status === 'queued')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-600/50 text-slate-600 dark:text-slate-400 text-xs font-bold">Na fila</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-xs font-bold">Falhou</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-[160px]">
                                    @if($log->smtp_response)
                                        <span title="{{ $log->smtp_response }}" class="block truncate text-slate-600 dark:text-slate-400 text-xs">{{ Str::limit($log->smtp_response, 40) }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-[200px]">
                                    @if($log->error_details)
                                        <span title="{{ $log->error_details }}" class="block truncate text-red-600 dark:text-red-400 text-xs">{{ Str::limit($log->error_details, 50) }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button type="button" data-log-id="{{ $log->id }}" data-body-url="{{ route('admin.mail.logs.body', $log) }}"
                                        class="visualizar-btn inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-500/20 font-bold text-sm transition-colors mr-1">
                                        <x-icon name="eye" style="duotone" class="w-4 h-4" /> Visualizar
                                    </button>
                                    @if($log->status === 'failed')
                                        <form action="{{ route('admin.mail.logs.resend', $log) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 font-bold text-sm transition-colors">
                                                <x-icon name="arrow-rotate-right" style="duotone" class="w-4 h-4" /> Reenviar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">Nenhum registro de e-mail ainda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.mail.templates.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-[#11C76F]">
                <x-icon name="envelope" style="duotone" class="w-4 h-4" /> Gerenciar templates
            </a>
        </div>

        {{-- Modal Visualizar: conteúdo exato do e-mail enviado --}}
        <div id="modal-visualizar" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div class="fixed inset-0 bg-black/50" data-close="modal"></div>
            <div class="fixed inset-4 md:inset-10 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 w-full max-w-4xl max-h-[90vh] flex flex-col">
                    <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Conteúdo enviado ao destinatário</h3>
                        <button type="button" class="modal-close p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700" aria-label="Fechar">
                            <x-icon name="xmark" style="solid" class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="flex-1 overflow-auto p-4">
                        <iframe id="modal-body-iframe" title="Conteúdo do e-mail" class="w-full min-h-[400px] border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900" sandbox="allow-same-origin"></iframe>
                        <p id="modal-body-fallback" class="text-slate-500 dark:text-slate-400 text-sm hidden">Carregando…</p>
                    </div>
                </div>
            </div>
        </div>
    </x-paneladmin::page>

    @push('scripts')
    <script>
        document.querySelectorAll('.visualizar-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var url = this.getAttribute('data-body-url');
                var modal = document.getElementById('modal-visualizar');
                var iframe = document.getElementById('modal-body-iframe');
                var fallback = document.getElementById('modal-body-fallback');
                fallback.classList.remove('hidden');
                iframe.classList.add('hidden');
                fetch(url).then(function(r) { return r.text(); }).then(function(html) {
                    iframe.srcdoc = html;
                    iframe.classList.remove('hidden');
                    fallback.classList.add('hidden');
                }).catch(function() {
                    fallback.textContent = 'Não foi possível carregar o conteúdo.';
                });
                modal.classList.remove('hidden');
            });
        });
        document.querySelectorAll('[data-close="modal"], .modal-close').forEach(function(el) {
            el.addEventListener('click', function() {
                document.getElementById('modal-visualizar').classList.add('hidden');
            });
        });
    </script>
    @endpush
</x-paneladmin::layouts.master>
