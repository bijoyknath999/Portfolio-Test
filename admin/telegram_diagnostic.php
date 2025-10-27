<?php
require_once '../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Diagnostic</title>
    <link rel="stylesheet" href="admin-styles.css">
    <style>
        .diagnostic-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-ok { color: #4caf50; font-weight: bold; }
        .status-error { color: #f44336; font-weight: bold; }
        .code-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            font-family: monospace;
            margin: 10px 0;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1>🔧 Telegram Diagnostic</h1>
        </div>

        <div class="diagnostic-box">
            <h3>PHP Configuration</h3>
            <ul>
                <li>PHP Version: <strong><?php echo phpversion(); ?></strong></li>
                <li>cURL Extension: 
                    <?php if (function_exists('curl_init')): ?>
                        <span class="status-ok">✅ Installed</span>
                    <?php else: ?>
                        <span class="status-error">❌ Not installed</span>
                    <?php endif; ?>
                </li>
                <li>allow_url_fopen: 
                    <?php if (ini_get('allow_url_fopen')): ?>
                        <span class="status-ok">✅ Enabled</span>
                    <?php else: ?>
                        <span class="status-error">❌ Disabled</span>
                    <?php endif; ?>
                </li>
                <li>OpenSSL: 
                    <?php if (extension_loaded('openssl')): ?>
                        <span class="status-ok">✅ Loaded</span>
                    <?php else: ?>
                        <span class="status-error">❌ Not loaded</span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>

        <div class="diagnostic-box">
            <h3>📝 How to Set Up Telegram Bot</h3>
            <ol>
                <li><strong>Create Bot:</strong>
                    <ul>
                        <li>Open Telegram and search for <code>@BotFather</code></li>
                        <li>Send: <code>/newbot</code></li>
                        <li>Follow instructions to create your bot</li>
                        <li>Copy the Bot Token (looks like: <code>123456789:ABCdefGHIjklMNOpqrsTUVwxyz</code>)</li>
                    </ul>
                </li>
                
                <li><strong>Get Chat ID:</strong>
                    <ul>
                        <li>Start a chat with your bot (click the link BotFather provides)</li>
                        <li>Send any message to your bot (e.g., "Hello")</li>
                        <li>Replace <code>YOUR_BOT_TOKEN</code> below and visit this URL:</li>
                    </ul>
                    <div class="code-box">
https://api.telegram.org/bot<strong>YOUR_BOT_TOKEN</strong>/getUpdates
                    </div>
                    <ul>
                        <li>Look for <code>"chat":{"id":123456789</code> in the JSON response</li>
                        <li>Copy that number (your Chat ID)</li>
                    </ul>
                </li>
                
                <li><strong>Test Connection:</strong>
                    <ul>
                        <li>Go to <a href="settings.php">Settings → Telegram Notifications</a></li>
                        <li>Enter your Bot Token and Chat ID</li>
                        <li>Click "Test Connection"</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="diagnostic-box">
            <h3>🐛 Common Issues</h3>
            <ul>
                <li><strong>Invalid bot token format:</strong> Make sure token looks like <code>1234567890:ABCdefGHIjklMNOpqrsTUVwxyz</code></li>
                <li><strong>Invalid chat ID:</strong> Chat ID should be a number like <code>123456789</code> or <code>-123456789</code></li>
                <li><strong>Bot hasn't received messages:</strong> You MUST send at least one message to your bot before getting Chat ID</li>
                <li><strong>cURL not available:</strong> Make sure cURL extension is enabled in php.ini</li>
                <li><strong>SSL errors:</strong> Make sure OpenSSL is loaded</li>
            </ul>
        </div>

        <div class="diagnostic-box">
            <h3>🧪 Manual Test</h3>
            <p>To manually test your Telegram bot, open this URL in your browser (replace values):</p>
            <div class="code-box">
https://api.telegram.org/bot<strong>YOUR_BOT_TOKEN</strong>/sendMessage?chat_id=<strong>YOUR_CHAT_ID</strong>&text=Test
            </div>
            <p>If successful, you'll see JSON response with <code>"ok":true</code> and receive a message in Telegram.</p>
        </div>

        <div class="diagnostic-box">
            <h3>Example Values</h3>
            <p><strong>Bot Token Example:</strong></p>
            <div class="code-box">1234567890:ABCdefGHIjklMNOpqrsTUVwxyz123456789</div>
            
            <p><strong>Chat ID Example:</strong></p>
            <div class="code-box">123456789</div>
            <p><em>or for groups/channels:</em></p>
            <div class="code-box">-1001234567890</div>
        </div>

        <a href="settings.php" class="btn btn-primary">← Back to Settings</a>
    </div>
</body>
</html>

