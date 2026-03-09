Você é um Engenheiro de Software Sênior especialista em Laravel, Padrão Modular (nWidart) e UX/UI para sistemas financeiros. Estamos fazendo um upgrade no módulo `Modules\Core` do sistema Vertex Contas. 

O objetivo deste upgrade é transformar o Core em um "Mentor Financeiro" para usuários com zero educação financeira, utilizando a **Regra 50/30/20** como base absoluta de toda a inteligência do sistema, e criar uma distinção matadora entre as contas **Free** e **Pro**.

Por favor, analise e modifique os seguintes arquivos no `@Modules/Core`:

1. `Modules/Core/app/Services/SubscriptionLimitService.php`
2. `Modules/Core/app/Services/FinancialHealthService.php`
3. `Modules/Core/app/Observers/LimitObserver.php`
4. `Modules/Core/resources/views/components/limit-status.blade.php`
5. `Modules/Core/resources/views/dashboard.blade.php`

Execute as seguintes tarefas passo a passo:

**PASSO 1: Refatoração do Controle de Limites (SubscriptionLimitService & LimitObserver)**
- Atualize o `SubscriptionLimitService` para não apenas retornar `true/false`, mas retornar um array detalhado com `['can_proceed' => bool, 'current_usage' => int, 'limit' => int, 'upsell_message' => string]`.
- Defina limites claros no código (ou referenciando configurações/banco): Free = 2 Contas, 1 Orçamento, 3 Metas. Categorias personalizadas bloqueadas para Free. Pro = Ilimitado.
- No `LimitObserver`, em vez de lançar uma exceção genérica que quebra a tela, certifique-se de que o sistema redirecione o usuário para uma rota nomeada (ex: `panel.user.limits.reached`) passando qual recurso estourou o limite, para podermos exibir uma tela bonita de "Faça o Upgrade".

**PASSO 2: Inteligência 50/30/20 (FinancialHealthService)**
- Modifique o `FinancialHealthService` para injetar a lógica do 50/30/20 de forma nativa. 
- Crie um método `calculate503020Distribution($userId, $month, $year)` que agrupe todas as despesas do mês baseando-se no campo `pillar` (Essencial, Desejo, Futuro) que já existe na tabela de categorias.
- O método deve retornar o valor gasto, a porcentagem atual em relação à renda do mês, e o "status" (Ex: Se 'Desejo' passou de 30%, retorna status 'warning' ou 'danger').
- Crie um método `getProInsights($userId)` que retorne dicas de IA baseadas nos desvios da regra 50/30/20 (bloqueie o uso deste método apenas para usuários Pro).

**PASSO 3: Upgrade Visual do Dashboard (dashboard.blade.php & limit-status.blade.php)**
- Atualize a `dashboard.blade.php` para destacar o componente 50/30/20 logo no topo. O usuário leigo precisa entrar e ver três barras ou cards: Necessidades (50%), Desejos (30%), Metas (20%).
- Use classes Tailwind/Alpine.js para animar as barras de progresso.
- Incorpore componentes de *paywall* (blur/bloqueio visual). Se o usuário for Free, adicione um card borrado chamado "Consultoria IA & Projeções" com um botão chamativo "Desbloquear com Pro".
- Crie um componente de notificação em `limit-status.blade.php` que exiba de forma amigável: "Você atingiu o limite de Contas do plano Grátis. Organize sua vida financeira sem limites com o Vertex Pro!".

**Regras de Código:**
- Siga estritamente o padrão PSR-12 e as convenções do Laravel.
- Escreva métodos curtos e descritivos.
- Mantenha a interface do usuário responsiva, utilizando o Tailwind CSS que já está configurado no projeto.
- Todo texto voltado ao usuário deve estar em Português (pt_BR) e ter um tom empático e encorajador. Nunca culpe o usuário por gastos altos, ofereça soluções.

Inicie a modificação pelos Serviços (Passo 1 e 2) e depois me mostre as alterações nas Views (Passo 3).

----------- PLANO DE AÇÂO ------------------
# upgrade-core-mentor-financeiro
 Refatorar limites de assinatura, injetar inteligência 50/30/20 e atualizar o dashboard do módulo Core para atuar como um mentor financeiro com distinções claras entre planos Free e Pro.

