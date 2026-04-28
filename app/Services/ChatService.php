<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Models\Message;

class ChatService
{
    public function sendMessage($data)
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'sender_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        // 🔥 هنا كتدير broadcast
        broadcast(new MessageSent($message))->toOthers();

        return $message;
    }
}