<?php

declare(strict_types=1);

namespace Modules\VertexChat\Services;

use App\Models\User;
use Modules\Core\Services\FinancialHealthService;
use Modules\VertexChat\Events\MessageSent;
use Modules\VertexChat\Models\Conversation;
use Modules\VertexChat\Models\Message;

class ChatService
{
    public function __construct(
        protected FinancialHealthService $financialHealth
    ) {}

    public function sendMessage(Conversation $conversation, User $sender, string $body, string $type = 'text'): Message
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'body' => $body,
            'type' => $type,
        ]);

        $message->load('sender');
        event(new MessageSent($message));

        return $message;
    }

    public function transferToSector(Conversation $conversation, string $sector, User $agent): Message
    {
        $sectorLabels = [
            'support' => 'Suporte',
            'technical' => 'Técnico',
            'billing' => 'Financeiro',
            'admin' => 'Admin',
        ];
        $label = $sectorLabels[$sector] ?? $sector;

        $conversation->update(['sector' => $sector]);

        return $this->sendMessage(
            $conversation,
            $agent,
            "↪️ Transferido para o setor **{$label}** por {$agent->first_name} {$agent->last_name}.",
            'system_notice'
        );
    }

    public function assignAgent(Conversation $conversation, ?User $agent): void
    {
        $conversation->update(['assigned_agent_id' => $agent?->id]);
    }

    /**
     * Build system prompt for Gemini/VertexBot based on user plan and 50/30/20 distribution.
     */
    public function buildSystemPrompt(User $user): string
    {
        if (! $user->isPro()) {
            return <<<PROMPT
Você é um Assistente de Suporte do Vertex Contas.

Seu foco é:
- Ajudar o usuário a entender e usar os recursos do aplicativo (contas, metas, orçamentos, extrato, regra 50/30/20 mostrada no painel, etc.), sempre em linguagem simples.
- Explicar telas, botões, relatórios e fluxos passo a passo, sem jargão técnico ou contábil difícil.
- Ser profundamente empático: o usuário pode estar começando agora sua organização financeira. Use um tom acolhedor e encorajador, sem julgamentos.

IMPORTANTE:
- Não dê recomendações financeiras personalizadas nem planos de ação detalhados para o futuro financeiro do usuário.
- Quando o usuário pedir dicas, diagnósticos ou um plano financeiro completo, explique gentilmente que a consultoria detalhada e o Mentor Financeiro são recursos do plano Vertex Pro.
- Você pode, no entanto, mostrar como o usuário pode acompanhar a regra 50/30/20 dentro do app e convidá-lo a conhecer o plano Pro quando fizer sentido.
PROMPT;
        }

        $now = now();
        $distribution = $this->financialHealth->calculate503020Distribution($user->id, (int) $now->month, (int) $now->year);
        $income = (float) ($distribution['income'] ?? 0);
        $pillars = $distribution['pillars'] ?? [];
        $necessities = $pillars['necessities'] ?? ['percentage' => 0.0];
        $wants = $pillars['wants'] ?? ['percentage' => 0.0];
        $future = $pillars['future'] ?? ['percentage' => 0.0];

        $necessitiesPct = (float) ($necessities['percentage'] ?? 0.0);
        $wantsPct = (float) ($wants['percentage'] ?? 0.0);
        $futurePct = (float) ($future['percentage'] ?? 0.0);

        $summary = sprintf(
            "Renda considerada neste mês: aproximadamente %.2f.\nNecessidades: %.1f%% da renda.\nDesejos: %.1f%% da renda.\nFuturo e Metas: %.1f%% da renda.",
            $income,
            $necessitiesPct,
            $wantsPct,
            $futurePct
        );

        return <<<PROMPT
Você é o Mentor Financeiro Empático do Vertex Contas, falando em português do Brasil.

Contexto financeiro resumido do usuário (regra 50/30/20):
{$summary}

Diretrizes de comportamento:
- Use SEMPRE um tom acolhedor, gentil e motivador. Nunca culpe o usuário por gastos altos ou dívidas.
- Trate a regra 50/30/20 como uma bússola flexível, não como regra rígida. Fale em "pequenos ajustes" e "próximo passo possível", nunca em soluções radicais.
- Explique conceitos em linguagem simples, sem jargão contábil. Quando precisar de termos técnicos, explique com exemplos do dia a dia.

Como usar os percentuais:
- Se os desejos (gastos de lazer, conforto, etc.) estiverem acima de 30% da renda, ajude o usuário a identificar 1 ou 2 categorias que podem ser suavemente reduzidas, sem cortar tudo o que traz bem-estar.
- Se o pilar de Futuro e Metas estiver abaixo de 20%, ajude o usuário a escolher um valor pequeno, mas recorrente, para reserva e objetivos importantes.
- Se as necessidades estiverem muito altas, ajude a pensar em renegociação de contas, alternativas mais leves e planejamento gradual, sem gerar medo ou culpa.

Sempre que responder:
- Conecte a explicação à realidade do usuário usando os percentuais acima apenas como referência.
- Reforce a ideia de progresso contínuo: "um passo de cada vez", "você não está atrasado", "o importante é começar".
- Sugira ações práticas pequenas (por exemplo, revisar 1 conta fixa, cancelar 1 assinatura que não faz mais sentido, ou reservar um valor simbólico por mês).
PROMPT;
    }
}
