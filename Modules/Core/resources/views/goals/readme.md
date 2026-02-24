---
name: ""
overview: "Redesenho do fluxo de metas para um sistema de Gestão Financeira Pessoal (GFP) de nível profissional: vínculo transação-meta com integridade via Observer, contribuição mensal automática com rastreabilidade, capacidade mensal que considera compromisso com metas, status de meta concluída, UX orientada (quanto/mês e prazo), dashboard/relatórios e notificação de meta atingida."
todos: []
isProject: false
---

# Plano (revisado): Metas em um GFP profissional

Este plano vai **além do pedido original**: além de vincular despesas à meta e de contribuição automática mensal, incorpora **melhores práticas de GFP** (integridade de dados, capacidade real, status de conclusão, UX orientada e notificações) para o Vertex Contas ficar consistente e profissional.

---

## Princípios adotados (GFP)

- **Uma única fonte de verdade para o progresso da meta:** o valor acumulado da meta deve refletir exatamente as transações vinculadas (contribuições). Qualquer criação/edição/remoção de transação com `goal_id` atualiza a meta via Observer — sem duplicar lógica no comando de recorrência.
- **Capacidade mensal realista:** a “capacidade” exibida no dashboard deve considerar o que já está comprometido com metas (contribuições automáticas), para o usuário ver quanto realmente sobra após renda, despesas fixas e metas.
- **Metas concluídas como estado explícito:** uso de `completed_at` para arquivar conclusão, esconder de “Vincular à meta” e desativar recorrência automaticamente.
- **Rastreabilidade total:** toda contribuição vira despesa no extrato (conta/categoria/meta); relatórios e filtros por meta.

---

## 1. Modelo de dados

### 1.1 Tabela `goals`

- **Novas colunas:**
  - `completed_at` (nullable timestamp): preenchido quando a meta atinge o alvo; usado para listar “metas ativas” e desativar recorrência.
  - `monthly_contribution` (decimal 15,2, nullable): valor mensal da contribuição automática.
  - `contribution_account_id` (FK accounts, nullable): conta de onde sai o débito.
  - `contribution_category_id` (FK categories, nullable): categoria da despesa (ex.: Economia para Meta).
  - `contribution_recurrence_day` (tinyint 1–31, nullable, default 1): dia do mês para gerar a contribuição.

Regra: se `monthly_contribution` > 0, exigir `contribution_account_id` e `contribution_category_id`.

### 1.2 Tabela `transactions`

- **Nova coluna:** `goal_id` (nullable, FK `goals.id`, onDelete: set null). Apenas para tipo `expense` (contribuição “para” a meta). Transações com `goal_id` são a fonte de verdade do progresso (via Observer).

### 1.3 Tabela `recurring_transactions`

- **Nova coluna:** `goal_id` (nullable, FK `goals.id`, onDelete: set null). Uma recorrência por meta com contribuição automática; ao processar, cria apenas a `Transaction` com `goal_id`; o Observer atualiza a meta.

### 1.4 Categoria padrão

- No [CategorySeeder](Modules/Core/database/seeders/CategorySeeder.php): adicionar despesa “Economia para Meta”, `type_group` = financial (pilar 20%). Será a sugestão no formulário de contribuição automática.

---

## 2. Migrations (ordem)

1. `add_contribution_and_completed_at_to_goals_table.php`: `completed_at`, `monthly_contribution`, `contribution_account_id`, `contribution_category_id`, `contribution_recurrence_day` (com FKs).
2. `add_goal_id_to_recurring_transactions_table.php`: `goal_id` nullable, FK.
3. `add_goal_id_to_transactions_table.php`: `goal_id` nullable, FK onDelete set null.

---

## 3. Integridade: TransactionObserver (núcleo do GFP)

O [TransactionObserver](Modules/Core/app/Observers/TransactionObserver.php) já existe e atualiza saldo da conta. **Estender** (ou extrair para um observer dedicado “GoalSync” chamado após o mesmo evento) a lógica de metas para que o progresso da meta seja **sempre** derivado de transações:

