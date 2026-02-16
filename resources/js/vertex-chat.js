/**
 * Vertex Chat VIP - Real-time messaging & typing indicator
 * Loaded only on vertexchat.chat.show page
 */
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

(function initVertexChat() {
    const config = window.vertexChatConfig;
    if (!config?.conversationId || !config?.pusherKey) return;

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
const messagesEl = document.getElementById('chat-messages');
const typingEl = document.getElementById('chat-typing-indicator');
let typingTimeout = null;

channel.listen('.agent.typing', () => {
    if (typingEl) {
        typingEl.classList.remove('hidden');
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            typingEl.classList.add('hidden');
        }, 3000);
    }
});

channel.listen('.message.sent', (data) => {
    if (typingEl) typingEl.classList.add('hidden');
    if (data.message && data.message.sender_id !== (window.vertexChatConfig?.userId || 0)) {
        const msg = data.message;
        const div = document.createElement('div');
        div.className = 'text-left';
        div.innerHTML = `
            <div class="inline-block">
                <p class="text-xs font-bold text-slate-500 mb-1">${escapeHtml(msg.sender_name || 'Suporte')}</p>
                <div class="px-4 py-2 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                    ${escapeHtml(msg.body)}
                </div>
                <p class="text-[10px] text-slate-400 mt-1">${formatTime(msg.created_at)}</p>
            </div>
        `;
        if (messagesEl) {
            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatTime(iso) {
    if (!iso) return '';
    const d = new Date(iso);
    return d.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
}

// Auto-scroll to bottom on load
if (messagesEl) {
    messagesEl.scrollTop = messagesEl.scrollHeight;
}
})();
