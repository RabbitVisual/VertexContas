<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\LegalDocument;

class LegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Documentos legais completos para produção (LGPD, CDC, Brasil).
     */
    public function run(): void
    {
        $documents = [
            [
                'slug' => 'termos-de-uso',
                'title' => 'Termos de Uso',
                'content' => $this->termosDeUsoContent(),
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => true,
            ],
            [
                'slug' => 'privacidade',
                'title' => 'Política de Privacidade (LGPD)',
                'content' => $this->privacidadeContent(),
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => true,
            ],
            [
                'slug' => 'termos-assinatura',
                'title' => 'Termos de Assinatura',
                'content' => $this->termosAssinaturaContent(),
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => false,
            ],
            [
                'slug' => 'politica-cookies',
                'title' => 'Política de Cookies',
                'content' => $this->politicaCookiesContent(),
                'version' => '1.0.0',
                'is_active' => true,
                'requires_acceptance' => false,
            ],
        ];

        foreach ($documents as $document) {
            LegalDocument::updateOrCreate(
                ['slug' => $document['slug']],
                $document
            );
        }
    }

    private function termosDeUsoContent(): string
    {
        return '<h1>Termos de Uso - Vertex Contas</h1>'
            . '<p>Estes Termos de Uso (“Termos”) regem o acesso e a utilização do serviço <strong>Vertex Contas</strong>, plataforma de gestão financeira pessoal operada pela <strong>Vertex Solutions LTDA</strong>, pessoa jurídica de direito privado, com sede no Brasil, inscrita no CNPJ sob o número que consta no site ou no painel administrativo da plataforma.</p>'
            . '<p>Ao acessar, cadastrar-se ou utilizar a plataforma Vertex Contas, o usuário (“Você” ou “Usuário”) declara ter lido, compreendido e concordado integralmente com estes Termos de Uso e com a <a href="' . route('privacy') . '" class="text-primary hover:underline">Política de Privacidade</a>, em conformidade com a legislação brasileira vigente, em especial o Código de Defesa do Consumidor (Lei nº 8.078/1990), a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 – LGPD) e o Marco Civil da Internet (Lei nº 12.965/2014).</p>'

            . '<h2>1. Objeto e aceite</h2>'
            . '<p>O Vertex Contas é uma plataforma de gestão financeira pessoal que permite o controle de receitas, despesas, contas, metas e orçamentos. No <strong>plano gratuito</strong>, estão disponíveis, entre outros: cadastro de contas e categorias; lançamento limitado de receitas e despesas (conforme limites do plano); planejamento de renda; metas e orçamentos; dashboard e indicadores básicos; e suporte via ticket. No <strong>plano PRO</strong> (assinatura paga), o usuário tem acesso a recursos adicionais detalhados nos <a href="' . route('public.legal.show', 'termos-assinatura') . '" class="text-primary hover:underline">Termos de Assinatura</a>, incluindo análise com inteligência artificial (IA), consultoria financeira, relatórios avançados, importação de extratos e suporte prioritário.</p>'
            . '<p>O uso da plataforma, seja em ambiente de teste ou em uso regular, implica aceitação destes Termos de Uso e da Política de Privacidade. Se você não concordar com qualquer parte destes Termos, não utilize o serviço.</p>'

            . '<h2>2. Funcionalidades do sistema</h2>'
            . '<p>O sistema inclui, sem limitar-se a: cadastro de contas bancárias e categorias; lançamento de receitas e despesas; planejamento de renda recorrente (Minha Renda); metas financeiras; orçamentos por categoria; relatórios e exportações (conforme plano contratado); dashboard com indicadores e gráficos; metodologia 50/30/20. Para assinantes do plano PRO: uso de ferramentas de <strong>inteligência artificial (IA)</strong> para análise e consultoria financeira (incluindo integração com modelos como Google Gemini), projeções financeiras interativas, relatórios em formato Business Statement com conclusão assinada por IA, importação de extratos (CSV) com categorização assistida por IA e suporte VIP via chat em tempo real. O usuário pode ainda participar de programas de <strong>gamificação</strong> (medalhas, conquistas) e, quando aplicável, autorizar <strong>inspeção remota</strong> por agente de suporte, nos termos da funcionalidade disponível na plataforma e com limite de tempo e escopo definidos pela Vertex Solutions LTDA.</p>'

            . '<h2>3. Uso de inteligência artificial</h2>'
            . '<p>Recursos que utilizam IA (incluindo, mas não se limitando a, modelos como Google Gemini) podem processar dados financeiros do usuário para fornecer análises, sugestões e textos de consultoria. Os dados são tratados conforme a Política de Privacidade e não são utilizados para treinar modelos de terceiros em prejuízo da confidencialidade. O usuário é integralmente responsável pelas decisões tomadas com base nas informações geradas pela IA. As análises e relatórios gerados têm caráter <strong>informativo e de apoio à gestão</strong> e não constituem aconselhamento financeiro, tributário ou jurídico profissional. A Vertex Solutions LTDA não se responsabiliza por perdas ou decisões tomadas com base em conteúdo gerado por IA.</p>'

            . '<h2>4. Cadastro e responsabilidade</h2>'
            . '<p>O usuário deve fornecer informações verdadeiras, completas e atualizadas no cadastro e manter senha e dados de acesso em sigilo. É responsável por todas as atividades realizadas em sua conta. A Vertex Solutions LTDA reserva-se o direito de suspender ou encerrar contas em caso de violação destes termos, uso indevido da plataforma, fraude, prática de atos ilícitos ou não cumprimento de obrigações contratuais ou legais. O usuário compromete-se a não utilizar a plataforma para fins ilegais, ofensivos ou que infrinjam direitos de terceiros.</p>'

            . '<h2>5. Propriedade intelectual</h2>'
            . '<p>O software, marcas, logotipos, layout e conteúdo da plataforma Vertex Contas são de propriedade da Vertex Solutions LTDA ou de seus licenciadores. O usuário não adquire qualquer direito de propriedade sobre eles, exceto o direito de uso não exclusivo, intransferível e revogável, durante a vigência do contrato e nos limites das funcionalidades disponíveis no plano contratado. É vedada a cópia, engenharia reversa, redistribuição ou uso comercial do sistema sem autorização expressa.</p>'

            . '<h2>6. Limitação de responsabilidade</h2>'
            . '<p>Na máxima extensão permitida pela lei aplicável, a Vertex Solutions LTDA não se responsabiliza por danos indiretos, incidentais, especiais ou consequenciais decorrentes do uso ou da impossibilidade de uso da plataforma, incluindo perda de dados ou decisões tomadas com base em relatórios ou análises geradas pelo sistema. A responsabilidade da empresa limita-se, quando cabível, ao valor efetivamente pago pelo usuário nos doze meses anteriores ao fato gerador, ressalvadas as hipóteses de dolo ou culpa grave e as garantias legais do consumidor.</p>'

            . '<h2>7. Indisponibilidade e manutenção</h2>'
            . '<p>A Vertex Solutions LTDA envidará esforços razoáveis para manter a plataforma disponível. Eventuais interrupções para manutenção programada serão comunicadas quando possível. Não há garantia de disponibilidade ininterrupta; a empresa não se responsabiliza por indisponibilidade decorrente de fatores fora de seu controle razoável (falhas de rede, terceiros, força maior).</p>'

            . '<h2>8. Alterações dos Termos</h2>'
            . '<p>Alterações nestes Termos de Uso podem ser publicadas na plataforma. Em caso de mudança relevante, o usuário poderá ser solicitado a aceitar novamente os termos. O uso continuado após a divulgação constitui aceite quando permitido pela lei. Em alterações que afetem substancialmente as obrigações do usuário ou os benefícios do serviço, a Vertex Solutions LTDA comunicará com destaque e, quando cabível, respeitará o direito de rescisão sem ônus nos termos do CDC.</p>'

            . '<h2>9. Vigência e rescisão</h2>'
            . '<p>Estes Termos vigoram a partir da data da primeira utilização da plataforma. O usuário pode encerrar sua conta a qualquer momento pelas ferramentas disponíveis no painel. A Vertex Solutions LTDA pode encerrar ou suspender o acesso em caso de violação destes Termos, conforme previsto na cláusula 4. Os efeitos das cláusulas que por natureza devam sobreviver (propriedade intelectual, limitação de responsabilidade, lei aplicável) permanecem após a rescisão.</p>'

            . '<h2>10. Lei aplicável e foro</h2>'
            . '<p>Estes Termos de Uso regem-se pelas leis da República Federativa do Brasil. Fica eleito o foro da comarca do domicílio do consumidor para dirimir quaisquer controvérsias oriundas destes termos, com ressalva às hipóteses de competência absoluta previstas em lei.</p>'

            . '<h2>11. Contato</h2>'
            . '<p>Para dúvidas sobre estes Termos de Uso, entre em contato pelos canais indicados no site da plataforma Vertex Contas (rodapé, página de ajuda ou painel do usuário), incluindo o e-mail de suporte ou contato oficial.</p>'
            . '<p><em>Documento emitido por <strong>Vertex Solutions LTDA</strong>. Jurisdição: Brasil. Última atualização: vigente a partir da publicação na plataforma.</em></p>';
    }

    private function privacidadeContent(): string
    {
        return '<h1>Política de Privacidade - LGPD</h1>'
            . '<p>A <strong>Vertex Solutions LTDA</strong> está comprometida com a proteção dos dados pessoais de seus usuários, em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 – LGPD). Esta Política de Privacidade descreve de forma transparente quais dados coletamos, para que finalidades, com qual base legal e como você pode exercer seus direitos como titular.</p>'

            . '<h2>1. Controlador e contato</h2>'
            . '<p><strong>Controlador dos dados:</strong> Vertex Solutions LTDA, pessoa jurídica de direito privado, com sede no Brasil. O endereço completo e o canal de contato para assuntos de privacidade e exercício de direitos do titular (incluindo o e-mail de privacidade ou DPO, quando aplicável) são divulgados no site da plataforma Vertex Contas, no rodapé e nas configurações disponíveis ao usuário. Para exercer seus direitos previstos na LGPD (acesso, correção, eliminação, portabilidade, revogação de consentimento etc.), entre em contato pelos canais indicados no site ou no painel do usuário.</p>'

            . '<h2>2. Dados coletados</h2>'
            . '<p>Coletamos: (a) <strong>dados de cadastro</strong>: nome, e-mail, CPF, data de nascimento, telefone e demais informados no registro; (b) <strong>dados de uso da plataforma</strong>: transações, contas, categorias, metas, orçamentos, renda planejada e demais informações inseridas pelo usuário para a gestão financeira; (c) <strong>dados de assinatura e pagamento</strong>: processados por gateways de pagamento (Stripe, Mercado Pago e correlatos), em conformidade com suas políticas de privacidade – não armazenamos números completos de cartão; (d) <strong>dados técnicos</strong>: endereço IP, tipo de navegador, sessão, registro de consentimento de cookies e logs de acesso necessários à segurança e ao cumprimento legal. Dados financeiros inseridos pelo usuário (valores, descrições, categorias) são armazenados exclusivamente para prestação do serviço de gestão financeira e, no plano PRO, para geração de relatórios, consultoria e análises com uso de inteligência artificial (IA).</p>'

            . '<h2>3. Finalidades do tratamento</h2>'
            . '<p>Os dados são utilizados para: prestação do serviço de gestão financeira pessoal; criação e manutenção de conta; processamento de assinatura (plano PRO) e cobrança; geração de relatórios, consultoria e análises com IA (incluindo integração com provedores como Google Gemini), quando aplicável ao plano contratado; suporte ao usuário (incluindo chat e inspeção remota quando autorizada pelo titular); melhoria da plataforma, segurança e experiência do usuário; cumprimento de obrigações legais e regulatórias; e comunicação com o usuário sobre o serviço (ex.: avisos de manutenção, alteração de termos). O consentimento de cookies é tratado conforme a <a href="' . route('public.legal.show', 'politica-cookies') . '" class="text-primary hover:underline">Política de Cookies</a>, com registro em banco de dados para auditoria e conformidade à LGPD, com validade de 90 (noventa) dias quando aplicável.</p>'

            . '<h2>4. Base legal</h2>'
            . '<p>O tratamento apoia-se em: <strong>execução de contrato</strong> (prestação do serviço e assinatura); <strong>cumprimento de obrigação legal</strong> (ex.: guarda de documentos fiscais e contábeis); <strong>legítimo interesse</strong> (segurança, melhoria do produto, prevenção de fraudes, garantia de integridade dos dados); e <strong>consentimento do titular</strong>, quando exigido pela LGPD (ex.: cookies não essenciais, comunicações de marketing). Quando o tratamento for baseado em consentimento, você pode revogá-lo a qualquer momento, sem prejuízo da licitude do tratamento anterior.</p>'

            . '<h2>5. Compartilhamento e subprocessadores</h2>'
            . '<p>Os dados podem ser compartilhados com operadores (subprocessadores) que nos auxiliam na prestação do serviço: provedores de hospedagem e infraestrutura em nuvem; serviços de e-mail transacional; gateways de pagamento (Stripe, Mercado Pago e correlatos); e provedores de IA utilizados para funcionalidades do plano PRO. Tais operadores são contratados com obrigação de confidencialidade e conformidade à LGPD. <strong>Não vendemos dados pessoais.</strong> Em inspeção remota autorizada pelo usuário, o agente de suporte pode visualizar apenas os dados necessários ao atendimento, nos limites e no período aceitos pelo usuário.</p>'

            . '<h2>6. Direitos do titular</h2>'
            . '<p>Conforme o art. 18 da LGPD, você tem direito a: confirmação da existência de tratamento; acesso aos dados; correção de dados incompletos, inexatos ou desatualizados; anonimização, bloqueio ou eliminação de dados desnecessários ou tratados em desconformidade; portabilidade dos dados a outro fornecedor de serviço ou produto; eliminação dos dados tratados com consentimento (respeitadas as hipóteses de conservação legal); informação sobre compartilhamento e possibilidade de não consentir; e revogação do consentimento. Para exercer esses direitos, entre em contato pelos canais indicados no site ou no painel. Os prazos e procedimentos seguem o disposto na LGPD. Você tem ainda o direito de apresentar reclamação perante a Autoridade Nacional de Proteção de Dados (ANPD).</p>'

            . '<h2>7. Segurança e retenção</h2>'
            . '<p>Adotamos medidas técnicas e organizacionais para proteger os dados contra acesso não autorizado, perda, destruição ou alteração indevida, em conformidade com o art. 46 da LGPD. Os dados são mantidos pelo tempo necessário à prestação do serviço, ao cumprimento de obrigações legais e ao exercício de direitos em juízo ou administrativamente. Após encerramento da conta, os dados podem ser mantidos de forma anonimizada ou pelo prazo legal aplicável (ex.: 5 anos para documentos que suportem obrigações fiscais e tributárias).</p>'

            . '<h2>8. Alterações desta Política</h2>'
            . '<p>Alterações nesta Política de Privacidade podem ser publicadas na plataforma. Em caso de mudança relevante, comunicaremos por e-mail ou por aviso destacado no painel, quando aplicável. O uso continuado após a divulgação constitui aceite quando permitido pela lei. Em alterações que exijam novo consentimento, solicitaremos sua manifestação.</p>'

            . '<h2>9. Contato e DPO</h2>'
            . '<p>Para exercer seus direitos ou esclarecer dúvidas sobre o tratamento de dados pessoais, entre em contato pelos canais indicados no site (rodapé, página de ajuda) ou no painel do usuário. Quando houver Encarregado de Proteção de Dados (DPO), o canal será divulgado na plataforma.</p>'
            . '<p><em>Documento emitido por <strong>Vertex Solutions LTDA</strong>. Jurisdição: Brasil. Em conformidade com a LGPD (Lei nº 13.709/2018).</em></p>';
    }

    private function termosAssinaturaContent(): string
    {
        return '<h1>Termos de Assinatura - Vertex Contas</h1>'
            . '<p>Os presentes Termos de Assinatura celebrados entre o usuário e a <strong>Vertex Solutions LTDA</strong> estabelecem as condições da prestação do serviço de controle financeiro mediante assinatura (plano PRO e correlatos), em conformidade com o Código de Defesa do Consumidor (Lei nº 8.078/1990) e demais normas aplicáveis no território nacional.</p>'

            . '<h2>1. Planos e preços</h2>'
            . '<p>Os planos, preços e condições de cobrança (mensal ou anual) são divulgados na página de planos do site, na página de assinatura no painel do usuário e podem ser alterados com comunicação prévia. A Vertex Solutions LTDA pode ajustar valores e benefícios para novos assinantes, respeitando o período já pago pelos assinantes atuais. O valor devido em cada ciclo é o vigente no momento da renovação.</p>'

            . '<h2>2. Benefícios do plano PRO</h2>'
            . '<p>O plano PRO pode incluir, conforme configuração vigente na plataforma: registro ilimitado (ou conforme limites do plano) de receitas e despesas; metodologia 50/30/20 com análise de IA; CFO virtual (análise e consultoria com uso de IA, ex.: Gemini); projeções financeiras interativas; relatórios em formato Business Statement com conclusão assinada por IA; importação inteligente de extratos (CSV) com categorização assistida por IA; e suporte VIP via chat em tempo real. Limites e funcionalidades podem ser alterados com aviso prévio, sem prejuízo do período já pago.</p>'

            . '<h2>3. Pagamento e renovação</h2>'
            . '<p>O pagamento é processado por gateways de pagamento (ex.: Stripe, Mercado Pago). A assinatura renova-se automaticamente no período escolhido (mensal ou anual) até cancelamento. O usuário é responsável por manter meio de pagamento válido. Cobranças não processadas podem resultar em suspensão do acesso aos benefícios PRO até regularização.</p>'

            . '<h2>4. Cancelamento e reembolso</h2>'
            . '<p>O usuário pode cancelar a assinatura a qualquer momento pelo painel (Planos e Assinatura). O cancelamento tem efeito ao final do período já pago; até lá, o acesso PRO permanece. Políticas de reembolso seguem a legislação aplicável e as regras do gateway de pagamento utilizado. Em caso de trial (período de teste), o cancelamento antes do fim do trial evita a primeira cobrança.</p>'

            . '<h2>5. Uso da IA e relatórios</h2>'
            . '<p>Recursos que utilizam IA (consultoria, conclusões em relatórios, categorização) são prestados “como estão”. A Vertex Solutions LTDA não se responsabiliza por decisões tomadas com base em análises ou textos gerados por IA. Os relatórios têm finalidade informativa e de apoio à gestão, não substituindo aconselhamento profissional específico (financeiro, tributário ou jurídico).</p>'

            . '<h2>6. Disposições gerais</h2>'
            . '<p>Os <a href="' . route('terms') . '" class="text-primary hover:underline">Termos de Uso</a> e a <a href="' . route('privacy') . '" class="text-primary hover:underline">Política de Privacidade</a> aplicam-se integralmente à assinatura. Em caso de conflito, estes Termos de Assinatura prevalecem nas questões relativas especificamente à assinatura e aos benefícios PRO.</p>'
            . '<p><em>Documento emitido por <strong>Vertex Solutions LTDA</strong>. Jurisdição: Brasil.</em></p>';
    }

    private function politicaCookiesContent(): string
    {
        $table = '<table class="w-full border border-slate-300 dark:border-slate-600 text-sm my-4" style="border-collapse: collapse;">'
            . '<thead><tr class="bg-slate-100 dark:bg-slate-700">'
            . '<th class="border border-slate-300 dark:border-slate-600 p-2 text-left">Tipo</th>'
            . '<th class="border border-slate-300 dark:border-slate-600 p-2 text-left">Finalidade</th>'
            . '<th class="border border-slate-300 dark:border-slate-600 p-2 text-left">Duração</th>'
            . '<th class="border border-slate-300 dark:border-slate-600 p-2 text-left">Pode desativar?</th>'
            . '</tr></thead><tbody>'
            . '<tr><td class="border border-slate-300 dark:border-slate-600 p-2">Essenciais</td><td class="border border-slate-300 dark:border-slate-600 p-2">Sessão de usuário autenticado, token CSRF, preferência de tema (claro/escuro), lembrete de login (“Lembrar-me”)</td><td class="border border-slate-300 dark:border-slate-600 p-2">Sessão ou persistente conforme necessidade</td><td class="border border-slate-300 dark:border-slate-600 p-2">Não (necessários ao funcionamento)</td></tr>'
            . '<tr><td class="border border-slate-300 dark:border-slate-600 p-2">Funcionais</td><td class="border border-slate-300 dark:border-slate-600 p-2">Lembrar preferências (ex.: idioma), registro de aceite ou rejeição do banner de cookies</td><td class="border border-slate-300 dark:border-slate-600 p-2">Até 90 dias</td><td class="border border-slate-300 dark:border-slate-600 p-2">Sim (via banner)</td></tr>'
            . '<tr><td class="border border-slate-300 dark:border-slate-600 p-2">Analíticos</td><td class="border border-slate-300 dark:border-slate-600 p-2">Métricas de uso agregadas e anônimas (desempenho, páginas visitadas)</td><td class="border border-slate-300 dark:border-slate-600 p-2">Conforme provedor</td><td class="border border-slate-300 dark:border-slate-600 p-2">Sim (quando ofertados)</td></tr>'
            . '</tbody></table>';

        return '<h1>Política de Cookies - Vertex Contas</h1>'
            . '<p>A <strong>Vertex Solutions LTDA</strong> utiliza cookies e tecnologias similares para garantir o funcionamento, a segurança e a melhor experiência do usuário na plataforma Vertex Contas. Esta política descreve de forma clara os tipos de cookies utilizados, suas finalidades, duração e como o usuário pode gerenciar ou revogar suas preferências, em conformidade com a Lei Geral de Proteção de Dados (LGPD – Lei nº 13.709/2018) e com as melhores práticas de privacidade e transparência.</p>'

            . '<h2>1. O que são cookies</h2>'
            . '<p>Cookies são pequenos arquivos de texto armazenados no seu dispositivo (computador, tablet ou celular) quando você visita nosso site. Eles permitem que a plataforma reconheça seu dispositivo e armazene informações sobre suas preferências ou ações (por exemplo, tema claro/escuro, consentimento de cookies, sessão de login). Tecnologias similares, como armazenamento local (localStorage) e sessão (sessionStorage), podem ser utilizadas para os mesmos fins, quando aplicável. Nesta política, “cookies” refere-se a cookies e a essas tecnologias correlatas.</p>'

            . '<h2>2. Tipos de cookies que utilizamos</h2>'
            . '<h3>2.1 Cookies essenciais</h3>'
            . '<p>Necessários para o funcionamento básico e seguro do site: sessão de usuário autenticado, proteção contra ataques CSRF (token), preferência de tema (claro/escuro) e lembrete de login (“Lembrar-me”). Não podem ser desativados sem prejuízo ao uso da plataforma. Sua base legal é a execução do contrato e o legítimo interesse em segurança.</p>'
            . '<h3>2.2 Cookies funcionais</h3>'
            . '<p>Permitem lembrar escolhas do usuário (ex.: idioma, aceite ou rejeição do banner de cookies) e melhorar a experiência. O registro do consentimento de cookies (aceite ou rejeição) é armazenado em nosso banco de dados para fins de auditoria e prova de conformidade com a LGPD, com validade de 90 (noventa) dias, podendo ser revogado a qualquer momento pelo usuário. A base legal para cookies funcionais que dependem de consentimento é o próprio consentimento do titular.</p>'
            . '<h3>2.3 Cookies analíticos</h3>'
            . '<p>Quando utilizados, servem para entender como os visitantes usam o site (métricas de uso, desempenho, páginas mais acessadas), de forma agregada e anônima, em conformidade com a LGPD. Não são utilizados para identificar individualmente o usuário sem base legal adequada. Caso a plataforma passe a utilizar cookies analíticos de terceiros, o usuário será informado e poderá aceitar ou rejeitar no banner de cookies.</p>'

            . '<h2>3. Resumo: tipo, finalidade, duração e possibilidade de desativação</h2>'
            . $table

            . '<h2>4. Duração e armazenamento</h2>'
            . '<p>O registro do seu consentimento em relação aos cookies (aceite ou rejeição) é armazenado em nosso banco de dados para fins de auditoria e conformidade à LGPD, com validade de 90 (noventa) dias. Após esse período, podemos solicitar novamente seu consentimento por meio do banner. Cookies de sessão são removidos ao encerrar o navegador; cookies persistentes (como o de preferência de tema ou de consentimento) podem ter duração de até 90 dias, salvo os essenciais que exijam prazo maior para segurança ou funcionamento.</p>'

            . '<h2>5. Banner de consentimento e como gerenciar ou revogar</h2>'
            . '<p>Na primeira visita ao site (ou quando não houver registro prévio de consentimento), exibimos um <strong>banner de consentimento</strong> na parte inferior da tela, permitindo que você <strong>Aceitar</strong> ou <strong>Rejeitar</strong> cookies não essenciais. A escolha é registrada e não exibiremos o banner novamente até o fim da validade (90 dias) ou até que você limpe os cookies/localStorage do domínio. Para revogar ou alterar suas preferências: (a) limpe os cookies e o armazenamento local do seu navegador para o domínio do Vertex Contas; ou (b) utilize as opções de privacidade do seu navegador para gerenciar ou bloquear cookies. A revogação não afeta a legalidade do tratamento realizado até então. Em caso de dúvidas, entre em contato pelos canais indicados na <a href="' . route('privacy') . '" class="text-primary hover:underline">Política de Privacidade</a> (incluindo o e-mail de privacidade, quando divulgado).</p>'

            . '<h2>6. Direitos do titular (LGPD)</h2>'
            . '<p>Conforme a LGPD, você tem direito à confirmação da existência de tratamento, acesso, correção, anonimização, portabilidade, eliminação e revogação do consentimento. Para exercer esses direitos em relação aos dados tratados via cookies (incluindo o registro de consentimento), entre em contato conosco através dos canais indicados na Política de Privacidade. Você pode ainda apresentar reclamação à Autoridade Nacional de Proteção de Dados (ANPD).</p>'

            . '<h2>7. Alterações desta Política</h2>'
            . '<p>Alterações nesta Política de Cookies podem ser publicadas na plataforma. Em caso de mudança relevante (por exemplo, inclusão de novos tipos de cookies), comunicaremos por aviso no site ou no banner, quando aplicável, e atualizaremos a data de “Última atualização” no rodapé deste documento.</p>'

            . '<p><em>Documento emitido por <strong>Vertex Solutions LTDA</strong>. Jurisdição: Brasil. Em conformidade com a LGPD (Lei nº 13.709/2018). Última atualização aplicável ao conteúdo desta política.</em></p>';
    }
}
