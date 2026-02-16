/**
 * Support Command Center - Pusher/Echo listener for real-time user messages
 * Loaded only on /support/chat/{id}. Requires window.supportChatPusherConfig.
 */
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

(function initSupportChatPusher() {
    const config = window.supportChatPusherConfig;
    if (!config?.conversationId || !config?.pusherKey || !config?.currentUserId) return;

    const messagesEl = document.getElementById('support-chat-messages');
    if (!messagesEl) return;

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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            },
        });
    }

    const channel = window.Echo.private(`conversation.${config.conversationId}`);

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

    function appendUserMessage(msg) {
        const bodySafe = escapeHtml(msg.body ?? '');
        const wrap = document.createElement('div');
        wrap.className = 'flex justify-start';
        wrap.innerHTML = '<div class="max-w-[75%]"><p class="text-[10px] font-bold text-slate-500 mb-1">' + escapeHtml(msg.sender_name || 'Cliente') + '</p><div class="px-4 py-3 rounded-2xl bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 text-slate-800 dark:text-slate-200 rounded-tl-md">' + bodySafe + '</div><p class="text-[9px] text-slate-400 mt-1">' + formatTime(msg.created_at) + '</p></div>';
        messagesEl.appendChild(wrap);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    channel.listen('.message.sent', (data) => {
        if (data.message && (data.message.sender_id || 0) !== (config.currentUserId || 0)) {
            appendUserMessage(data.message);
        }
    });
})();
