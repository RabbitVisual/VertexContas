/**
 * Vertex Chat VIP - Envio via AJAX (sem recarregar página) e Pusher para tempo real
 * Carregado apenas na página vertexchat.chat.show
 */
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

(function initVertexChat() {
    const config = window.vertexChatConfig;
    if (!config?.conversationId) return;

    const messagesEl = document.getElementById('chat-messages');
    const typingEl = document.getElementById('chat-typing-indicator');
    const form = document.getElementById('vertex-chat-form');
    const input = document.getElementById('vertex-chat-input');
    const submitBtn = document.getElementById('vertex-chat-submit');

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

    function avatarHtml(photoUrl, initial) {
        const i = (initial || '?').toString().charAt(0).toUpperCase();
        if (photoUrl) {
            return `<img src="${escapeHtml(photoUrl)}" alt="" class="w-full h-full object-cover" loading="lazy" />`;
        }
        return `<span class="text-sm font-bold text-slate-600 dark:text-slate-300">${escapeHtml(i)}</span>`;
    }

    function appendMessage(msg, isOwn) {
        if (!messagesEl) return;
        const wrap = document.createElement('div');
        wrap.className = isOwn ? 'flex justify-end' : 'flex justify-start';
        const avatar = avatarHtml(msg.sender_photo, msg.sender_initial);
        const bodySafe = escapeHtml(msg.body ?? '');
        wrap.innerHTML = isOwn
            ? `
            <div class="max-w-[85%] flex gap-2 flex-row-reverse">
                <div class="shrink-0 w-9 h-9 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-600 flex items-center justify-center ring-2 ring-white dark:ring-slate-800 shadow">${avatar}</div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">${escapeHtml(msg.sender_name)}</p>
                    <div class="px-4 py-2.5 rounded-2xl bg-primary text-white rounded-br-md">${bodySafe}</div>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">${formatTime(msg.created_at)}</p>
                </div>
            </div>
            `
            : `
            <div class="max-w-[85%] flex gap-2">
                <div class="shrink-0 w-9 h-9 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-600 flex items-center justify-center ring-2 ring-white dark:ring-slate-800 shadow">${avatar}</div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">${escapeHtml(msg.sender_name || 'Suporte')}</p>
                    <div class="px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-100 dark:border-slate-600 rounded-bl-md">${bodySafe}</div>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">${formatTime(msg.created_at)}</p>
                </div>
            </div>
            `;
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    // Envio via AJAX (sem recarregar a página)
    if (form && input && config.sendUrl) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const body = (input.value || '').trim();
            if (!body) return;

            const origLabel = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Enviando...';

            fetch(config.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams({ body, _token: config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '' }),
            })
                .then((res) => {
                    if (res.ok) return res.json();
                    throw new Error('Falha ao enviar');
                })
                .then((data) => {
                    if (data.message) {
                        appendMessage(data.message, true);
                        input.value = '';
                    }
                })
                .catch(() => {
                    if (typeof window.dispatchEvent === 'function') {
                        window.dispatchEvent(new CustomEvent('new-notification', {
                            detail: { type: 'danger', title: 'Erro', message: 'Falha ao enviar. Verifique a conexão e tente novamente.', icon: 'circle-xmark' }
                        }));
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = origLabel;
                });
        });
    }

    // Pusher: mensagens em tempo real e indicador de digitação
    if (config.pusherKey) {
        window.Pusher = Pusher;
        if (!window.Echo) {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: config.pusherKey,
                cluster: config.pusherCluster || 'mt1',
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json',
                    },
                },
            });
        }

        const channel = window.Echo.private(`conversation.${config.conversationId}`);
        let typingTimeout = null;

        channel.listen('.agent.typing', () => {
            if (typingEl) {
                typingEl.classList.remove('hidden');
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => typingEl.classList.add('hidden'), 3000);
            }
        });

        channel.listen('.message.sent', (data) => {
            if (typingEl) typingEl.classList.add('hidden');
            if (data.message && data.message.sender_id !== (config.userId || 0)) {
                appendMessage(data.message, false);
            }
        });
    }

    if (messagesEl) {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }
})();
