<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        // Process skills input
        $skillsInput = sanitizeInput($_POST['skills'] ?? '');
        $skillsArray = array_filter(explode("\n", $skillsInput));
        $skillsJson = [];
        
        foreach ($skillsArray as $skill) {
            $skillsJson[] = [
                'name' => trim($skill),
                'icon' => 'fas fa-code' // Default icon
            ];
        }
        
        $data = [
            'category_name' => sanitizeInput($_POST['category_name'] ?? ''),
            'skills' => json_encode($skillsJson),
            'icon_class' => sanitizeInput($_POST['icon_class'] ?? ''),
            'custom_icon' => sanitizeInput($_POST['custom_icon'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if (addSkill($data)) {
            $message = '<div class="notification success"><i class="fas fa-check"></i> Skill category added successfully!</div>';
            $action = 'list';
        } else {
            $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to add skill category.</div>';
        }
    }
}

$skills = getSkills();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-code"></i> Technical Skills</h1>
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Skill Category
                    </a>
                    <?php else: ?>
                    <a href="skills.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Skills
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $message; ?>

            <?php if ($action === 'add'): ?>
            <!-- Add Skills Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Add Skill Category</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-group">
                            <label for="category_name">Category Name *</label>
                            <input type="text" id="category_name" name="category_name" required placeholder="e.g., Mobile Development">
                        </div>
                        
                        <div class="form-group">
                            <label for="skills">Skills (one per line) *</label>
                            <textarea id="skills" name="skills" rows="6" required placeholder="Flutter&#10;Android&#10;iOS&#10;React Native"></textarea>
                            <small>Enter each skill on a new line</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon_class">Category Icon Class</label>
                            <input type="text" id="icon_class" name="icon_class" placeholder="fas fa-mobile-alt" value="fas fa-code">
                            <small>Font Awesome icon class (optional)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="custom_icon">Custom Icon URL</label>
                            <input type="url" id="custom_icon" name="custom_icon" placeholder="https://example.com/icon.png">
                            <small>Custom icon URL (overrides icon class)</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Upload Category Icon</label>
                            <div class="image-upload-area" onclick="document.getElementById('skillIconInput').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-upload"></i>
                                </div>
                                <div class="upload-text">Click to upload category icon</div>
                                <div class="upload-hint">PNG, SVG, JPG up to 2MB (recommended: 64x64px)</div>
                                <input type="file" id="skillIconInput" accept="image/*" style="display: none;">
                                <div class="upload-progress" style="display: none;">
                                    <div class="upload-progress-bar"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="0" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Skill Category
                        </button>
                    </form>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Skills List -->
            <div class="dashboard-card">
                <div class="card-content">
                    
                    <?php if ($skills): ?>
                        <?php foreach ($skills as $skillCategory): ?>
                        <div class="info-item">
                            <strong><?php echo htmlspecialchars($skillCategory['category_name']); ?></strong>
                            <br><small>
                                <?php 
                                $skillsList = json_decode($skillCategory['skills'], true) ?: [];
                                $skillNames = array_map(function($skill) { return $skill['name']; }, $skillsList);
                                echo implode(', ', array_slice($skillNames, 0, 5));
                                if (count($skillNames) > 5) echo '...';
                                ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-data">No skill categories found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
    // Skills icon upload functionality
    document.getElementById('skillIconInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'skill_icon');
        
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
                // Update the custom icon URL field
                document.getElementById('custom_icon').value = data.url;
                
                // Show success message
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = '<i class="fas fa-check"></i> Skill icon uploaded successfully!';
                document.querySelector('.main-content').insertBefore(notification, document.querySelector('.dashboard-card'));
                
                // Auto-remove notification after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
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
    </script>
</body>
</html>
