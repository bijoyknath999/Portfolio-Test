<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$seoSettings = getSEOSettings('home');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'meta_title' => sanitizeInput($_POST['meta_title'] ?? ''),
        'meta_description' => sanitizeInput($_POST['meta_description'] ?? ''),
        'meta_keywords' => sanitizeInput($_POST['meta_keywords'] ?? ''),
        'og_title' => sanitizeInput($_POST['og_title'] ?? ''),
        'og_description' => sanitizeInput($_POST['og_description'] ?? ''),
        'og_image' => sanitizeInput($_POST['og_image'] ?? '')
    ];
    
    if (updateSEOSettings('home', $data)) {
        $message = '<div class="notification success"><i class="fas fa-check"></i> SEO settings updated successfully!</div>';
        $seoSettings = getSEOSettings('home'); // Refresh data
    } else {
        $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to update SEO settings.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Settings - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-search"></i> SEO Settings</h1>
            </div>

            <?php echo $message; ?>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Homepage SEO Settings</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" id="meta_title" name="meta_title" value="<?php echo htmlspecialchars($seoSettings['meta_title'] ?? ''); ?>" maxlength="60">
                            <small>Recommended: 50-60 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"><?php echo htmlspecialchars($seoSettings['meta_description'] ?? ''); ?></textarea>
                            <small>Recommended: 150-160 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" id="meta_keywords" name="meta_keywords" value="<?php echo htmlspecialchars($seoSettings['meta_keywords'] ?? ''); ?>">
                            <small>Comma-separated keywords</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="og_title">Open Graph Title</label>
                            <input type="text" id="og_title" name="og_title" value="<?php echo htmlspecialchars($seoSettings['og_title'] ?? ''); ?>">
                            <small>For social media sharing</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="og_description">Open Graph Description</label>
                            <textarea id="og_description" name="og_description" rows="2"><?php echo htmlspecialchars($seoSettings['og_description'] ?? ''); ?></textarea>
                            <small>For social media sharing</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="og_image">Open Graph Image URL</label>
                            <input type="url" id="og_image" name="og_image" value="<?php echo htmlspecialchars($seoSettings['og_image'] ?? ''); ?>">
                            <small>Image for social media sharing (1200x630px recommended)</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update SEO Settings
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
