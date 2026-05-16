<?php
/**
 * Room Model
 */

namespace Models;

use Core\Database;

class Room
{
    public static function create(array $data): int|false
    {
        try {
            Database::execute(
                'INSERT INTO rooms (creator_id, name, description, is_public, access_key, expires_at) VALUES (?, ?, ?, ?, ?, ?)',
                [
                    $data['creator_id'],
                    $data['name'],
                    $data['description'] ?? null,
                    $data['is_public'] ?? 1,
                    \Helpers\generateToken(16),
                    $data['expires_at'] ?? date('Y-m-d H:i:s', time() + 3600),
                ]
            );
            return (int)Database::lastInsertId();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getPublicRooms(): array
    {
        return Database::query(
            'SELECT r.*, u.username, COUNT(DISTINCT rm.id) as online_count, COUNT(DISTINCT f.id) as file_count '
            . 'FROM rooms r '
            . 'LEFT JOIN users u ON r.creator_id = u.id '
            . 'LEFT JOIN room_members rm ON r.id = rm.room_id AND rm.last_seen > DATE_SUB(NOW(), INTERVAL 5 MINUTE) '
            . 'LEFT JOIN files f ON r.id = f.room_id '
            . 'WHERE r.is_public = 1 AND r.status = "active" AND r.expires_at > NOW() '
            . 'GROUP BY r.id '
            . 'ORDER BY r.created_at DESC '
            . 'LIMIT 50'
        );
    }

    public static function getById(int $id): array|bool
    {
        return Database::queryOne(
            'SELECT r.*, u.username FROM rooms r LEFT JOIN users u ON r.creator_id = u.id WHERE r.id = ?',
            [$id]
        );
    }

    public static function getByAccessKey(string $key): array|bool
    {
        return Database::queryOne(
            'SELECT r.*, u.username FROM rooms r LEFT JOIN users u ON r.creator_id = u.id WHERE r.access_key = ? AND r.status = "active"',
            [$key]
        );
    }

    public static function delete(int $id): bool
    {
        return (bool)Database::execute(
            'UPDATE rooms SET status = "deleted" WHERE id = ?',
            [$id]
        );
    }

    public static function getOnlineMembers(int $roomId): array
    {
        return Database::query(
            'SELECT rm.guest_name, u.username, rm.joined_at FROM room_members rm '
            . 'LEFT JOIN users u ON rm.user_id = u.id '
            . 'WHERE rm.room_id = ? AND rm.last_seen > DATE_SUB(NOW(), INTERVAL 5 MINUTE) '
            . 'ORDER BY rm.joined_at DESC',
            [$roomId]
        );
    }
}
