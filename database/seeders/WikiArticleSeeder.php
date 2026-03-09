<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Core\Models\WikiArticle;
use Modules\Core\Models\WikiCategory;

class WikiArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::role('admin')->first();
        if (! $author) {
            $this->command->warn('Admin user not found. Run DatabaseSeeder (RolesAndPermissions + AdminUserSeeder) first.');
            return;
        }

        $categories = WikiCategory::all()->keyBy('slug');
        if ($categories->isEmpty()) {
            $this->command->warn('Wiki categories not found. Run WikiCategorySeeder first.');
            return;
        }

        $articles = $this->getArticlesData();

        foreach ($articles as $data) {
            $category = $categories->get($data['category_slug']);
            if (! $category) {
                continue;
            }

            $slug = Str::slug($data['title']);
            $uniqueSlug = $slug;
            $n = 0;
            while (WikiArticle::where('slug', $uniqueSlug)->exists()) {
                $n++;
                $uniqueSlug = $slug . '-' . $n;
            }

            WikiArticle::firstOrCreate(
                [
                    'category_id' => $category->id,
                    'title' => $data['title'],
                ],
                [
                    'slug' => $uniqueSlug,
                    'content' => $data['content'],
                    'is_published' => true,
                    'views' => 0,
                    'author_id' => $author->id,
                ]
            );
        }
    }

    /**
     * @return array<int, array{title: string, category_slug: string, content: string}>
     */
    private function getArticlesData(): array
    {
        return [
            // Primeiros Passos
            [
                'title' => 'Como criar sua conta no Vertex Contas',
                'category_slug' => 'primeiros-passos',
                'content' => "Acesse a página de cadastro do Vertex Contas e informe seu e-mail, nome completo e uma senha segura.\n\nApós confirmar o e-mail (se a verificação estiver ativa), faça login e complete o perfil com os dados solicitados.\n\nNo primeiro acesso ao painel, você pode configurar sua primeira conta (conta corrente, poupança ou carteira) e cadastrar sua renda em Minha Renda para começar a usar o sistema.",
            ],
            [
                'title' => 'Configurar a primeira transação',
                'category_slug' => 'primeiros-passos',
                'content' => "Após criar uma conta no Vertex, acesse o menu Extrato ou Nova transação.\n\nEscolha o tipo: Receita ou Despesa. Selecione a conta que será movimentada, informe o valor, a data e a categoria (Alimentação, Transporte, etc.).\n\nOpcionalmente adicione uma descrição. Salve e a transação aparecerá no extrato e refletirá no saldo da conta.\n\nPara transferência entre duas contas suas, use a opção Transferências no menu.",
            ],
            [
                'title' => 'Entendendo o Dashboard',
                'category_slug' => 'primeiros-passos',
                'content' => "O Dashboard do Vertex exibe um resumo da sua situação financeira: saldo das contas, receitas e despesas do período, e indicadores configurados.\n\nUsuários no plano PRO podem ver gráficos de fluxo de caixa e por categoria. Use os filtros de período (mês atual, anterior ou personalizado) para analisar o que precisar.\n\nO Dashboard é a tela inicial após o login e ajuda a tomar decisões com base nos números reais cadastrados.",
            ],
            // Contas
            [
                'title' => 'Como adicionar uma nova conta',
                'category_slug' => 'contas',
                'content' => "No menu, acesse Contas (ou equivalente no seu módulo). Clique em Adicionar conta ou Novo.\n\nInforme o nome (ex.: Conta Corrente Nubank, Poupança, Carteira). Escolha o tipo quando disponível (conta corrente, poupança, carteira, investimento). Defina um saldo inicial se a conta já tiver valor.\n\nSalve. A nova conta passará a aparecer no extrato e nas telas de seleção de conta ao registrar transações e transferências.",
            ],
            [
                'title' => 'Diferença entre conta corrente, poupança e carteira',
                'category_slug' => 'contas',
                'content' => "No Vertex Contas você pode cadastrar diferentes tipos de conta para organizar seu dinheiro:\n\nConta corrente: representa contas em banco (débito, PIX, saques).\nPoupança: para reserva ou poupança bancária.\nCarteira: dinheiro em espécie ou em carteiras digitais sem vínculo bancário.\n\nA diferença é apenas organizacional. Todas entram no saldo total e podem receber transferências entre si. Use o tipo que melhor descreve o dinheiro na vida real.",
            ],
            [
                'title' => 'Como excluir ou inativar uma conta',
                'category_slug' => 'contas',
                'content' => "Acesse Contas e localize a conta desejada. Abra os detalhes ou edição.\n\nSe o sistema permitir exclusão, use a opção Excluir. Atenção: contas com transações vinculadas podem não ser excluídas ou exigir que você mova/delete transações antes.\n\nEm alguns fluxos é possível apenas \"arquivar\" ou ocultar a conta para não aparecer no dia a dia, mantendo o histórico. Consulte a tela de configurações da conta para as opções disponíveis no seu plano.",
            ],
            // Transações
            [
                'title' => 'Cadastrar uma receita',
                'category_slug' => 'transacoes',
                'content' => "Vá em Nova transação (ou Extrato e depois Novo). Selecione o tipo Receita.\n\nEscolha a conta que receberá o valor (ex.: conta corrente). Informe o valor, a data da entrada e a categoria (Salário, Freelance, Outros). Adicione uma descrição se quiser (ex.: Salário Janeiro/2026).\n\nSalve. A receita será somada ao saldo da conta e aparecerá no extrato e nos relatórios do período.",
            ],
            [
                'title' => 'Cadastrar uma despesa',
                'category_slug' => 'transacoes',
                'content' => "Acesse Nova transação e selecione o tipo Despesa.\n\nSelecione a conta de onde saiu o dinheiro. Informe o valor, a data e a categoria (Alimentação, Transporte, Lazer, etc.). Preencha a descrição quando útil (ex.: Supermercado dia 15).\n\nAo salvar, o valor será debitado do saldo da conta e aparecerá no extrato. Manter categorias corretas ajuda nos relatórios e orçamentos.",
            ],
            [
                'title' => 'Fazer transferência entre contas',
                'category_slug' => 'transacoes',
                'content' => "No menu, use a opção Transferências (ou equivalente).\n\nSelecione a conta de origem (de onde sai o valor) e a conta de destino (para onde vai). Informe o valor e a data.\n\nA transferência não é receita nem despesa: apenas move o saldo entre suas contas. Ambas as contas serão atualizadas e o lançamento aparecerá no histórico. Disponível conforme seu plano (Vertex PRO).",
            ],
            [
                'title' => 'Editar ou excluir uma transação',
                'category_slug' => 'transacoes',
                'content' => "No Extrato, localize a transação e abra (clique na linha ou no ícone de edição).\n\nPara editar: altere valor, data, categoria ou descrição e salve. O saldo da conta será recalculado.\n\nPara excluir: use a opção Excluir. A transação será removida e o saldo da conta ajustado. Essa ação não pode ser desfeita. Em caso de transferência, verifique se ambas as contas serão ajustadas conforme as regras do sistema.",
            ],
            // Minha Renda
            [
                'title' => 'O que é Minha Renda',
                'category_slug' => 'minha-renda',
                'content' => "Minha Renda é o recurso do Vertex para você planejar e registrar suas fontes de receita mensal.\n\nVocê informa quanto espera receber em cada mês (salário, freelance, aluguel, etc.). O sistema usa isso para calcular sua \"capacidade\" de gastos e para comparar com o que de fato entrou e saiu no extrato.\n\nAssim você evita gastar como se a renda fosse maior do que é e consegue planejar reserva e metas com base em números reais.",
            ],
            [
                'title' => 'Como cadastrar fontes de renda',
                'category_slug' => 'minha-renda',
                'content' => "Acesse o menu Minha Renda. Adicione uma nova fonte informando o nome (ex.: Salário, Freelance) e o valor esperado no mês.\n\nVocê pode ter várias fontes (salário + bicos + aluguel recebido). O total das fontes representa sua renda planejada do mês.\n\nAtualize os valores quando sua renda mudar. Em meses com renda variável, ajuste conforme o esperado para aquele mês específico.",
            ],
            [
                'title' => 'Ajustar Minha Renda para renda variável',
                'category_slug' => 'minha-renda',
                'content' => "Quem tem renda variável pode atualizar Minha Renda mês a mês.\n\nUse uma média dos últimos meses como base e, no início de cada mês, revise o valor esperado. Se tiver previsão de valores por projeto ou comissão, cadastre como fontes separadas e atualize conforme fechamentos.\n\nO Vertex não exige um valor fixo: o importante é manter o planejamento alinhado à realidade para que relatórios e capacidade de gasto façam sentido.",
            ],
            // Metas
            [
                'title' => 'Criar uma meta financeira',
                'category_slug' => 'metas',
                'content' => "No menu Metas, clique em Nova meta ou Criar meta.\n\nDefina o nome (ex.: Reserva de emergência, Viagem), o valor alvo em reais e o prazo desejado (data final ou número de meses).\n\nO sistema calcula quanto você precisa guardar por mês. Opcionalmente vincule aportes (valores que você \"separou\" para a meta) para acompanhar o progresso. Disponível no plano Vertex PRO.",
            ],
            [
                'title' => 'Acompanhar o progresso de uma meta',
                'category_slug' => 'metas',
                'content' => "Na listagem de Metas, cada item mostra o valor alvo, o que já foi reservado e a porcentagem concluída.\n\nRegistre aportes sempre que separar dinheiro para aquela meta (pode ser uma transferência para uma conta de reserva ou um lançamento simbólico). O progresso é atualizado e você pode ver se está no ritmo para bater a meta no prazo.\n\nAjuste o valor alvo ou o prazo se sua situação mudar.",
            ],
            [
                'title' => 'Vincular transações a uma meta',
                'category_slug' => 'metas',
                'content' => "Conforme a configuração do Vertex, você pode registrar aportes ligados a uma meta.\n\nAo registrar um aporte (valor que você separou para a meta), selecione a meta de destino. Esse valor passa a contar no progresso da meta.\n\nAlguns fluxos permitem vincular uma conta específica à meta (ex.: conta poupança da viagem). Consulte a tela de edição da meta para as opções disponíveis no seu plano.",
            ],
            // Orçamentos
            [
                'title' => 'Configurar orçamento por categoria',
                'category_slug' => 'orcamentos',
                'content' => "No menu Orçamentos (Vertex PRO), defina um limite de gastos por categoria para o mês.\n\nSelecione a categoria (Alimentação, Lazer, Transporte, etc.) e informe o valor máximo que deseja gastar. Salve. Durante o mês, o sistema soma as despesas daquela categoria e mostra o percentual usado.\n\nAssim você evita estourar em uma categoria sem perceber. Ajuste os limites conforme sua realidade e revise periodicamente.",
            ],
            [
                'title' => 'Alertas de limite de orçamento',
                'category_slug' => 'orcamentos',
                'content' => "Quando o orçamento de uma categoria estiver próximo ou acima do limite, o Vertex pode exibir alertas (conforme configuração do plano).\n\nVerifique na tela de Orçamentos o status de cada categoria (dentro do limite, próximo, estourado). Use esses indicadores para frear gastos naquele grupo ou para revisar o limite no mês seguinte.\n\nAlertas visuais (cores, ícones) ajudam a tomar decisão sem precisar calcular manualmente.",
            ],
            [
                'title' => 'Orçamento mensal e anual',
                'category_slug' => 'orcamentos',
                'content' => "Orçamentos no Vertex são configurados por período. O mais comum é o orçamento mensal: você define o teto de cada categoria para o mês corrente.\n\nSe houver suporte a orçamento anual, você pode planejar o total do ano e acompanhar o acumulado. Consulte a documentação da sua versão para saber se há visão anual e como os relatórios consideram período mensal vs anual.",
            ],
            // Categorias
            [
                'title' => 'Como criar e editar categorias',
                'category_slug' => 'categorias',
                'content' => "No menu Categorias (disponível no plano PRO), você pode criar categorias personalizadas de receita e despesa.\n\nInforme o nome (ex.: Supermercado, Streaming) e, se existir, o tipo (receita ou despesa). Salve. Ao registrar transações, a nova categoria aparecerá na lista.\n\nPara editar, abra a categoria e altere o nome. Categorias em uso continuarão vinculadas às transações já lançadas.",
            ],
            [
                'title' => 'Organizar despesas por categoria',
                'category_slug' => 'categorias',
                'content' => "Ao cadastrar cada despesa, escolha a categoria que melhor descreve o gasto (Alimentação, Transporte, Saúde, Lazer, etc.).\n\nManter esse hábito permite que os relatórios por categoria mostrem onde seu dinheiro está indo. Use categorias consistentes: por exemplo, evite misturar \"Restaurante\" e \"Alimentação\" se quiser relatórios mais finos.\n\nNo Vertex PRO você pode definir orçamentos e metas por categoria.",
            ],
            // Relatórios
            [
                'title' => 'Relatório de fluxo de caixa',
                'category_slug' => 'relatorios',
                'content' => "O relatório de fluxo de caixa (Vertex PRO) mostra entradas e saídas ao longo do tempo, por mês ou período escolhido.\n\nUse para ver se em determinados meses houve sobra ou déficit, e para identificar sazonalidade (ex.: dezembro mais caro). Os dados vêm das transações cadastradas nas suas contas.\n\nExporte para PDF ou planilha quando precisar enviar ao contador ou analisar fora do sistema.",
            ],
            [
                'title' => 'Relatório por categoria',
                'category_slug' => 'relatorios',
                'content' => "O relatório por categoria agrupa suas despesas (e receitas, se aplicável) por categoria no período filtrado.\n\nAssim você vê quanto gastou em Alimentação, Transporte, Lazer, etc. e pode comparar com meses anteriores ou com orçamentos. Disponível no plano Vertex PRO.\n\nUse esse relatório para cortar gastos onde estiver alto e para validar se está dentro do planejado.",
            ],
            // Assinatura e Pagamentos
            [
                'title' => 'Como assinar o Vertex PRO',
                'category_slug' => 'assinatura-pagamentos',
                'content' => "Acesse o menu Assinatura ou a página de planos. Escolha o plano Vertex PRO e clique em Assinar ou Assinar agora.\n\nSelecione a forma de pagamento (cartão de crédito, PIX ou outro gateway disponível). Preencha os dados solicitados e confirme. Após a aprovação do pagamento, sua conta será atualizada para PRO e você terá acesso a relatórios, múltiplas contas, metas e demais recursos do plano pago.",
            ],
            [
                'title' => 'Formas de pagamento aceitas',
                'category_slug' => 'assinatura-pagamentos',
                'content' => "O Vertex Contas aceita as formas de pagamento configuradas pela administração do sistema: geralmente cartão de crédito e PIX.\n\nCada gateway (processador de pagamento) pode ter regras próprias (parcelamento, recorrência). Consulte a tela de assinatura para ver as opções disponíveis na sua instalação.",
            ],
            [
                'title' => 'Como cancelar a assinatura',
                'category_slug' => 'assinatura-pagamentos',
                'content' => "Acesse Assinatura ou Perfil e localize a opção de gerenciar assinatura ou cancelar.\n\nAo cancelar, você deixa de ser cobrado no próximo ciclo. O acesso PRO permanece até o fim do período já pago. Após isso, sua conta volta ao plano gratuito e recursos exclusivos PRO deixam de estar disponíveis.\n\nSe tiver dúvidas, abra um chamado de suporte antes de cancelar.",
            ],
            [
                'title' => 'Consultar faturas e histórico de pagamentos',
                'category_slug' => 'assinatura-pagamentos',
                'content' => "No menu Assinatura ou Faturas (Vertex PRO), você visualiza as faturas geradas e o status (paga, pendente, vencida).\n\nClique em uma fatura para ver detalhes (valor, data, forma de pagamento). Use esse histórico para controle pessoal ou para enviar ao contador. Em caso de cobrança indevida ou problema, entre em contato com o suporte com o número ou data da fatura.",
            ],
            // Chamados e Suporte
            [
                'title' => 'Abrir um chamado de suporte',
                'category_slug' => 'chamados-suporte',
                'content' => "No painel do usuário, acesse Suporte ou Chamados. Clique em Novo chamado ou Abrir ticket.\n\nPreencha o assunto e descreva o problema ou dúvida com o máximo de detalhes (passos que fez, mensagem de erro, tela). Anexe capturas de tela se ajudar. Envie.\n\nVocê receberá um número do ticket e poderá acompanhar as respostas na mesma página. Responda às mensagens do suporte para agilizar o atendimento.",
            ],
            [
                'title' => 'O que é inspeção de conta (suporte)',
                'category_slug' => 'chamados-suporte',
                'content' => "A inspeção é um recurso que permite ao atendente visualizar sua tela (com permissão e limite de tempo) para ajudar a resolver um problema técnico.\n\nVocê precisa autorizar explicitamente. Durante a inspeção, o agente vê apenas o que você vê no sistema, dentro das regras de privacidade. O acesso é temporário e registrado. Clientes PRO podem ter janelas de inspeção maiores conforme política do produto.",
            ],
            // Segurança e LGPD
            [
                'title' => 'Acesso temporário de suporte e revogação',
                'category_slug' => 'seguranca-lgpd',
                'content' => "Se você concedeu acesso temporário a um agente de suporte (inspeção), esse acesso tem data/hora de término.\n\nVocê pode revogar antes do fim em Segurança ou na tela do chamado. Ao revogar, o agente perde a visualização imediatamente. Recomenda-se conceder apenas quando necessário para resolução do problema e sempre que possível acompanhar a sessão.",
            ],
            [
                'title' => 'LGPD e proteção de dados no Vertex',
                'category_slug' => 'seguranca-lgpd',
                'content' => "O Vertex Contas trata seus dados conforme a Lei Geral de Proteção de Dados (LGPD). As informações que você cadastra (nome, e-mail, CPF, transações, etc.) são usadas para oferecer o serviço, melhorar a plataforma e cumprir obrigações legais.\n\nVocê pode solicitar cópia dos seus dados, correção ou exclusão conforme a política de privacidade. Dados sensíveis são mascarados em contextos de suporte quando o atendente não precisa vê-los.",
            ],
            [
                'title' => 'Como alterar senha e e-mail',
                'category_slug' => 'seguranca-lgpd',
                'content' => "Para alterar a senha: acesse Perfil ou Configurações de conta. Localize a opção Alterar senha. Informe a senha atual e a nova senha duas vezes. Salve.\n\nPara alterar o e-mail: geralmente é necessário informar o novo e-mail e confirmar por link enviado à nova caixa de entrada. O e-mail é usado para login e recuperação de conta; mantenha-o atualizado e seguro.",
            ],
            // Dúvidas Frequentes
            [
                'title' => 'Quais os limites do plano gratuito',
                'category_slug' => 'duvidas-frequentes',
                'content' => "No plano gratuito do Vertex Contas você tem acesso ao painel básico: cadastro de contas, lançamento de receitas e despesas, extrato e visão geral.\n\nLimites típicos (conforme configuração da instalação): número de contas, número de transações ou de categorias pode ser limitado. Relatórios avançados, múltiplas contas, metas, orçamentos e exportação costumam ser recursos do plano PRO. Consulte a página de planos no site para a lista atual.",
            ],
            [
                'title' => 'Como exportar meus dados',
                'category_slug' => 'duvidas-frequentes',
                'content' => "A exportação de dados (extrato, relatórios) está disponível no plano Vertex PRO.\n\nAcesse Relatórios ou Extrato e use a opção Exportar (PDF, CSV ou Excel, conforme disponível). Escolha o período e baixe o arquivo. Para solicitar uma cópia completa dos seus dados por LGPD, use o canal indicado na política de privacidade (geralmente suporte ou formulário de solicitação).",
            ],
            [
                'title' => 'Diferença entre Vertex Grátis e Vertex PRO',
                'category_slug' => 'duvidas-frequentes',
                'content' => "Vertex Grátis: controle básico de contas, receitas, despesas e extrato, com limites de uso definidos pela administração.\n\nVertex PRO: mais contas, categorias ilimitadas (conforme plano), relatórios de fluxo de caixa e por categoria, metas financeiras, orçamentos, transferências entre contas, exportação de relatórios, faturas de assinatura e suporte prioritário. Ideal para quem quer análise completa e múltiplas contas sem limites restritivos.",
            ],
        ];
    }
}
