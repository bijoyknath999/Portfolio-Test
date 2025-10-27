<?php
require_once 'includes/functions.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = getDB();
    echo "✅ Database connection successful<br><br>";
    
    // Check if admin_users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'admin_users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ admin_users table exists<br>";
        
        // Check if admin user exists
        $stmt = $db->query("SELECT * FROM admin_users WHERE username = 'admin'");
        $user = $stmt->fetch();
        
        if ($user) {
            echo "✅ Admin user exists<br>";
            echo "Username: " . $user['username'] . "<br>";
            echo "Email: " . $user['email'] . "<br>";
            echo "Created: " . $user['created_at'] . "<br><br>";
            
            // Test password verification
            if (password_verify('admin123', $user['password'])) {
                echo "✅ Password verification works<br>";
            } else {
                echo "❌ Password verification failed<br>";
                echo "Creating new admin user...<br>";
                
                // Create new admin user
                $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE admin_users SET password = ? WHERE username = 'admin'");
                if ($stmt->execute([$hashedPassword])) {
                    echo "✅ Admin password updated successfully<br>";
                } else {
                    echo "❌ Failed to update password<br>";
                }
            }
        } else {
            echo "❌ Admin user does not exist<br>";
            echo "Creating admin user...<br>";
            
            // Create admin user
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
            if ($stmt->execute(['admin', $hashedPassword, 'admin@portfolio.com'])) {
                echo "✅ Admin user created successfully<br>";
            } else {
                echo "❌ Failed to create admin user<br>";
            }
        }
    } else {
        echo "❌ admin_users table does not exist<br>";
        echo "Please run setup.php or import the SQL file manually<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
    echo "<br><strong>Possible solutions:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP<br>";
    echo "2. Check database configuration in config/database.php<br>";
    echo "3. Run setup.php to create database<br>";
}

echo "<br><hr>";
echo "<strong>Test Login:</strong><br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";
echo "<a href='admin/login.php'>Go to Login Page</a>";
?>
