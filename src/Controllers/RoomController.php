<?php
/**
 * Room Controller
 */

namespace Controllers;

use Models\Room;
use Helpers\response;
use Helpers\sanitize;

class RoomController
{
    public function listPublic(): never
    {
        $rooms = Room::getPublicRooms();
        response(['success' => true, 'data' => $rooms]);
    }

    public function create(): never
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['name'])) {
            response(['error' => 'Room name is required'], 400);
        }

        $roomId = Room::create([
            'creator_id' => $_SESSION['user_id'] ?? 1,
            'name' => sanitize($data['name']),
            'description' => sanitize($data['description'] ?? ''),
            'is_public' => $data['is_public'] ?? 1,
        ]);

        if (!$roomId) {
            response(['error' => 'Failed to create room'], 500);
        }

        response(['success' => true, 'room_id' => $roomId, 'message' => 'Room created successfully']);
    }

    public function show(): never
    {
        $roomId = (int)($_GET['id'] ?? 0);
        $room = Room::getById($roomId);
        
        if (!$room) {
            response(['error' => 'Room not found'], 404);
        }

        response(['success' => true, 'data' => $room]);
    }

    public function delete(): never
    {
        $roomId = (int)($_GET['id'] ?? 0);
        
        if (!$roomId) {
            response(['error' => 'Invalid room ID'], 400);
        }

        if (Room::delete($roomId)) {
            response(['success' => true, 'message' => 'Room deleted successfully']);
        }
        
        response(['error' => 'Failed to delete room'], 500);
    }
}
