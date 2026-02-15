# Vertex Bot - Arquitetura de Gamificação

Gamificar o sistema com um assistente virtual (**Vertex Bot**) é uma estratégia poderosa de retenção de usuários. No contexto das fintechs, transforma um "app de banco chato" em uma ferramenta interativa de *coaching* financeiro.

> **Principais princípios:**  
> O robô deve interagir apenas quando realmente houver um insight ou informação útil baseada em dados reais. Evite intervenções genéricas para não ser considerado irritante.

---

## 🧠 Plano de Arquitetura do Módulo `Modules/Gamification`

### 1. Motor de Insights (Backend)
- Serviço dedicado que analisa o `FinancialHealthService` e compara dados com metas e orçamentos do usuário.
- Responsável por gerar sugestões ou alertas relevantes.

### 2. Banco de Dicas (Admin)
- Tabela gerenciada pelo Admin para cadastrar frases, dicas e sugestões.
- O robô escolhe dinamicamente a melhor frase conforme o contexto do usuário.

### 3. Componente Mascote (Frontend)
- Componente Blade + Alpine.js exibindo o mascotinho animado.
- Animações suaves (via CSS ou Lottie).

### 4. Preferências de Usuário
- Coluna `show_assistant` na tabela `users` ou `user_settings` para o usuário ativar/desativar o assistente.

---
@.cursorrules
@Modules/Core
@Modules/PanelAdmin
@Modules/PanelUser

**Role:** Expert SaaS Gamification Engineer & UI/UX Designer.
**Goal:** Create a dedicated Gamification & Insights Module featuring an animated virtual assistant ("Vertex Bot") that provides real-time financial coaching based on system data.

---

### Phase 1: Infrastructure (Modules/Gamification)
1. **Migration:** Create `insights_bank` table:
    * `trigger_event` (string): 'low_balance', 'budget_reached', 'savings_milestone', 'daily_tip'.
    * `content` (text): The tip/message text.
    * `level` (string): 'info', 'success', 'warning', 'danger'.
