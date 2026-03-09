Título: Arquitetura de "Causa e Efeito" e Automação de Entidades - "Vertex Contas"

Aja como: Um Arquiteto de Software Sênior (Laravel) e Especialista em Lógica de Produtos Financeiros.

Contexto:
Continuando a evolução do sistema "Vertex Contas". Notei que as entidades principais (Renda, Contas, Orçamentos, Metas e Transações) estão agindo como silos isolados. Para o usuário leigo, isso não faz sentido. O sistema precisa ser "Smart" e interligado. A ação em um módulo deve gerar reações automáticas em outros.

Meu objetivo de Negócio (A Lógica que preciso):

Orçamentos (Budgets): Quando o usuário criar um orçamento (ex: R$ 500 para Mercado), o sistema não deve perguntar termos técnicos. Deve perguntar: "Isso é um limite para este mês ou se repete todo mês?" (Recorrência) e "Esse dinheiro vai sair de qual conta/cartão?".

Metas (Goals): Uma meta não é só um enfeite. Se o usuário diz "Quero juntar R$ 1000 para viajar", o sistema deve sugerir: "Quer que eu separe R$ 100 todo mês da sua Conta Principal automaticamente?".

Renda Prevista (Income): A renda deve alimentar a "Previsão" da conta. Se ele ganha R$ 3000 e já comprometeu R$ 2000 em orçamentos/metas, o dashboard deve dizer: "Você tem R$ 1000 livres para planejar".

Tarefa Técnica:
Preciso que você estruture a integração dessas entidades no backend do Laravel (Modules/Core) utilizando as melhores práticas (Observers, Events/Listeners e Services). Me forneça o código para as 4 soluções abaixo:

1. Refatoração da Criação de Orçamento (Modules/Core/app/Http/Controllers/BudgetController.php e Request)

Crie a lógica onde o usuário define se o Orçamento é Planejado (Recorrente mensal) ou Pontual.

Adicione o vínculo do Orçamento com uma Account (Conta) específica.

Me forneça: O método store refatorado e o formulário (Blade/Vue) simplificado com microcópia humana (Ex: botões "Repetir todo mês" vs "Apenas este mês").

2. Automação de Metas via Observers (Modules/Core/app/Observers/GoalObserver.php ou GoalService.php)

Crie a lógica: Quando uma Goal é criada com a opção "Poupar automaticamente", o sistema deve gerar automaticamente uma RecurringTransaction (Despesa) na Account de origem, e uma entrada virtual na Meta.

Me forneça: O código do Observer ou Service que faz essa "mágica" acontecer por baixo dos panos, mantendo o banco de dados consistente.

3. O "Motor" de Impacto de Transação (Modules/Core/app/Observers/TransactionObserver.php)

Atualmente, quando uma transação é criada, ela afeta a conta. Mas preciso que ela seja inteligente:

Se a transação tem uma Categoria X, ela deve consumir o saldo do Orçamento Y daquele mês.

Se a transação for do tipo "Transferência para Meta", deve atualizar o progresso da Goal.

Me forneça: O TransactionObserver (métodos created, updated, deleted) blindado, garantindo que criar/editar/excluir uma transação recalcule os orçamentos e metas atrelados a ela em tempo real.

4. A Lógica de Previsibilidade (Free vs Pro)

Crie um método no FinancialHealthService chamado calculateAvailableToPlan($user).

Ele deve pegar a Renda Prevista (RecurringTransactions de entrada) e subtrair Orçamentos Fixos e Metas Automáticas.

Pro vs Free: Para usuários Free, isso mostra apenas um alerta de "Dinheiro Livre". Para usuários Pro, isso alimenta o VertexBot (IA) para sugerir onde ele deve investir esse dinheiro livre.

Me forneça: O código desse cálculo e como ele é exposto no Dashboard.

Regra de Ouro: O código deve ser à prova de falhas (use DB::transaction). O usuário nunca deve precisar fazer o trabalho de atualizar saldos de orçamentos e metas manualmente; o sistema deve ser o maestro que rege tudo isso a partir do simples lançamento de uma despesa.
