<template>
  <div ref="rootRef" class="ticket-chat flex flex-col flex-1 min-h-[400px] rounded-3xl bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-white/5 overflow-hidden">
    <!-- Messages area -->
    <div
      ref="messagesContainer"
      class="flex-1 overflow-y-auto p-4 md:p-6 space-y-1 custom-scrollbar bg-gray-50/50 dark:bg-slate-900/30 flex flex-col"
    >
      <!-- Exibir anteriores / Ocultar -->
      <div
        v-if="hasOlderMessages || canHideMessages"
        class="flex justify-center gap-2 py-3 shrink-0 flex-wrap"
      >
        <button
          v-if="hasOlderMessages"
          type="button"
          @click="loadMoreMessages"
          :disabled="loadingMore"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-500/10 border border-primary-200 dark:border-primary-500/30 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <i v-if="loadingMore" class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
          <i v-else class="fa-solid fa-angles-up" aria-hidden="true"></i>
          Exibir anteriores
        </button>
        <button
          v-if="canHideMessages"
          type="button"
          @click="hideOlderMessages"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 border border-gray-200 dark:border-slate-600 transition-colors"
        >
          <i class="fa-solid fa-angles-down" aria-hidden="true"></i>
          Ocultar
        </button>
      </div>

      <div class="flex-1 flex flex-col justify-end min-h-0">
        <div
          v-for="(msg, idx) in visibleMessages"
          :key="msg.id"
          :class="[
            'flex gap-2 md:gap-3 mb-1',
            msg.is_system ? 'justify-center' : msg.is_admin_reply ? 'justify-start' : 'justify-end',
          ]"
        >
          <!-- Suporte: avatar esquerda | bolha -->
          <template v-if="msg.is_admin_reply && !msg.is_system">
            <div
              v-if="shouldShowAvatar(msg, idx)"
              class="shrink-0 mt-auto order-1"
            >
              <img
                v-if="msg.user_photo"
                :src="msg.user_photo"
                :alt="msg.sender_name"
                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-900 shadow-sm"
              />
              <div
                v-else
                class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-xs font-bold ring-2 ring-white dark:ring-slate-900 shadow-sm bg-primary/20 text-primary dark:bg-primary/30"
              >
                {{ (msg.sender_name || 'S').charAt(0) }}
              </div>
            </div>
            <div v-else class="w-8 md:w-10 shrink-0 order-1" />
            <div class="order-2 max-w-[85%] md:max-w-[70%] flex flex-col items-start">
              <div
                v-if="shouldShowSender(msg, idx)"
                class="flex items-center gap-2 mb-0.5 px-1"
              >
                <span class="text-[10px] md:text-xs font-bold text-primary-600 dark:text-primary-400">Suporte</span>
                <span class="text-[9px] text-gray-400">{{ formatTime(msg.created_at) }}{{ isPro ? ' · ' + formatDate(msg.created_at) : '' }}</span>
              </div>
              <div class="px-4 py-2.5 md:px-5 md:py-3 rounded-2xl rounded-tl-md text-sm leading-relaxed whitespace-pre-wrap break-words shadow-sm bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 border border-gray-100 dark:border-slate-700">
                {{ msg.message }}
              </div>
            </div>
          </template>

          <!-- Usuário: bolha | avatar direita -->
          <template v-else-if="!msg.is_system">
            <div class="max-w-[85%] md:max-w-[70%] flex flex-col items-end shrink-0">
              <div
                v-if="shouldShowSender(msg, idx)"
                class="flex items-center gap-2 mb-0.5 px-1 flex-row-reverse"
              >
                <span class="text-[10px] md:text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                  {{ senderLabel(msg) }}
                  <i v-if="isPro && !msg.is_admin_reply" class="fa-solid fa-crown text-amber-500 dark:text-amber-400 text-[10px]" aria-hidden="true" title="Cliente Pro"></i>
                </span>
                <span class="text-[9px] text-gray-400">{{ formatTime(msg.created_at) }}{{ isPro ? ' · ' + formatDate(msg.created_at) : '' }}</span>
              </div>
              <div class="px-4 py-2.5 md:px-5 md:py-3 rounded-2xl rounded-tr-md text-sm leading-relaxed whitespace-pre-wrap break-words shadow-sm bg-primary-600 dark:bg-primary-500 text-white">
                {{ msg.message }}
              </div>
            </div>
            <div
              v-if="shouldShowAvatar(msg, idx)"
              class="shrink-0 mt-auto"
            >
              <img
                v-if="msg.user_photo"
                :src="msg.user_photo"
                :alt="msg.sender_name"
                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover ring-2 ring-white dark:ring-slate-900 shadow-sm"
              />
              <div
                v-else
                class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-xs font-bold ring-2 ring-white dark:ring-slate-900 shadow-sm bg-gray-200 dark:bg-slate-700 text-gray-600 dark:text-gray-400"
              >
                {{ (msg.sender_name || 'U').charAt(0) }}
              </div>
            </div>
            <div v-else class="w-8 md:w-10 shrink-0" />
          </template>

          <!-- Sistema: centralizado -->
          <div
            v-else
            class="w-full flex flex-col items-center max-w-2xl mx-auto"
          >
            <div class="flex items-center gap-2 px-2 py-0.5 rounded-lg bg-amber-100 dark:bg-amber-500/20 mb-1">
              <span class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ msg.sender_name }}</span>
            </div>
            <div class="px-4 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-900 dark:text-amber-100 text-sm">
              {{ msg.message }}
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="messages.length === 0 && !loading"
        class="flex flex-col items-center justify-center py-16 text-center"
      >
        <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4 text-slate-400">
          <i class="fa-duotone fa-comments text-2xl opacity-50" aria-hidden="true"></i>
        </div>
        <p class="text-sm font-bold text-gray-500 dark:text-slate-400">Nenhuma mensagem ainda</p>
      </div>

      <div v-if="loading && messages.length === 0" class="flex justify-center py-12">
        <div class="animate-spin w-10 h-10 border-2 border-primary border-t-transparent rounded-full" />
      </div>
    </div>

    <!-- Reply form -->
    <div
      v-if="canReply && !isClosed"
      class="p-4 md:p-6 bg-white dark:bg-slate-900 border-t border-gray-200 dark:border-white/5"
    >
      <form @submit.prevent.stop="sendMessage" data-no-loading class="space-y-4">
        <!-- Status selector (support/admin only) -->
        <div v-if="statusOptions && statusOptions.length" class="flex flex-wrap gap-2">
          <select
            v-model="status"
            name="status"
            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-slate-800 text-sm font-medium text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-primary/20 focus:border-primary"
          >
            <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
        </div>

        <div class="relative flex gap-2 items-end">
          <button
            type="button"
            @click="showEmojiPicker = !showEmojiPicker"
            class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 flex items-center justify-center text-gray-500 dark:text-gray-400 transition-colors"
            title="Emojis"
          >
            <i class="fa-regular fa-face-smile text-lg" aria-hidden="true"></i>
          </button>

          <div v-if="showEmojiPicker" class="absolute bottom-full left-0 mb-2 z-20">
            <div class="emoji-grid p-3 rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 shadow-xl max-h-48 overflow-y-auto grid grid-cols-8 gap-1">
              <button
                v-for="emoji in commonEmojis"
                :key="emoji"
                type="button"
                @click="insertEmoji(emoji)"
                class="text-xl hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg p-1 transition-colors"
              >
                {{ emoji }}
              </button>
            </div>
          </div>

          <textarea
            v-model="newMessage"
            ref="textareaRef"
            name="message"
            rows="2"
            :placeholder="placeholder"
            class="flex-1 px-4 py-3 rounded-2xl border-2 border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-slate-800 text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-4 focus:ring-primary/20 focus:border-primary resize-none text-sm font-medium min-h-[48px] max-h-32"
            :disabled="sending"
            @keydown.enter.exact.prevent="sendMessage"
            @keydown.enter.shift.exact.prevent="newMessage += '\n'"
          />
          <button
            type="submit"
            :disabled="sending || !newMessage.trim()"
            class="shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white flex items-center justify-center transition-all shadow-lg shadow-primary-500/20"
          >
            <i v-if="sending" class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
            <i v-else class="fa-solid fa-paper-plane" aria-hidden="true"></i>
          </button>
        </div>
        <p v-if="error" class="text-xs text-red-600 dark:text-red-400">{{ error }}</p>
      </form>
    </div>

    <!-- Closed state -->
    <div
      v-else-if="isClosed"
      class="p-8 text-center bg-gray-50 dark:bg-slate-800/50 border-t border-gray-200 dark:border-white/5"
    >
      <div class="w-12 h-12 bg-gray-200 dark:bg-slate-700 rounded-xl flex items-center justify-center mx-auto mb-3 text-gray-500 dark:text-slate-400">
        <i class="fa-solid fa-lock text-xl" aria-hidden="true"></i>
      </div>
      <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Chamado encerrado</p>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Não é possível enviar novas mensagens.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';

