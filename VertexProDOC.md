# Vertex PRO – Documentação Completa

Este documento descreve tudo o que foi implementado para o plano **Vertex PRO** no sistema Vertex Contas.

---

## 1. Identificação do Usuário PRO

### 1.1 Método `User::isPro()`
**Arquivo:** `app/Models/User.php`

```php
public function isPro(): bool
{
    return $this->hasRole('pro_user') || $this->hasRole('admin');
}
```

- Usuários com role `pro_user` ou `admin` são considerados PRO.
- Usado em toda a aplicação para verificar acesso a recursos exclusivos.

### 1.2 Role e Permissões
**Arquivo:** `database/seeders/RolesAndPermissionsSeeder.php`

**Role:** `pro_user` (guard: web)

**Permissões do PRO:**
- `dashboard.view`
- `gateways.view`, `gateways.create`, `gateways.edit`, `gateways.delete`
- `core.view`, `core.create`
- `core.reports.full` (relatórios avançados)

**Comparado ao FREE:** O PRO tem `core.reports.full` e permissões completas de gateways (create/edit/delete).

---

## 2. Limites – FREE vs PRO

### 2.1 SubscriptionLimitService
**Arquivo:** `Modules/Core/app/Services/SubscriptionLimitService.php`

| Entidade  | FREE | PRO  |
|-----------|------|------|
| Contas    | 1    | Ilimitado |
| Receitas  | 5    | Ilimitado |
| Despesas  | 5    | Ilimitado |
| Metas     | 1    | Ilimitado |
| Orçamentos| 1    | Ilimitado |
| Categorias personalizadas | 0 | Ilimitado |

- `getLimit()` retorna `'unlimited'` para PRO.
- `canCreate()` retorna `true` sempre para PRO.
- `getUsageStats()` retorna `limit => 'unlimited'` para PRO (o componente `limit-status` **não é exibido**).

### 2.2 Componente limit-status
**Arquivo:** `Modules/Core/resources/views/components/limit-status.blade.php`

- **FREE:** Exibe barra de uso, X/Y, e botão "Desbloquear Ilimitado".
- **PRO:** Componente **não é renderizado** (oculto).

---

## 3. Páginas de Contas – Recursos PRO

### 3.1 index (Minhas Contas)
**Arquivo:** `Modules/Core/resources/views/accounts/index.blade.php`

| Recurso | Quem vê | Descrição |
|---------|---------|-----------|
| limit-status | Apenas FREE | Barra de limite de contas – **oculto para PRO** |
| Saldo consolidado | Todos | Total de todas as contas |
| **Resumo por tipo** | **Apenas PRO** | Grid com saldo por tipo (Corrente, Poupança, Dinheiro) + qtd de contas de cada tipo |
| Cards de conta | Todos | Lista de contas com nome no cartão |
| Botão Nova conta | Todos (se `can('create')`) | PRO pode criar sem limite |

### 3.2 show (Detalhe da Conta)
**Arquivo:** `Modules/Core/resources/views/accounts/show.blade.php`

| Recurso | Quem vê | Descrição |
|---------|---------|-----------|
| Card visual da conta | Todos | Dados da conta em card |
| Tabela de transações | Todos | Últimas transações em tabela estilo Excel |
| **Entradas / Saídas** | **Apenas PRO** | Dois cards com total de entradas e total de saídas da conta (quando há transações) |

### 3.3 create e edit
- **Sem diferença visual entre FREE e PRO.**
- PRO não vê limite na criação; backend permite criação ilimitada via `SubscriptionLimitService::canCreate()`.

---

## 4. Sidebar – Itens PRO

**Arquivo:** `Modules/PanelUser/resources/views/components/layouts/sidebar.blade.php`

| Item | Comportamento |
|------|---------------|
| **Dashboard Financeiro Pro** | Visível **apenas para PRO** – link para `core.dashboard` |
| Contas | FREE vê badge `X/1`; PRO não vê badge |
| Transações | FREE vê badge `X/10`; PRO não vê badge |
| Metas | FREE vê badge `X/1`; PRO não vê badge |
| Orçamentos | FREE vê badge `X/1`; PRO não vê badge |
| Categorias | FREE vê badge "PRO" (bloqueado); PRO acessa normalmente |
| Relatórios | FREE vê badge "PRO" (exportação bloqueada); PRO acessa normalmente |
| Planos | PRO vê badge "PRO" ao lado de "Planos" |
| **Faturas** | **PRO:** Link ativo para `core.invoices.index` com badge "PRO". **FREE:** Item desabilitado com badge "Em breve". |

---

## 5. Página de Faturas (PRO)

**Rota:** `core.invoices.index` (`/invoices`)  
**Arquivos:** `Modules/Core/app/Http/Controllers/InvoiceController.php`, `Modules/Core/resources/views/invoices/index.blade.php`

