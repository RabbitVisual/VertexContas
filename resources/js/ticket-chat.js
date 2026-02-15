import { createApp } from 'vue';
import TicketChat from './components/TicketChat.vue';

const mountPoints = ['ticket-chat-user', 'ticket-chat-support', 'ticket-chat-admin', 'ticket-chat'];

for (const id of mountPoints) {
  const el = document.getElementById(id);
  if (!el) continue;

  const props = {
    ticketId: Number(el.dataset.ticketId) || el.dataset.ticketId,
    postUrl: el.dataset.postUrl || '',
    messagesUrl: el.dataset.messagesUrl || '',
    initialMessages: JSON.parse(el.dataset.initialMessages || '[]'),
    currentUserId: Number(el.dataset.currentUserId) || el.dataset.currentUserId,
    isClosed: el.dataset.isClosed === '1' || el.dataset.isClosed === 'true',
    canReply: el.dataset.canReply !== '0' && el.dataset.canReply !== 'false',
    context: el.dataset.context || 'user',
    statusOptions: JSON.parse(el.dataset.statusOptions || '[]'),
    placeholder: el.dataset.placeholder || 'Digite sua mensagem...',
    csrfToken: el.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.content || '',
    isPro: el.dataset.isPro === '1' || el.dataset.isPro === 'true',
  };

  createApp(TicketChat, props).mount(el);
  break;
}
