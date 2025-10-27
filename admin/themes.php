<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        $data = [
            'theme_name' => sanitizeInput($_POST['theme_name'] ?? ''),
            'primary_color' => sanitizeInput($_POST['primary_color'] ?? ''),
            'secondary_color' => sanitizeInput($_POST['secondary_color'] ?? ''),
            'accent_color' => sanitizeInput($_POST['accent_color'] ?? ''),
            'gradient_start' => sanitizeInput($_POST['gradient_start'] ?? ''),
            'gradient_end' => sanitizeInput($_POST['gradient_end'] ?? '')
        ];
        
        if (addColorTheme($data)) {
            $message = '<div class="notification success"><i class="fas fa-check"></i> Theme created successfully!</div>';
            $action = 'list';
        } else {
            $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to create theme.</div>';
        }
    }
}

$themes = getColorThemes();
$activeTheme = getActiveTheme();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Themes - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-palette"></i> Color Themes</h1>
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Theme
                    </a>
                    <?php else: ?>
                    <a href="themes.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Themes
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $message; ?>

            <?php if ($action === 'add'): ?>
            <!-- Add Theme Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Create New Color Theme</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-group">
                            <label for="theme_name">Theme Name *</label>
                            <input type="text" id="theme_name" name="theme_name" required placeholder="e.g., Ocean Blue">
                        </div>
                        
                        <div class="color-picker-grid">
                            <div class="color-picker-group">
                                <label for="primary_color">Primary Color *</label>
                                <div class="color-input-wrapper">
                                    <input type="color" id="primary_color" name="primary_color" value="#667eea" required>
                                    <input type="text" id="primary_color_hex" value="#667eea" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                            </div>
                            
                            <div class="color-picker-group">
                                <label for="secondary_color">Secondary Color *</label>
                                <div class="color-input-wrapper">
                                    <input type="color" id="secondary_color" name="secondary_color" value="#764ba2" required>
                                    <input type="text" id="secondary_color_hex" value="#764ba2" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                            </div>
                            
                            <div class="color-picker-group">
                                <label for="accent_color">Accent Color *</label>
                                <div class="color-input-wrapper">
                                    <input type="color" id="accent_color" name="accent_color" value="#f093fb" required>
                                    <input type="text" id="accent_color_hex" value="#f093fb" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                            </div>
                        </div>
                        
                        <div class="color-picker-grid">
                            <div class="color-picker-group">
                                <label for="gradient_start">Gradient Start *</label>
                                <div class="color-input-wrapper">
                                    <input type="color" id="gradient_start" name="gradient_start" value="#667eea" required>
                                    <input type="text" id="gradient_start_hex" value="#667eea" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                            </div>
                            
                            <div class="color-picker-group">
                                <label for="gradient_end">Gradient End *</label>
                                <div class="color-input-wrapper">
                                    <input type="color" id="gradient_end" name="gradient_end" value="#764ba2" required>
                                    <input type="text" id="gradient_end_hex" value="#764ba2" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div id="theme-preview" style="height: 100px; border-radius: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 20px 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                Theme Preview
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Theme
                        </button>
                    </form>
                </div>
            </div>
            
            <script>
            // Live preview update
            function updatePreview() {
                const start = document.getElementById('gradient_start').value;
                const end = document.getElementById('gradient_end').value;
                const preview = document.getElementById('theme-preview');
                preview.style.background = `linear-gradient(135deg, ${start} 0%, ${end} 100%)`;
            }
            
            // Sync color picker with hex input
            function syncColorInputs() {
                const colorPairs = [
                    ['primary_color', 'primary_color_hex'],
                    ['secondary_color', 'secondary_color_hex'],
                    ['accent_color', 'accent_color_hex'],
                    ['gradient_start', 'gradient_start_hex'],
                    ['gradient_end', 'gradient_end_hex']
                ];
                
                colorPairs.forEach(([colorId, hexId]) => {
                    const colorInput = document.getElementById(colorId);
                    const hexInput = document.getElementById(hexId);
                    
                    // Update hex when color picker changes
                    colorInput.addEventListener('input', function() {
                        hexInput.value = this.value.toUpperCase();
                        updatePreview();
                    });
                    
                    // Update color picker when hex input changes
                    hexInput.addEventListener('input', function() {
                        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                            colorInput.value = this.value;
                            updatePreview();
                        }
                    });
                    
                    // Format hex input
                    hexInput.addEventListener('blur', function() {
                        if (this.value && !this.value.startsWith('#')) {
                            this.value = '#' + this.value;
                        }
                        this.value = this.value.toUpperCase();
                    });
                });
            }
            
            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                syncColorInputs();
                updatePreview();
            });
            </script>
            
            <?php else: ?>
            <!-- Themes Grid -->
            <div class="dashboard-grid">
                <?php foreach ($themes as $theme): ?>
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>
                            <span class="theme-preview" style="background: linear-gradient(135deg, <?php echo $theme['gradient_start']; ?> 0%, <?php echo $theme['gradient_end']; ?> 100%)"></span>
                            <?php echo htmlspecialchars($theme['theme_name']); ?>
                            <?php if ($theme['is_active']): ?>
                                <span class="badge">Active</span>
                            <?php endif; ?>
                        </h3>
                        <?php if (!$theme['is_active']): ?>
                        <button class="btn btn-sm btn-primary" onclick="setActiveTheme(<?php echo $theme['id']; ?>)">
                            Activate
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <strong>Primary:</strong> <span style="color: <?php echo $theme['primary_color']; ?>"><?php echo $theme['primary_color']; ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Secondary:</strong> <span style="color: <?php echo $theme['secondary_color']; ?>"><?php echo $theme['secondary_color']; ?></span>
                        </div>
                        <div class="info-item">
                            <strong>Accent:</strong> <span style="color: <?php echo $theme['accent_color']; ?>"><?php echo $theme['accent_color']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
    function setActiveTheme(themeId) {
        fetch('../theme_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `theme_id=${themeId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to change theme: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error changing theme');
        });
    }
    </script>
</body>
</html>
