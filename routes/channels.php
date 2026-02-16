<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\VertexChat\Models\Conversation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('conversation.{id}', function ($user, $id) {
    $conversation = Conversation::find($id);

    if (! $conversation) {
        return false;
    }

    return (int) $conversation->user_id === (int) $user->id
        || (int) $conversation->assigned_agent_id === (int) $user->id
        || $user->hasRole('admin')
        || $user->hasRole('support');
});
