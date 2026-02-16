<?php

declare(strict_types=1);

namespace Modules\VertexChat\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $table = 'vertex_chat_messages';

    /** Atualiza conversation.updated_at ao criar/atualizar mensagem para o painel do suporte ordenar corretamente. */
    protected $touches = ['conversation'];

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'type',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function isSystemNotice(): bool
    {
        return $this->type === 'system_notice';
    }
}
