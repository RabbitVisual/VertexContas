<x-panelsuporte::layouts.master title="Chat VIP - Central de Comando" :navbarTitle="'Chat VIP - Central de Comando'">
    {{-- Container integrado ao painel (sem -m-6): ocupa altura disponível dentro do main --}}
    <div class="flex flex-col min-h-[calc(100vh-14rem)] rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900/50 shadow-sm overflow-hidden">
        <div class="flex flex-1 min-h-0">
            {{-- Left: Chat List --}}
            <div class="w-80 shrink-0 flex flex-col border-r border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900/50">
                <div class="p-4 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="font-black text-slate-900 dark:text-white text-sm uppercase tracking-widest flex items-center gap-2">
                        <x-icon name="comments" style="duotone" class="text-primary w-4 h-4" />
                        Conversas Ativas
                    </h2>
                </div>
                <div class="flex-1 overflow-y-auto p-2 space-y-1">
                    @forelse($conversations as $conv)
                        <a href="{{ route('support.chat.show', $conv) }}"
                           class="flex items-center gap-3 p-3 rounded-xl transition-all {{ ($selectedConversation->id ?? null) === $conv->id ? 'bg-primary/10 border border-primary/20' : 'hover:bg-gray-50 dark:hover:bg-slate-800 border border-transparent' }}">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center font-black text-slate-600 dark:text-slate-400 text-sm">
                                {{ $conv->user ? substr($conv->user->first_name ?? 'U', 0, 1) : '?' }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $conv->user?->first_name }} {{ $conv->user?->last_name }}</span>
                                    @if($conv->user?->isPro())
                                        <span class="shrink-0 px-1.5 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase rounded">
                                            <x-icon name="crown" style="solid" class="w-2.5 h-2.5 inline" /> PRO
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @php
                                        $sectorLabels = ['support' => 'Suporte', 'technical' => 'Técnico', 'billing' => 'Financeiro', 'admin' => 'Admin'];
                                        $latest = $conv->latestMessage;
                                    @endphp
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $sectorLabels[$conv->sector] ?? $conv->sector }}</span>
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                </div>
                                @if($latest)
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-1">{{ $latest->isSystemNotice() ? 'Sistema' : ($latest->sender?->first_name ?? '?') }}: {{ Str::limit($latest->body, 40) }}</p>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-slate-500 text-sm font-medium">Nenhuma conversa ativa</div>
                    @endforelse
                </div>
            </div>

            {{-- Center: Message Area --}}
            <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 dark:bg-slate-900/30">
                @if($selectedConversation)
                    @php $conv = $selectedConversation; @endphp
                    <div class="shrink-0 p-4 border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-black text-slate-900 dark:text-white">{{ $conv->user?->first_name }} {{ $conv->user?->last_name }}</h3>
                            <p class="text-xs text-slate-500">{{ $conv->user?->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">{{ $sectorLabels[$conv->sector] ?? $conv->sector }}</span>
                            <div id="transfer-dropdown" class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-xl text-sm font-bold hover:bg-primary/20 transition-all">
                                    <x-icon name="arrows-rotate" style="duotone" class="w-4 h-4" />
                                    Transferir para Setor
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute right-0 top-full mt-1 z-50 py-2 bg-white dark:bg-slate-900 rounded-xl border border-gray-200 dark:border-slate-700 shadow-xl min-w-[180px]">
                                    <form action="{{ route('support.chat.transfer', $conv) }}" method="POST" class="space-y-0">
                                        @csrf
                                        <button type="submit" name="sector" value="technical" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">Técnico</button>
                                        <button type="submit" name="sector" value="billing" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">Financeiro</button>
                                        <button type="submit" name="sector" value="admin" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">Admin</button>
                                        <button type="submit" name="sector" value="support" class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800">Suporte</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="support-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 min-h-0">
                        @foreach($conv->messages as $msg)
                            <div class="{{ $msg->isSystemNotice() ? 'flex justify-center' : ($msg->sender_id === auth()->id() ? 'flex justify-end' : 'flex justify-start') }}">
                                @if($msg->isSystemNotice())
                                    <div class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-sm max-w-[80%] text-center">
                                        {!! nl2br(e($msg->body)) !!}
                                    </div>
                                @else
                                    <div class="max-w-[75%] {{ $msg->sender_id === auth()->id() ? 'order-2' : '' }}">
                                        <p class="text-[10px] font-bold text-slate-500 mb-1">{{ $msg->sender?->first_name }} {{ $msg->sender?->last_name }}</p>
                                        <div class="px-4 py-3 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-primary text-white rounded-tr-md' : 'bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-tl-md' }}">
                                            {{ $msg->body }}
                                        </div>
                                        <p class="text-[9px] text-slate-400 mt-1">{{ $msg->created_at->format('d/m H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <form id="support-chat-form" action="{{ route('support.chat.send', $conv) }}" method="POST" class="shrink-0 p-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900" data-no-loading
                          x-data="supportChatTyping()">
                        @csrf
                        <div class="flex gap-3">
                            <input type="text" name="body" id="support-chat-input" required placeholder="Escreva aqui sua resposta técnica..."
                                   @input="onTyping()"
                                   class="flex-1 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <button type="submit" id="support-chat-submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-dark transition-all shrink-0">
                                Enviar
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex-1 flex items-center justify-center text-slate-500">
                        <div class="text-center">
                            <x-icon name="comments" style="duotone" class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" />
                            <p class="font-bold">Selecione uma conversa</p>
                            <p class="text-sm mt-1">Ou aguarde um cliente PRO iniciar o chat.</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right: User X-Ray + Inspection --}}
            <div class="w-96 shrink-0 flex flex-col border-l border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 overflow-y-auto">
                @if($selectedConversation)
                    @php $user = $selectedConversation->user; @endphp
                    <div class="p-6 space-y-6">
                        <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-widest text-[11px] flex items-center gap-2">
                            <x-icon name="user-tie" style="duotone" class="text-primary" />
                            Perfil do Cliente
                        </h3>
                        <div class="flex flex-col items-center">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-gray-100 dark:ring-slate-800">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center font-black text-2xl text-slate-500">
                                    {{ substr($user->first_name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <h4 class="font-black text-slate-900 dark:text-white mt-3">{{ $user->first_name }} {{ $user->last_name }}</h4>
                            <p class="text-xs text-slate-500 truncate max-w-full">{{ $user->email }}</p>
                            @if($user->isPro())
                                <span class="mt-2 px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase rounded-lg inline-flex items-center gap-1">
                                    <x-icon name="crown" style="solid" class="w-3 h-3" /> PRO
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
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-gray-100 dark:border-slate-800">
                                <span class="text-[10px] font-black text-slate-500 uppercase block">Saldo</span>
                                <span class="text-base font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['account_balance'] ?? 0) }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-gray-100 dark:border-slate-800">
                                <span class="text-[10px] font-black text-slate-500 uppercase block">Despesas</span>
                                <span class="text-base font-black text-slate-900 dark:text-white">{{ format_currency($financialSnapshot['monthly_expenses'] ?? 0) }}</span>
                            </div>
                            <div class="p-3 rounded-xl {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'bg-emerald-50 dark:bg-emerald-500/5' : 'bg-rose-50 dark:bg-rose-500/5' }} border {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'border-emerald-200' : 'border-rose-200' }}">
                                <span class="text-[10px] font-black uppercase block">Fluxo Livre</span>
                                <span class="text-base font-black {{ ($financialSnapshot['free_cashflow'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ format_currency($financialSnapshot['free_cashflow'] ?? 0) }}</span>
                            </div>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-50/50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10">
                            <span class="text-[10px] font-black text-amber-600 dark:text-amber-400 uppercase block">Reserva</span>
                            <span class="text-base font-black text-slate-900 dark:text-white">{{ format_number($reserveMonths ?? 0, 1) }} meses</span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
                            @if($user->support_access_expires_at?->isFuture())
                                <a href="{{ route('support.users.show', $user) }}"
                                   class="block w-full py-4 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl text-center hover:bg-primary-dark transition-all mb-3">
                                    <x-icon name="id-card-clip" style="duotone" class="w-4 h-4 inline mr-2" />
                                    Perfil Completo (X-Ray)
                                </a>
                                <p class="text-[10px] text-slate-500 text-center mb-3">Acesso ao User X-Ray autorizado</p>
                            @endif
                            @if($user->isPro())
                                @if($hasExistingInspection ?? false)
                                    <button type="button" disabled
                                            class="block w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-400 font-black text-xs uppercase tracking-[0.2em] rounded-2xl text-center cursor-not-allowed">
                                        <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                        Inspeção em Andamento
                                    </button>
                                    <p class="text-[9px] text-slate-400 text-center mt-2">Aguardando autorização ou sessão ativa.</p>
                                @else
                                    <form action="{{ route('support.chat.inspection.request', $conv) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full py-4 bg-primary text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl text-center hover:bg-primary-dark transition-all">
                                            <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                            Iniciar Inspeção Técnica
                                        </button>
                                    </form>
                                    <p class="text-[9px] text-slate-400 text-center mt-2">Acesso remoto ao painel do cliente PRO via Chat VIP.</p>
                                @endif
                            @else
                                <button type="button" disabled
                                        class="block w-full py-4 bg-slate-100 dark:bg-slate-800 text-slate-400 font-black text-xs uppercase tracking-[0.2em] rounded-2xl text-center cursor-not-allowed">
                                    <x-icon name="magnifying-glass-chart" style="duotone" class="w-4 h-4 inline mr-2" />
                                    Inspeção via Chamado
                                </button>
                                <p class="text-[9px] text-slate-400 text-center mt-2">Usuário Free: use a inspeção pelo chamado/ticket.</p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center p-6 text-slate-500 text-sm">
                        Selecione uma conversa para ver o perfil do cliente
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('paneluser::components.flash-messages', ['class' => 'mb-4'])

    @if($selectedConversation ?? null)
    @php
        $typingUrl = route('support.chat.typing', $selectedConversation);
        $sendUrl = route('support.chat.send', $selectedConversation);
    @endphp
    @if(config('broadcasting.default') === 'pusher' && config('broadcasting.connections.pusher.key'))
    <script>
        window.supportChatPusherConfig = {
            conversationId: {{ $selectedConversation->id }},
            currentUserId: {{ auth()->id() }},
            pusherKey: @json(config('broadcasting.connections.pusher.key')),
            pusherCluster: @json(config('broadcasting.connections.pusher.options.cluster', 'mt1'))
        };
    </script>
    @vite('resources/js/support-chat-pusher.js')
    @endif
    @push('scripts')
    <script>
        (function() {
            const form = document.getElementById('support-chat-form');
            const input = document.getElementById('support-chat-input');
            const submitBtn = document.getElementById('support-chat-submit');
            const messagesEl = document.getElementById('support-chat-messages');
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
                wrap.innerHTML = '<div class="max-w-[75%] order-2"><p class="text-[10px] font-bold text-slate-500 mb-1">' + escapeHtml(msg.sender_name) + '</p><div class="px-4 py-3 rounded-2xl bg-primary text-white rounded-tr-md">' + bodySafe + '</div><p class="text-[9px] text-slate-400 mt-1">' + formatTime(msg.created_at) + '</p></div>';
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
                            window.dispatchEvent(new CustomEvent('new-notification', {
                                detail: { type: 'danger', title: 'Erro', message: 'Falha ao enviar. Verifique a conexão e tente novamente.', icon: 'circle-xmark' }
                            }));
                        })
                        .finally(function() {
                            submitBtn.disabled = false;
                            submitBtn.textContent = origLabel;
                        });
                });
            }
        })();
        document.addEventListener('alpine:init', () => {
            Alpine.data('supportChatTyping', () => ({
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
</x-panelsuporte::layouts.master>
