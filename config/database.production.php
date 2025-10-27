<?php
/**
 * Production Database Configuration
 * 
 * INSTRUCTIONS FOR CPANEL SETUP:
 * 1. Create a MySQL database in cPanel
 * 2. Create a database user in cPanel
 * 3. Assign the user to the database with ALL PRIVILEGES
 * 4. Update the values below with your cPanel database details
 * 5. Rename this file to "database.php" (backup the old one first)
 */

class Database {
    // cPanel Database Settings
    // Format: username_dbname (e.g., if your cPanel username is 'mysite', it might be 'mysite_portfolio')
    private $host = 'localhost';  // Usually 'localhost' on cPanel
    private $db_name = 'YOUR_CPANEL_USERNAME_portfolio_db';  // Update this
    private $username = 'YOUR_CPANEL_USERNAME_dbuser';  // Update this
    private $password = 'YOUR_STRONG_DATABASE_PASSWORD';  // Update this
    private $charset = 'utf8mb4';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,  // Enable persistent connections for better performance
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            // In production, don't show detailed error messages
            error_log("Connection error: " . $exception->getMessage());
            
            // Show a generic error to users
            die("Database connection failed. Please contact the administrator.");
        }
        
        return $this->conn;
    }
    
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            return $conn !== null;
        } catch(Exception $e) {
            error_log("Connection test failed: " . $e->getMessage());
            return false;
        }
    }
}

// Global database instance
function getDB() {
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    return $db;
}
?>