- **Acesso:** Apenas PRO (middleware `pro`).
- **Conteúdo:**
  - **Próxima cobrança:** Data calculada (último pagamento + 1 mês). Plano mensal recorrente (R$ 29,90/mês). Sem pagamento confirmado: mensagem informativa.
  - **Histórico de pagamentos:** Tabela com data, método, valor, status e referência de cada pagamento (PaymentLog do usuário).
- FREE não acessa; sidebar mostra "Em breve" no item Faturas.

---

## 6. Dashboard Financeiro Pro

- **Rota:** `core.dashboard`
- **Visível:** Apenas PRO (sidebar + rota protegida).
- **Arquivo:** `Modules/Core/resources/views/dashboard.blade.php`
- Estilo Flowbite Admin, cards de estatísticas e tabela de transações.

---

## 7. Relatórios Avançados

**Arquivos:**  
`Modules/Core/resources/views/reports/index.blade.php`  
`Modules/Core/resources/views/reports/cashflow.blade.php`  
`Modules/Core/resources/views/reports/category-ranking.blade.php`

- **PRO:** Acesso completo; botões de exportar PDF/CSV habilitados.
- **FREE:** Bloqueio com `@if(!hasRole('pro_user') && !hasRole('admin'))`; botões desabilitados com alerta.

---

## 8. Categorias Personalizadas

**Arquivo:** `Modules/Core/app/Models/Category.php`

- **PRO/Admin:** Podem criar categorias personalizadas (ilimitadas).
- **FREE:** Limite 0 categorias personalizadas; vê badge "PRO" na sidebar.

**Middleware/Controller:** `EnsureUserIsPro` e `CategoryController` checam `isPro()` antes de permitir create/edit.

---

## 9. Blog – Conteúdo Premium

**Arquivo:** `Modules/Blog/resources/views/show.blade.php`

- Posts com `is_premium = true` são restritos a usuários com role `pro_user`, `admin` ou `support`.
- FREE vê mensagem de conteúdo exclusivo PRO com CTA para upgrade.

---

## 10. Perfil do Usuário

**Arquivo:** `Modules/PanelUser/resources/views/profile/show.blade.php`

- PRO vê badge "Vertex PRO" e benefícios do plano.
- FREE vê CTA "Quero Ser PRO" com link para assinatura.

---

## 11. Assinatura e Upgrade

### 11.1 Página de Planos
**Arquivo:** `Modules/PanelUser/resources/views/subscription/index.blade.php`

**Comportamento diferenciado por tipo de usuário:**

| Usuário | O que vê |
|---------|----------|
| **PRO** | Mensagem de agradecimento ("Obrigado por ser Vertex PRO!"), cards didáticos com todos os benefícios em linguagem clara (contas ilimitadas, relatórios PDF/Excel, metas e orçamentos, suporte VIP), botão "Ver minhas faturas" e "Ir para Contas". **Não vê** cards de comparação de planos nem "Assinar PRO Agora". |
| **FREE** | Título "Preços - Evolua seu controle financeiro", cards de Plano Grátis e Vertex PRO, botão "Assinar PRO Agora" com gateways, histórico de pagamentos. |

- Texto para PRO: linguagem simples, não técnica, reforçando o sentimento de privilégio.
- Histórico de pagamentos visível para ambos.

### 11.2 CTA na Sidebar
**Arquivo:** `Modules/PanelUser/resources/views/components/layouts/sidebar.blade.php`

- Banner de upgrade exibido **apenas para FREE** (quando `!$isPro && !session('sidebar_cta_dismissed')`).
- Link para `user.subscription.index`.

---

## 12. Gateways – Assinaturas Recorrentes

### 12.1 Stripe Subscriptions
**Arquivo:** `Modules/Gateways/app/Drivers/StripeDriver.php`

- `createCheckoutSession()` usa `mode => 'subscription'` com `price_data.recurring.interval => 'month'`.
- Cobrança mensal automática de R$ 29,90.

**Webhooks (configurar no Dashboard Stripe):**
- `checkout.session.completed` – nova assinatura criada, upgrade do usuário
- `invoice.paid` – cobrança recorrente paga
- `invoice.payment_failed` – falha de pagamento (status `past_due`)

### 12.2 Mercado Pago Assinaturas (PreApproval)
**Arquivo:** `Modules/Gateways/app/Drivers/MercadoPagoDriver.php`

- Usa `PreApprovalClient` para criar assinatura recorrente (assinatura sem plano, com `auto_recurring`).
- Cobrança mensal automática de R$ 29,90.

**Webhooks (via `notification_url` no create):**
- `subscription_preapproval` – assinatura autorizada, upgrade do usuário
- `subscription_authorized_payment` – cobrança recorrente paga

