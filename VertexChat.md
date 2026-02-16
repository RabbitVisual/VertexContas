## Módulo Vertex Chat VIP & Inspeção 2.0 (Otimizado para Hostinger/XAMPP)

**Role:** Senior Full-Stack Architect & Lead UX Designer.
**Goal:** Implement the "Vertex Chat VIP" module and upgrade the "Inspection Tool" to version 2.0. The system must use Pusher for real-time and Flowbite/Tailwind v4 for the UI.

---

### Phase 1: Core Infrastructure (Pusher Ready)
1. **Migrations:**
    * `conversations`: `id, user_id (Pro User), assigned_agent_id, sector (support, technical, billing), status (open, closed, transferred)`.
    * `messages`: `id, conversation_id, sender_id, body, type (text, system_notice), read_at`.
2. **Broadcasting Config:** Configure `config/broadcasting.php` to use the `pusher` driver.
    * Create a `MessageSent` event that implements `ShouldBroadcast`.
3. **Logic:** Create `Modules/Chat/app/Services/ChatService.php` to handle message storage and sector transfers.
4. **Access Rule:** Only `user->isPro()` or agents/admins can initiate/view chats.

---

### Phase 2: Support "Command Center" (Modules/PanelSuporte)
1. **Flowbite Chat UI:** Create a high-end chat interface in the support panel.
    * **Layout:** Three columns using Flowbite components.
    * **Left:** List of active chats with "Plan Badges" (PRO) and online status.
    * **Center:** Scrollable message area with professional speech bubbles.
    * **Right:** Quick User X-Ray (Snapshot financeiro) + **BIG BUTTON: "Iniciar Inspeção Técnica"**.
2. **Sector Transfer:** Add a header action to "Transfer to Sector" (Técnico, Financeiro, Admin).
    * When transferred, update the `sector` and log a `system_notice` in the chat.

---

### Phase 3: Inspection 2.0 (The Professional Audit Wall)
1. **Universal Access:** Ensure the "Login as User" works for both Free (via Tickets) and Pro (via Chat).
2. **Flowbite Banner:** Replace the old banner with a fixed `Flowbite Floating Banner` at the top of the screen during inspection.
    * **Features:** Show a "Stop Session" button and a real-time counter of the inspection duration.
3. **Mandatory Audit Report:**
    * When "Stop Session" is clicked, open a Flowbite Modal.
    * **Action:** The agent MUST fill a "Relatório de Atendimento" (textarea).
    * **Storage:** Save the report and the duration to `SupportAuditLog` linked to the user and the agent.

---

### Phase 4: User VIP Widget (Modules/PanelUser)
1. **Component:** `<x-chat::widget />`.
    * **Free Users:** Show a locked icon with the message: "Suporte VIP via Chat é exclusivo para membros PRO. [Ver Planos]".
    * **Pro Users:** Modern chat interface with glassmorphism, auto-scroll, and "Agent typing" indicator.
2. **Design:** Use strictly Tailwind v4 + Flowbite. Icons: FontAwesome 7.1 Pro Local Duotone (`fa-comment-active`, `fa-user-headset`).

---

### Technical Constraints & Security:
* **Hostinger Compatibility:** Use Pusher as the primary driver. Ensure the frontend handles connection losses gracefully.
* **Sensitive Data:** Maintain the `BlockSensitiveInspectionActions` middleware to protect user passwords/keys even during high-level technical inspections.
* **Auditability:** Every message and every sector change must be auditable by the Admin.

**Execute Phase 1 and 2 first. Build the database structure and the main Support Chat UI.**

🛡️ Por que este é o caminho para o seu sucesso:
Auditoria para o Admin: Se você contratar um funcionário de suporte no futuro, você poderá ler exatamente o que ele escreveu no chat e o que ele fez durante a "Inspeção", garantindo que ele não abusou do acesso.

Escalabilidade (Setores): Ao criar a coluna sector, você organiza sua empresa. Problemas simples ficam no "Suporte", problemas de conta no "Financeiro", e bugs reais você transfere para o seu setor "Técnico/Admin".

Visual Flowbite: O suporte deixará de parecer um sistema interno simples e passará a ser uma ferramenta de trabalho de alta performance.

Dica para o XAMPP: Quando você criar sua conta no Pusher, ele vai te dar as chaves PUSHER_APP_ID, APP_KEY, etc. Basta colocá-las no seu arquivo .env local e o chat já vai começar a funcionar na hora! 🚀🤖🦾
