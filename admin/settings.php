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
        'show_theme_selector' => isset($_POST['show_theme_selector']) ? 'true' : 'false'
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

            <div class="settings-grid">
                <!-- Basic Settings Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-cog"></i> Basic Settings</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($siteName); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="site_tagline">Site Tagline</label>
                                <input type="text" id="site_tagline" name="site_tagline" value="<?php echo htmlspecialchars($siteTagline); ?>">
                            </div>
                            <div class="form-group">
                                <label for="site_url">Site URL</label>
                                <input type="url" id="site_url" name="site_url" value="<?php echo htmlspecialchars($siteUrl); ?>" placeholder="https://yoursite.com">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Settings Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-envelope"></i> Contact Settings</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="contact_form_email">Contact Email</label>
                                <input type="email" id="contact_form_email" name="contact_form_email" value="<?php echo htmlspecialchars($contactFormEmail); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="contact_form_enabled" <?php echo $contactFormEnabled ? 'checked' : ''; ?>>
                                    Enable Contact Form
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Analytics & SEO Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-line"></i> Analytics & SEO</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="google_analytics_id">Google Analytics ID</label>
                                <input type="text" id="google_analytics_id" name="google_analytics_id" value="<?php echo htmlspecialchars($googleAnalyticsId); ?>" placeholder="G-XXXXXXXXXX">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Appearance Settings Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-palette"></i> Appearance</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="show_theme_selector" <?php echo $showThemeSelector ? 'checked' : ''; ?>>
                                    Show Theme Selector
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="favicon_type">Favicon Source</label>
                                <select id="favicon_type" name="favicon_type">
                                    <option value="logo" <?php echo $faviconType === 'logo' ? 'selected' : ''; ?>>Use Logo</option>
                                    <option value="custom" <?php echo $faviconType === 'custom' ? 'selected' : ''; ?>>Custom URL</option>
                                    <option value="file" <?php echo $faviconType === 'file' ? 'selected' : ''; ?>>Use favicon.ico</option>
                                </select>
                            </div>
                            <div class="form-group" id="custom-favicon-group" style="<?php echo $faviconType === 'custom' ? '' : 'display: none;'; ?>">
                                <label for="custom_favicon_url">Custom Favicon URL</label>
                                <input type="url" id="custom_favicon_url" name="custom_favicon_url" value="<?php echo htmlspecialchars($customFaviconUrl); ?>" placeholder="https://example.com/favicon.png">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Logo Upload Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-image"></i> Website Logo</h3>
                    </div>
                    <div class="card-content">
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
                </div>

                <!-- Default Values Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-circle"></i> Default Profile</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="default_name">Default Name</label>
                                <input type="text" id="default_name" name="default_name" value="<?php echo htmlspecialchars($defaultName); ?>">
                            </div>
                            <div class="form-group">
                                <label for="default_title">Default Title</label>
                                <input type="text" id="default_title" name="default_title" value="<?php echo htmlspecialchars($defaultTitle); ?>">
                            </div>
                            <div class="form-group">
                                <label for="default_email">Default Email</label>
                                <input type="email" id="default_email" name="default_email" value="<?php echo htmlspecialchars($defaultEmail); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>

                <!-- System Settings Card -->
                <div class="settings-card">
                    <div class="card-header">
                        <h3><i class="fas fa-tools"></i> System</h3>
                    </div>
                    <div class="card-content">
                        <form method="POST">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="maintenance_mode" <?php echo $maintenanceMode ? 'checked' : ''; ?>>
                                    Maintenance Mode
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-save"></i> Save
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
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="contact_form_enabled" <?php echo $contactFormEnabled ? 'checked' : ''; ?>>
                                    Enable Contact Form
                                </label>
                                <small>Show/hide the contact form on your portfolio</small>
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
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="smtp_enabled" <?php echo $smtpEnabled ? 'checked' : ''; ?>>
                                    Enable SMTP Email Notifications
                                </label>
                                <small>Enable this to send email notifications when contact forms are submitted</small>
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
    </script>
</body>
</html>
