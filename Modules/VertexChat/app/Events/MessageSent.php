<?php

declare(strict_types=1);

namespace Modules\VertexChat\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\VertexChat\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.'.$this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $this->message->load('sender');
        $sender = $this->message->sender;
        $avatarUrl = $sender ? $sender->photo_url : null;
        $senderInitial = $sender
            ? strtoupper(mb_substr($sender->first_name ?? '?', 0, 1))
            : '?';

        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'sender_id' => $this->message->sender_id,
                'sender_name' => $sender ? $sender->first_name.' '.$sender->last_name : '',
                'sender_photo' => $avatarUrl,
                'sender_initial' => $senderInitial,
                'body' => $this->message->body,
                'type' => $this->message->type,
                'created_at' => $this->message->created_at?->toIso8601String(),
            ],
        ];
    }
}
