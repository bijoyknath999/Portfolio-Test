<?php
require_once 'includes/functions.php';

// Check maintenance mode
if (getSiteSetting('maintenance_mode') === 'true') {
    include 'maintenance.php';
    exit();
}

// Track visitor
trackVisitor();

// Get dynamic data
$personalInfo = getPersonalInfo();
$projects = getProjects();
$experience = getExperience();
$education = getEducation();
$skills = getSkills();
$socialLinks = getSocialLinks();
$colorThemes = getColorThemes();
$activeTheme = getActiveTheme();
$seoSettings = getSEOSettings('home');

// Get typing roles for hero animation
$typingRolesJson = getSiteSetting('typing_roles');
$typingRoles = $typingRolesJson ? $typingRolesJson : json_encode([
    'Full-Stack Developer',
    'Mobile App Developer',
    'UI/UX Enthusiast',
    'Problem Solver'
]);

// Set default values if no data exists (now dynamic from admin settings)
$personalInfo = $personalInfo ?: getDefaultPersonalInfo();

// Debug: Ensure resume_file is available
if (!isset($personalInfo['resume_file']) || empty($personalInfo['resume_file'])) {
    // Check if there's an old resume file in root
    if (file_exists(__DIR__ . '/Resume of Bijoy Kumar Nath.pdf')) {
        $personalInfo['resume_file'] = 'Resume of Bijoy Kumar Nath.pdf';
    }
}

