<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';

// Check if redirected after successful save
if (isset($_GET['saved']) && $_GET['saved'] == '1') {
    $message = '<div class="notification success"><i class="fas fa-check"></i> Settings updated successfully!</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => sanitizeInput($_POST['site_name'] ?? ''),
        'site_tagline' => sanitizeInput($_POST['site_tagline'] ?? ''),
        'google_analytics_id' => sanitizeInput($_POST['google_analytics_id'] ?? ''),
        'contact_form_email' => sanitizeInput($_POST['contact_form_email'] ?? ''),
        'smtp_host' => sanitizeInput($_POST['smtp_host'] ?? ''),
        'smtp_port' => sanitizeInput($_POST['smtp_port'] ?? ''),
        'smtp_username' => sanitizeInput($_POST['smtp_username'] ?? ''),
        'smtp_password' => sanitizeInput($_POST['smtp_password'] ?? ''),
        'smtp_encryption' => sanitizeInput($_POST['smtp_encryption'] ?? ''),
        'smtp_enabled' => isset($_POST['smtp_enabled']) ? 'true' : 'false',
        'site_url' => sanitizeInput($_POST['site_url'] ?? ''),
        'favicon_type' => sanitizeInput($_POST['favicon_type'] ?? 'logo'),
        'custom_favicon_url' => sanitizeInput($_POST['custom_favicon_url'] ?? ''),
        'contact_form_enabled' => isset($_POST['contact_form_enabled']) ? 'true' : 'false',
        'contact_form_title' => sanitizeInput($_POST['contact_form_title'] ?? ''),
        'contact_form_subtitle' => sanitizeInput($_POST['contact_form_subtitle'] ?? ''),
        'contact_success_message' => sanitizeInput($_POST['contact_success_message'] ?? ''),
        'contact_error_message' => sanitizeInput($_POST['contact_error_message'] ?? ''),
        'default_name' => sanitizeInput($_POST['default_name'] ?? ''),
        'default_title' => sanitizeInput($_POST['default_title'] ?? ''),
        'default_description' => sanitizeInput($_POST['default_description'] ?? ''),
        'default_email' => sanitizeInput($_POST['default_email'] ?? ''),
        'default_phone' => sanitizeInput($_POST['default_phone'] ?? ''),
        'default_location' => sanitizeInput($_POST['default_location'] ?? ''),
        'maintenance_mode' => isset($_POST['maintenance_mode']) ? 'true' : 'false',
        'show_theme_selector' => isset($_POST['show_theme_selector']) ? 'true' : 'false',
        'telegram_bot_token' => sanitizeInput($_POST['telegram_bot_token'] ?? ''),
        'telegram_chat_id' => sanitizeInput($_POST['telegram_chat_id'] ?? ''),
        'telegram_notifications_enabled' => isset($_POST['telegram_notifications_enabled']) ? 'true' : 'false'
    ];
    
    $success = true;
    $errors = [];
    
    foreach ($settings as $key => $value) {
        if (!updateSiteSetting($key, $value)) {
            $success = false;
            $errors[] = $key;
        }
    }
    
    if ($success) {
        // Redirect to prevent resubmission on refresh
        header('Location: settings.php?saved=1');
        exit();
    } else {
        $errorList = implode(', ', $errors);
        $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to update settings: ' . $errorList . '</div>';
    }
}

