<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$messagesCount = count(getContactMessages('new'));
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-user-cog"></i> Portfolio Admin</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="personal-info.php" class="nav-item <?php echo $currentPage === 'personal-info.php' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i> Personal Info
        </a>
        <a href="projects.php" class="nav-item <?php echo $currentPage === 'projects.php' ? 'active' : ''; ?>">
            <i class="fas fa-project-diagram"></i> Projects
        </a>
        <a href="experience.php" class="nav-item <?php echo $currentPage === 'experience.php' ? 'active' : ''; ?>">
            <i class="fas fa-briefcase"></i> Experience
        </a>
        <a href="education.php" class="nav-item <?php echo $currentPage === 'education.php' ? 'active' : ''; ?>">
            <i class="fas fa-graduation-cap"></i> Education
        </a>
        <a href="skills.php" class="nav-item <?php echo $currentPage === 'skills.php' ? 'active' : ''; ?>">
            <i class="fas fa-code"></i> Skills
        </a>
        <a href="themes.php" class="nav-item <?php echo $currentPage === 'themes.php' ? 'active' : ''; ?>">
            <i class="fas fa-palette"></i> Color Themes
        </a>
        <a href="seo.php" class="nav-item <?php echo $currentPage === 'seo.php' ? 'active' : ''; ?>">
            <i class="fas fa-search"></i> SEO Settings
        </a>
        <a href="messages.php" class="nav-item <?php echo $currentPage === 'messages.php' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i> Messages
            <?php if ($messagesCount > 0): ?>
            <span class="badge"><?php echo $messagesCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="visitors.php" class="nav-item <?php echo $currentPage === 'visitors.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Visitors
        </a>
        <a href="settings.php" class="nav-item <?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </nav>
</aside>