todos:
  - id: refine-limit-service
    content: Refatorar SubscriptionLimitService para retornar array detalhado de uso/limite com mensagem de upsell e limites fixos Free vs Pro.
    status: pending
  - id: update-limit-observer
    content: Atualizar LimitObserver para usar novo contrato de limite e redirecionar para rota paneluser.limits.reached ao estourar limite.
    status: pending
  - id: extend-limit-status-component
    content: Aprimorar componente limit-status.blade.php para exibir notificação amigável de limite atingido com call-to-action para Vertex Pro.
    status: pending
  - id: add-503020-distribution
    content: Implementar método calculate503020Distribution no FinancialHealthService, usando pillar/type_group e renda mensal como base.
    status: pending
  - id: add-pro-insights
    content: Implementar getProInsights no FinancialHealthService com dicas exclusivas para usuários Pro baseadas nos desvios 50/30/20.
    status: pending
  - id: update-dashboard-503020
    content: Atualizar dashboard.blade.php para exibir cards/barras 50/30/20 no topo usando Tailwind e Alpine.js.
    status: pending
  - id: add-dashboard-paywall
    content: Incorporar card de paywall “Consultoria IA & Projeções” no dashboard, borrado para Free e desbloqueado para Pro.
    status: pending
isProject: false
---

## Visão Geral

Vamos evoluir o `Core` para funcionar como um mentor financeiro baseado na regra 50/30/20, reforçando limites e diferenciais entre plano Free e Pro. O trabalho será dividido em três blocos: refino do serviço de limites/observer, criação da inteligência 50/30/20 no serviço financeiro e upgrade visual do dashboard com paywall e componente de distribuição.

## PASSO 1 – Refatoração de Limites (SubscriptionLimitService & LimitObserver)

- **1.1. Modelar contrato rico de resposta de limite**
  - Alterar o método principal de verificação de limite em `Modules/Core/app/Services/SubscriptionLimitService.php` para expor um método como `checkLimit(User $user, string $entity): array` que retorne:
    - `can_proceed: bool` – se ainda pode criar o recurso.
    - `current_usage: int` – quantidade atual de registros daquela entidade.
    - `limit: int|string` – limite numérico ou `'unlimited'` para Pro.
    - `upsell_message: string` – mensagem empática em pt_BR incentivando o upgrade quando estiver próximo/estourado.
  - Manter métodos auxiliares existentes (`getCurrentCount`, `getLimit`, `getUsageStats`) reaproveitando-os internamente para montar o novo array.
  - **Fixar limites base no código para Free** (quando não houver plano configurado/Settings):
    - Contas (`account`): 2
    - Orçamentos (`budget`): 1
    - Metas (`goal`): 3
    - Categorias personalizadas (`category`): 0
    - Rendimentos/Despesas podem continuar com limites mais altos baseada na estrutura atual ou serem tratados como ilimitados para a nova regra, documentando a decisão.
  - **Fixar comportamento Pro como ilimitado** por padrão (ou usar `Plan`/Settings quando disponíveis), de forma que planos pagos não sofram restrições de contas, orçamentos, metas e categorias.
- **1.2. Ajustar mensagens de upsell**
  - Reaproveitar lógica de `getLimitReachedMessage` para construir `upsell_message` com tom acolhedor, deixando claro:
    - Qual recurso atingiu o limite (contas, orçamentos, metas, categorias).
    - Benefício direto do Vertex Pro (sem limites + mentor financeiro mais completo).
  - Garantir que o texto seja sempre positivo e orientado a solução, sem culpar o usuário.
- **1.3. Evoluir o LimitObserver para redirecionar ao paywall**
  - Em `Modules/Core/app/Observers/LimitObserver.php`, além de `created`, introduzir um fluxo que, ao detectar estouro de limite para um usuário Free:
    - Use o novo método do `SubscriptionLimitService` (`checkLimit`) logo antes da persistência ser considerada definitiva (podemos validar num hook adequado ou fazer uma checagem pós-criação com rollback/soft-block, conforme a forma atual de criação).
    - Quando `can_proceed` for `false`, em vez de lançar exceção genérica, preparar um redirect para rota nomeada `paneluser.limits.reached` (conforme sua escolha), enviando:
      - `resource` (por exemplo, `account`, `budget`, `goal`, `category`).
      - Dados de uso atual e limite (para renderizar na view do paywall).
  - Decidir a abordagem de implementação do redirect no contexto do Observer:
    - Utilizar `abort(redirect()->route(...))` ou outro padrão já utilizado no projeto para redirecionamento a partir de camadas de domínio.
    - Documentar na docblock o comportamento para que controladores possam, se necessário, checar limites antes de tentar salvar (para UX mais suave).
