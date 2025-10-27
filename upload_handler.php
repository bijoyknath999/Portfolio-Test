<?php
require_once 'includes/functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login first.']);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Check if file was uploaded
    if (!isset($_FILES['file'])) {
        throw new Exception('No file provided in request');
    }
    
    // Check for upload errors
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        $errorCode = $_FILES['file']['error'];
        $errorMsg = $errorMessages[$errorCode] ?? 'Unknown upload error';
        throw new Exception($errorMsg . ' (Error code: ' . $errorCode . ')');
    }
    
    $file = $_FILES['file'];
    $uploadType = $_POST['type'] ?? 'general';
    
    // Validate file type based on upload type
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($uploadType === 'resume') {
        // For resumes, only allow PDF
        $allowedTypes = ['application/pdf'];
        $allowedExtensions = ['pdf'];
        $maxSize = 10 * 1024 * 1024; // 10MB for PDFs
        
        if (!in_array($file['type'], $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only PDF files are allowed for resumes. Uploaded: ' . $file['type']);
        }
    } elseif ($uploadType === 'favicon') {
        // For favicons, only allow PNG and ICO
        $allowedTypes = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon'];
        $allowedExtensions = ['png', 'ico'];
        $maxSize = 1 * 1024 * 1024; // 1MB for favicons
        
        if (!in_array($file['type'], $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only PNG and ICO files are allowed for favicons. Uploaded: ' . $file['type']);
        }
    } else {
        // For images
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $maxSize = 5 * 1024 * 1024; // 5MB for images
        
        if (!in_array($file['type'], $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only JPEG, PNG, GIF, WebP, and SVG images are allowed. Uploaded: ' . $file['type']);
        }
    }
    
    // Validate file size
    if ($file['size'] > $maxSize) {
        $maxSizeMB = $maxSize / (1024 * 1024);
        throw new Exception('File too large. Maximum size is ' . $maxSizeMB . 'MB.');
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
        
        // Update database based on upload type
        if ($uploadType === 'profile') {
            $db = getDB();
            $stmt = $db->prepare("UPDATE personal_info SET profile_image = :image WHERE id = 1");
            $stmt->execute(['image' => $relativePath]);
        } elseif ($uploadType === 'resume') {
            $db = getDB();
            $stmt = $db->prepare("UPDATE personal_info SET resume_file = :resume WHERE id = 1");
            $stmt->execute(['resume' => $relativePath]);
        } elseif ($uploadType === 'favicon') {
            // Save favicon to site settings and update favicon type
            updateSiteSetting('uploaded_favicon', $filename);
            updateSiteSetting('favicon_type', 'upload');
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
