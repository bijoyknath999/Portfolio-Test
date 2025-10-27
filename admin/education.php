<?php
require_once '../includes/functions.php';
requireLogin();

$message = '';
$action = $_GET['action'] ?? 'list';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        // Process highlights input
        $highlightsInput = sanitizeInput($_POST['highlights'] ?? '');
        $highlightsArray = array_filter(explode(',', $highlightsInput));
        
        $data = [
            'degree_title' => sanitizeInput($_POST['degree_title'] ?? ''),
            'institution_name' => sanitizeInput($_POST['institution_name'] ?? ''),
            'start_year' => intval($_POST['start_year'] ?? date('Y')),
            'end_year' => intval($_POST['end_year'] ?? date('Y')),
            'is_current' => isset($_POST['is_current']) ? 1 : 0,
            'description' => sanitizeInput($_POST['description'] ?? ''),
            'highlights' => json_encode($highlightsArray),
            'grade' => sanitizeInput($_POST['grade'] ?? ''),
            'location' => sanitizeInput($_POST['location'] ?? ''),
            'institution_url' => sanitizeInput($_POST['institution_url'] ?? ''),
            'sort_order' => intval($_POST['sort_order'] ?? 0)
        ];
        
        if (addEducation($data)) {
            $message = '<div class="notification success"><i class="fas fa-check"></i> Education added successfully!</div>';
            $action = 'list';
        } else {
            $message = '<div class="notification error"><i class="fas fa-times"></i> Failed to add education.</div>';
        }
    }
}

$education = getEducation();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Education - Portfolio Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-graduation-cap"></i> Education</h1>
                <div class="header-actions">
                    <?php if ($action === 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Education
                    </a>
                    <?php else: ?>
                    <a href="education.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $message; ?>

            <?php if ($action === 'add'): ?>
            <!-- Add Education Form -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Add Education</h3>
                </div>
                <div class="card-content">
                    <form method="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="degree_title">Degree/Certificate Title *</label>
                                <input type="text" id="degree_title" name="degree_title" required placeholder="e.g., Bachelor of Computer Science">
                            </div>
                            
                            <div class="form-group">
                                <label for="institution_name">Institution Name *</label>
                                <input type="text" id="institution_name" name="institution_name" required placeholder="e.g., Adamas University">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="start_year">Start Year *</label>
                                <input type="number" id="start_year" name="start_year" required min="1990" max="<?php echo date('Y') + 10; ?>" value="<?php echo date('Y'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="end_year">End Year</label>
                                <input type="number" id="end_year" name="end_year" min="1990" max="<?php echo date('Y') + 10; ?>" value="<?php echo date('Y'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_current"> Currently studying
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="grade">Grade/GPA</label>
                                <input type="text" id="grade" name="grade" placeholder="e.g., 3.8/4.0, First Class">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" placeholder="e.g., Kolkata, India">
                        </div>
                        
                        <div class="form-group">
                            <label for="institution_url">Institution Website</label>
                            <input type="url" id="institution_url" name="institution_url" placeholder="https://university.edu">
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Brief description of your studies..."></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="highlights">Key Highlights (comma-separated)</label>
                            <input type="text" id="highlights" name="highlights" placeholder="Computer Science, Software Engineering, Programming">
                        </div>
                        
                        <div class="form-group">
                            <label for="sort_order">Sort Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="0" min="0">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Education
                        </button>
                    </form>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Education List -->
            <div class="dashboard-card">
                <div class="card-content">
                    
                    <?php if ($education): ?>
                        <?php foreach ($education as $edu): ?>
                        <div class="info-item">
                            <strong><?php echo htmlspecialchars($edu['degree_title']); ?></strong> from <?php echo htmlspecialchars($edu['institution_name']); ?>
                            <br><small><?php echo $edu['start_year']; ?> - <?php echo $edu['is_current'] ? 'Present' : $edu['end_year']; ?></small>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-data">No education entries found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
