<?php

declare(strict_types=1);

/**
 * Autor: Reinan Rodrigues
 * Empresa: Vertex Solutions LTDA © 2026
 * Email: r.rodriguesjs@gmail.com
 */

namespace Modules\Blog\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\Models\BlogCategory;
use Modules\Blog\Models\Post;

class BlogPostSeeder extends Seeder
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

        $categories = BlogCategory::all()->keyBy('slug');
        if ($categories->isEmpty()) {
            $this->command->warn('Blog categories not found. Run BlogCategorySeeder first.');
            return;
        }

        $posts = $this->getPostsData();

        foreach ($posts as $data) {
            $category = $categories->get($data['category_slug']);
            if (! $category) {
                continue;
            }

            $slug = Str::slug($data['title']);

            Post::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'category_id' => $category->id,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'featured_image' => null,
                    'meta_description' => $data['meta_description'],
                    'og_image' => null,
                    'status' => 'published',
                    'is_premium' => $data['is_premium'],
                    'views' => 0,
                ]
            );
        }
    }

    /**
     * @return array<int, array{title: string, category_slug: string, is_premium: bool, meta_description: string, content: string}>
     */
    private function getPostsData(): array
    {
        return [
            // —— GRATUITOS ——
            [
                'title' => 'O que é reserva de emergência e como começar em 2026',
                'category_slug' => 'educacao-financeira',
                'is_premium' => false,
                'meta_description' => 'Entenda o que é reserva de emergência, por que ela importa e como começar a construir a sua em 2026 com passos práticos.',
                'content' => '<p>A <strong>reserva de emergência</strong> é um valor guardado para cobrir gastos inesperados, como desemprego, doença ou reparos urgentes. Em 2026, especialistas seguem recomendando o equivalente a <strong>6 a 12 meses de despesas fixas</strong> em aplicações de alta liquidez.</p><p>Para começar, defina seu gasto mensal médio (moradia, alimentação, saúde, transporte). Multiplique por 6 para um mínimo de segurança. O primeiro passo é abrir uma conta em banco ou aplicativo que pague pelo menos 100% do CDI e ir depositando um percentual fixo do que sobra no mês.</p><p>Dicas práticas: automatize um débito mensal no dia do salário, não use esse dinheiro para investimentos arriscados e mantenha em Tesouro Selic ou CDB líquido para resgate imediato quando precisar.</p>',
            ],
            [
                'title' => '5 passos para organizar suas finanças no novo ano',
                'category_slug' => 'organizacao-financeira',
                'is_premium' => false,
                'meta_description' => 'Cinco passos práticos para colocar as finanças em ordem em 2026: controle de gastos, metas e ferramentas que funcionam.',
                'content' => '<p>Organizar as finanças em 2026 não precisa ser complicado. Siga estes cinco passos e ganhe clareza sobre seu dinheiro.</p><ul><li><strong>1. Liste receitas e despesas:</strong> Anote ou use um app para registrar tudo que entra e sai durante um mês.</li><li><strong>2. Categorize os gastos:</strong> Agrupe em alimentação, transporte, lazer, contas fixas etc. para ver onde vai o dinheiro.</li><li><strong>3. Defina prioridades:</strong> Reserve primeiro para essenciais e reserva de emergência; o restante pode ir para metas e lazer.</li><li><strong>4. Automatize o que for possível:</strong> Débito automático para reserva e contas evita esquecimento e atrasos.</li><li><strong>5. Revise mensalmente:</strong> Ajuste o plano conforme a realidade e novos objetivos.</li></ul><p>Ferramentas como o Vertex Contas ajudam a centralizar contas, transações e metas em um só lugar, facilitando esse processo.</p>',
            ],
            [
                'title' => 'Regra 50-30-20 simplificada',
                'category_slug' => 'educacao-financeira',
                'is_premium' => false,
                'meta_description' => 'Aprenda a regra 50-30-20: 50% necessidades, 30% desejos e 20% poupança. Como aplicar no dia a dia em 2026.',
                'content' => '<p>A <strong>regra 50-30-20</strong> divide sua renda líquida em três blocos: 50% para necessidades (moradia, alimentação, saúde, transporte), 30% para desejos (lazer, assinaturas, compras) e 20% para poupança e investimentos.</p><p>Para aplicar: calcule sua renda líquida mensal. Some todas as despesas essenciais e verifique se cabem em 50%. Se passar, corte o que for supérfluo ou renegocie. Os 30% de desejos devem ser usados com consciência — não é obrigatório gastar tudo. Os 20% de poupança devem ser separados assim que a renda entrar, não no fim do mês.</p><p>Em 2026, essa regra continua válida como ponto de partida. Quem tem dívidas pode adaptar temporariamente (por exemplo, 50-20-30 até quitar) e depois voltar ao 50-30-20.</p>',
            ],
            [
                'title' => 'Como o Vertex Contas ajuda a controlar gastos',
                'category_slug' => 'dicas-vertex',
                'is_premium' => false,
                'meta_description' => 'Conheça como o Vertex Contas facilita o controle de gastos com contas, categorias, extrato e metas em um só lugar.',
                'content' => '<p>O <strong>Vertex Contas</strong> é uma plataforma pensada para quem quer enxergar de verdade para onde vai o dinheiro. Com ele você centraliza contas (conta corrente, poupança, carteira), registra receitas e despesas por categoria e acompanha o saldo em tempo real.</p><p>O controle de gastos começa no <strong>extrato</strong>: cada transação pode ser classificada (alimentação, transporte, lazer etc.), o que gera relatórios por categoria. Assim você identifica vazamentos e excessos. O recurso de <strong>Minha Renda</strong> ajuda a planejar a capacidade de gasto do mês com base na sua renda real.</p><p>Para quem quer ir além, o plano Vertex PRO oferece relatórios avançados, múltiplas contas e metas financeiras com acompanhamento de progresso. Tudo sem depender de conexão com banco — você mantém os dados sob seu controle.</p>',
            ],
            [
                'title' => 'Dicas para evitar dívidas no cartão de crédito',
                'category_slug' => 'economia-dia-a-dia',
                'is_premium' => false,
                'meta_description' => 'Evite dívidas no cartão em 2026: limite de uso, data de fechamento e como acompanhar faturas no dia a dia.',
                'content' => '<p>O cartão de crédito é prático, mas vira vilão quando o gasto supera o que você pode pagar à vista. Para evitar dívidas em 2026, siga estas práticas.</p><p><strong>Use até 30% do limite:</strong> Manter o uso abaixo de 30% do limite disponível protege seu score e evita surpresas na fatura. <strong>Pague a fatura total:</strong> Pagar só o mínimo alonga a dívida e encarece com juros altos. <strong>Conheça as datas:</strong> Saiba o dia do fechamento e do vencimento; programe o pagamento para antes do vencimento.</p><p>Registrar cada compra no Vertex Contas assim que fizer ajuda a não perder o controle. Crie uma categoria "Cartão de crédito" ou separe por tipo de gasto e confira no fim do mês se o total bate com a fatura. Assim você evita dívidas e mantém as finanças organizadas.</p>',
            ],
            [
                'title' => 'Primeiro emprego: primeiros passos financeiros',
                'category_slug' => 'educacao-financeira',
                'is_premium' => false,
                'meta_description' => 'Quem está no primeiro emprego: como organizar a renda, montar reserva de emergência e evitar armadilhas comuns.',
                'content' => '<p>Quem está no <strong>primeiro emprego</strong> muitas vezes sente a tentação de gastar tudo no início do mês. O segredo é criar hábitos desde cedo: separar uma parte para reserva, outra para metas e o restante para viver com tranquilidade.</p><p>Passos iniciais: abra uma conta em um banco ou fintech; se possível, já separe 10% a 20% da renda para reserva de emergência em aplicação de liquidez diária. Evite parcelar demais e não use o limite do cartão como "renda extra". Anote ou use um app para ver para onde vai o dinheiro.</p><p>Ferramentas como o Vertex Contas ajudam a visualizar receitas e despesas por categoria e a acompanhar uma meta de reserva. Quanto antes você criar o hábito de controlar e poupar, mais fácil fica construir patrimônio ao longo dos anos.</p>',
            ],
            [
                'title' => 'Por que ter mais de uma conta pode ajudar suas finanças',
                'category_slug' => 'organizacao-financeira',
                'is_premium' => false,
                'meta_description' => 'Separar contas por finalidade (conta do dia a dia, reserva, metas) ajuda a não misturar dinheiro e a poupar melhor.',
                'content' => '<p>Ter mais de uma conta não é luxo: é uma estratégia para <strong>separar finalidades</strong> e evitar que o dinheiro da reserva ou das metas seja consumido por gastos do dia a dia.</p><p>Exemplo de estrutura: uma conta para receber salário e pagar contas fixas e despesas correntes; outra para reserva de emergência (pode ser poupança ou CDB); e, se quiser, uma terceira para metas específicas (viagem, móveis, carro). Assim você enxerga claramente o que é "intocável" e o que pode usar no mês.</p><p>No Vertex Contas você pode cadastrar várias contas e registrar transferências entre elas. Isso facilita o controle sem precisar abrir vários bancos: o importante é a organização mental e o compromisso de não mexer nas contas de reserva e metas sem planejamento.</p>',
            ],
            [
                'title' => 'Economia no supermercado: como reduzir sem passar necessidade',
                'category_slug' => 'economia-dia-a-dia',
                'is_premium' => false,
                'meta_description' => 'Dicas práticas para gastar menos no supermercado em 2026: lista, marcas e planejamento semanal.',
                'content' => '<p>Reduzir gastos no supermercado em 2026 é possível sem abrir mão da qualidade. O primeiro passo é <strong>fazer lista</strong> e ir às compras depois de se alimentar, para evitar compras por impulso.</p><p>Compare preços entre marcas e versões (produtos genéricos costumam ser mais baratos). Aproveite promoções de itens não perecíveis e evite desperdício: planeje refeições da semana e use o que já tem na despensa. Comprar a granel para itens secos e congelar porções também ajuda.</p><p>Registrar no Vertex Contas quanto você gasta por mês em "Alimentação" ou "Supermercado" permite acompanhar se está dentro do orçamento e ajustar quando necessário. Pequenas mudanças de hábito geram economia consistente ao longo do ano.</p>',
            ],
            [
                'title' => 'Metas financeiras: como definir e acompanhar',
                'category_slug' => 'metas-e-sonhos',
                'is_premium' => false,
                'meta_description' => 'Defina metas financeiras realistas, com prazo e valor. Aprenda a acompanhar o progresso e ajustar quando necessário.',
                'content' => '<p>Metas financeiras dão direção ao seu dinheiro: em vez de "sobrar no fim do mês", você define <strong>o quê</strong> quer (reserva de emergência, viagem, carro, entrada do imóvel) e <strong>quando</strong> quer, e passa a guardar com propósito.</p><p>Passos: defina a meta em valor e data; calcule quanto precisa guardar por mês; se não couber no orçamento, estenda o prazo ou reduza o valor. Registre a meta em um lugar visível — no Vertex Contas você pode criar metas e acompanhar o progresso com valores já reservados.</p><p>Revise as metas de tempos em tempos. Se a renda aumentar, pode antecipar; se surgir um imprevisto, ajuste o prazo sem desistir. O importante é manter o hábito de separar um percentual para os seus objetivos.</p>',
            ],
            [
                'title' => 'Planejamento financeiro anual: por onde começar em 2026',
                'category_slug' => 'planejamento',
                'is_premium' => false,
                'meta_description' => 'Como montar um planejamento financeiro anual em 2026: metas, reserva, investimentos e revisão trimestral.',
                'content' => '<p>Um <strong>planejamento financeiro anual</strong> ajuda a alinhar gastos, poupança e investimentos aos seus objetivos. Em 2026, comece listando o que você quer alcançar no ano: quitar uma dívida, aumentar a reserva, fazer uma viagem, começar a investir.</p><p>Estime sua renda e despesas mensais e veja quanto sobra. Dessa sobra, defina quanto vai para reserva de emergência (se ainda não tiver 6 meses), quanto para metas específicas e quanto para investimentos de longo prazo. Use um aplicativo ou planilha para projetar os 12 meses e revisar a cada trimestre.</p><p>Ferramentas como o Vertex Contas permitem cadastrar receitas, despesas e metas em um só lugar. Com os dados organizados, fica mais fácil tomar decisões e manter o plano no ritmo ao longo do ano.</p>',
            ],
            // —— PREMIUM ——
            [
                'title' => 'Estratégia de investimento em CDB e Tesouro para 2026',
                'category_slug' => 'investimentos',
                'is_premium' => true,
                'meta_description' => 'Estratégia prática para alocar entre CDB e Tesouro em 2026: liquidez, prazo e diversificação para quem está começando.',
                'content' => '<p>Em 2026, <strong>CDB</strong> e <strong>Tesouro Direto</strong> continuam sendo opções sólidas para quem busca rentabilidade com risco controlado. A escolha depende de liquidez, prazo e tributação.</p><p><strong>Reserva de emergência:</strong> Priorize liquidez diária — Tesouro Selic ou CDB com liquidez diária, que acompanham a Selic e permitem resgate quando precisar. <strong>Metas de médio prazo (1 a 3 anos):</strong> CDBs prefixados ou pós-fixados com prazos alinhados à meta podem render mais que a poupança. <strong>Longo prazo (aposentadoria):</strong> Tesouro IPCA+ ou prefixado longo para proteger do risco inflacionário e travar uma taxa por muitos anos.</p><p>Diversifique entre instituições e prazos. Use o Vertex Contas para registrar quanto está em cada "conta" de investimento e acompanhar o progresso das metas sem misturar com o dinheiro do dia a dia.</p>',
            ],
            [
                'title' => 'Otimização fiscal com planejamento financeiro pessoal',
                'category_slug' => 'investimentos',
                'is_premium' => true,
                'meta_description' => 'Como o planejamento financeiro pessoal ajuda a reduzir impostos em 2026: declaração, deduções e investimentos incentivados.',
                'content' => '<p>O <strong>planejamento financeiro pessoal</strong> não é só sobre gastar menos: também envolve aproveitar deduções e investimentos que reduzem a carga tributária na declaração de IR.</p><p>Em 2026, mantenha registros de gastos dedutíveis: saúde, educação, dependentes e contribuições à previdência (PGBL). Quem tem renda variável pode compensar prejuízos com ganhos e declarar corretamente. Investimentos como LCI, LCA e debêntures incentivadas são isentos de IR para pessoa física e podem compor a carteira.</p><p>Organizar receitas, despesas e ativos no Vertex Contas facilita ter uma visão anual para o contador ou para você mesmo preencher a declaração. Com relatórios por categoria e por conta, fica mais simples comprovar despesas e planejar os próximos anos de forma mais eficiente do ponto de vista fiscal.</p>',
            ],
            [
                'title' => 'Como estruturar múltiplas contas e categorias no Vertex',
                'category_slug' => 'dicas-vertex',
                'is_premium' => true,
                'meta_description' => 'Vertex PRO: como organizar várias contas e categorias para relatórios precisos e controle total das finanças.',
                'content' => '<p>No <strong>Vertex Contas PRO</strong>, você pode criar várias contas (corrente, poupança, investimentos, carteira) e diversas categorias de receita e despesa. A estrutura certa faz toda a diferença nos relatórios e no controle.</p><p><strong>Contas:</strong> Use uma conta por "pote" real (banco X, corretora, dinheiro em casa). Assim o saldo de cada uma reflete a realidade e as transferências entre contas ficam registradas. <strong>Categorias:</strong> Crie categorias que você realmente usa: Alimentação, Transporte, Lazer, Saúde, Educação, Cartão de crédito etc. Subdivida só se fizer sentido para suas decisões (ex.: Supermercado vs Restaurante).</p><p>Com tudo categorizado, os relatórios de fluxo de caixa e por categoria passam a mostrar onde está indo o dinheiro e em qual conta. Use metas vinculadas a categorias ou contas para acompanhar reserva e objetivos sem confusão.</p>',
            ],
            [
                'title' => 'Relatórios avançados: fluxo de caixa e análise por categoria',
                'category_slug' => 'dicas-vertex',
                'is_premium' => true,
                'meta_description' => 'Vertex PRO: como usar relatórios de fluxo de caixa e análise por categoria para decisões financeiras melhores.',
                'content' => '<p>Os <strong>relatórios avançados</strong> do Vertex PRO permitem enxergar sua saúde financeira em gráficos e números: fluxo de caixa por período e análise por categoria.</p><p><strong>Fluxo de caixa:</strong> Mostra entradas e saídas ao longo do tempo (por mês ou período escolhido). Use para ver se há meses de déficit, sazonalidade (ex.: dezembro mais caro) e se a tendência é de sobra ou aperto. <strong>Análise por categoria:</strong> Revela em que você mais gasta (alimentação, transporte, lazer etc.) e permite comparar meses. Assim você identifica onde cortar ou onde está dentro do planejado.</p><p>Com esses dados, fica mais fácil ajustar orçamentos, definir tetos por categoria e tomar decisões com base em histórico real, não em "achismo". Exporte quando precisar para análise offline ou para o contador.</p>',
            ],
            [
                'title' => 'Planejamento de metas de longo prazo: carro, casa, aposentadoria',
                'category_slug' => 'metas-e-sonhos',
                'is_premium' => true,
                'meta_description' => 'Como planejar metas de longo prazo (carro, casa, aposentadoria) com prazos, valores e investimentos adequados em 2026.',
                'content' => '<p>Metas de <strong>longo prazo</strong> (carro, casa, aposentadoria) exigem prazos claros, valores definidos e uma estratégia de aportes e investimentos.</p><p><strong>Carro:</strong> Defina o valor alvo e a data. Calcule a parcela mensal necessária e guarde em aplicação de liquidez (CDB, Tesouro Selic) até a data. Evite financiar se os juros forem altos. <strong>Casa:</strong> Para entrada do imóvel, projete o valor e o prazo em anos. Aporte mensal em mix de renda fixa (CDB, Tesouro) e, se o prazo for longo, uma parte em renda variável (fundos ou ETFs) pode aumentar o potencial de ganho. <strong>Aposentadoria:</strong> Aporte regular em Tesouro IPCA+, previdência privada (PGBL ou VGBL) ou fundos de longo prazo, conforme seu perfil de risco.</p><p>No Vertex PRO você pode criar metas com valor e prazo, acompanhar o progresso e manter o histórico de aportes sem misturar com o dinheiro do dia a dia. Revisar as metas anualmente e ajustar aportes quando a renda mudar garante que o plano siga viável.</p>',
            ],
            [
                'title' => 'PIX e gestão do dia a dia: como registrar e não perder o controle',
                'category_slug' => 'organizacao-financeira',
                'is_premium' => true,
                'meta_description' => 'PIX tornou gastos mais rápidos; saiba como registrar cada transferência e manter o controle no Vertex em 2026.',
                'content' => '<p>O <strong>PIX</strong> facilitou pagamentos, mas também tornou os gastos mais "invisíveis" se você não registrar. Em 2026, o hábito de anotar ou sincronizar movimentações evita surpresas no fim do mês.</p><p>Crie o hábito: sempre que fizer um PIX de despesa (pagamento de conta, compra, transferência para alguém), registre no Vertex como despesa na categoria correta. Se receber por PIX, registre como receita. Assim o extrato reflete a realidade e os relatórios por categoria ficam precisos.</p><p>Quem usa Vertex PRO pode ter várias contas (incluindo "conta PIX" ou a conta do banco de onde sai o PIX). As transferências entre suas próprias contas não são receita nem despesa — só a saída para terceiros ou a entrada de terceiros. Organizando assim, você mantém controle total mesmo com o volume alto de transações via PIX.</p>',
            ],
            [
                'title' => 'Orçamento por categoria: como definir limites e não estourar',
                'category_slug' => 'planejamento',
                'is_premium' => true,
                'meta_description' => 'Vertex PRO: como definir orçamento por categoria, acompanhar limites e evitar estouro no mês.',
                'content' => '<p>Definir <strong>orçamento por categoria</strong> é uma forma prática de limitar gastos sem proibir nada: você estipula um teto para Alimentação, Lazer, Transporte etc. e acompanha ao longo do mês.</p><p>Passos: liste suas categorias principais; com base no histórico (ou em uma meta), defina um valor máximo por mês para cada uma; registre todas as despesas no Vertex nas categorias corretas. O Vertex PRO permite configurar orçamentos por categoria e acompanhar o percentual usado. Quando se aproximar do limite, você pode frear gastos naquela categoria antes de estourar.</p><p>Revise os limites a cada trimestre: se sempre sobra em uma categoria e falta em outra, redistribua. Orçamento não é prisão — é uma ferramenta para gastar com consciência e priorizar o que importa para você.</p>',
            ],
            [
                'title' => 'Renda variável: como planejar e controlar no Vertex',
                'category_slug' => 'planejamento',
                'is_premium' => true,
                'meta_description' => 'Quem tem renda variável: como usar Minha Renda e metas no Vertex para não perder o controle em 2026.',
                'content' => '<p>Quem tem <strong>renda variável</strong> (freelancers, comissionados, autônomos) precisa de um sistema que se adapte a entradas que mudam mês a mês. O Vertex Contas ajuda com o recurso Minha Renda e com metas flexíveis.</p><p>Cadastre todas as fontes de renda possíveis no Vertex (salário fixo, freelance, bicos, investimentos) e atualize os valores conforme os valores reais de cada mês. Use uma média dos últimos 6 ou 12 meses para planejar o "piso" de gastos e deixe o que passar disso para reserva e metas. Assim você evita gastar como se todo mês fosse o melhor mês.</p><p>Com relatórios de fluxo de caixa e por categoria, você enxerga em quais meses entrou mais ou menos e como os gastos se comportaram. Isso permite ajustar o orçamento e a reserva de emergência (para quem tem renda variável, 9 a 12 meses de despesas é mais seguro). No Vertex PRO, múltiplas contas e metas ajudam a separar o que é reserva do que é renda do mês.</p>',
            ],
        ];
    }
}