- **created(Transaction):** se `goal_id` preenchido e `type === 'expense'` e `status === 'completed'`: incrementar `Goal::current_amount` com teto `target_amount`; se após incremento `current_amount >= target_amount`, marcar `goal->completed_at = now()`, salvar, e desativar recorrências dessa meta (`RecurringTransaction::where('goal_id', $goal->id)->update(['is_active' => false])).
- **updated(Transaction):** tratar mudança de `goal_id` (ou remoção): valor antigo decrementa a meta antiga (se existia); valor novo incrementa a nova meta (com teto). Ajustar `completed_at` e recorrências se a meta antiga deixar de estar “cheia”.
- **deleted(Transaction):** se tinha `goal_id` e `type === 'expense'`: decrementar `Goal::current_amount` (mínimo 0) e, se a meta tinha `completed_at`, limpar `completed_at` (meta reaberta).

Assim, **não** incrementar meta dentro de `RecurringTransaction::process()` nem no comando: apenas criar a `Transaction` com `goal_id`; o Observer é o único ponto que altera `goal.current_amount` e `goal.completed_at`. Evita duplicação e garante consistência ao editar/excluir transações manualmente.

---

## 4. Models

- **Goal:** `$fillable` + casts para os novos campos; relações `contributionAccount()`, `contributionCategory()`, `transactions()`, `recurringContributions()` (hasMany RecurringTransaction onde goal_id); `hasAutomatedContribution(): bool`; accessor `is_completed` pode usar `completed_at !== null` ou `current_amount >= target_amount`.
- **Transaction:** `goal_id` em `$fillable`; relação `goal()`; scope `scopeForGoal($query, $goalId)`.
- **RecurringTransaction:** `goal_id` em `$fillable`; relação `goal()`; em `process()`: criar a Transaction **já com** `goal_id` quando existir (descrição ex.: “Contribuição automática: {nome da meta}”); **não** alterar `Goal` aqui — o Observer faz isso ao persistir a transação.

---

## 5. Serviço GoalContributionService

- **syncRecurringForGoal(Goal $goal):**
  - Se a meta tem contribuição automática válida (valor > 0, conta e categoria preenchidos) e **não** está concluída (`completed_at` null):
    - Criar ou atualizar **um** `RecurringTransaction`: type=expense, amount=monthly_contribution, account_id, category_id, recurrence_day, frequency=monthly, description “Contribuição automática: {nome}”, goal_id=goal->id, is_baseline=false, is_active=true, next_date = próximo dia do mês (recurrence_day).
  - Caso contrário: desativar (is_active=false) ou soft-delete as recorrências com esse `goal_id`.
- Chamado em GoalController::store e ::update após salvar a meta.

---

## 6. Comando ProcessRecurringTransactions

- Manter como hoje: obter recorrências due/processable, não baseline; para cada uma, chamar `$recurring->process()`.
- **Não** adicionar lógica de meta aqui: `process()` já cria a Transaction com `goal_id`; o Observer atualiza a meta e desativa a recorrência quando a meta for atingida.

---

## 7. Capacidade mensal (FinancialHealthService)

- Em [FinancialHealthService](Modules/Core/app/Services/FinancialHealthService.php), método `calculateMonthlyCapacity(User $user)` (ou um novo método usado pelo dashboard):
  - Hoje: capacidade = receitas baseline − despesas fixas baseline.
  - **Incluir:** subtrair a soma de `monthly_contribution` de todas as metas do usuário que tenham contribuição automática ativa e **não** concluídas (`completed_at` null). Assim, “capacidade” = o que sobra após renda, despesas fixas e compromisso com metas.
- Opcional: método `getMonthlyGoalContributions(User $user): float` para exibir no dashboard algo como “Comprometido com metas: R$ X/mês” ou “Capacidade após metas: R$ Y”.

---

## 8. GoalController e formulários (create/edit)

- **StoreGoalRequest / UpdateGoalRequest:** validar `monthly_contribution` (nullable, numeric, min:0); se > 0, exigir `contribution_account_id` e `contribution_category_id` (exists, do user); `contribution_recurrence_day` (nullable, 1–31).
- **GoalController:** persistir os novos campos; após save, chamar `GoalContributionService::syncRecurringForGoal($goal)`.
- **Views create/edit:** bloco “Contribuição mensal automática”: toggle “Dedico todo mês um valor para esta meta”; campos valor, conta, categoria (sugerir “Economia para Meta”), dia (1–31).
- **UX orientação (GFP):**
  - Se a meta tem prazo (`deadline`): exibir “Para atingir até [data] você precisa de aproximadamente R$ X/mês” ( (target_amount − current_amount) / meses restantes ).
  - Quando o usuário preenche “contribuição mensal”: exibir “Com R$ Y/mês você atinge esta meta em aproximadamente N meses.”
- **Listagem (index):** indicador de contribuição automática ativa (ícone + valor mensal); metas concluídas com badge “Concluída” e opção de arquivar/ocultar se desejado depois.

---

## 9. Transações: vínculo opcional à meta

- **StoreTransactionRequest / UpdateTransactionRequest:** `goal_id` nullable; se preenchido, exists em goals e goal.user_id = auth id; permitir apenas quando type=expense.
- **TransactionController:** incluir `goal_id` no create/update da Transaction.
- **Views create/edit transação:** quando type=expense, select opcional “Vincular à meta” listando **apenas metas ativas** (completed_at null).
- **Extrato (transactions index):** coluna ou badge “Meta” (nome da meta); filtro “Por meta” (dropdown).

Ao vincular manualmente uma despesa a uma meta, o Observer incrementa a meta; ao desvincular ou excluir, decrementa. Comportamento unificado com a contribuição automática.

---

## 10. Dashboard e relatórios

- **Dashboard:** além da capacidade já ajustada (item 7), exibir “Destinado a metas este mês”: soma de transações (expense, com goal_id) no mês atual. Opcional: “Capacidade após metas” quando houver contribuições automáticas.
- **Relatórios / 50/30/20:** transações com categoria “Economia para Meta” entram no pilar financial; filtros por meta quando aplicável.

---

## 11. Notificações (meta concluída)

- Ao marcar meta como concluída no Observer (`completed_at` preenchido), disparar evento Laravel ex.: `GoalCompleted` (Goal $goal).
- No módulo **Notifications**: listener que envia notificação (in-app e/ou e-mail) ao usuário: “Parabéns! Você atingiu a meta [nome].” Incluir no escopo para um produto GFP completo.

---

## 12. Fluxo resumido (contribuição automática)

```mermaid
sequenceDiagram
    participant U as Usuário
    participant G as Meta
    participant S as GoalContributionService
    participant R as RecurringTransaction
    participant Cmd as ProcessRecurringTransactions
    participant T as Transaction
    participant Obs as TransactionObserver

    U->>G: Cria/edita meta com contribuição (R$ 500, conta, categoria, dia 5)
    G->>S: syncRecurringForGoal(goal)
    S->>R: Cria/atualiza recorrência (expense, goal_id, monthly)
    Note over R: Todo mês dia 5
    Cmd->>R: process()
    R->>T: Cria Transaction (despesa, goal_id)
    T->>Obs: created(T)
    Obs->>G: Incrementa current_amount (teto); se atingiu, completed_at + desativa R
```



---

## 13. Checklist de arquivos


| Ação    | Arquivo / responsabilidade                                                                                                                    |
| ------- | --------------------------------------------------------------------------------------------------------------------------------------------- |
| Criar   | Migrations: goals (completed_at + contribution_*), recurring_transactions (goal_id), transactions (goal_id)                                   |
| Alterar | CategorySeeder: adicionar “Economia para Meta” (financial)                                                                                    |
| Criar   | GoalContributionService (syncRecurringForGoal)                                                                                                |
| Alterar | Goal, Transaction, RecurringTransaction (fillable, relations, process com goal_id)                                                            |
| Alterar | **TransactionObserver**: created/updated/deleted para sync de goal (current_amount, completed_at, desativar recorrência)                      |
| Alterar | FinancialHealthService: capacidade mensal subtraindo contribuições automáticas de metas ativas; opcional getMonthlyGoalContributions          |
| Alterar | StoreGoalRequest, UpdateGoalRequest (regras contribuição)                                                                                     |
| Alterar | GoalController (store/update + sync); views goals create/edit/index (contribuição + UX prazo/valor mensal)                                    |
| Alterar | StoreTransactionRequest, UpdateTransactionRequest (goal_id); TransactionController (goal_id); views transactions (select meta, filtro, badge) |
| Alterar | RecurringTransaction::process(): passar goal_id na Transaction criada; não alterar Goal                                                       |
| Alterar | CoreController/dashboard: passar capacidade já descontando metas; exibir “Destinado a metas este mês” (e opcional “Capacidade após metas”)    |
| Criar   | Evento GoalCompleted; listener em Notifications para notificar usuário                                                                        |


Este plano torna o fluxo de metas **consistente, rastreável e alinhado a um sistema de Gestão Financeira Pessoal profissional**, sem duplicar regras e com capacidade mensal e notificações que fazem sentido para o usuário.