// Get current settings
$siteName = getSiteSetting('site_name') ?: 'Portfolio';
$siteTagline = getSiteSetting('site_tagline') ?: 'Full-Stack Developer';
$googleAnalyticsId = getSiteSetting('google_analytics_id') ?: '';
$contactFormEmail = getSiteSetting('contact_form_email') ?: '';
$smtpHost = getSiteSetting('smtp_host') ?: '';
$smtpPort = getSiteSetting('smtp_port') ?: '587';
$smtpUsername = getSiteSetting('smtp_username') ?: '';
$smtpPassword = getSiteSetting('smtp_password') ?: '';
$smtpEncryption = getSiteSetting('smtp_encryption') ?: 'tls';
$smtpEnabled = getSiteSetting('smtp_enabled') === 'true';
$siteUrl = getSiteSetting('site_url') ?: 'http://localhost/portfolio';
$faviconType = getSiteSetting('favicon_type') ?: 'logo';
$customFaviconUrl = getSiteSetting('custom_favicon_url') ?: '';
$contactFormEnabled = getSiteSetting('contact_form_enabled') !== 'false';
$contactFormTitle = getSiteSetting('contact_form_title') ?: 'Get In Touch';
$contactFormSubtitle = getSiteSetting('contact_form_subtitle') ?: 'Let\'s work together on your next project';
$contactSuccessMessage = getSiteSetting('contact_success_message') ?: 'Message sent successfully! I\'ll get back to you soon.';
$contactErrorMessage = getSiteSetting('contact_error_message') ?: 'Failed to send message. Please try again.';
$defaultName = getSiteSetting('default_name') ?: 'Bijoy Kumar Nath';
$defaultTitle = getSiteSetting('default_title') ?: 'Full-Stack & Mobile App Developer';
$defaultDescription = getSiteSetting('default_description') ?: 'Experienced Full Stack App Developer with 5+ years of expertise in Android, iOS, and Web development.';
$defaultEmail = getSiteSetting('default_email') ?: 'bijoyknath999@gmail.com';
$defaultPhone = getSiteSetting('default_phone') ?: '+8801831980819';
$defaultLocation = getSiteSetting('default_location') ?: 'Dhaka, Bangladesh';
$maintenanceMode = getSiteSetting('maintenance_mode') === 'true';
$showThemeSelector = getSiteSetting('show_theme_selector') !== 'false';
$telegramBotToken = getSiteSetting('telegram_bot_token') ?: '';
$telegramChatId = getSiteSetting('telegram_chat_id') ?: '';
$telegramNotificationsEnabled = getSiteSetting('telegram_notifications_enabled') === 'true';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-cog"></i> Site Settings</h1>
            </div>

            <?php echo $message; ?>

            <!-- Feature Toggles Section (Full Width) -->
            <div class="settings-card-full">
                <div class="card-header">
                    <h3><i class="fas fa-toggle-on"></i> Feature Toggles</h3>
                    <p style="font-size: 0.9rem; color: #666; margin: 5px 0 0 0;">Enable or disable features across your portfolio</p>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="toggles-grid">
                            <div class="toggle-item">
                                <div class="toggle-header">
                                    <i class="fas fa-tools"></i>
                                    <div>
                                        <h4>Maintenance Mode</h4>
                                        <p>Put site in maintenance mode</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="maintenance_mode" <?php echo $maintenanceMode ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-item">
                                <div class="toggle-header">
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <h4>Contact Form</h4>
                                        <p>Enable contact form on website</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="contact_form_enabled" <?php echo $contactFormEnabled ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-item">
                                <div class="toggle-header">
                                    <i class="fas fa-palette"></i>
                                    <div>
                                        <h4>Theme Selector</h4>
                                        <p>Show theme selector on website</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="show_theme_selector" <?php echo $showThemeSelector ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-item">
                                <div class="toggle-header">
                                    <i class="fab fa-telegram"></i>
                                    <div>
                                        <h4>Telegram Notifications</h4>
                                        <p>Get visitor alerts on Telegram</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="telegram_notifications_enabled" <?php echo $telegramNotificationsEnabled ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>

                            <div class="toggle-item">
                                <div class="toggle-header">
                                    <i class="fas fa-server"></i>
                                    <div>
                                        <h4>SMTP Email</h4>
                                        <p>Use SMTP for sending emails</p>
                                    </div>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" name="smtp_enabled" <?php echo $smtpEnabled ? 'checked' : ''; ?>>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="button-group" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save All Toggles
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="settings-grid">
                <!-- Basic Settings Card -->
                <!-- General Settings Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-cog"></i> General Settings</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="site_name">
                                    <i class="fas fa-globe"></i> Site Name *
                                </label>
                                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($siteName); ?>" required>
                                <small class="form-text">Your website's name</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_tagline">
                                    <i class="fas fa-tag"></i> Site Tagline
                                </label>
                                <input type="text" id="site_tagline" name="site_tagline" value="<?php echo htmlspecialchars($siteTagline); ?>">
                                <small class="form-text">Brief description or slogan</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_url">
                                    <i class="fas fa-link"></i> Site URL
                                </label>
                                <input type="url" id="site_url" name="site_url" value="<?php echo htmlspecialchars($siteUrl); ?>" placeholder="https://yoursite.com">
                                <small class="form-text">Your website's URL</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_analytics_id">
                                    <i class="fas fa-chart-line"></i> Google Analytics ID
                                </label>
                                <input type="text" id="google_analytics_id" name="google_analytics_id" value="<?php echo htmlspecialchars($googleAnalyticsId); ?>" placeholder="G-XXXXXXXXXX">
                                <small class="form-text">Track your website visitors (optional)</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save General Settings
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Branding & Appearance Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-palette"></i> Branding & Appearance</h3>
                    </div>
                    <div class="card-content">
                        <!-- Logo Upload Section -->
                        <div class="form-section">
                            <h4 style="margin: 0 0 15px 0; color: #667eea; font-size: 1rem;">
                                <i class="fas fa-image"></i> Website Logo
                            </h4>
                            <div class="logo-upload-area">
                                <div class="image-upload-area" onclick="document.getElementById('logoImageInput').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Click to upload website logo</div>
                                    <div class="upload-hint">PNG, JPG, SVG up to 2MB (recommended: 200x200px)</div>
                                    <input type="file" id="logoImageInput" accept="image/*" style="display: none;">
                                    <div class="upload-progress" style="display: none;">
                                        <div class="upload-progress-bar"></div>
                                    </div>
                                </div>
                                <?php if (file_exists('../logo.png')): ?>
                                <div style="margin-top: 15px;">
                                    <strong>Current Logo:</strong><br>
                                    <img src="../logo.png?v=<?php echo time(); ?>" alt="Current Logo" class="image-preview">
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Favicon Settings Section -->
                        <div class="form-section" style="margin-top: 30px; padding-top: 30px; border-top: 2px solid rgba(102, 126, 234, 0.1);">
                            <h4 style="margin: 0 0 15px 0; color: #667eea; font-size: 1rem;">
                                <i class="fas fa-star"></i> Favicon Settings
                            </h4>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="favicon_type">Favicon Source</label>
                                    <select id="favicon_type" name="favicon_type">
                                        <option value="logo" <?php echo $faviconType === 'logo' ? 'selected' : ''; ?>>Use Logo</option>
                                        <option value="custom" <?php echo $faviconType === 'custom' ? 'selected' : ''; ?>>Custom URL</option>
                                        <option value="file" <?php echo $faviconType === 'file' ? 'selected' : ''; ?>>Use favicon.ico</option>
                                    </select>
                                    <small class="form-text">Choose where to get your favicon from</small>
                                </div>
                                <div class="form-group" id="custom-favicon-group" style="<?php echo $faviconType === 'custom' ? '' : 'display: none;'; ?>">
                                    <label for="custom_favicon_url">Custom Favicon URL</label>
                                    <input type="url" id="custom_favicon_url" name="custom_favicon_url" value="<?php echo htmlspecialchars($customFaviconUrl); ?>" placeholder="https://example.com/favicon.png">
                                    <small class="form-text">URL to your custom favicon image</small>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Save Favicon Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Default Values Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-circle"></i> Fallback Profile</h3>
                    </div>
                    <div class="card-content">
                        <div class="notification info" style="margin-bottom: 20px;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> These values are used as fallbacks when personal information is not available.
                        </div>
                        
                        <form method="POST">
                            <div class="form-group">
                                <label for="default_name">
                                    <i class="fas fa-user"></i> Default Name
                                </label>
                                <input type="text" id="default_name" name="default_name" value="<?php echo htmlspecialchars($defaultName); ?>">
                                <small class="form-text">Fallback name to display</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="default_title">
                                    <i class="fas fa-briefcase"></i> Default Title
                                </label>
                                <input type="text" id="default_title" name="default_title" value="<?php echo htmlspecialchars($defaultTitle); ?>">
                                <small class="form-text">Fallback job title or role</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="default_email">
                                    <i class="fas fa-envelope"></i> Default Email
                                </label>
                                <input type="email" id="default_email" name="default_email" value="<?php echo htmlspecialchars($defaultEmail); ?>">
                                <small class="form-text">Fallback email address</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save Fallback Settings
                            </button>
                        </form>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-comments"></i> Contact Form Settings</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="notification info" style="margin-bottom: 20px;">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> Enable/disable Contact Form in the Feature Toggles section above.
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_form_email">
                                    <i class="fas fa-envelope"></i> Contact Email *
                                </label>
                                <input type="email" id="contact_form_email" name="contact_form_email" value="<?php echo htmlspecialchars($contactFormEmail); ?>" placeholder="your-email@example.com" required>
                                <small class="form-text">Email address where contact form submissions will be sent</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_form_title">Contact Section Title</label>
                                <input type="text" id="contact_form_title" name="contact_form_title" value="<?php echo htmlspecialchars($contactFormTitle); ?>" placeholder="Get In Touch">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_form_subtitle">Contact Section Subtitle</label>
                                <input type="text" id="contact_form_subtitle" name="contact_form_subtitle" value="<?php echo htmlspecialchars($contactFormSubtitle); ?>" placeholder="Let's work together on your next project">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_success_message">Success Message</label>
                                <input type="text" id="contact_success_message" name="contact_success_message" value="<?php echo htmlspecialchars($contactSuccessMessage); ?>" placeholder="Message sent successfully!">
                                <small>Message shown when contact form is submitted successfully</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_error_message">Error Message</label>
                                <input type="text" id="contact_error_message" name="contact_error_message" value="<?php echo htmlspecialchars($contactErrorMessage); ?>" placeholder="Failed to send message. Please try again.">
                                <small>Message shown when contact form submission fails</small>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Contact Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-envelope-open"></i> Email & SMTP Settings</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="notification info" style="margin-bottom: 15px;">
                                <i class="fas fa-info-circle"></i>
                                <strong>Note:</strong> Enable SMTP in the Feature Toggles section above to configure email settings.
                            </div>
                            
                            <div class="notification warning" style="margin-bottom: 20px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Why SMTP?</strong> PHP's built-in mail() function often fails or goes to spam. SMTP provides reliable email delivery to Gmail and other providers with proper authentication.
                            </div>
                            
                            <div id="smtp-settings" style="<?php echo $smtpEnabled ? '' : 'display: none;'; ?>">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="smtp_host">SMTP Host *</label>
                                        <input type="text" id="smtp_host" name="smtp_host" value="<?php echo htmlspecialchars($smtpHost); ?>" placeholder="smtp.gmail.com">
                                        <small>Your email provider's SMTP server</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="smtp_port">SMTP Port *</label>
                                        <input type="number" id="smtp_port" name="smtp_port" value="<?php echo htmlspecialchars($smtpPort); ?>" placeholder="587">
                                        <small>Usually 587 for TLS or 465 for SSL</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="smtp_encryption">Encryption</label>
                                    <select id="smtp_encryption" name="smtp_encryption">
                                        <option value="tls" <?php echo $smtpEncryption === 'tls' ? 'selected' : ''; ?>>TLS</option>
                                        <option value="ssl" <?php echo $smtpEncryption === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                        <option value="none" <?php echo $smtpEncryption === 'none' ? 'selected' : ''; ?>>None</option>
                                    </select>
                                </div>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="smtp_username">SMTP Username *</label>
                                        <input type="text" id="smtp_username" name="smtp_username" value="<?php echo htmlspecialchars($smtpUsername); ?>" placeholder="your-email@gmail.com">
                                        <small>Your email address</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="smtp_password">SMTP Password *</label>
                                        <input type="password" id="smtp_password" name="smtp_password" value="<?php echo htmlspecialchars($smtpPassword); ?>" placeholder="Your app password">
                                        <small>Use app password for Gmail</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="notification warning">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Gmail Setup:</strong> Use your Gmail address as username and create an "App Password" in your Google Account settings. Don't use your regular Gmail password.
                                    </div>
                                </div>
                                
                                <div class="button-group">
                                    <button type="button" class="btn btn-outline" onclick="testEmailSettings()">
                                        <i class="fas fa-paper-plane"></i> Test Email Settings
                                    </button>
                                </div>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Email Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Telegram Notifications -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fab fa-telegram"></i> Telegram Notifications</h3>
                    </div>
                    <div class="card-content">
                        <div class="notification info" style="margin-bottom: 20px;">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Enable Telegram Notifications in the Feature Toggles section above to configure settings.
                        </div>
                        
                        <div id="telegram-settings" style="<?php echo $telegramNotificationsEnabled ? '' : 'display: none;'; ?>">
                            <p class="card-description" style="margin-bottom: 20px;">
                                <i class="fas fa-bell"></i>
                                Get instant Telegram notifications when someone visits your website for the first time. Set up your Telegram bot to receive real-time alerts.
                            </p>
                            
                            <div class="telegram-setup-guide">
                            <h4><i class="fas fa-robot"></i> How to Set Up Telegram Bot:</h4>
                            <ol>
                                <li>Open Telegram and search for <strong>@BotFather</strong></li>
                                <li>Send <code>/newbot</code> command to create a new bot</li>
                                <li>Follow the instructions and copy your <strong>Bot Token</strong></li>
                                <li>Start a chat with your bot and send any message</li>
                                <li>Visit this URL in your browser (replace YOUR_BOT_TOKEN):
                                    <div class="url-box">https://api.telegram.org/bot<strong>YOUR_BOT_TOKEN</strong>/getUpdates</div>
                                </li>
                                <li>Find your <strong>Chat ID</strong> in the JSON response (look for "chat":{"id":...})</li>
                                <li>Enter both values below and click "Test Connection"</li>
                            </ol>
                        </div>
                        
                        <form method="POST" id="telegramForm">
                            <div class="form-group">
                                <label for="telegram_bot_token">
                                    <i class="fas fa-key"></i> Telegram Bot Token *
                                </label>
                                <input type="text" 
                                       id="telegram_bot_token" 
                                       name="telegram_bot_token" 
                                       value="<?php echo htmlspecialchars($telegramBotToken); ?>" 
                                       placeholder="1234567890:ABCdefGHIjklMNOpqrsTUVwxyz"
                                       class="form-control">
                                <small class="form-text">Get this from @BotFather on Telegram</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="telegram_chat_id">
                                    <i class="fas fa-user"></i> Telegram Chat ID *
                                </label>
                                <input type="text" 
                                       id="telegram_chat_id" 
                                       name="telegram_chat_id" 
                                       value="<?php echo htmlspecialchars($telegramChatId); ?>" 
                                       placeholder="123456789"
                                       class="form-control">
                                <small class="form-text">Your unique chat ID from getUpdates</small>
                            </div>
                            
                            <div class="button-group">
                                <button type="button" class="btn btn-outline" id="testTelegramBtn">
                                    <i class="fas fa-vial"></i> Test Connection
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Telegram Settings
                                </button>
                            </div>
                            
                            <div id="telegramTestResult" style="display: none; margin-top: 15px;"></div>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>System Information</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <strong>PHP Version:</strong> <?php echo phpversion(); ?>
                        </div>
                        <div class="info-item">
                            <strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>
                        </div>
                        <div class="info-item">
                            <strong>Database:</strong> 
                            <?php 
                            try {
                                $db = getDB();
                                echo "Connected ✅";
                            } catch (Exception $e) {
                                echo "Error ❌";
                            }
                            ?>
                        </div>
                        <div class="info-item">
                            <strong>Upload Max Size:</strong> <?php echo ini_get('upload_max_filesize'); ?>
                        </div>
                        <div class="info-item">
                            <strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions">
                            <a href="../index.php" class="quick-action" target="_blank">
                                <i class="fas fa-external-link-alt"></i> View Portfolio
                            </a>
                            <a href="themes.php" class="quick-action">
                                <i class="fas fa-palette"></i> Manage Themes
                            </a>
                            <a href="seo.php" class="quick-action">
                                <i class="fas fa-search"></i> SEO Settings
                            </a>
                            <a href="messages.php" class="quick-action">
                                <i class="fas fa-envelope"></i> View Messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded - Settings page');
        
        // Handle SMTP toggle from Feature Toggles section
        const smtpToggle = document.querySelector('input[name="smtp_enabled"]');
        const smtpSettings = document.getElementById('smtp-settings');
        
        if (smtpToggle && smtpSettings) {
            // Set initial state
            smtpSettings.style.display = smtpToggle.checked ? 'block' : 'none';
            
            // Listen for changes
            smtpToggle.addEventListener('change', function() {
                if (this.checked) {
                    smtpSettings.style.display = 'block';
                    // Scroll to SMTP settings
                    setTimeout(() => {
                        smtpSettings.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                } else {
                    smtpSettings.style.display = 'none';
                }
            });
        }
        
        // Handle Telegram toggle from Feature Toggles section
        const telegramToggle = document.querySelector('input[name="telegram_notifications_enabled"]');
        const telegramSettings = document.getElementById('telegram-settings');
        
        if (telegramToggle && telegramSettings) {
            // Set initial state
            telegramSettings.style.display = telegramToggle.checked ? 'block' : 'none';
            
            // Listen for changes
            telegramToggle.addEventListener('change', function() {
                if (this.checked) {
                    telegramSettings.style.display = 'block';
                    // Scroll to Telegram settings
                    setTimeout(() => {
                        telegramSettings.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                } else {
                    telegramSettings.style.display = 'none';
                }
            });
        }
        
        // Toggle favicon custom URL field
        const faviconTypeSelect = document.getElementById('favicon_type');
        if (faviconTypeSelect) {
            faviconTypeSelect.addEventListener('change', function() {
                const customFaviconGroup = document.getElementById('custom-favicon-group');
                if (customFaviconGroup) {
                    if (this.value === 'custom') {
                        customFaviconGroup.style.display = 'block';
                    } else {
                        customFaviconGroup.style.display = 'none';
                    }
                }
            });
        }
        
        // Logo upload functionality (copied exactly from personal-info.php)
        const logoInput = document.getElementById('logoImageInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', 'logo');
                
                const progressBar = document.querySelector('.upload-progress-bar');
                const progress = document.querySelector('.upload-progress');
                const uploadArea = document.querySelector('.image-upload-area');
                
                progress.style.display = 'block';
                uploadArea.style.pointerEvents = 'none';
                
                fetch('../upload_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const notification = document.createElement('div');
                        notification.className = 'notification success';
                        notification.innerHTML = '<i class="fas fa-check"></i> Logo uploaded successfully!';
                        document.querySelector('.main-content').insertBefore(notification, document.querySelector('.settings-grid'));
                        
                        // Refresh page to show new logo
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        alert('Upload failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Upload failed. Please try again.');
                })
                .finally(() => {
                    progress.style.display = 'none';
                    uploadArea.style.pointerEvents = 'auto';
                    progressBar.style.width = '0%';
                });
                
                // Simulate progress
                let width = 0;
                const interval = setInterval(() => {
                    width += 10;
                    progressBar.style.width = width + '%';
                    if (width >= 90) clearInterval(interval);
                }, 100);
            });
        }
    });
    
    // Test email settings
    function testEmailSettings() {
        const formData = new FormData();
        formData.append('action', 'test_email');
        formData.append('smtp_host', document.getElementById('smtp_host').value);
        formData.append('smtp_port', document.getElementById('smtp_port').value);
        formData.append('smtp_username', document.getElementById('smtp_username').value);
        formData.append('smtp_password', document.getElementById('smtp_password').value);
        formData.append('smtp_encryption', document.getElementById('smtp_encryption').value);
        formData.append('contact_form_email', '<?php echo htmlspecialchars($contactFormEmail); ?>');
        
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
        btn.disabled = true;
        
        fetch('test_email.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Test email sent successfully! Check your inbox.');
            } else {
                alert('❌ Test failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Test error:', error);
            alert('❌ Test failed. Please check your settings.');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    
    // Telegram test connection
    const testTelegramBtn = document.getElementById('testTelegramBtn');
    if (testTelegramBtn) {
        testTelegramBtn.addEventListener('click', testTelegramConnection);
    }
    
    function testTelegramConnection() {
        const btn = document.getElementById('testTelegramBtn');
        const resultDiv = document.getElementById('telegramTestResult');
        const botToken = document.getElementById('telegram_bot_token').value;
        const chatId = document.getElementById('telegram_chat_id').value;
        
        if (!botToken || !chatId) {
            resultDiv.innerHTML = '<div class="notification error"><i class="fas fa-times-circle"></i> Please enter both Bot Token and Chat ID!</div>';
            resultDiv.style.display = 'block';
            return;
        }
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
        btn.disabled = true;
        
        const formData = new FormData();
        formData.append('bot_token', botToken);
        formData.append('chat_id', chatId);
        
        fetch('telegram_test.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<div class="notification success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
            } else {
                resultDiv.innerHTML = '<div class="notification error"><i class="fas fa-times-circle"></i> ' + data.message + '</div>';
            }
            resultDiv.style.display = 'block';
        })
        .catch(error => {
            console.error('Telegram test error:', error);
            resultDiv.innerHTML = '<div class="notification error"><i class="fas fa-times-circle"></i> Test failed. Please check your settings.</div>';
            resultDiv.style.display = 'block';
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
    </script>
    
    <style>
    .telegram-setup-guide {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-left: 4px solid #667eea;
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
    }
    
    .telegram-setup-guide h4 {
        color: #667eea;
        margin: 0 0 15px 0;
        font-size: 1.1rem;
    }
    
    .telegram-setup-guide ol {
        margin: 0;
        padding-left: 20px;
    }
    
    .telegram-setup-guide li {
        margin: 10px 0;
        line-height: 1.6;
    }
    
    .telegram-setup-guide code {
        background: rgba(102, 126, 234, 0.1);
        padding: 3px 8px;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
        color: #667eea;
    }
    
    .badge-success {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .badge-inactive {
        background: #e0e0e0;
        color: #666;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .card-description {
        background: #f8f9ff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #666;
        line-height: 1.6;
    }
    
    .card-description i {
        color: #667eea;
        margin-right: 5px;
    }
    
    .url-box {
        background: rgba(102, 126, 234, 0.05);
        border: 2px dashed rgba(102, 126, 234, 0.3);
        padding: 12px 16px;
        border-radius: 8px;
        margin: 10px 0;
        font-family: 'Courier New', monospace;
        font-size: 0.9em;
        color: #667eea;
        word-break: break-all;
        line-height: 1.6;
    }
    
    .url-box strong {
        color: #f093fb;
        font-weight: 700;
    }
    
    /* Feature Toggles Section */
    .settings-card-full {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.8);
        position: relative;
        transition: all 0.3s ease;
    }
    
    .settings-card-full::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 16px 16px 0 0;
    }
    
    .settings-card-full:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 30px rgba(102, 126, 234, 0.15);
    }
    
    .settings-card-full:hover::before {
        opacity: 1;
    }
    
    .toggles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .toggle-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border-radius: 10px;
        border: 2px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }
    
    .toggle-item:hover {
        border-color: rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    .toggle-header {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .toggle-header > i {
        font-size: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        width: 35px;
        text-align: center;
    }
    
    .toggle-header h4 {
        margin: 0 0 3px 0;
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }
    
    .toggle-header p {
        margin: 0;
        font-size: 0.8rem;
        color: #666;
    }
    
    /* Toggle Switch Styles */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 28px;
        margin: 0;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #ccc;
        transition: 0.4s;
        border-radius: 28px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 3px;
        bottom: 3px;
        background: white;
        transition: 0.4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .toggle-switch input:checked + .toggle-slider {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .toggle-switch input:checked + .toggle-slider:before {
        transform: translateX(22px);
    }
    
    .toggle-switch:hover .toggle-slider {
        box-shadow: 0 0 8px rgba(102, 126, 234, 0.4);
    }
    
    /* SMTP & Telegram Settings Sections */
    #smtp-settings,
    #telegram-settings {
        padding: 20px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.1);
        margin-top: 15px;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .toggles-grid {
            grid-template-columns: 1fr;
        }
        
        .toggle-item {
            padding: 15px;
        }
        
        .toggle-header h4 {
            font-size: 1rem;
        }
        
        .toggle-header p {
            font-size: 0.8rem;
        }
    }
    </style>
</body>
</html>
