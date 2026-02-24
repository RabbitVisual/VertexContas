<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TourService
{
    public const TOUR_INCOME = 'income';
    public const TOUR_DASHBOARD = 'dashboard';
    public const TOUR_ACCOUNTS = 'accounts';
    public const TOUR_TRANSACTIONS = 'transactions';
    public const TOUR_TRANSFER = 'transfer';
    public const TOUR_GOALS = 'goals';
    public const TOUR_BUDGETS = 'budgets';
    public const TOUR_REPORTS = 'reports';
    public const TOUR_CATEGORIES = 'categories';
    public const TOUR_TICKETS = 'tickets';
    public const TOUR_TICKETS_CREATE = 'tickets-create';
    public const TOUR_TICKETS_SHOW = 'tickets-show';
    public const TOUR_SUBSCRIPTION = 'subscription';
    public const TOUR_PROFILE = 'profile';
    public const TOUR_SECURITY = 'security';
    public const TOUR_BLOG = 'blog';
    public const TOUR_LEGAL = 'legal';
    public const TOUR_ACHIEVEMENTS = 'achievements';
    public const TOUR_ACHIEVEMENTS_SHOW = 'achievements-show';
    public const TOUR_SETTINGS_ADMIN = 'settings-admin';
    public const TOUR_USERS_ADMIN = 'users-admin';
    public const TOUR_SUPPORT_ADMIN = 'support-admin';

    /**
     * Tours available per route (panel user).
     */
    protected array $routeTours = [
        'core.income.index' => self::TOUR_INCOME,
        'core.dashboard' => self::TOUR_DASHBOARD,
        'paneluser.index' => self::TOUR_DASHBOARD,
        'core.accounts.index' => self::TOUR_ACCOUNTS,
        'core.transactions.index' => self::TOUR_TRANSACTIONS,
        'core.transactions.create' => self::TOUR_TRANSACTIONS,
        'core.transactions.transfer' => self::TOUR_TRANSFER,
        'core.goals.index' => self::TOUR_GOALS,
        'core.budgets.index' => self::TOUR_BUDGETS,
        'core.reports.index' => self::TOUR_REPORTS,
        'core.categories.index' => self::TOUR_CATEGORIES,
        'user.tickets.index' => self::TOUR_TICKETS,
        'user.tickets.create' => self::TOUR_TICKETS_CREATE,
        'user.tickets.show' => self::TOUR_TICKETS_SHOW,
        'user.subscription.index' => self::TOUR_SUBSCRIPTION,
        'user.profile.show' => self::TOUR_PROFILE,
        'user.profile.edit' => self::TOUR_PROFILE,
        'user.security.index' => self::TOUR_SECURITY,
        'paneluser.blog.index' => self::TOUR_BLOG,
        'paneluser.blog.show' => self::TOUR_BLOG,
        'paneluser.legal.acceptance' => self::TOUR_LEGAL,
        'user.achievements.index' => self::TOUR_ACHIEVEMENTS,
        'user.achievements.show' => self::TOUR_ACHIEVEMENTS_SHOW,
    ];

    /**
     * Admin route tours.
     */
    protected array $adminRouteTours = [
        'admin.settings.index' => self::TOUR_SETTINGS_ADMIN,
        'admin.users.index' => self::TOUR_USERS_ADMIN,
        'admin.support.index' => self::TOUR_SUPPORT_ADMIN,
    ];

    /**
     * Get tour id for current route (if any).
     */
    public function getTourForRoute(?string $routeName, bool $isAdmin = false): ?string
    {
        $map = $isAdmin ? $this->adminRouteTours : $this->routeTours;

        return $map[$routeName] ?? null;
    }

    /**
     * Get steps definition for a tour. Returns array suitable for JS registerTourSteps().
     *
     * @return array<int, array{element: string, title: string, description: string, side?: string}>
     */
    public function getStepsForTour(string $tourId, bool $isPro = false): array
    {
        $parts = explode('-', $tourId);
        $method = 'steps' . implode('', array_map('ucfirst', $parts));
        if (method_exists($this, $method)) {
            return $this->{$method}($isPro);
        }

        return [];
    }

    /**
     * Record tour completion for analytics / gamification.
     */
    public function recordCompletion(User $user, string $tourId): void
    {
        $key = 'tour_completed_' . $tourId . '_' . $user->id;
        Cache::put($key, true, now()->addYears(1));

        // Optional: persist to DB for analytics (if table exists later)
        if ($this->hasTourCompletionsTable()) {
            DB::table('tour_completions')->insertOrIgnore([
                'user_id' => $user->id,
                'tour_id' => $tourId,
                'completed_at' => now(),
            ]);
        }
    }

    /**
     * Get tour completion stats for analytics (count per tour_id).
     *
     * @return array<string, int>
     */
    public function getCompletionStats(): array
    {
        if (! $this->hasTourCompletionsTable()) {
            return [];
        }

        return DB::table('tour_completions')
            ->select('tour_id')
            ->selectRaw('count(*) as total')
            ->groupBy('tour_id')
            ->pluck('total', 'tour_id')
            ->all();
    }

    /**
     * Check if user has completed a tour (from cache or DB).
     */
    public function hasCompleted(User $user, string $tourId): bool
    {
        $key = 'tour_completed_' . $tourId . '_' . $user->id;
        if (Cache::has($key)) {
            return true;
        }
        if ($this->hasTourCompletionsTable()) {
            $exists = DB::table('tour_completions')
                ->where('user_id', $user->id)
                ->where('tour_id', $tourId)
                ->exists();
            if ($exists) {
                Cache::put($key, true, now()->addYears(1));
                return true;
            }
        }
        return false;
    }

    protected function hasTourCompletionsTable(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('tour_completions');
    }

    /**
     * Steps for "Minha Renda" tour. Overridden by full implementation in implement-income-tour.
     */
    protected function stepsIncome(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="income-intro"]',
                'title' => 'Minha Renda',
                'description' => 'Aqui você planeja sua renda mensal esperada. Isso é diferente das transações: o planejamento define sua capacidade de gastos; as transações registram o que realmente entrou e saiu.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="income-form"]',
                'title' => 'Cadastro de renda',
                'description' => 'Informe a descrição (ex.: Salário), o valor, o dia de recebimento, a conta e a categoria. No plano Free você pode cadastrar uma renda; no PRO, várias fontes e despesas fixas.',
                'side' => 'top',
            ],
            [
                'element' => '[data-tour="income-list"]',
                'title' => 'Suas rendas',
                'description' => 'Lista das rendas planejadas. O total compõe sua capacidade mensal no dashboard.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsDashboard(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="dashboard-intro"]',
                'title' => 'Painel Financeiro',
                'description' => 'Bem-vindo ao seu centro de controle. Aqui você vê o resumo das finanças: saldo total das contas, receitas e despesas do mês e capacidade mensal. Use os cards abaixo e os atalhos para Extrato, metas e orçamentos.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="dashboard-balance"]',
                'title' => 'Saldo total',
                'description' => 'Soma do saldo de todas as suas contas neste momento. Atualizado a cada transação ou transferência que você faz.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="dashboard-income"]',
                'title' => 'Receitas do mês',
                'description' => 'Total da sua renda planejada (Minha Renda). A capacidade mensal é receitas menos despesas fixas' . ($isPro ? '; no Pro já descontamos o comprometido com metas' : '') . '. Compare com o que de fato entrou no Extrato.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="dashboard-capacity"]',
                'title' => 'Capacidade mensal',
                'description' => 'O que sobra para gastar após receitas e despesas fixas (Minha Renda).' . ($isPro ? ' No Vertex Pro, já descontamos o valor comprometido com metas; assim você vê quanto realmente sobra.' : ' Planeje em Minha Renda para manter esse número atualizado.') . '',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="dashboard-actions"]',
                'title' => 'Ações rápidas',
                'description' => 'Nova Transação: lançar receita ou despesa no Extrato. Transferir: mover saldo entre contas. Relatórios: fluxo de caixa, categorias, extrato e consultoria (Pro).',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsAccounts(bool $isPro): array
    {
        $steps = [
            [
                'element' => '[data-tour="accounts-intro"]',
                'title' => 'Minhas Contas',
                'description' => 'Cadastre contas bancárias ou em dinheiro. Cada conta tem seu saldo; as transações do Extrato movimentam o saldo da conta escolhida.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="accounts-balance"]',
                'title' => 'Saldo consolidado',
                'description' => 'Soma do saldo de todas as suas contas. O número de contas ativas aparece ao lado. Abaixo ficam as contas individuais para editar ou excluir.',
                'side' => 'bottom',
            ],
        ];

        if ($isPro) {
            $steps[] = [
                'element' => '[data-tour="accounts-summary-by-type"]',
                'title' => 'Resumo por tipo (PRO)',
                'description' => 'Exclusivo Vertex PRO: aqui você vê o total por tipo de conta — Corrente, Poupança e Dinheiro. Quando tiver contas cadastradas, cada coluna mostra o saldo e a quantidade daquele tipo.',
                'side' => 'bottom',
            ];
            $steps[] = [
                'element' => '[data-tour="accounts-cards"]',
                'title' => 'Cartões das contas',
                'description' => 'Aqui aparecem os cartões de cada conta. No Vertex PRO cada conta tem um cartão virtual com número mascarado (•••• •••• •••• XXXX), nome e saldo. Se ainda não houver nenhuma conta, este é o espaço onde elas aparecerão; use o botão "Nova conta" no topo para cadastrar. Quando já existir pelo menos uma conta, também aparece um card "Nova conta" aqui na grade para adicionar mais.',
                'side' => 'bottom',
            ];
            $steps[] = [
                'element' => '[data-tour="accounts-new-account"]',
                'title' => 'Nova conta',
                'description' => 'Clique aqui para cadastrar uma nova conta (corrente, poupança ou dinheiro em espécie). Cada conta tem seu próprio saldo e pode ser usada no Extrato ao registrar transações.',
                'side' => 'bottom',
            ];
        }

        return $steps;
    }

    protected function stepsTransactions(bool $isPro): array
    {
        $steps = [
            [
                'element' => '[data-tour="transactions-intro"]',
                'title' => 'Extrato mensal',
                'description' => 'Registre entradas e saídas de dinheiro. Diferente do planejamento em Minha Renda, aqui você lança o que de fato aconteceu. Use os filtros para ver um período ou tipo específico.',
                'side' => 'bottom',
            ],
        ];
        if ($isPro) {
            $steps[] = [
                'element' => '[data-tour="transactions-pro-export"]',
                'title' => 'Vertex Pro ativo',
                'description' => 'Exporte o extrato: clique no ícone PDF para abrir a versão em HTML (imprima ou salve como PDF pelo navegador) ou no Excel para baixar a planilha. Use Transferência para mover saldo entre contas.',
                'side' => 'bottom',
            ];
        }
        return $steps;
    }

    protected function stepsTransfer(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="transfer-intro"]',
                'title' => 'Transferência entre Contas',
                'description' => 'Aqui você move dinheiro de uma conta para outra (ex.: da corrente para a poupança). O saldo total das suas contas não muda — só a distribuição. Minha Renda e capacidade mensal não são alteradas; use esta tela apenas para organizar a liquidez entre contas.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="transfer-origin-destiny"]',
                'title' => 'Conta de origem e conta de destino',
                'description' => 'Conta de origem: de onde sai o dinheiro (ex.: Conta Corrente). Conta de destino: para onde vai (ex.: Poupança). Escolha contas diferentes; o sistema não permite origem e destino iguais. As duas listas mostram todas as suas contas ativas.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="transfer-amount"]',
                'title' => 'Valor da transferência',
                'description' => 'Informe o valor em reais (R$). Digite apenas os números; a formatação com vírgula para centavos é feita automaticamente. O valor não pode ser zero nem maior que o saldo disponível na conta de origem.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="transfer-date-description"]',
                'title' => 'Data e descrição',
                'description' => 'Data: em qual dia a transferência deve ser considerada (por padrão, hoje). Essa data aparece no Extrato. Descrição: opcional — use para identificar a transferência (ex.: "Ajuste para reserva", "Mês de dezembro"). Ajuda a localizar o lançamento depois no Extrato.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="transfer-actions"]',
                'title' => 'Confirmar ou cancelar',
                'description' => 'Confirmar transferência: valida origem, destino e valor e aplica a movimentação; os saldos são atualizados na hora e o lançamento aparece no Extrato. Cancelar: volta para o Extrato sem salvar nada.',
                'side' => 'top',
            ],
            [
                'element' => '[data-tour="transfer-tip"]',
                'title' => 'Lembrete importante',
                'description' => 'A transferência só move saldo entre contas — não gera receita nem despesa. O total do seu dinheiro continua o mesmo. Minha Renda e capacidade de gastos vêm do planejamento; use transferências apenas para organizar onde o dinheiro está (corrente, poupança etc.).',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsGoals(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="goals-intro"]',
                'title' => 'Minhas Metas',
                'description' => 'Metas são objetivos de economia com valor alvo (ex.: viagem, reserva de emergência). Clique em "Nova Meta" para criar; informe o valor que deseja acumular e acompanhe o progresso. No Vertex Pro você vê totais e progresso geral no topo.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="goals-list"]',
                'title' => 'Lista de metas',
                'description' => 'Cada card mostra uma meta: valor atual, valor alvo e barra de progresso. Clique em uma meta para editar ou adicionar aportes. Você pode ter várias metas ao mesmo tempo e acompanhar quanto falta para cada uma.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsBudgets(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="budgets-intro"]',
                'title' => 'Meus Orçamentos',
                'description' => 'Orçamentos definem um limite de gastos por categoria (ex.: Alimentação R$ 800). Você acompanha quanto já gastou no mês em relação ao limite. Clique em "Novo orçamento" para criar. No Pro você vê total orçado, total utilizado e consumo médio no topo.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="budgets-list"]',
                'title' => 'Lista de orçamentos',
                'description' => 'Cada card é um orçamento por categoria: limite definido, valor já gasto e barra de uso. As cores indicam se está dentro do limite (verde), próximo (âmbar) ou estourado (vermelho). Clique para editar o limite ou ver detalhes.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsReports(bool $isPro): array
    {
        $steps = [
            [
                'element' => '[data-tour="reports-intro"]',
                'title' => 'Seus Relatórios',
                'description' => 'Aqui você acessa relatórios financeiros: fluxo de caixa, ranking de categorias, extrato para impressão/PDF e, no Pro, consultoria mensal com análise 50/30/20 e score. Cada card leva à tela ou exportação correspondente.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="reports-cards"]',
                'title' => 'Cards de relatórios',
                'description' => 'Clique em um card para abrir o relatório: Fluxo de Caixa (entradas e saídas por período), Categorias (quem mais gasta), Extrato (para imprimir ou exportar). No Vertex Pro há também a Consultoria Mensal com recomendações e medalhas.',
                'side' => 'top',
            ],
        ];
        return $steps;
    }

    protected function stepsCategories(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="categories-intro"]',
                'title' => 'Minhas Categorias',
                'description' => 'Categorias classificam receitas e despesas no Extrato, nos orçamentos e nos relatórios. O sistema traz categorias padrão (ex.: Alimentação, Transporte). Com Vertex Pro você pode criar categorias personalizadas com ícone e cor. Use "Nova categoria" para criar.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="categories-list"]',
                'title' => 'Lista de categorias',
                'description' => 'Abaixo aparecem as categorias de receitas e, em seguida, as de despesas. Padrão = do sistema (não podem ser excluídas). Personalizadas = criadas por você (Pro); no hover aparece a opção de excluir. Cada categoria é usada ao lançar transações no Extrato.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsTickets(bool $isPro): array
    {
        $sla = $isPro ? '24h' : '72h';
        return [
            [
                'element' => '[data-tour="tickets-intro"]',
                'title' => 'Central de Ajuda',
                'description' => 'Gerencie seus chamados de suporte. O resumo no topo mostra total, abertos, pendentes e resolvidos. "Novo Chamado" abre uma solicitação; nossa equipe responde em até ' . $sla . ' conforme seu plano. No Pro você pode exportar o histórico em CSV.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="tickets-list"]',
                'title' => 'Lista de chamados',
                'description' => 'Todos os seus tickets aparecem aqui. Clique em um chamado para abrir a conversa, ver o status e responder. Você pode acompanhar até o fechamento.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsTicketsCreate(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="tickets-create-intro"]',
                'title' => 'Novo Chamado',
                'description' => 'Descreva o problema e a prioridade. Nossa equipe responderá conforme o SLA do seu plano.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="tickets-create-form"]',
                'title' => 'Formulário',
                'description' => 'Preencha assunto, urgência e detalhamento para abrir a solicitação.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsTicketsShow(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="tickets-show-conversation"]',
                'title' => 'Conversa',
                'description' => 'Mensagens trocadas com o suporte. Acompanhe o status do chamado aqui.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="tickets-show-status"]',
                'title' => 'Status',
                'description' => 'Estado atual do ticket: aberto, pendente, respondido, resolvido ou fechado.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsSubscription(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="subscription-intro"]',
                'title' => 'Planos e Assinatura',
                'description' => 'Gerencie sua assinatura, altere o plano ou cancele. Veja os benefícios de cada opção.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsProfile(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="profile-intro"]',
                'title' => 'Meu Perfil',
                'description' => 'Dados pessoais, foto e preferências. Edite para manter suas informações atualizadas.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsSecurity(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="security-intro"]',
                'title' => 'Segurança',
                'description' => 'Altere sua senha, gerencie acesso de suporte e exporte logs de acesso.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsBlog(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="blog-intro"]',
                'title' => 'Blog',
                'description' => 'Artigos e conteúdo sobre finanças. Leia, comente e salve seus favoritos.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsLegal(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="legal-intro"]',
                'title' => 'Aceite Legal',
                'description' => 'Termos de uso e políticas. Aceite para continuar usando o painel.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsAchievements(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="achievements-intro"]',
                'title' => 'Conquistas',
                'description' => 'Medalhas e progresso. Desbloqueie conquistas ao usar o sistema.',
                'side' => 'bottom',
            ],
            [
                'element' => '[data-tour="achievements-list"]',
                'title' => 'Lista de medalhas',
                'description' => 'Clique em uma medalha para ver detalhes e compartilhar.',
                'side' => 'top',
            ],
        ];
    }

    protected function stepsAchievementsShow(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="achievements-show-detail"]',
                'title' => 'Detalhe da conquista',
                'description' => 'Informações da medalha e opção de compartilhar.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsSettingsAdmin(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="settings-tabs"]',
                'title' => 'Configurações',
                'description' => 'As abas organizam: Geral, Marca, E-mail, Blog, Documentos, Segurança, Funcionalidades, Pusher, Homepage, Ferramentas e Gemini/IA.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsUsersAdmin(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="users-intro"]',
                'title' => 'Gestão de usuários',
                'description' => 'Liste, filtre e gerencie usuários. Clique em um usuário para ver detalhes, alterar plano ou acompanhar indicadores financeiros.',
                'side' => 'bottom',
            ],
        ];
    }

    protected function stepsSupportAdmin(bool $isPro): array
    {
        return [
            [
                'element' => '[data-tour="support-intro"]',
                'title' => 'Central de Suporte',
                'description' => 'Atenda tickets, atribua agentes e acompanhe o status das solicitações. Use os filtros para encontrar tickets abertos ou por prioridade.',
                'side' => 'bottom',
            ],
        ];
    }
}
