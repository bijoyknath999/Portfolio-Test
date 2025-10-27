<?php
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $themeId = intval($_POST['theme_id'] ?? 0);
    
    if ($themeId <= 0) {
        throw new Exception('Invalid theme ID');
    }
    
    if (setActiveTheme($themeId)) {
        $theme = getActiveTheme();
        echo json_encode([
            'success' => true,
            'message' => 'Theme changed successfully',
            'theme' => $theme
        ]);
    } else {
        throw new Exception('Failed to change theme');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
