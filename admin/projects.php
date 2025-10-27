<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$action = $_GET['action'] ?? 'list';
$projectId = $_GET['id'] ?? null;
$project = null;

// Check if redirected after successful save
if (isset($_GET['saved'])) {
    switch ($_GET['saved']) {
        case 'add':
            $message = '<div class="notification success"><i class="fas fa-check"></i> Project added successfully!</div>';
            break;
        case 'edit':
            $message = '<div class="notification success"><i class="fas fa-check"></i> Project updated successfully!</div>';
            break;
        case 'delete':
            $message = '<div class="notification success"><i class="fas fa-check"></i> Project deleted successfully!</div>';
            break;
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $data = [
            'title' => sanitizeInput($_POST['title'] ?? ''),
            'description' => sanitizeInput($_POST['description'] ?? ''),
            'image' => sanitizeInput($_POST['image'] ?? ''),
            'project_url' => sanitizeInput($_POST['project_url'] ?? ''),
            'github_url' => sanitizeInput($_POST['github_url'] ?? ''),
            'download_url' => sanitizeInput($_POST['download_url'] ?? ''),
            'technologies' => json_encode(array_filter(explode(',', sanitizeInput($_POST['technologies'] ?? '')))),
            'job_type' => sanitizeInput($_POST['job_type'] ?? 'personal'),
            'client_name' => sanitizeInput($_POST['client_name'] ?? ''),
            'duration' => sanitizeInput($_POST['duration'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if ($action === 'add') {
            if (addProject($data)) {
                header('Location: projects.php?saved=add');
                exit();
            } else {
                $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to add project.</div>';
            }
        } elseif ($action === 'edit' && $projectId) {
            if (updateProject($projectId, $data)) {
                header('Location: projects.php?saved=edit');
                exit();
            } else {
                $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to update project.</div>';
            }
        }
    }
} elseif ($action === 'delete' && $projectId) {
    if (deleteProject($projectId)) {
        header('Location: projects.php?saved=delete');
        exit();
    } else {
        $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to delete project.</div>';
    }
    $action = 'list';
}

// Get project for editing
if ($action === 'edit' && $projectId) {
    $project = getProject($projectId);
    if (!$project) {
        $message = '<div class="notification error"><i class="fas fa-times"></i> Project not found.</div>';
        $action = 'list';
    }
}

$projects = getProjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-project-diagram"></i> Projects</h1>
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Project
                    </a>
                    <?php else: ?>
                    <a href="projects.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $message; ?>

            <?php if ($action === 'add' || $action === 'edit'): ?>
            <!-- Add/Edit Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><?php echo $action === 'add' ? 'Add New Project' : 'Edit Project'; ?></h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="title">Project Title *</label>
                                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="job_type">Job Type</label>
                                <select id="job_type" name="job_type">
                                    <option value="personal" <?php echo ($project['job_type'] ?? '') === 'personal' ? 'selected' : ''; ?>>Personal</option>
                                    <option value="freelance" <?php echo ($project['job_type'] ?? '') === 'freelance' ? 'selected' : ''; ?>>Freelance</option>
                                    <option value="fulltime" <?php echo ($project['job_type'] ?? '') === 'fulltime' ? 'selected' : ''; ?>>Full-time</option>
                                    <option value="internship" <?php echo ($project['job_type'] ?? '') === 'internship' ? 'selected' : ''; ?>>Internship</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="client_name">Client Name</label>
                                <input type="text" id="client_name" name="client_name" value="<?php echo htmlspecialchars($project['client_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($project['duration'] ?? ''); ?>" placeholder="e.g., 3 months">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($project['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="technologies">Technologies (comma-separated)</label>
                            <input type="text" id="technologies" name="technologies" 
                                   value="<?php echo htmlspecialchars(implode(', ', json_decode($project['technologies'] ?? '[]', true) ?: [])); ?>" 
                                   placeholder="e.g., Flutter, Firebase, REST API">
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="project_url">Project URL</label>
                                <input type="url" id="project_url" name="project_url" value="<?php echo htmlspecialchars($project['project_url'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="github_url">GitHub URL</label>
                                <input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($project['github_url'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="download_url">Download URL</label>
                                <input type="url" id="download_url" name="download_url" value="<?php echo htmlspecialchars($project['download_url'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="image">Project Image</label>
                                <input type="url" id="image" name="image" value="<?php echo htmlspecialchars($project['image'] ?? ''); ?>" placeholder="Image URL (or upload below)">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Upload Project Image</label>
                            <div class="image-upload-area" onclick="document.getElementById('projectImageInput').click()">
                                <div class="upload-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="upload-text">Click to upload project image</div>
                                <div class="upload-hint">JPG, PNG, GIF up to 5MB (recommended: 800x600px)</div>
                                <input type="file" id="projectImageInput" accept="image/*" style="display: none;">
                                <div class="upload-progress" style="display: none;">
                                    <div class="upload-progress-bar"></div>
                                </div>
                            </div>
                            <?php if (!empty($project['image'])): ?>
                            <div style="margin-top: 15px;">
                                <strong>Current Image:</strong><br>
                                <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="Project Image" class="image-preview">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="<?php echo htmlspecialchars($project['sort_order'] ?? '0'); ?>" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $action === 'add' ? 'Add Project' : 'Update Project'; ?>
                        </button>
                    </form>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Projects List -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Job Type</th>
                            <th>Client</th>
                            <th>Technologies</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($projects): ?>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($project['title']); ?></strong></td>
                                <td>
                                    <span class="job-type <?php echo $project['job_type']; ?>">
                                        <?php echo ucfirst($project['job_type']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($project['client_name'] ?: 'N/A'); ?></td>
                                <td>
                                    <?php 
                                    $technologies = json_decode($project['technologies'], true) ?: [];
                                    echo implode(', ', array_slice($technologies, 0, 3));
                                    if (count($technologies) > 3) echo '...';
                                    ?>
                                </td>
                                <td>
                                    <span class="status <?php echo $project['status']; ?>">
                                        <?php echo ucfirst($project['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?action=edit&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-outline">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?action=delete&id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">No projects found. <a href="?action=add">Add your first project</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
    // Project image upload functionality
    document.getElementById('projectImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'project');
        
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
                // Update the image URL field
                document.getElementById('image').value = data.url;
                
                // Show success message
                const notification = document.createElement('div');
                notification.className = 'notification success';
                notification.innerHTML = '<i class="fas fa-check"></i> Project image uploaded successfully!';
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
