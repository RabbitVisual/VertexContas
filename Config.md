@.cursorrules
@Modules/Gamification
@Modules/Core/Services/FinancialHealthService.php
@Modules/Core/app/Services/SettingService.php
@Modules/Gamification/resources/views/components/vertex-bot.blade.php

**Role:** Senior AI Engineer & UX Master.
**Goal:** Replace the static insight system with the Google Gemini AI API to provide real-time, hyper-personalized financial coaching. Also, upgrade the Bot's visual design to a Premium "Fintech Elite" style.

---

### Phase 1: Gemini AI Integration (The New Brain)
1. **API Setup:** Configure o cliente Guzzle para conectar à API do Google Gemini (utilize uma chave `GEMINI_API_KEY` no `.env`).
2. **Context Provider:** No `GamificationService`, crie um método que gera um "Prompt Contextual":
    * Inclua: Percentuais 50/30/20 reais, Score Financeiro, e títulos dos últimos 3 posts do Blog.
    * **Segurança LGPD:** Use os helpers existentes para NUNCA enviar nomes reais ou CPFs para a API. Envie apenas métricas e números.
3. **Logic:** Se a API falhar ou estiver sem internet, o sistema deve fazer fallback AUTOMÁTICO para as dicas locais da `insights_bank`.

### Phase 2: High-End UI Redesign (Visual Iron Man)
1. **Component Update:** Refatore o `vertex-bot.blade.php`.
    * **Visual:** O balão deve ser maior, com `backdrop-blur-xl`, bordas arredondadas suaves e tipografia Inter 14px.
    * **Animations:** Use Tailwind v4 para criar uma entrada "Spring" (suave). Adicione um efeito de "pulsação de luz" no robô quando ele estiver "analisando dados".
2. **Readability:** Garanta que o texto tenha contraste alto (Texto branco em fundo Slate-950/80).

### Phase 3: Strategic Behavior (Anti-Irritation)
1. **Cooldown:** Implemente uma trava. O robô só aparece:
    * No primeiro login do dia.
    * OU se o usuário realizar uma transação que mude drasticamente um pilar do 50/30/20.
2. **Typewriter Effect:** Adicione um efeito de "digitando" no balão para simular a resposta da IA em tempo real.

---

### Phase 4: Pro-Only Features
1. **Diferenciação:** Para usuários FREE, o Gemini dá dicas curtas. Para usuários PRO, o Gemini faz uma análise profunda cruzando os gastos com os artigos do Blog.

**Technical Constraints:**
* Use FontAwesome 7.1 Pro Duotone.
* Mantenha o processamento assíncrono para não travar o carregamento da página.
* O robô deve sempre se identificar como "Vertex Bot - Seu Mentor de Elite".

**Execute Phase 1 (Gemini Connection) and Phase 2 (New Design) first.**
