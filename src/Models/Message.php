<?php
/**
 * Message Model
 */

namespace Models;

use Core\Database;

class Message
{
    public static function create(array $data): int|false
    {
        try {
            Database::execute(
                'INSERT INTO messages (room_id, sender_id, sender_name, content, is_private, recipient_id) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $data['room_id'],
                    $data['sender_id'] ?? null,
                    $data['sender_name'],
                    $data['content'],
                    $data['is_private'] ?? 0,
                    $data['recipient_id'] ?? null,
                ]
            );
            return (int)Database::lastInsertId();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getByRoomId(int $roomId, int $limit = 100): array
    {
        return Database::query(
            'SELECT * FROM messages WHERE room_id = ? ORDER BY created_at DESC LIMIT ?',
            [$roomId, $limit]
        );
    }
}
