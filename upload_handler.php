<?php
require_once 'includes/functions.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error');
    }
    
    $file = $_FILES['file'];
    $uploadType = $_POST['type'] ?? 'general';
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
    }
    
    // Validate file size (5MB max)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        throw new Exception('File too large. Maximum size is 5MB.');
    }
    
    // Create uploads directory if it doesn't exist
    $uploadDir = __DIR__ . '/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate filename based on type
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    if ($uploadType === 'logo') {
        $filename = 'logo.' . $extension;
        $targetPath = __DIR__ . '/' . $filename; // Save directly to root
    } else {
        $filename = $uploadType . '_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        if ($uploadType === 'logo') {
            $relativePath = $filename; // Logo is in root directory
        } else {
            $relativePath = 'uploads/' . $filename;
        }
        
        // If it's a profile image, update the database
        if ($uploadType === 'profile') {
            $db = getDB();
            $stmt = $db->prepare("UPDATE personal_info SET profile_image = :image WHERE id = 1");
            $stmt->execute(['image' => $relativePath]);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'File uploaded successfully',
            'filename' => $filename,
            'url' => $relativePath
        ]);
    } else {
        throw new Exception('Failed to move uploaded file');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
