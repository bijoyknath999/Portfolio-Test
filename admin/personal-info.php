<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$personalInfo = getPersonalInfo();

// Check if redirected after successful save
if (isset($_GET['saved']) && $_GET['saved'] == '1') {
    $message = '<div class="notification success"><i class="fas fa-check"></i> Personal information updated successfully!</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'title' => sanitizeInput($_POST['title'] ?? ''),
        'description' => sanitizeInput($_POST['description'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'location' => sanitizeInput($_POST['location'] ?? ''),
        'availability_text' => sanitizeInput($_POST['availability_text'] ?? '')
    ];
    
    if (updatePersonalInfo($data)) {
        header('Location: personal-info.php?saved=1');
        exit();
    } else {
        $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to update personal information.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Info - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-user"></i> Personal Information</h1>
                <div class="header-actions">
                    <a href="../index.php" class="btn btn-outline" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Portfolio
                    </a>
                </div>
            </div>

            <?php echo $message; ?>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Update Personal Details</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($personalInfo['name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="title">Professional Title</label>
                                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($personalInfo['title'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($personalInfo['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($personalInfo['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($personalInfo['location'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Professional Description</label>
                            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($personalInfo['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="availability_text">Availability Text</label>
                            <textarea id="availability_text" name="availability_text" rows="2"><?php echo htmlspecialchars($personalInfo['availability_text'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Profile Image</label>
                            <div class="image-upload-area" onclick="document.getElementById('profileImageInput').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload profile image</div>
                                <div class="upload-hint">JPG, PNG, GIF up to 5MB</div>
                                <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
                                <div class="upload-progress" style="display: none;">
                                    <div class="upload-progress-bar"></div>
                                </div>
                            </div>
                            <?php if (!empty($personalInfo['profile_image'])): ?>
                            <div style="margin-top: 15px;">
                                <strong>Current Image:</strong><br>
                                <img src="../<?php echo htmlspecialchars($personalInfo['profile_image']); ?>" alt="Current Profile" class="image-preview">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Information
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script>
    // Image upload functionality
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'profile');
        
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
                notification.innerHTML = '<i class="fas fa-check"></i> Profile image uploaded successfully!';
                document.querySelector('.main-content').insertBefore(notification, document.querySelector('.dashboard-card'));
                
                // Refresh page to show new image
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
    </script>
</body>
</html>