const INITIAL_VISIBLE = 6;
const LOAD_MORE_STEP = 6;

const props = defineProps({
  ticketId: { type: [Number, String], required: true },
  postUrl: { type: String, required: true },
  messagesUrl: { type: String, required: true },
  initialMessages: { type: Array, default: () => [] },
  currentUserId: { type: [Number, String], required: true },
  isClosed: { type: Boolean, default: false },
  canReply: { type: Boolean, default: true },
  context: { type: String, default: 'user' },
  statusOptions: { type: Array, default: () => [] },
  placeholder: { type: String, default: 'Digite sua mensagem...' },
  csrfToken: { type: String, required: true },
  isPro: { type: Boolean, default: false },
});

const messages = ref([...props.initialMessages]);
const newMessage = ref('');
const loading = ref(false);
const loadingMore = ref(false);
const sending = ref(false);
const error = ref('');
const showEmojiPicker = ref(false);
const messagesContainer = ref(null);
const textareaRef = ref(null);
const rootRef = ref(null);
const status = ref(props.statusOptions?.length ? (props.statusOptions[0]?.value ?? 'answered') : null);
const pollInterval = ref(null);
const visibleCount = ref(INITIAL_VISIBLE);

const visibleMessages = computed(() => {
  const total = messages.value.length;
  if (total <= visibleCount.value) return messages.value;
  return messages.value.slice(-visibleCount.value);
});