$seoSettings = $seoSettings ?: [
    'meta_title' => $personalInfo['name'] . ' - ' . $personalInfo['title'],
    'meta_description' => $personalInfo['description'],
    'meta_keywords' => 'full-stack developer, mobile app developer, flutter, laravel, android',
    'og_title' => $personalInfo['name'] . ' - Portfolio',
    'og_description' => $personalInfo['description'],
    'og_image' => $personalInfo['profile_image']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo htmlspecialchars($seoSettings['meta_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seoSettings['meta_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoSettings['meta_keywords']); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($personalInfo['name']); ?>">
    
    <?php 
    // Get dynamic site URL
    $siteUrl = getSiteSetting('site_url') ?: ((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    ?>
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($seoSettings['og_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoSettings['og_description']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($seoSettings['og_image']); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo rtrim($siteUrl, '/') . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seoSettings['og_title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seoSettings['og_description']); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($seoSettings['og_image']); ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo rtrim($siteUrl, '/') . $currentPath; ?>">
    
    <!-- Dynamic Favicon -->
    <?php renderFavicon(); ?>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dynamic Theme CSS -->
    <?php if ($activeTheme): ?>
    <style>
        :root {
            --primary-color: <?php echo $activeTheme['primary_color']; ?>;
            --secondary-color: <?php echo $activeTheme['secondary_color']; ?>;
            --accent-color: <?php echo $activeTheme['accent_color']; ?>;
            --gradient: linear-gradient(135deg, <?php echo $activeTheme['gradient_start']; ?> 0%, <?php echo $activeTheme['gradient_end']; ?> 100%);
        }
    </style>
    <?php endif; ?>
    
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "<?php echo htmlspecialchars($personalInfo['name']); ?>",
        "jobTitle": "<?php echo htmlspecialchars($personalInfo['title']); ?>",
        "description": "<?php echo htmlspecialchars($personalInfo['description']); ?>",
        "email": "<?php echo htmlspecialchars($personalInfo['email']); ?>",
        "telephone": "<?php echo htmlspecialchars($personalInfo['phone']); ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo htmlspecialchars($personalInfo['location']); ?>"
        },
        "image": "<?php echo htmlspecialchars($personalInfo['profile_image']); ?>",
        "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>",
        "sameAs": [
            <?php 
            $socialUrls = array_map(function($link) {
                return '"' . htmlspecialchars($link['url']) . '"';
            }, $socialLinks);
            echo implode(',', $socialUrls);
            ?>
        ]
    }
    </script>
</head>
<body>
    <!-- Color Theme Selector -->
    <?php if (getSiteSetting('show_theme_selector') !== 'false'): ?>
    <div class="theme-selector">
        <?php foreach ($colorThemes as $index => $theme): ?>
        <button class="theme-btn <?php echo $theme['is_active'] ? 'active' : ''; ?>" 
                onclick="changeTheme(<?php echo $theme['id']; ?>)" 
                data-theme-id="<?php echo $theme['id']; ?>"
                title="<?php echo htmlspecialchars($theme['theme_name']); ?>">
            <span class="color-preview" style="background: linear-gradient(135deg, <?php echo $theme['gradient_start']; ?> 0%, <?php echo $theme['gradient_end']; ?> 100%)"></span>
        </button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="logo.png" alt="<?php echo htmlspecialchars($personalInfo['name'] ?? 'Portfolio'); ?>" class="logo-img">
                <span class="logo-text"><?php echo htmlspecialchars($personalInfo['name'] ?? 'Portfolio'); ?></span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#home" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#projects" class="nav-link">Projects</a>
                </li>
                <li class="nav-item">
                    <a href="#experience" class="nav-link">Experience</a>
                </li>
                <li class="nav-item">
                    <a href="#education" class="nav-link">Education</a>
                </li>
                <li class="nav-item">
                    <a href="#skills" class="nav-link">Skills</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Contact</a>
                </li>
                <?php if (!empty($personalInfo['resume_file'])): ?>
                <li class="nav-item">
                    <a href="<?php echo htmlspecialchars($personalInfo['resume_file']); ?>" class="nav-link cv-link" target="_blank" data-external="true">
                        <i class="fas fa-file-pdf"></i> CV
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Hi, I'm <span class="highlight"><?php echo htmlspecialchars($personalInfo['name']); ?></span>
                    </h1>
                    <h2 class="hero-subtitle" data-roles='<?php echo $typingRoles; ?>'><?php echo htmlspecialchars($personalInfo['title']); ?></h2>
                    <p class="hero-description">
                        <?php echo nl2br(htmlspecialchars($personalInfo['description'])); ?>
                        <?php if ($personalInfo['availability_text']): ?>
                        <br><strong><?php echo htmlspecialchars($personalInfo['availability_text']); ?></strong>
                        <?php endif; ?>
                    </p>
                    <div class="hero-buttons">
                        <a href="#projects" class="btn btn-primary">View My Work</a>
                        <a href="#contact" class="btn btn-secondary">Get In Touch</a>
                        <?php if (!empty($personalInfo['resume_file'])): ?>
                        <a href="<?php echo htmlspecialchars($personalInfo['resume_file']); ?>" class="btn btn-outline" download="<?php echo htmlspecialchars($personalInfo['name']); ?>_Resume.pdf" target="_blank">
                            <i class="fas fa-download"></i> Download CV
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="profile-card">
                        <img src="<?php echo htmlspecialchars($personalInfo['profile_image'] ?: 'bijoy.png'); ?>" alt="<?php echo htmlspecialchars($personalInfo['name']); ?> Profile Picture" class="profile-img">
                        <div class="profile-info">
                            <h3><?php echo htmlspecialchars($personalInfo['name']); ?></h3>
                            <p><?php echo htmlspecialchars($personalInfo['title']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="projects">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Projects</h2>
                <p class="section-subtitle">Here are some of my recent works</p>
            </div>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <div class="project-image">
                        <img src="<?php echo htmlspecialchars($project['image'] ?: 'https://placehold.co/400x250/667eea/ffffff?text=' . urlencode($project['title'])); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>">
                        <div class="project-overlay">
                            <div class="project-links">
                                <?php if ($project['github_url']): ?>
                                <a href="<?php echo htmlspecialchars($project['github_url']); ?>" class="project-link" target="_blank"><i class="fab fa-github"></i></a>
                                <?php endif; ?>
                                <?php if ($project['project_url']): ?>
                                <a href="<?php echo htmlspecialchars($project['project_url']); ?>" class="project-link" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                                <?php endif; ?>
                                <?php if ($project['download_url']): ?>
                                <a href="<?php echo htmlspecialchars($project['download_url']); ?>" class="project-link" target="_blank"><i class="fas fa-download"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="project-tech">
                            <?php 
                            $technologies = json_decode($project['technologies'], true) ?: [];
                            foreach ($technologies as $tech): ?>
                            <span class="tech-tag"><?php echo htmlspecialchars($tech); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-meta">
                            <span class="job-type <?php echo $project['job_type']; ?>">
                                <?php echo $project['client_name'] ? 'Client: ' . htmlspecialchars($project['client_name']) : ucfirst($project['job_type']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="experience">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Work Experience</h2>
                <p class="section-subtitle">My professional journey</p>
            </div>
            <div class="timeline">
                <?php foreach ($experience as $exp): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="experience-card">
                            <div class="experience-header">
                                <h3 class="company-name"><?php echo htmlspecialchars($exp['company_name']); ?></h3>
                                <span class="job-type <?php echo $exp['job_type']; ?>"><?php echo ucfirst($exp['job_type']); ?></span>
                            </div>
                            <h4 class="role-title"><?php echo htmlspecialchars($exp['position']); ?></h4>
                            <p class="duration">
                                <?php 
                                echo formatDate($exp['start_date'], 'M Y');
                                echo ' - ';
                                echo $exp['is_current'] ? 'Present' : formatDate($exp['end_date'], 'M Y');
                                ?>
                            </p>
                            <?php if ($exp['description']): ?>
                            <p class="experience-description"><?php echo nl2br(htmlspecialchars($exp['description'])); ?></p>
                            <?php endif; ?>
                            <?php 
                            $responsibilities = json_decode($exp['responsibilities'], true);
                            if ($responsibilities): ?>
                            <ul class="responsibilities">
                                <?php foreach ($responsibilities as $responsibility): ?>
                                <li><?php echo htmlspecialchars($responsibility); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Education Section -->
    <section id="education" class="education">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Education</h2>
                <p class="section-subtitle">My academic background and qualifications</p>
            </div>
            <div class="education-grid">
                <?php foreach ($education as $edu): ?>
                <div class="education-card">
                    <div class="education-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="education-content">
                        <h3 class="degree-title"><?php echo htmlspecialchars($edu['degree_title']); ?></h3>
                        <h4 class="institution-name"><?php echo htmlspecialchars($edu['institution_name']); ?></h4>
                        <p class="education-duration">
                            <?php 
                            echo $edu['start_year'];
                            echo ' - ';
                            echo $edu['is_current'] ? 'Present' : $edu['end_year'];
                            ?>
                        </p>
                        <?php if ($edu['description']): ?>
                        <p class="education-description"><?php echo nl2br(htmlspecialchars($edu['description'])); ?></p>
                        <?php endif; ?>
                        <?php 
                        $highlights = json_decode($edu['highlights'], true);
                        if ($highlights): ?>
                        <div class="education-highlights">
                            <?php foreach ($highlights as $highlight): ?>
                            <span class="highlight-tag"><?php echo htmlspecialchars($highlight); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Technical Skills</h2>
                <p class="section-subtitle">Technologies I work with</p>
            </div>
            <div class="skills-grid">
                <?php foreach ($skills as $skillCategory): ?>
                <div class="skill-category">
                    <h3 class="category-title"><?php echo htmlspecialchars($skillCategory['category_name']); ?></h3>
                    <div class="skills-list">
                        <?php 
                        $skillsList = json_decode($skillCategory['skills'], true) ?: [];
                        foreach ($skillsList as $skill): ?>
                        <div class="skill-item">
                            <?php if ($skillCategory['custom_icon']): ?>
                            <img src="<?php echo htmlspecialchars($skillCategory['custom_icon']); ?>" alt="<?php echo htmlspecialchars($skill['name']); ?>" class="skill-custom-icon">
                            <?php else: ?>
                            <i class="<?php echo htmlspecialchars($skill['icon'] ?: 'fas fa-code'); ?>"></i>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($skill['name']); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <?php if (getSiteSetting('contact_form_enabled') !== 'false'): ?>
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo htmlspecialchars(getSiteSetting('contact_form_title') ?: 'Get In Touch'); ?></h2>
                <p class="section-subtitle"><?php echo htmlspecialchars(getSiteSetting('contact_form_subtitle') ?: 'Let\'s work together on your next project'); ?></p>
            </div>
            <div class="contact-grid">
                <div class="contact-info-card">
                    <h3>Contact Information</h3>
                    <div class="contact-items">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email</strong>
                                <p><?php echo htmlspecialchars($personalInfo['email']); ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>Phone</strong>
                                <p><?php echo htmlspecialchars($personalInfo['phone']); ?></p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Location</strong>
                                <p><?php echo htmlspecialchars($personalInfo['location']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-card">
                    <h3>Send Message</h3>
                    <form id="contactForm" method="POST" action="contact_handler.php">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
            <div class="social-links">
                <?php foreach ($socialLinks as $social): ?>
                <a href="<?php echo htmlspecialchars($social['url']); ?>" class="social-link" target="_blank">
                    <?php if ($social['custom_icon']): ?>
                    <img src="<?php echo htmlspecialchars($social['custom_icon']); ?>" alt="<?php echo htmlspecialchars($social['platform_name']); ?>" class="social-custom-icon">
                    <?php else: ?>
                    <i class="<?php echo htmlspecialchars($social['icon_class']); ?>"></i>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($personalInfo['name']); ?>. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
    
    <!-- Google Analytics -->
    <?php 
    $gaId = getSiteSetting('google_analytics_id');
    if ($gaId): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $gaId; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $gaId; ?>');
    </script>
    <?php endif; ?>
</body>
</html>
