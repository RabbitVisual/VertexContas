@.cursorrules
@Modules/Core/app/Services/SubscriptionLimitService.php
@Modules/PanelAdmin/app/Http/Controllers/SettingsController.php
@Modules/PanelUser/resources/views/subscription/index.blade.php

**Role:** Senior Business Logic Architect & SaaS Strategist.
**Goal:** Replace hardcoded "unlimited" logic for the PRO plan with a dynamic, Admin-configurable limit system. Ensure these limits reflect globally in the UI and Enforcement logic.

---

### Phase 1: Dynamic Settings Storage (Modules/Core & Admin)
1. **Settings Update:** No `PanelAdmin`, adicione campos na Central de Configurações para definir os limites do **Plano PRO**:
    * `pro_limit_accounts` (int)
    * `pro_limit_transactions_month` (int)
    * `pro_limit_categories` (int)
    * `pro_limit_goals` (int)
    * `pro_limit_budgets` (int)
2. **Persistence:** Salve esses valores na tabela `settings` usando o padrão de chave-valor já existente.

### Phase 2: Centralized Enforcement (Modules/Core)
1. **Refactor Service:** Update `Modules/Core/app/Services/SubscriptionLimitService.php`.
    * **Action:** Instead of returning `PHP_INT_MAX` or `9999` when `user->isPro()` is true, it MUST fetch the value from the `SettingService`.
    * **Fallback:** Define reasonable default values in the code (e.g., Accounts: 50, Transactions: 5000) if the setting is missing.
2. **Method Unification:** Ensure methods like `canAddAccount()`, `canAddTransaction()`, etc., use these dynamic values for BOTH Free and Pro, just changing the key they lookup.

### Phase 3: Global UI Sync (Frontend)
1. **Pricing Page:** Update `Modules/PanelUser/resources/views/subscription/index.blade.php`.
    * **Action:** Replace hardcoded text like "Ilimitado" with dynamic variables.
    * **Example:** "Cadastre até {{ $proLimitAccounts }} contas" em vez de "Contas Ilimitadas".
2. **Dashboard Feedback:** In the usage progress bars (if any), ensure the "Max" value is pulled from the dynamic limit.

### Phase 4: Business Logic Audit
1. **Scan Controllers:** Verifique se `AccountController`, `TransactionController` e `BudgetController` estão chamando o `SubscriptionLimitService` antes de salvar novos registros.
2. **Consistency:** Ensure that if an Admin changes the PRO limit from 50 to 10 accounts, users who already have 15 accounts are handled gracefully (they can't add more, but their data isn't deleted).

---

### Technical Constraints:
* Use APENAS ícones FontAwesome Pro local.
* Siga o padrão de design System Vertex (Tailwind v4 + Glassmorphism).
* Mantenha a tipagem estrita no Service.

**Execute Phase 1 and 2 first. Show me how you are structuring the new PRO settings in the Admin Panel.**


## 💡 Dicas de Limites Saudáveis para o Plano PRO:
Se você quer uma sugestão de valores iniciais "saudáveis" para o Admin:

| Recurso              | Sugestão Plano FREE | Sugestão Plano PRO (Dinâmico) |
|----------------------|--------------------|-------------------------------|
| Contas Bancárias     | 2 a 3              | 20 a 50                       |
| Transações / Mês     | 100 a 200          | 5.000 a 10.000                |
| Orçamentos           | 2                  | 20                            |
| Metas Financeiras    | 1                  | 15                            |
| Acesso ao Blog       | Apenas Grátis      | Tudo (PRO)                    |
