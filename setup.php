<?php
/**
 * Portfolio Setup Script
 * 
 * This script will:
 * 1. Connect to your database
 * 2. Import the database schema
 * 3. Create the admin user
 * 4. Set up initial configuration
 * 
 * IMPORTANT: Delete this file after successful setup for security!
 */

// Prevent direct access after setup is complete
$setupCompleteFile = __DIR__ . '/.setup_complete';
if (file_exists($setupCompleteFile)) {
    die('⚠️ Setup has already been completed. Delete .setup_complete file to run setup again.');
}

// Configuration
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$message = '';
$error = '';

// Database configuration
$configFile = __DIR__ . '/config/database.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 1) {
        // Step 1: Database Configuration
        $dbHost = trim($_POST['db_host']);
        $dbName = trim($_POST['db_name']);
        $dbUser = trim($_POST['db_user']);
        $dbPass = $_POST['db_pass'];
        
        // Test database connection
        try {
            $dsn = "mysql:host=$dbHost;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Check if database exists, create if not
            $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
            if ($stmt->rowCount() === 0) {
                $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $message = "✅ Database '$dbName' created successfully!";
            } else {
                $message = "✅ Database '$dbName' exists!";
            }
            
            // Save database configuration
            $configContent = "<?php\n";
            $configContent .= "// Database Configuration\n";
            $configContent .= "define('DB_HOST', '" . addslashes($dbHost) . "');\n";
            $configContent .= "define('DB_NAME', '" . addslashes($dbName) . "');\n";
            $configContent .= "define('DB_USER', '" . addslashes($dbUser) . "');\n";
            $configContent .= "define('DB_PASS', '" . addslashes($dbPass) . "');\n\n";
            $configContent .= "// Create database connection\n";
            $configContent .= "try {\n";
            $configContent .= "    \$dsn = \"mysql:host=\" . DB_HOST . \";dbname=\" . DB_NAME . \";charset=utf8mb4\";\n";
            $configContent .= "    \$pdo = new PDO(\$dsn, DB_USER, DB_PASS, [\n";
            $configContent .= "        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,\n";
            $configContent .= "        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,\n";
            $configContent .= "        PDO::ATTR_EMULATE_PREPARES => false\n";
            $configContent .= "    ]);\n";
            $configContent .= "} catch (PDOException \$e) {\n";
            $configContent .= "    die('Database connection failed: ' . \$e->getMessage());\n";
            $configContent .= "}\n";
            
            if (!is_dir(__DIR__ . '/config')) {
                mkdir(__DIR__ . '/config', 0755, true);
            }
            
            file_put_contents($configFile, $configContent);
            
            // Move to step 2
            header("Location: setup.php?step=2");
            exit;
            
        } catch (PDOException $e) {
            $error = "❌ Database connection failed: " . $e->getMessage();
        }
        
    } elseif ($step === 2) {
        // Step 2: Import Database Schema
        require_once $configFile;
        
        $sqlFile = __DIR__ . '/database/portfolio.sql';
        if (!file_exists($sqlFile)) {
            $error = "❌ SQL file not found: database/portfolio.sql";
        } else {
            try {
                // Read SQL file
                $sql = file_get_contents($sqlFile);
                
                // Split into individual queries
                $queries = array_filter(array_map('trim', explode(';', $sql)));
                
                // Execute each query
                $pdo->beginTransaction();
                foreach ($queries as $query) {
                    if (!empty($query)) {
                        $pdo->exec($query);
                    }
                }
                $pdo->commit();
                
                $message = "✅ Database schema imported successfully!";
                
                // Move to step 3
                header("Location: setup.php?step=3");
                exit;
                
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = "❌ Failed to import database: " . $e->getMessage();
            }
        }
        
    } elseif ($step === 3) {
        // Step 3: Create Admin User
        require_once $configFile;
        
        $username = trim($_POST['admin_username']);
        $password = $_POST['admin_password'];
        $confirmPassword = $_POST['confirm_password'];
        $email = trim($_POST['admin_email']);
        
        if (strlen($password) < 6) {
            $error = "❌ Password must be at least 6 characters long!";
        } elseif ($password !== $confirmPassword) {
            $error = "❌ Passwords do not match!";
        } else {
            try {
                // Check if admin table exists and has users
                $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
                $adminCount = $stmt->fetchColumn();
                
                if ($adminCount > 0) {
                    // Update existing admin
                    $stmt = $pdo->prepare("UPDATE admin_users SET username = ?, password = ?, email = ? LIMIT 1");
                    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email]);
                    $message = "✅ Admin user updated successfully!";
                } else {
                    // Create new admin
                    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
                    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email]);
                    $message = "✅ Admin user created successfully!";
                }
                
                // Mark setup as complete
                file_put_contents($setupCompleteFile, date('Y-m-d H:i:s'));
                
                // Move to step 4 (complete)
                header("Location: setup.php?step=4");
                exit;
                
            } catch (PDOException $e) {
                $error = "❌ Failed to create admin user: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .logo p {
            color: #666;
            margin-top: 10px;
        }
        
        .progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .progress::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }
        
        .progress-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #999;
            position: relative;
            z-index: 1;
        }
        
        .progress-step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .progress-step.completed {
            background: #4caf50;
            color: white;
        }
        
        .step-content h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .info-box ul {
            margin-left: 20px;
            margin-top: 10px;
        }
        
        .info-box li {
            margin-bottom: 5px;
            color: #555;
        }
        
        .complete-icon {
            text-align: center;
            font-size: 4rem;
            color: #4caf50;
            margin-bottom: 20px;
        }
        
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            color: #856404;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🚀 Portfolio Setup</h1>
            <p>Easy Installation Wizard</p>
        </div>
        
        <div class="progress">
            <div class="progress-step <?php echo $step >= 1 ? 'active' : ''; ?> <?php echo $step > 1 ? 'completed' : ''; ?>">1</div>
            <div class="progress-step <?php echo $step >= 2 ? 'active' : ''; ?> <?php echo $step > 2 ? 'completed' : ''; ?>">2</div>
            <div class="progress-step <?php echo $step >= 3 ? 'active' : ''; ?> <?php echo $step > 3 ? 'completed' : ''; ?>">3</div>
            <div class="progress-step <?php echo $step >= 4 ? 'active' : ''; ?>">✓</div>
        </div>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($step === 1): ?>
            <!-- Step 1: Database Configuration -->
            <div class="step-content">
                <h2>📊 Database Configuration</h2>
                <div class="info-box">
                    <strong>ℹ️ Database Requirements:</strong>
                    <ul>
                        <li>MySQL 5.7+ or MariaDB 10.2+</li>
                        <li>Create a database or provide credentials</li>
                        <li>Ensure user has CREATE and INSERT privileges</li>
                    </ul>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Database Host</label>
                        <input type="text" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" name="db_name" value="portfolio_db" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database Username</label>
                        <input type="text" name="db_user" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Database Password</label>
                        <input type="password" name="db_pass">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Continue →</button>
                </form>
            </div>
            
        <?php elseif ($step === 2): ?>
            <!-- Step 2: Import Database -->
            <div class="step-content">
                <h2>📥 Import Database Schema</h2>
                <div class="info-box">
                    <strong>ℹ️ This will:</strong>
                    <ul>
                        <li>Create all required database tables</li>
                        <li>Import default settings and sample data</li>
                        <li>Set up the initial structure</li>
                    </ul>
                </div>
                
                <form method="POST">
                    <button type="submit" class="btn btn-primary">Import Database →</button>
                </form>
            </div>
            
        <?php elseif ($step === 3): ?>
            <!-- Step 3: Create Admin User -->
            <div class="step-content">
                <h2>👤 Create Admin Account</h2>
                <div class="info-box">
                    <strong>ℹ️ Admin Access:</strong>
                    <ul>
                        <li>Use a strong password (min 6 characters)</li>
                        <li>Remember these credentials for login</li>
                        <li>You can change them later in settings</li>
                    </ul>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Admin Username</label>
                        <input type="text" name="admin_username" value="admin" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Password</label>
                        <input type="password" name="admin_password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Admin & Finish →</button>
                </form>
            </div>
            
        <?php elseif ($step === 4): ?>
            <!-- Step 4: Complete -->
            <div class="step-content">
                <div class="complete-icon">✅</div>
                <h2 style="text-align: center;">Setup Complete!</h2>
                <div class="info-box" style="margin-top: 20px;">
                    <strong>🎉 Your portfolio is ready!</strong>
                    <ul>
                        <li>Database configured successfully</li>
                        <li>Admin account created</li>
                        <li>All tables imported</li>
                    </ul>
                </div>
                
                <a href="admin/login.php" class="btn btn-success" style="display: block; text-decoration: none; text-align: center;">Go to Admin Login →</a>
                
                <div class="warning">
                    ⚠️ IMPORTANT: For security, please delete setup.php and .setup_complete files from your server!
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