### 12.3 Tabela `subscriptions`
**Arquivo:** `Modules/Gateways/database/migrations/2026_02_14_000001_create_subscriptions_table.php`

- Armazena `external_subscription_id`, `current_period_end`, `status` para próxima cobrança.

### 12.4 InvoiceController
**Arquivo:** `Modules/Core/app/Http/Controllers/InvoiceController.php`

- Prioriza `subscriptions.current_period_end` para exibir "Próxima cobrança".
- Fallback: último pagamento + 1 mês.

### 12.5 Painel Admin – Coerência

| Página | Arquivo | Descrição |
|--------|---------|-----------|
| Pagamentos | `PanelAdmin/PaymentController`, `payments/index.blade.php` | Lista PaymentLog com filtros (gateway, status), coluna "Tipo" (Recorrente/—), gráfico de receita |
| Assinaturas | `PanelAdmin/SubscriptionController`, `subscriptions/index.blade.php` | Lista assinaturas ativas com filtros, stats (ativas, past_due, canceladas) |
| Dashboard | `PanelAdminController`, `index.blade.php` | Card PRO mostra `activeSubscriptionsCount`; link para Assinaturas |

### 12.6 Fluxo do Usuário – Alinhamento

1. **Página Planos** (`user/subscription`) – FREE vê cards, clica "Assinar PRO Agora" → escolhe gateway (Stripe/Mercado Pago).
2. **CheckoutController** – Redireciona para Stripe Checkout (subscription) ou Mercado Pago PreApproval.
3. **Webhooks** – Criam Subscription, PaymentLog, upgrade para `pro_user`.
4. **Faturas** (`/invoices`) – Usa `current_period_end` da assinatura para "Próxima cobrança".
5. **Idempotência** – `processPayment` verifica `gateway_slug + external_id` antes de criar PaymentLog (evita duplicatas em retries).

---

## 13. Conta Demo PRO

### 13.1 Seeder
**Arquivo:** `database/seeders/DatabaseSeeder.php`

- **Email:** `pro@vertexcontas.com`
- **Senha:** `password`
- **Role:** `pro_user`
- Criado junto com Admin, User (FREE) e Support.

### 13.2 Auto Login (Login Demo)
**Arquivo:** `Modules/HomePage/resources/views/auth/login.blade.php`

- Botão "Pro" no bloco "Acesso Rápido (Demo)".
- Preenche email `pro@vertexcontas.com` e senha `password` e submete o formulário.

---

## 14. Painel Admin e Suporte

- **Admin:** Vê contagem de usuários PRO e badges PRO/FREE na listagem.
- **Suporte:** Vê badge "PRO" em tickets de usuários PRO; badge "Membro PRO" no perfil do usuário.

---

## 15. Resumo de Arquivos PRO-Relevantes

| Arquivo | Uso |
|---------|-----|
| `app/Models/User.php` | `isPro()` |
| `Modules/Core/app/Services/SubscriptionLimitService.php` | Limites FREE vs PRO |
| `Modules/Core/resources/views/components/limit-status.blade.php` | Oculto para PRO |
| `Modules/Core/resources/views/accounts/index.blade.php` | Resumo por tipo (PRO) |
| `Modules/Core/resources/views/accounts/show.blade.php` | Entradas/Saídas (PRO) |
| `Modules/PanelUser/resources/views/components/layouts/sidebar.blade.php` | Itens e badges PRO, link Faturas (PRO) |
| `Modules/PanelUser/resources/views/subscription/index.blade.php` | Página planos: PRO = agradecimento + benefícios; FREE = comparação |
| `Modules/Core/app/Http/Controllers/InvoiceController.php` | Faturas PRO |
| `Modules/Core/resources/views/invoices/index.blade.php` | View de faturas (histórico + próxima cobrança) |
| `Modules/Core/app/Http/Middleware/EnsureUserIsPro.php` | Proteção de rotas PRO |
| `Modules/Core/app/Models/Category.php` | Categorias personalizadas (PRO) |
| `Modules/Gateways/app/Http/Controllers/WebhookController.php` | Upgrade via pagamento |
| `database/seeders/DatabaseSeeder.php` | Conta demo PRO |
| `Modules/HomePage/resources/views/auth/login.blade.php` | Auto login PRO |
| `database/seeders/RolesAndPermissionsSeeder.php` | Role e permissões `pro_user` |

---

*Documento gerado em fevereiro/2025. Atualize conforme novas funcionalidades PRO forem implementadas.*

Configuração necessária
Stripe
No Dashboard Stripe → Webhooks, cadastre a URL e selecione:
checkout.session.completed
invoice.paid
invoice.payment_failed
Mercado Pago
Assinaturas usam notification_url enviada na criação do PreApproval, não a URL geral. O Mercado Pago chama essa URL em GET ou POST. A rota aceita ambos os métodos.
