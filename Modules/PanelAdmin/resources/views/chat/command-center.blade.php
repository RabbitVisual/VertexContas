<x-paneladmin::layouts.master>
    <x-slot name="navbarTitle">Chat VIP - Central de Comando</x-slot>

    <div class="flex flex-col gap-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-4 text-emerald-600 dark:text-emerald-400" role="alert">
                <x-icon name="circle-check" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-center gap-4 text-amber-600 dark:text-amber-400" role="alert">
                <x-icon name="triangle-exclamation" style="duotone" class="w-5 h-5 shrink-0" />
                <p class="font-bold text-sm">{{ session('warning') }}</p>
            </div>
        @endif

        <div class="flex flex-col min-h-[calc(100vh-14rem)] rounded-xl border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800/50 shadow-sm overflow-hidden">
            <div class="flex flex-1 min-h-0">
                {{-- Left: Chat List --}}
                <div class="w-80 shrink-0 flex flex-col border-r border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800/50">
                    <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                        <h2 class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-widest flex items-center gap-2">
                            <x-icon name="comments" style="duotone" class="text-[#11C76F] w-4 h-4" />
                            Conversas Ativas
                        </h2>
                    </div>
                    <div class="flex-1 overflow-y-auto p-2 space-y-1">
                        @php $sectorLabels = ['support' => 'Suporte', 'technical' => 'Técnico', 'billing' => 'Financeiro', 'admin' => 'Admin']; @endphp
                        @forelse($conversations as $conv)
                            <a href="{{ route('admin.chat.show', $conv) }}"
                               class="flex items-center gap-3 p-3 rounded-xl transition-all {{ ($selectedConversation->id ?? null) === $conv->id ? 'bg-[#11C76F]/10 border border-[#11C76F]/20' : 'hover:bg-slate-50 dark:hover:bg-slate-700/30 border border-transparent' }}">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-black text-slate-600 dark:text-slate-400 text-sm">
                                    {{ $conv->user ? substr($conv->user->name ?? 'U', 0, 1) : '?' }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $conv->user?->name }}</span>
                                        @if($conv->user?->isPro())
                                            <span class="shrink-0 px-1.5 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase rounded inline-flex items-center gap-0.5">
                                                <x-icon name="crown" style="duotone" class="w-2.5 h-2.5" /> PRO
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $sectorLabels[$conv->sector] ?? $conv->sector }}</span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    </div>
                                    @if($conv->latestMessage)
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-1">{{ $conv->latestMessage->isSystemNotice() ? 'Sistema' : ($conv->latestMessage->sender?->name ?? '?') }}: {{ Str::limit($conv->latestMessage->body, 40) }}</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-6 text-center text-slate-500 dark:text-slate-400 text-sm font-medium">
                                <x-icon name="comments" style="duotone" class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2" />
                                Nenhuma conversa ativa
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Center: Message Area --}}
                <div class="flex-1 flex flex-col min-w-0 bg-slate-50/50 dark:bg-slate-900/30">
                    @if($selectedConversation ?? null)
                        @php $conv = $selectedConversation; @endphp
                        <div class="shrink-0 p-4 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-black text-slate-900 dark:text-white">{{ $conv->user?->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $conv->user?->email }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">{{ $sectorLabels[$conv->sector] ?? $conv->sector }}</span>
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button" @click="open = !open"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#11C76F]/10 text-[#11C76F] rounded-xl text-sm font-bold hover:bg-[#11C76F]/20 transition-all">
                                        <x-icon name="arrows-rotate" style="duotone" class="w-4 h-4" />
                                        Transferir para Setor
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-cloak
                                         class="absolute right-0 top-full mt-1 z-50 py-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-xl min-w-[180px]">
                                        <form action="{{ route('admin.chat.transfer', $conv) }}" method="POST" class="space-y-0">
                                            @csrf
                                            <button type="submit" name="sector" value="technical" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Técnico</button>
                                            <button type="submit" name="sector" value="billing" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Financeiro</button>
                                            <button type="submit" name="sector" value="admin" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Admin</button>
                                            <button type="submit" name="sector" value="support" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Suporte</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="admin-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 min-h-0">
                            @foreach($conv->messages as $msg)
                                <div class="{{ $msg->isSystemNotice() ? 'flex justify-center' : ($msg->sender_id === auth()->id() ? 'flex justify-end' : 'flex justify-start') }}">
                                    @if($msg->isSystemNotice())
                                        <div class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-sm max-w-[80%] text-center">
                                            {!! nl2br(e($msg->body)) !!}
                                        </div>
                                    @else
                                        <div class="max-w-[75%] {{ $msg->sender_id === auth()->id() ? 'order-2' : '' }}">
                                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-1">{{ $msg->sender?->name }}</p>
                                            <div class="px-4 py-3 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-[#11C76F] text-white rounded-tr-md' : 'bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-tl-md' }}">
                                                {{ $msg->body }}
                                            </div>
                                            <p class="text-[9px] text-slate-400 mt-1">{{ $msg->created_at->format('d/m H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <form id="admin-chat-form" action="{{ route('admin.chat.send', $conv) }}" method="POST" class="shrink-0 p-4 border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800" data-no-loading
                              x-data="adminChatTyping()">
                            @csrf
                            <div class="flex gap-3">
                                <input type="text" name="body" id="admin-chat-input" required placeholder="Escreva aqui sua resposta..."
                                       @input="onTyping()"
                                       class="flex-1 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#11C76F]/20 focus:border-[#11C76F]">
                                <button type="submit" id="admin-chat-submit" class="px-6 py-3 bg-[#11C76F] text-white font-bold rounded-xl hover:bg-[#0EA85A] transition-all shrink-0">
                                    Enviar
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="flex-1 flex items-center justify-center text-slate-500 dark:text-slate-400">
                            <div class="text-center">
                                <x-icon name="comments" style="duotone" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" />
                                <p class="font-bold">Selecione uma conversa</p>
                                <p class="text-sm mt-1">Ou aguarde um cliente PRO iniciar o chat.</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right: User X-Ray + Inspection --}}
                <div class="w-96 shrink-0 flex flex-col border-l border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800/50 overflow-y-auto">
                    @if($selectedConversation ?? null)
                        @php $user = $selectedConversation->user; @endphp
                        <div class="p-6 space-y-6">
                            <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[11px] flex items-center gap-2">
                                <x-icon name="user" style="duotone" class="text-[#11C76F]" />
                                Perfil do Cliente
                            </h3>
                            <div class="flex flex-col items-center">
                                @if($user->photo)
                                    <img src="{{ $user->photo_url }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-slate-100 dark:ring-slate-700">
                                @else
                                    <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center font-black text-2xl text-slate-500">
                                        {{ substr($user->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <h4 class="font-black text-slate-900 dark:text-white mt-3">{{ $user->name }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-full">{{ $user->email }}</p>
                                @if($user->isPro())
                                    <span class="mt-2 px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase rounded-lg inline-flex items-center gap-1">
                                        <x-icon name="crown" style="duotone" class="w-3 h-3" /> PRO
                                    </span>
                                @endif
                            </div>

                            <h3 class="font-black text-slate-400 uppercase tracking-[0.2em] text-[11px] flex items-center gap-2">
                                <x-icon name="chart-pie" style="duotone" class="text-indigo-500" /> Snapshot Financeiro
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-indigo-50/50 dark:bg-indigo-500/5 border border-indigo-100 dark:border-indigo-500/10">
                                    <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase block">Renda</span>
                                    <span class="text-base font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['monthly_income'] ?? 0) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700">
                                    <span class="text-[10px] font-black text-slate-500 uppercase block">Saldo</span>
                                    <span class="text-base font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['account_balance'] ?? 0) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/30 border border-slate-100 dark:border-slate-700">
                                    <span class="text-[10px] font-black text-slate-500 uppercase block">Despesas</span>
                                    <span class="text-base font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['monthly_expenses'] ?? 0) }}</span>
                                </div>
                                <div class="p-3 rounded-xl {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/5' : 'bg-rose-50 dark:bg-rose-500/5' }} border {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'border-emerald-200 dark:border-emerald-500/20' : 'border-rose-200 dark:border-rose-500/20' }}">
                                    <span class="text-[10px] font-black uppercase block">Fluxo Livre</span>
                                    <span class="text-base font-black {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ format_currency($financialSnapshot['free_cashflow'] ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10">
                                <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase block">Reserva</span>
                                <span class="text-base font-black text-slate-900 dark:text-white">{{ format_number($reserveMonths ?? 0, 1) }} meses</span>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="block w-full py-4 bg-[#11C76F] text-white font-black text-xs uppercase tracking-widest rounded-2xl text-center hover:bg-[#0EA85A] transition-all mb-3">
                                    <x-icon name="folder-open" style="duotone" class="w-4 h-4 inline mr-2" />
                                    Dossier Completo (Admin)
                                </a>
                                @if($user->isPro())
                                    @if($hasExistingInspection ?? false)
                                        <button type="button" disabled
                                                class="block w-full py-4 bg-slate-100 dark:bg-slate-700 text-slate-400 font-black text-xs uppercase tracking-widest rounded-2xl text-center cursor-not-allowed">
                                            <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                            Inspeção em Andamento
                                        </button>
                                        <p class="text-[9px] text-slate-400 text-center mt-2">Aguardando autorização ou sessão ativa.</p>
                                    @else
                                        <form action="{{ route('admin.chat.inspection.request', $conv) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="block w-full py-4 bg-[#11C76F] text-white font-black text-xs uppercase tracking-widest rounded-2xl text-center hover:bg-[#0EA85A] transition-all">
                                                <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                                Iniciar Inspeção Técnica
                                            </button>
                                        </form>
                                        <p class="text-[9px] text-slate-400 text-center mt-2">Acesso remoto ao painel do cliente PRO via Chat VIP.</p>
                                    @endif
                                @else
                                    <button type="button" disabled
                                            class="block w-full py-4 bg-slate-100 dark:bg-slate-700 text-slate-400 font-black text-xs uppercase tracking-widest rounded-2xl text-center cursor-not-allowed">
                                        <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                        Inspeção via Chamado
                                    </button>
                                    <p class="text-[9px] text-slate-400 text-center mt-2">Usuário Free: use a inspeção pelo chamado/ticket.</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex-1 flex items-center justify-center p-6 text-slate-500 dark:text-slate-400 text-sm">
                            Selecione uma conversa para ver o perfil do cliente
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($selectedConversation ?? null)
    @php
        $typingUrl = route('admin.chat.typing', $selectedConversation);
        $sendUrl = route('admin.chat.send', $selectedConversation);
    @endphp
    @push('scripts')
    <script>
        (function() {
            const form = document.getElementById('admin-chat-form');
            const input = document.getElementById('admin-chat-input');
            const submitBtn = document.getElementById('admin-chat-submit');
            const messagesEl = document.getElementById('admin-chat-messages');
            const sendUrl = @json($sendUrl);
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text ?? '';
                return div.innerHTML;
            }
            function formatTime(iso) {
                if (!iso) return '';
                const d = new Date(iso);
                return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
            }
            function appendAgentMessage(msg) {
                if (!messagesEl) return;
                const bodySafe = escapeHtml(msg.body ?? '');
                const wrap = document.createElement('div');
                wrap.className = 'flex justify-end';
                wrap.innerHTML = '<div class="max-w-[75%] order-2"><p class="text-[10px] font-bold text-slate-500 mb-1">' + escapeHtml(msg.sender_name) + '</p><div class="px-4 py-3 rounded-2xl bg-[#11C76F] text-white rounded-tr-md">' + bodySafe + '</div><p class="text-[9px] text-slate-400 mt-1">' + formatTime(msg.created_at) + '</p></div>';
                messagesEl.appendChild(wrap);
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            if (form && input && submitBtn && messagesEl && sendUrl) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const body = (input.value || '').trim();
                    if (!body) return;
                    const origLabel = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Enviando...';
                    fetch(sendUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new URLSearchParams({ body: body, _token: csrf }),
                    })
                        .then(function(res) {
                            if (res.ok) return res.json();
                            throw new Error('Falha ao enviar');
                        })
                        .then(function(data) {
                            if (data.message) {
                                appendAgentMessage(data.message);
                                input.value = '';
                            }
                        })
                        .catch(function() {
                            submitBtn.textContent = 'Erro – tente novamente';
                        })
                        .finally(function() {
                            submitBtn.disabled = false;
                            submitBtn.textContent = origLabel;
                        });
                });
            }
        })();
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminChatTyping', () => ({
                timeout: null,
                onTyping() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        fetch('{{ $typingUrl }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        }).catch(() => {});
                    }, 400);
                }
            }));
        });
    </script>
    @endpush
    @endif
</x-paneladmin::layouts.master>
