<?php
require_once '../includes/functions.php';
requireLogin();

$personalInfo = getPersonalInfo();
$projectsCount = count(getProjects());
$experienceCount = count(getExperience());
$messagesCount = count(getContactMessages('new'));
$totalVisitors = getTotalVisitors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Portfolio</title>
    <?php renderFavicon(true); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <a href="../index.php" class="btn btn-outline" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Portfolio
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon projects">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $projectsCount; ?></h3>
                        <p>Projects</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon experience">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $experienceCount; ?></h3>
                        <p>Work Experience</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon messages">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $messagesCount; ?></h3>
                        <p>New Messages</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon visitors">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($totalVisitors); ?></h3>
                        <p>Total Visitors</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                        <a href="personal-info.php" class="btn btn-sm">Edit</a>
                    </div>
                    <div class="card-content">
                        <div class="info-item">
                            <strong>Name:</strong> <?php echo htmlspecialchars($personalInfo['name'] ?? 'Not set'); ?>
                        </div>
                        <div class="info-item">
                            <strong>Title:</strong> <?php echo htmlspecialchars($personalInfo['title'] ?? 'Not set'); ?>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong> <?php echo htmlspecialchars($personalInfo['email'] ?? 'Not set'); ?>
                        </div>
                        <div class="info-item">
                            <strong>Phone:</strong> <?php echo htmlspecialchars($personalInfo['phone'] ?? 'Not set'); ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-envelope"></i> Recent Messages</h3>
                        <a href="messages.php" class="btn btn-sm">View All</a>
                    </div>
                    <div class="card-content">
                        <?php 
                        $recentMessages = array_slice(getContactMessages('new'), 0, 3);
                        if ($recentMessages): ?>
                            <?php foreach ($recentMessages as $message): ?>
                            <div class="message-item">
                                <strong><?php echo htmlspecialchars($message['name']); ?></strong>
                                <span class="message-subject"><?php echo htmlspecialchars($message['subject']); ?></span>
                                <span class="message-date"><?php echo date('M j, Y', strtotime($message['created_at'])); ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-data">No new messages</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-project-diagram"></i> Recent Projects</h3>
                        <a href="projects.php" class="btn btn-sm">Manage</a>
                    </div>
                    <div class="card-content">
                        <?php 
                        $recentProjects = array_slice(getProjects(), 0, 3);
                        if ($recentProjects): ?>
                            <?php foreach ($recentProjects as $project): ?>
                            <div class="project-item">
                                <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                                <span class="project-type"><?php echo ucfirst($project['job_type']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-data">No projects added yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-line"></i> Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions">
                            <a href="projects.php?action=add" class="quick-action">
                                <i class="fas fa-plus"></i> Add Project
                            </a>
                            <a href="experience.php?action=add" class="quick-action">
                                <i class="fas fa-plus"></i> Add Experience
                            </a>
                            <a href="themes.php?action=add" class="quick-action">
                                <i class="fas fa-plus"></i> Add Theme
                            </a>
                            <a href="seo.php" class="quick-action">
                                <i class="fas fa-search"></i> SEO Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
