<?php
require_once 'includes/functions.php';

echo "<h2>Initializing Default Settings</h2>";

try {
    $db = getDB();
    
    // Default settings to create
    $defaultSettings = [
        'site_name' => 'Bijoy Kumar Nath Portfolio',
        'site_tagline' => 'Full-Stack & Mobile App Developer',
        'google_analytics_id' => '',
        'contact_form_email' => 'bijoyknath999@gmail.com',
        'maintenance_mode' => 'false',
        'show_theme_selector' => 'true'
    ];
    
    echo "<h3>Creating Default Settings...</h3>";
    
    foreach ($defaultSettings as $key => $value) {
        // Check if setting already exists
        $existing = getSiteSetting($key);
        
        if ($existing === null) {
            // Setting doesn't exist, create it
            if (updateSiteSetting($key, $value)) {
                echo "✅ Created setting: <strong>$key</strong> = " . htmlspecialchars($value) . "<br>";
            } else {
                echo "❌ Failed to create setting: <strong>$key</strong><br>";
            }
        } else {
            echo "ℹ️ Setting already exists: <strong>$key</strong> = " . htmlspecialchars($existing) . "<br>";
        }
    }
    
    // Initialize default personal info if it doesn't exist
    echo "<h3>Checking Personal Info...</h3>";
    $personalInfo = getPersonalInfo();
    
    if (!$personalInfo) {
        echo "Creating default personal info...<br>";
        $defaultPersonalInfo = [
            'name' => 'Bijoy Kumar Nath',
            'title' => 'Full-Stack & Mobile App Developer',
            'description' => 'Experienced Full Stack App Developer with 5+ years of expertise in Android, iOS, and Web development. Specializing in Laravel, Firebase, Flutter, and Linux. Based in Dhaka, Bangladesh.',
            'email' => 'bijoyknath999@gmail.com',
            'phone' => '+8801831980819',
            'location' => 'Dhaka, Bangladesh',
            'availability_text' => 'Available for Remote work worldwide & Onsite opportunities in Bangladesh.'
        ];
        
        $db = getDB();
        $sql = "INSERT INTO personal_info (name, title, description, email, phone, location, availability_text) 
                VALUES (:name, :title, :description, :email, :phone, :location, :availability_text)";
        
        $stmt = $db->prepare($sql);
        if ($stmt->execute($defaultPersonalInfo)) {
            echo "✅ Default personal info created<br>";
        } else {
            echo "❌ Failed to create personal info<br>";
        }
    } else {
        echo "ℹ️ Personal info already exists<br>";
    }
    
    // Initialize default SEO settings
    echo "<h3>Checking SEO Settings...</h3>";
    $seoSettings = getSEOSettings('home');
    
    if (!$seoSettings) {
        echo "Creating default SEO settings...<br>";
        $defaultSEO = [
            'meta_title' => 'Bijoy Kumar Nath - Full-Stack & Mobile App Developer | Portfolio',
            'meta_description' => 'Experienced Full Stack App Developer with 5+ years expertise in Android, iOS, Web development. Specializing in Laravel, Firebase, Flutter, Linux. Available for remote work worldwide.',
            'meta_keywords' => 'full-stack developer, mobile app developer, flutter developer, laravel developer, android developer, web developer, firebase, bangladesh developer',
            'og_title' => 'Bijoy Kumar Nath - Portfolio',
            'og_description' => 'Experienced Full Stack App Developer with 5+ years expertise in Android, iOS, Web development.',
            'og_image' => 'bijoy.png'
        ];
        
        if (updateSEOSettings('home', $defaultSEO)) {
            echo "✅ Default SEO settings created<br>";
        } else {
            echo "❌ Failed to create SEO settings<br>";
        }
    } else {
        echo "ℹ️ SEO settings already exist<br>";
    }
    
    echo "<br><strong>✅ Initialization completed!</strong><br>";
    echo "<p>Settings should now work properly in the admin panel.</p>";
    echo "<p><a href='admin/settings.php'>Go to Settings</a> | <a href='admin/dashboard.php'>Admin Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "❌ Error during initialization: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initialize Settings</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h2, h3 { color: #333; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .info { color: #17a2b8; }
    </style>
</head>
<body>
    <!-- PHP output appears above -->
</body>
</html>
