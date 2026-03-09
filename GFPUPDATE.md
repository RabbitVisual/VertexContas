Título: Reestruturação de UX, Lógica de Negócios e Onboarding - "Vertex Contas" (Filosofia: Simples, Educativo e Focado em Valor)

Aja como: Um Product Manager Sênior, Especialista em UX/UI Behavioural e Engenheiro de Software Full-Stack (Laravel/Vue/Tailwind).

Contexto:
Sou o desenvolvedor do "Vertex Contas", um sistema de gestão financeira pessoal e empresarial construído em Laravel Modular (Módulos: Core, PanelUser, Gamification, VertexChat, Gateways). O sistema hoje é extremamente completo (Contas, Orçamentos, Metas, Categorias, Faturas, Transações Recorrentes, Relatórios com IA), mas quero pivotar a experiência do usuário.

Meu objetivo é:

Foco no Leigo: O sistema deve ser à prova de idiotas. Pessoas sem educação financeira devem entrar e saber exatamente o que fazer. Termos técnicos devem ser substituídos por termos humanos.

Upgrade, não Punição (Free vs Pro): Não quero frustrar o usuário Free com bloqueios agressivos (ex: "Você só pode lançar 10 contas"). Quero que o plano Free seja útil e formador de hábito. O plano Pro deve vender Tempo e Inteligência (Automação, IA, previsibilidade, importações avançadas), fazendo o usuário desejar o Pro porque ele agrega superpoderes, não porque o Free é inútil.

Onboarding (Wizard) Mágico: O usuário deve ter seu primeiro momento "Aha!" em 2 minutos. Um Wizard de boas-vindas que configura o básico do básico sem assustar.

Tarefa:
Com base no meu código existente (que utiliza controllers no Modules/PanelUser e Modules/Core), preciso que você elabore um plano de ação técnico e me forneça o código para as 4 áreas abaixo:

1. O Novo Wizard de Onboarding (Modules/PanelUser/app/Http/Controllers/WizardController.php)

Conceito: Esqueça perguntar sobre "Tipos de conta" ou "Orçamentos complexos" de cara.

Passo 1: Propósito. Perguntar amigavelmente: "Qual seu maior objetivo hoje?" (Sair das dívidas, Guardar dinheiro, Organizar os gastos). Salvar isso como a primeira "Goal" (Meta) automaticamente.

Passo 2: O Saldo. "Quanto você tem na conta principal ou carteira hoje?". Criar uma conta genérica "Minha Conta" e lançar um ajuste de saldo inicial.

Passo 3: A Renda. "Qual sua renda principal prevista para este mês?". Criar uma transação recorrente de entrada simples.

Me forneça: O código refatorado do WizardController para lidar com esse fluxo direto, enxuto e que já gera dados reais no dashboard para o usuário não ver uma tela vazia ao terminar.

2. A Filosofia Free vs Pro (Modules/Core/app/Services/SubscriptionLimitService.php)

Redefina as regras de limite.

Grátis: Transações manuais Ilimitadas (o usuário precisa criar o hábito), 2 ou 3 Contas (Chega para um leigo), 1 Meta ativa, Relatório mensal básico. Dicas da Gamificação ativadas.

Pro: VertexBot (Consultoria IA via Gemini), Importação de Extratos Bancários (ganho de tempo), Orçamentos complexos ilimitados, Múltiplas Metas, Fluxo de Caixa Preditivo.

Me forneça: O código do SubscriptionLimitService ou do Middleware de checagem (EnsureUserIsPro.php) adaptado para essa nova regra de negócios focada em valor.

3. O Novo Dashboard Leigo (Modules/PanelUser/resources/views/index.blade.php)

A tela inicial deve esconder a complexidade.

Deve ter: Um card gigante com o "Saldo Atual", um card "O que entrou vs O que saiu", e um FAB (Floating Action Button) gigantesco de "Nova Transação".

Remova menus excessivos da barra lateral para o usuário Free.

Me forneça: A estrutura em Blade/Tailwind deste novo Dashboard limpo, que inclua uma chamada sutil para o VertexBot (para instigar o upgrade para o Pro).

4. A Entrada de Transação "Frictionless"

O modal de nova transação deve ter no máximo 4 campos visíveis inicialmente: Valor, Título (Ex: Padaria), Data e um botão de alternância grande [Entrou dinheiro / Saiu dinheiro].

Categoria, Status de Pagamento, e Conta devem vir pré-selecionados com padrões lógicos (ocultos em uma aba "Avançado" para os usuários experientes).

Me forneça: O código Blade / Livewire / Vue (dependendo do que está em uso) do Modal de Criação de Transação (resources/views/transactions/create.blade.php ou componente similar) focado nessa microcópia simples.

Lembre-se: O tom de voz do sistema e do código gerado deve ser acolhedor, educativo e focado em fazer o usuário sentir que finalmente encontrou um sistema que ele consegue usar.