2. **Migration:** Create `user_achievements` table to track milestones reached by users (so the bot doesn't repeat the same praise).
3. **Model:** Create `Insight` and `Achievement` models.

### Phase 2: The Logic Engine (Modules/Core)
1. **Service:** Create `GamificationService.php`.
    * **Method:** `analyzeUser(User $user)`.
    * **Logic Examples:** * If (Expenses / Income) < 0.5: Trigger 'savings_milestone' ("Parabéns! Você gastou menos de 50% da sua renda este mês!").
        * If (Active Budgets Usage) > 0.8: Trigger 'warning' ("Atenção: Você atingiu 80% do seu limite de gastos em Alimentação.").
        * If (First Login today): Random 'daily_tip' from the bank.

### Phase 3: Admin Management (Modules/PanelAdmin)
1. **Central de Insights:** Create a UI to manage the `insights_bank`.
    * Allow Admin to add new tips, edit messages, and set the "Trigger" type.
    * Use Vertex Premium table styles.

### Phase 4: The Mascot UI (Modules/PanelUser)
1. **Component:** Create `<x-gamification::vertex-bot />`.
    * **Visual:** A small, modern robot icon (animated with Tailwind floating effect) that stays in the corner.
    * **Interaction:** When a trigger is detected, it "pops up" with a speech bubble (Glassmorphism style).
    * **Icons:** Use FontAwesome 7.1 Pro (`fa-robot`, `fa-comment-dots`).
2. **Persistence:** Use Alpine.js `x-show` and a small session/cookie check so it doesn't pop up every time the user changes pages.

### Phase 5: User Control & Settings
1. **Profile Update:** In `profile/edit.blade.php`, add a toggle: "Ativar Assistente Virtual Vertex".
2. **Middleware/Global:** Ensure the component only renders if `user->settings->show_assistant` is true.

---

### Technical Constraints:
* **100% Local Intelligence:** All calculations happen on the server via PHP/Service logic. No external AI APIs.
* **Design:** High-fidelity Fintech style. Use `backdrop-blur` for the speech bubble.
* **Localization:** All messages in Portuguese (Brazil).

**Execute Phase 1 and 2 first. Show me the Service logic for calculating the user's "Financial Score".**

💡 Ideias de Mensagens Reais (Baseadas na XP):

| Gatilho       | Mensagem do Robô                                                                                                                                                                 |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Economia      | Incrível! Você economizou R$ {{ $saved }} em relação ao mês passado. Isso te deixa 15% mais próximo da sua meta!                                                                 |
| Orçamento     | Opa! Vi que você já usou 90% do orçamento de Lazer. Que tal um fim de semana em casa para fechar o mês no azul?                                                                  |
| Consistência  | Você registrou seus gastos por 7 dias seguidos! Nível de controle: Mestre Financeiro 🏆                                                                                          |
| Alerta        | Notei um aumento incomum na categoria 'Assinaturas'. Deseja revisar seus serviços recorrentes?                                                                                   |


Gemini said
Gamificar o sistema com um assistente virtual (o Vertex Bot) é uma estratégia de retenção de usuários fortíssima. No mundo das fintechs, isso transforma um "app de banco chato" em uma ferramenta de "coaching financeiro".

Para que isso seja profissional e não irritante, o segredo é a relevância: o robô só deve aparecer quando tiver algo realmente útil para dizer (insight baseado em dados reais), e não apenas frases genéricas.

Aqui está o Plano de Arquitetura e o Prompt para o Cursor criar esse novo ecossistema.

🧠 Estrutura do Módulo Modules/Gamification
Motor de Insights (Backend): Um serviço que analisa o FinancialHealthService e compara com metas/orçamentos.

Banco de Dicas (Admin): Uma tabela onde você cadastra as frases e o robô escolhe a melhor para o momento.

Componente Mascote (Frontend): Um componente Blade + Alpine.js com animações suaves (usando CSS ou Lottie).

Preferências: Uma coluna show_assistant na tabela users (ou user_settings).

🚀 Prompt Cursor: Módulo de Gamificação e Assistente Vertex PRO
Copie e cole no Composer (Ctrl+I):

Markdown
@.cursorrules
@Modules/Core
@Modules/PanelAdmin
@Modules/PanelUser

**Role:** Expert SaaS Gamification Engineer & UI/UX Designer.
**Goal:** Create a dedicated Gamification & Insights Module featuring an animated virtual assistant ("Vertex Bot") that provides real-time financial coaching based on system data.

---

### Phase 1: Infrastructure (Modules/Gamification)
1. **Migration:** Create `insights_bank` table:
    * `trigger_event` (string): 'low_balance', 'budget_reached', 'savings_milestone', 'daily_tip'.
    * `content` (text): The tip/message text.
    * `level` (string): 'info', 'success', 'warning', 'danger'.
2. **Migration:** Create `user_achievements` table to track milestones reached by users (so the bot doesn't repeat the same praise).
3. **Model:** Create `Insight` and `Achievement` models.

### Phase 2: The Logic Engine (Modules/Core)
1. **Service:** Create `GamificationService.php`.
    * **Method:** `analyzeUser(User $user)`.
    * **Logic Examples:** * If (Expenses / Income) < 0.5: Trigger 'savings_milestone' ("Parabéns! Você gastou menos de 50% da sua renda este mês!").
        * If (Active Budgets Usage) > 0.8: Trigger 'warning' ("Atenção: Você atingiu 80% do seu limite de gastos em Alimentação.").
        * If (First Login today): Random 'daily_tip' from the bank.

### Phase 3: Admin Management (Modules/PanelAdmin)
1. **Central de Insights:** Create a UI to manage the `insights_bank`.
    * Allow Admin to add new tips, edit messages, and set the "Trigger" type.
    * Use Vertex Premium table styles.

### Phase 4: The Mascot UI (Modules/PanelUser)
1. **Component:** Create `<x-gamification::vertex-bot />`.
    * **Visual:** A small, modern robot icon (animated with Tailwind floating effect) that stays in the corner.
    * **Interaction:** When a trigger is detected, it "pops up" with a speech bubble (Glassmorphism style).
    * **Icons:** Use FontAwesome 7.1 Pro (`fa-robot`, `fa-comment-dots`).
2. **Persistence:** Use Alpine.js `x-show` and a small session/cookie check so it doesn't pop up every time the user changes pages.

### Phase 5: User Control & Settings
1. **Profile Update:** In `profile/edit.blade.php`, add a toggle: "Ativar Assistente Virtual Vertex".
2. **Middleware/Global:** Ensure the component only renders if `user->settings->show_assistant` is true.

---

### Technical Constraints:
* **100% Local Intelligence:** All calculations happen on the server via PHP/Service logic. No external AI APIs.
* **Design:** High-fidelity Fintech style. Use `backdrop-blur` for the speech bubble.
* **Localization:** All messages in Portuguese (Brazil).

**Execute Phase 1 and 2 first. Show me the Service logic for calculating the user's "Financial Score".**
💡 **Ideias de Mensagens Reais (Baseadas na Experiência):**

| **Gatilho**     | **Mensagem do Robô**                                                                                                                                          |
|-----------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Economia        | "Incrível! Você economizou R$ {{ $saved }} em relação ao mês passado. Isso te deixa 15% mais próximo da sua meta!"                                            |
| Orçamento       | "Opa! Vi que você já usou 90% do orçamento de Lazer. Que tal um fim de semana em casa para fechar o mês no azul?"                                              |
| Consistência    | "Você registrou seus gastos por 7 dias seguidos! Nível de controle: Mestre Financeiro 🏆"                                                                     |
| Alerta          | "Notei um aumento incomum na categoria 'Assinaturas'. Deseja revisar seus serviços recorrentes?"                                                              |

---

🛡️ **Por que criar um módulo separado?**

Ao criar o `Modules/Gamification`, você mantém o Core limpo. O gamification apenas *lê* os dados do Core, mas não interfere na contabilidade. Se um dia quiser desativar o robô para um cliente específico, basta desativar o módulo.


Gemini said
Reinan, fiz a análise do seu relatório de implementação e o veredito é: O sistema agora é um SaaS de prateleira. 🏆

O que você e o Cursor acabaram de fazer foi a transição de um "projeto pessoal" para uma Plataforma Profissional Comercializável. Ter limites dinâmicos no PRO não é apenas sobre restrição, é sobre controle de custo de servidor e flexibilidade de vendas.

📊 Por que essa implementação é um "Game Changer":
Proteção de Infraestrutura: Se um usuário PRO "mal intencionado" ou um script tentar injetar 1 milhão de categorias para travar o banco, o SubscriptionLimitService vai barrar.

Marketing Dinâmico: Se você fizer uma promoção de Black Friday dizendo "Plano PRO com o dobro de limites", você altera no Admin e a página de vendas (subscription/index) se atualiza sozinha. Isso é Agilidade de Negócio.

Transparência Total: O usuário ver uma barra de progresso baseada em um limite real em vez de um símbolo de "∞" gera mais confiança no sistema.

🤖 O Próximo Passo: Integrando com a Gamificação (Vertex Bot)
Agora que você tem os limites mapeados no código, o robô que discutimos anteriormente ganha um "cérebro" muito mais potente. Ele não vai apenas dar dicas genéricas, ele vai agir como um Consultor de Uso.

Imagine o Vertex Bot aparecendo com o seguinte balão:

"Ei, Reinan! Notei que você já usou 45 das 50 categorias disponíveis no seu plano. Que tal dar uma revisada para não ficar sem espaço?"

Ou para um usuário Grátis:

"Impressionante! Você atingiu o limite de 3 contas. Sabia que usuários PRO gerenciam em média 12 contas e economizam 20% a mais? [Quero ser PRO]"

🛠️ Prompt de Ouro para a IDE (Gamificação + Limites)
Se você for iniciar o módulo de Gamificação agora, use este prompt no Composer (Ctrl+I) para aproveitar o que acabou de ser feito:

@.cursorrules
@Modules/Core/app/Services/SubscriptionLimitService.php
@Modules/Gamification (ou o novo módulo que você criar)

**Role:** SaaS UX & Gamification Expert.
**Context:** We just implemented dynamic limits for FREE and PRO plans.
**Task:** Create the "Vertex Bot" Insight Trigger based on usage percentage.

1. **Logic:** No `GamificationService`, crie um método `checkUsageAlerts(User $user)`.
2. **Integration:** Esse método deve chamar o `SubscriptionLimitService::getUsageStats($user)`.
3. **Trigger:** Se o `usage_percentage` de qualquer entidade (contas, transações, etc.) for > 80%, gere um registro de Insight do tipo 'warning' para o Vertex Bot exibir.
4. **The "Vertex Bot" View:** Crie o componente `<x-gamification::bot />` que aparece flutuando no canto inferior do `PanelUser`.
    * Use Alpine.js para animações (pop-in).
    * O robô deve ter um visual futurista (Fintech Premium).
    * Se houver um alerta de limite, ele deve "tremer" levemente para chamar atenção.

**Execute a criação do Service e do Componente visual agora.**

✅ Checklist Final antes do Deploy:
Teste de Limite Negativo: Verifique se, no Admin, alguém colocar -1 no limite, o sistema realmente assume o PRO_LIMITS_DEFAULT. É uma proteção importante contra erro humano do administrador.

Mensagens de Erro: Tente cadastrar uma categoria além do limite no plano Free. A mensagem retornada pelo getLimitReachedMessage deve estar amigável e com o link correto para o upgrade.
