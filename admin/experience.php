<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$action = $_GET['action'] ?? 'list';
$expId = $_GET['id'] ?? null;
$exp = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add' || $action === 'edit') {
        $data = [
            'company_name' => sanitizeInput($_POST['company_name'] ?? ''),
            'position' => sanitizeInput($_POST['position'] ?? ''),
            'job_type' => sanitizeInput($_POST['job_type'] ?? 'fulltime'),
            'start_date' => sanitizeInput($_POST['start_date'] ?? ''),
            'end_date' => sanitizeInput($_POST['end_date'] ?? ''),
            'is_current' => isset($_POST['is_current']) ? 1 : 0,
            'description' => sanitizeInput($_POST['description'] ?? ''),
            'responsibilities' => json_encode(array_filter(explode("\n", sanitizeInput($_POST['responsibilities'] ?? '')))),
            'location' => sanitizeInput($_POST['location'] ?? ''),
            'company_url' => sanitizeInput($_POST['company_url'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if ($action === 'add') {
            if (addExperience($data)) {
                $message = '<div class="notification success"><i class="fas fa-check"></i> Experience added successfully!</div>';
                $action = 'list';
            } else {
                $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to add experience.</div>';
            }
        }
    }
}

$experience = getExperience();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Experience - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-briefcase"></i> Work Experience</h1>
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Experience
                    </a>
                    <?php else: ?>
                    <a href="experience.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $message; ?>

            <?php if ($action === 'add'): ?>
            <!-- Add Experience Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Add Work Experience</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="company_name">Company Name *</label>
                                <input type="text" id="company_name" name="company_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="position">Position *</label>
                                <input type="text" id="position" name="position" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="job_type">Job Type</label>
                                <select id="job_type" name="job_type">
                                    <option value="fulltime">Full-time</option>
                                    <option value="parttime">Part-time</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" id="location" name="location" placeholder="e.g., Dhaka, Bangladesh">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="start_date">Start Date *</label>
                                <input type="date" id="start_date" name="start_date" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="date" id="end_date" name="end_date">
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_current"> Currently working here
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="company_url">Company Website</label>
                                <input type="url" id="company_url" name="company_url">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Job Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Brief description of the role..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="responsibilities">Key Responsibilities (one per line)</label>
                            <textarea id="responsibilities" name="responsibilities" rows="5" placeholder="• Developed mobile applications&#10;• Led team of 3 developers&#10;• Implemented new features"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="0" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Experience
                        </button>
                    </form>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Experience List -->
            <div class="dashboard-card">
                <div class="card-content">
                    
                    <?php if ($experience): ?>
                        <?php foreach ($experience as $exp): ?>
                        <div class="info-item">
                            <strong><?php echo htmlspecialchars($exp['position']); ?></strong> at <?php echo htmlspecialchars($exp['company_name']); ?>
                            <br><small><?php echo formatDate($exp['start_date'], 'M Y'); ?> - <?php echo $exp['is_current'] ? 'Present' : formatDate($exp['end_date'], 'M Y'); ?></small>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-data">No experience entries found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
