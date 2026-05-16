<?php
/**
 * Message Controller
 */

namespace Controllers;

use Models\Message;
use Helpers\response;
use Helpers\sanitize;

class MessageController
{
    public function listMessages(): never
    {
        $roomId = (int)($_GET['id'] ?? 0);
        $messages = Message::getByRoomId($roomId);
        response(['success' => true, 'data' => $messages]);
    }

    public function store(): never
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $roomId = (int)($_GET['id'] ?? 0);

        if (empty($data['content'])) {
            response(['error' => 'Message content is required'], 400);
        }

        $messageId = Message::create([
            'room_id' => $roomId,
            'sender_id' => $_SESSION['user_id'] ?? null,
            'sender_name' => sanitize($data['sender_name'] ?? 'Guest'),
            'content' => sanitize($data['content']),
            'is_private' => $data['is_private'] ?? 0,
            'recipient_id' => $data['recipient_id'] ?? null,
        ]);

        if (!$messageId) {
            response(['error' => 'Failed to store message'], 500);
        }

        response(['success' => true, 'message_id' => $messageId]);
    }
}