- **1.4. Ajustes no componente de status de limite**
  - Atualizar o componente `Modules/Core/resources/views/components/limit-status.blade.php` para opcionalmente receber o array detalhado de limite (quando já estiver calculado) ou continuar chamando o serviço internamente.
  - Incluir um pequeno texto de upsell (usando `upsell_message` ou derivando dele) quando o uso estiver em 80%+ ou 100%, com botão/link para `user.subscription.index`.
  - Manter o design atual (barras com Tailwind), garantindo responsividade e consistência com o restante do painel.

## PASSO 2 – Inteligência 50/30/20 (FinancialHealthService)

- **2.1. Mapear pilar/pillar e estrutura de categorias**
  - Confirmar que a tabela de categorias possui campo equivalente a `pillar` ou `type_group` (`essential`, `lifestyle`, `financial`), como já utilizado em `get503020Breakdown` e `getBudgetHealthAnalysis`.
  - Decidir o mapeamento semântico:
    - `essential` → Necessidades (50%).
    - `lifestyle` (ou similar) → Desejos (30%).
    - `financial` → Futuro/Metas (20%).
- **2.2. Implementar `calculate503020Distribution($userId, $month, $year)`**
  - Em `Modules/Core/app/Services/FinancialHealthService.php`, adicionar método público que:
    - Resolve o `User` via `User::findOrFail($userId)`.
    - Determina o intervalo de datas do mês (`Carbon::create($year, $month, 1)->startOfMonth()/endOfMonth()`).
    - Calcula uma `incomeBase` deste mês:
      - Preferencialmente usando `getBaselineIncome($user)`; se 0, cair para soma de receitas reais (`Transaction` de `type=income` e `status=completed` no mês).
    - Faz query de despesas (`Transaction` com `type=expense`, `status=completed`) no período, join com categorias (coluna `pillar`/`type_group`).
    - Agrupa por pilar e calcula para cada um:
      - `amount` (valor gasto).
      - `percentage` em relação à renda base do mês.
      - `target` (50, 30, 20 conforme pilar).
      - `status` com base no desvio:
        - `ok` – dentro de faixa aceitável (por exemplo, até +5 p.p. acima do alvo, ou mais flexível para Essenciais).
        - `warning` – levemente acima, sugerindo atenção.
        - `danger` – bem acima (por exemplo, `Desejos` > 30% ou `Futuro` << 20%).
    - Retorna um array estruturado, por exemplo:
      - `['income' => float, 'pillars' => ['necessities' => [...], 'wants' => [...], 'future' => [...]], 'total_expenses' => float, 'savings_pct' => float]`.
- **2.3. Implementar `getProInsights($userId)` com paywall lógico**
  - Adicionar método público `getProInsights(int $userId): array` que:
    - Resolve o `User` e verifica se é Pro (usando `isPro()` e, se necessário, validações adicionais como presença de plano pago atual ou flag em tabela de planos; base padrão: tratar `isPro()` como verdade principal e só negar acesso se claramente marcado como trial expirado).
    - Se NÃO for Pro, retorna uma estrutura marcada como bloqueada, por exemplo:
      - `['available' => false, 'message' => 'Consultoria detalhada é exclusiva do Vertex Pro.']`.
    - Se for Pro:
      - Invoca `calculate503020Distribution` para o mês corrente.
      - Analisa desvios principais (por exemplo, se `Desejos` bem acima de 30%, se `Futuro` muito abaixo de 20%, se `Necessidades` comprimem demais a renda).
      - Gera um conjunto curto de dicas em pt_BR com tom empático:
        - Focar em alternativas: “Talvez renegociar contas fixas”, “Planejar cortes suaves em gastos variáveis”, “Priorizar uma reserva de emergência pequena e crescer aos poucos”.
      - Retorna algo como:
        - `['available' => true, 'highlights' => [...], 'actions' => [...], 'summary' => 'Texto curto acolhedor.']`.
