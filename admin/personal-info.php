<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$personalInfo = getPersonalInfo();

// Get current typing roles
$typingRolesJson = getSiteSetting('typing_roles');
$typingRoles = $typingRolesJson ? json_decode($typingRolesJson, true) : [
    'Full-Stack Developer',
    'Mobile App Developer',
    'UI/UX Enthusiast',
    'Problem Solver'
];

// Check if redirected after successful save
if (isset($_GET['saved']) && $_GET['saved'] == '1') {
    $message = '<div class="notification success"><i class="fas fa-check"></i> Personal information updated successfully!</div>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle personal info save (includes typing roles now)
    $data = [
        'name' => sanitizeInput($_POST['name'] ?? ''),
        'title' => sanitizeInput($_POST['title'] ?? ''),
        'description' => sanitizeInput($_POST['description'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? ''),
        'phone' => sanitizeInput($_POST['phone'] ?? ''),
        'location' => sanitizeInput($_POST['location'] ?? ''),
        'availability_text' => sanitizeInput($_POST['availability_text'] ?? '')
    ];
    
    // Handle typing roles
    $roles = [];
    if (isset($_POST['typing_roles']) && is_array($_POST['typing_roles'])) {
        foreach ($_POST['typing_roles'] as $role) {
            $cleaned = sanitizeInput($role);
            if (!empty($cleaned)) {
                $roles[] = $cleaned;
            }
        }
    }
    
    $success = true;
    
    // Update personal info
    if (!updatePersonalInfo($data)) {
        $success = false;
    }
    
    // Update typing roles
    if (count($roles) > 0) {
        $rolesJson = json_encode($roles);
        if (!updateSiteSetting('typing_roles', $rolesJson)) {
            $success = false;
        } else {
            $typingRoles = $roles;
        }
    }
    
    if ($success) {
        header('Location: personal-info.php?saved=1');
        exit();
    } else {
        $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to update information.</div>';
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
    <style>
        .btn-delete-role {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            border: none;
            border-radius: 6px;
            color: white;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(245, 101, 101, 0.25);
        }
        
        .btn-delete-role:hover {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
        }
        
        .btn-delete-role i {
            font-size: 0.85rem;
        }
        
        .role-item input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .role-item input:hover {
            border-color: #cbd5e0;
        }
    </style>
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
                        
                        <!-- Typing Roles Section -->
                        <div class="form-group" style="margin-top: 24px;">
                            <label style="font-size: 1.1rem; font-weight: 600; color: #2d3748; margin-bottom: 8px; display: block;">
                                <i class="fas fa-keyboard" style="color: #667eea;"></i> Hero Typing Animation
                            </label>
                            <p style="margin-bottom: 16px; color: #718096; font-size: 0.9rem;">
                                Roles displayed in the typing animation on your homepage
                            </p>
                            
                            <div id="rolesContainer">
                                <?php foreach ($typingRoles as $index => $role): ?>
                                <div class="role-item" style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                                    <input type="text" name="typing_roles[]" value="<?php echo htmlspecialchars($role); ?>" 
                                           placeholder="Enter role title" required
                                           style="flex: 1; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; background: white;">
                                    <button type="button" class="btn-delete-role" onclick="removeRole(this)" title="Remove role">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-outline" style="margin-top: 12px; padding: 8px 16px; font-size: 0.9rem;" onclick="addRole()">
                                <i class="fas fa-plus"></i> Add Role
                            </button>
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
                        
                        <div class="form-group">
                            <label>Resume / CV</label>
                            <div class="image-upload-area" onclick="document.getElementById('resumeFileInput').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="upload-text">Click to upload your resume/CV</div>
                                <div class="upload-hint">PDF up to 10MB</div>
                                <input type="file" id="resumeFileInput" accept=".pdf" style="display: none;">
                                <div class="upload-progress-resume" style="display: none;">
                                    <div class="upload-progress-bar-resume"></div>
                                </div>
                            </div>
                            <?php if (!empty($personalInfo['resume_file'])): ?>
                            <div style="margin-top: 15px;">
                                <strong>Current Resume:</strong><br>
                                <a href="../<?php echo htmlspecialchars($personalInfo['resume_file']); ?>" target="_blank" class="btn btn-outline btn-sm">
                                    <i class="fas fa-file-pdf"></i> View Current Resume
                                </a>
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
    
    // Resume upload functionality
    document.getElementById('resumeFileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'resume');
        
        const progressBar = document.querySelector('.upload-progress-bar-resume');
        const progress = document.querySelector('.upload-progress-resume');
        const uploadAreas = document.querySelectorAll('.image-upload-area');
        const resumeUploadArea = uploadAreas[1];
        
        progress.style.display = 'block';
        resumeUploadArea.style.pointerEvents = 'none';
        
        fetch('../upload_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = '<i class="fas fa-check"></i> Resume uploaded successfully!';
                document.querySelector('.main-content').insertBefore(notification, document.querySelector('.dashboard-card'));
                
                // Refresh page to show new resume
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
            resumeUploadArea.style.pointerEvents = 'auto';
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
    
    // Typing roles functions
    function addRole() {
        const container = document.getElementById('rolesContainer');
        const roleItem = document.createElement('div');
        roleItem.className = 'role-item';
        roleItem.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: center;';
        roleItem.innerHTML = `
            <input type="text" name="typing_roles[]" placeholder="Enter role title" required
                   style="flex: 1; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; background: white;">
            <button type="button" class="btn-delete-role" onclick="removeRole(this)" title="Remove role">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(roleItem);
    }

    function removeRole(btn) {
        const container = document.getElementById('rolesContainer');
        if (container.children.length > 1) {
            btn.closest('.role-item').remove();
        } else {
            alert('You must have at least one role!');
        }
    }
    </script>
</body>
</html>
