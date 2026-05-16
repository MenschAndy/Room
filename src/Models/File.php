<?php
/**
 * File Model
 */

namespace Models;

use Core\Database;

class File
{
    public static function create(array $data): int|false
    {
        try {
            Database::execute(
                'INSERT INTO files (room_id, uploader_id, uploader_name, original_filename, stored_filename, file_size, mime_type, file_path, expires_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $data['room_id'],
                    $data['uploader_id'] ?? null,
                    $data['uploader_name'],
                    $data['original_filename'],
                    $data['stored_filename'],
                    $data['file_size'],
                    $data['mime_type'],
                    $data['file_path'],
                    $data['expires_at'] ?? null,
                ]
            );
            return (int)Database::lastInsertId();
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function getByRoomId(int $roomId): array
    {
        return Database::query(
            'SELECT * FROM files WHERE room_id = ? AND expires_at > NOW() ORDER BY uploaded_at DESC',
            [$roomId]
        );
    }

    public static function getById(int $id): array|bool
    {
        return Database::queryOne(
            'SELECT * FROM files WHERE id = ?',
            [$id]
        );
    }
}
