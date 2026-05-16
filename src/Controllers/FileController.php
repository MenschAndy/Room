<?php
/**
 * File Controller
 */

namespace Controllers;

use Models\File;
use Helpers\response;
use Helpers\sanitize;

class FileController
{
    public function upload(): never
    {
        $roomId = (int)($_GET['id'] ?? 0);

        if (!isset($_FILES['file'])) {
            response(['error' => 'No file uploaded'], 400);
        }

        $file = $_FILES['file'];
        $config = require BASE_PATH . '/config/config.php';
        $uploadDir = $config['upload']['upload_dir'];
        $allowedExt = $config['upload']['allowed_extensions'];

        // Validate file
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            response(['error' => 'File type not allowed'], 400);
        }

        if ($file['size'] > $config['upload']['max_size']) {
            response(['error' => 'File too large'], 400);
        }

        // Store file
        $storedName = uniqid('file_') . '.' . $ext;
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $storedName)) {
            response(['error' => 'Failed to upload file'], 500);
        }

        // Save to database
        $fileId = File::create([
            'room_id' => $roomId,
            'uploader_id' => $_SESSION['user_id'] ?? null,
            'uploader_name' => sanitize($_POST['uploader_name'] ?? 'Guest'),
            'original_filename' => sanitize($file['name']),
            'stored_filename' => $storedName,
            'file_size' => $file['size'],
            'mime_type' => $file['type'],
            'file_path' => '/uploads/' . $storedName,
        ]);

        if (!$fileId) {
            unlink($uploadDir . $storedName);
            response(['error' => 'Failed to save file info'], 500);
        }

        response(['success' => true, 'file_id' => $fileId, 'path' => '/uploads/' . $storedName]);
    }

    public function listFiles(): never
    {
        $roomId = (int)($_GET['id'] ?? 0);
        $files = File::getByRoomId($roomId);
        response(['success' => true, 'data' => $files]);
    }
}
