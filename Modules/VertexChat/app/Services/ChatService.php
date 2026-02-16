<?php

declare(strict_types=1);

namespace Modules\VertexChat\Services;

use App\Models\User;
use Modules\VertexChat\Events\MessageSent;
use Modules\VertexChat\Models\Conversation;
use Modules\VertexChat\Models\Message;

class ChatService
{
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
}
