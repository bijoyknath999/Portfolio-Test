<?php
/**
 * Portfolio Setup Script
 * Run this file once to set up the database and initial data
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'portfolio_db';

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '$dbname' created successfully<br>";
    
    // Connect to the created database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL file
    $sqlFile = __DIR__ . '/database/portfolio.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }
        
        echo "✓ Database tables created successfully<br>";
        echo "✓ Default data inserted successfully<br>";
    } else {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    // Create uploads directory
    $uploadsDir = __DIR__ . '/uploads';
    if (!file_exists($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
        echo "✓ Uploads directory created<br>";
    }
    
    // Create admin directory if it doesn't exist
    $adminDir = __DIR__ . '/admin';
    if (!file_exists($adminDir)) {
        mkdir($adminDir, 0777, true);
        echo "✓ Admin directory created<br>";
    }
    
    echo "<br><strong>Setup completed successfully!</strong><br><br>";
    echo "<strong>Default Admin Credentials:</strong><br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";
    echo "<strong>Next Steps:</strong><br>";
    echo "1. <a href='admin/login.php'>Login to Admin Panel</a><br>";
    echo "2. <a href='index.php'>View Your Portfolio</a><br>";
    echo "3. Change the default admin password<br>";
    echo "4. Update your personal information<br>";
    echo "5. Add your projects and experience<br><br>";
    echo "<em>Note: Delete this setup.php file after setup is complete for security.</em>";
    
} catch (Exception $e) {
    echo "❌ Setup failed: " . $e->getMessage() . "<br>";
    echo "Please check your database configuration and try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .success {
            color: #28a745;
        }
        .error {
            color: #dc3545;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Portfolio Setup</h1>
        <p>This script will set up your dynamic portfolio database and create the necessary files.</p>
        <hr>
        <div id="setup-output">
            <!-- PHP output will appear here -->
        </div>
    </div>
</body>
</html>