- **2.4. Reutilização dentro do Core**
  - Atualizar (num passo posterior à aprovação) o controlador que povoa dados do dashboard para consumir `calculate503020Distribution` e `getProInsights`, preparando os arrays necessários para a view.
  - Garantir que, para usuários Free, apenas o painel básico 50/30/20 seja exibido; as `ProInsights` ficarão reservadas ao card com paywall.

## PASSO 3 – Upgrade Visual do Dashboard & limit-status

- **3.1. Inserir componente 50/30/20 no topo do dashboard**
  - Em `Modules/Core/resources/views/dashboard.blade.php`:
    - Logo após o hero principal (ou integrando-o), adicionar uma nova seção de cards/barras para a regra 50/30/20 baseada nos dados de `calculate503020Distribution` enviados pelo controlador.
    - Layout proposto:
      - Um card grande com três colunas responsivas (stack no mobile): Necessidades 50%, Desejos 30%, Futuro 20%.
      - Para cada pilar:
        - Título (“Necessidades”, “Desejos”, “Futuro”).
        - Barra de progresso horizontal com Tailwind (`bg-slate-200` + `bg-emerald/amber/indigo` com `transition-[width] duration-700` para animar).
        - Texto curto explicando o papel daquele pilar em linguagem simples.
      - Usar Alpine.js para aplicar um `x-data` com estado de carregamento das barras (iniciam em 0 e animam até a porcentagem real ao montar).
- **3.2. Paywall visual para consultoria IA & projeções**
  - Adicionar um card "Consultoria IA & Projeções" em `dashboard.blade.php`, próximo da seção da projeção Vertex AI:
    - Se usuário for Free:
      - Exibir card com efeito de blur/glassmorphism (ex.: `backdrop-blur`, `opacity-60`, `pointer-events-none` no conteúdo principal, mas com um overlay clicável).
      - Mensagem empática como: “Consultoria personalizada e projeções de longo prazo fazem parte do Vertex Pro. Desbloqueie para receber um plano guiado passo a passo.”
      - Botão chamativo "Desbloquear com Pro" levando a `user.subscription.index`.
    - Se usuário for Pro:
      - Card fica totalmente visível, usando dados retornados de `getProInsights` para mostrar 2–3 bullets de insights.
- **3.3. Atualizar/estender `limit-status.blade.php` como componente de notificação amigável**
  - Aproveitar o componente existente e criar um modo de "notificação" mais destacado (pode ser via prop `mode="banner"` ou similar) para mensagens de limite atingido.
  - Para o caso específico de contas no plano grátis, exibir copy algo como:
    - “Você atingiu o limite de Contas do plano Grátis. Organize sua vida financeira sem limites com o Vertex Pro!”
  - Incluir botão "Conhecer Vertex Pro" apontando para a rota de assinatura.
  - Garantir responsividade e coerência com o estilo glassmorphism/dark mode vigente.
- **3.4. Integração com rota `paneluser.limits.reached`**
  - (Em etapa futura de implementação) criar/ou integrar uma view para a rota `paneluser.limits.reached` (em módulo PanelUser) que utilize o componente `limit-status` em modo banner, recebendo via sessão ou query string os dados de qual recurso atingiu o limite e a mensagem de upsell.
  - Essa tela será o destino dos redirects disparados pelo `LimitObserver`.

## Considerações Técnicas e UX

- **Back-end**
  - Manter PSR-12 e padrões Laravel, com métodos curtos e nomes descritivos em `SubscriptionLimitService` e `FinancialHealthService`.
  - Centralizar toda a lógica 50/30/20 no serviço, de forma que Admin/Support possam reusar para relatórios futuros.
- **Front-end**
  - Usar exclusivamente Tailwind + Alpine já presentes, sem introduzir novas libs.
  - Garantir que todas as mensagens ao usuário estejam em pt_BR, com tom empático e encorajador.
  - Lembrar posteriormente de rodar `npm run build` caso seja necessário ajustar qualquer JS/CSS adicional.