const hasOlderMessages = computed(() => messages.value.length > visibleCount.value);
const canHideMessages = computed(() => visibleCount.value > INITIAL_VISIBLE);

const commonEmojis = [
  '😀', '😃', '😊', '😍', '🥰', '😎', '👍', '👋', '🙏', '❤️', '💙', '🔥',
  '✅', '❌', '⚠️', '📌', '💡', '🎉', '🙌', '😅', '🤔', '😢', '😤', '😴',
  '📧', '📱', '💻', '🔒', '⭐', '✨', '💪', '🚀',
];

function formatTime(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  return d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

function formatDate(iso) {
  if (!iso) return '';
  const d = new Date(iso);
  return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric' });
}

function senderLabel(msg) {
  if (msg.is_own) return 'Você';
  return 'Cliente';
}

function shouldShowAvatar(msg, idx) {
  if (msg.is_system) return false;
  const visible = visibleMessages.value;
  const prev = idx > 0 ? visible[idx - 1] : null;
  if (!prev) return true;
  return prev.user_id !== msg.user_id || prev.is_admin_reply !== msg.is_admin_reply || prev.is_system;
}

function shouldShowSender(msg, idx) {
  if (msg.is_system) return false;
  const visible = visibleMessages.value;
  const prev = idx > 0 ? visible[idx - 1] : null;
  if (!prev) return true;
  return prev.user_id !== msg.user_id || prev.is_admin_reply !== msg.is_admin_reply || prev.is_system;
}

async function loadMoreMessages() {
  if (loadingMore.value) return;
  loadingMore.value = true;
  const container = messagesContainer.value;
  const scrollHeightBefore = container?.scrollHeight ?? 0;
  const scrollTopBefore = container?.scrollTop ?? 0;
  visibleCount.value += LOAD_MORE_STEP;
  await nextTick();
  if (container) {
    const scrollHeightAfter = container.scrollHeight;
    container.scrollTop = scrollHeightAfter - scrollHeightBefore + scrollTopBefore;
  }
  loadingMore.value = false;
}

function hideOlderMessages() {
  visibleCount.value = INITIAL_VISIBLE;
  scrollToBottom();
}

async function loadMessages() {
  if (loading.value) return;
  loading.value = true;
  error.value = '';
  try {
    const res = await fetch(props.messagesUrl, {
      headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    if (!res.ok) throw new Error('Falha ao carregar mensagens');
    const data = await res.json();
    messages.value = data.messages || [];
    scrollToBottom();
  } catch (e) {
    error.value = e.message || 'Erro ao carregar';
  } finally {
    loading.value = false;
  }
}

function scrollToBottom() {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    }
  });
}

async function sendMessage() {
  const text = newMessage.value.trim();
  if (!text || sending.value) return;
  sending.value = true;
  error.value = '';
  const formData = new FormData();
  formData.append('message', text);
  formData.append('_token', props.csrfToken);
  if (props.statusOptions?.length && status.value) {
    formData.append('status', status.value);
  }
  try {
    const res = await fetch(props.postUrl, {
      method: 'POST',
      body: formData,
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.message || 'Falha ao enviar');
    if (data.message) {
      messages.value.push(data.message);
      newMessage.value = '';
      scrollToBottom();
    }
  } catch (e) {
    error.value = e.message || 'Erro ao enviar';
  } finally {
    sending.value = false;
    window.dispatchEvent(new Event('stop-loading'));
  }
}

function insertEmoji(emoji) {
  const ta = textareaRef.value;
  if (ta) {
    const start = ta.selectionStart;
    const end = ta.selectionEnd;
    const text = newMessage.value;
    newMessage.value = text.substring(0, start) + emoji + text.substring(end);
    nextTick(() => {
      ta.focus();
      ta.setSelectionRange(start + emoji.length, start + emoji.length);
    });
  } else {
    newMessage.value += emoji;
  }
}

function startPolling() {
  if (props.isClosed) return;
  pollInterval.value = setInterval(() => {
    if (document.visibilityState !== 'visible') return;
    loadMessages();
  }, 4000);
}

function stopPolling() {
  if (pollInterval.value) {
    clearInterval(pollInterval.value);
    pollInterval.value = null;
  }
}

onMounted(() => {
  if (props.initialMessages?.length) {
    messages.value = [...props.initialMessages];
  } else {
    loadMessages();
  }
  startPolling();
  scrollToBottom();
});

onUnmounted(() => {
  stopPolling();
});

watch(showEmojiPicker, (val) => {
  if (val) {
    const h = (e) => {
      const root = rootRef.value;
      if (root && !root.contains(e.target)) {
        showEmojiPicker.value = false;
        document.removeEventListener('click', h);
      }
    };
    setTimeout(() => document.addEventListener('click', h), 100);
  }
});
</script>
