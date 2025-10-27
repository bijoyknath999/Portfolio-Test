<?php
require_once 'includes/functions.php';

echo "<h2>Cleaning up HTML Entity Encoding Issues</h2>";

try {
    $db = getDB();
    
    // Clean up personal_info table
    echo "<h3>Cleaning Personal Info...</h3>";
    $stmt = $db->query("SELECT * FROM personal_info");
    $personalInfo = $stmt->fetch();
    
    if ($personalInfo) {
        $cleanData = [];
        foreach (['name', 'title', 'description', 'email', 'phone', 'location', 'availability_text'] as $field) {
            if (isset($personalInfo[$field])) {
                $original = $personalInfo[$field];
                $cleaned = html_entity_decode($original, ENT_QUOTES, 'UTF-8');
                
                // If it was encoded, clean it
                if ($original !== $cleaned) {
                    $cleanData[$field] = $cleaned;
                    echo "Cleaned $field: " . htmlspecialchars($original) . " → " . htmlspecialchars($cleaned) . "<br>";
                } else {
                    $cleanData[$field] = $original;
                }
            }
        }
        
        // Update with cleaned data
        if (!empty($cleanData)) {
            updatePersonalInfo($cleanData);
            echo "✅ Personal info cleaned<br>";
        }
    }
    
    // Clean up site_settings table
    echo "<h3>Cleaning Site Settings...</h3>";
    $stmt = $db->query("SELECT * FROM site_settings");
    $settings = $stmt->fetchAll();
    
    foreach ($settings as $setting) {
        $original = $setting['setting_value'];
        $cleaned = html_entity_decode($original, ENT_QUOTES, 'UTF-8');
        
        if ($original !== $cleaned) {
            updateSiteSetting($setting['setting_key'], $cleaned);
            echo "Cleaned " . $setting['setting_key'] . ": " . htmlspecialchars($original) . " → " . htmlspecialchars($cleaned) . "<br>";
        }
    }
    
    // Clean up SEO settings
    echo "<h3>Cleaning SEO Settings...</h3>";
    $stmt = $db->query("SELECT * FROM seo_settings");
    $seoSettings = $stmt->fetchAll();
    
    foreach ($seoSettings as $seo) {
        $cleanData = [];
        $needsUpdate = false;
        
        foreach (['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description'] as $field) {
            if (isset($seo[$field])) {
                $original = $seo[$field];
                $cleaned = html_entity_decode($original, ENT_QUOTES, 'UTF-8');
                
                if ($original !== $cleaned) {
                    $cleanData[$field] = $cleaned;
                    $needsUpdate = true;
                    echo "Cleaned SEO $field: " . htmlspecialchars($original) . " → " . htmlspecialchars($cleaned) . "<br>";
                } else {
                    $cleanData[$field] = $original;
                }
            }
        }
        
        if ($needsUpdate) {
            updateSEOSettings($seo['page_type'], $cleanData);
        }
    }
    
    echo "<br><strong>✅ Cleanup completed!</strong><br>";
    echo "<p>The &amp;amp;amp; encoding issues should now be resolved.</p>";
    echo "<p><a href='index.php'>View Portfolio</a> | <a href='admin/login.php'>Admin Panel</a></p>";
    
} catch (Exception $e) {
    echo "❌ Error during cleanup: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encoding Cleanup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h2, h3 { color: #333; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <!-- PHP output appears above -->
</body>
</html>
