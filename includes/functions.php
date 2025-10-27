<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Portfolio Functions
 * All database operations and utility functions
 */

// Personal Info Functions
function getPersonalInfo() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM personal_info ORDER BY id DESC LIMIT 1");
    return $stmt->fetch();
}

function updatePersonalInfo($data) {
    $db = getDB();
    $sql = "UPDATE personal_info SET 
            name = :name, 
            title = :title, 
            description = :description, 
            email = :email, 
            phone = :phone, 
            location = :location, 
            availability_text = :availability_text,
            updated_at = NOW() 
            WHERE id = 1";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Projects Functions
function getProjects($status = 'active') {
    $db = getDB();
    $sql = "SELECT * FROM projects WHERE status = :status ORDER BY sort_order ASC, created_at DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

function getProject($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function addProject($data) {
    $db = getDB();
    $sql = "INSERT INTO projects (title, description, image, project_url, github_url, download_url, technologies, job_type, client_name, duration, sort_order) 
            VALUES (:title, :description, :image, :project_url, :github_url, :download_url, :technologies, :job_type, :client_name, :duration, :sort_order)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

function updateProject($id, $data) {
    $db = getDB();
    $sql = "UPDATE projects SET 
            title = :title, 
            description = :description, 
            image = :image, 
            project_url = :project_url, 
            github_url = :github_url, 
            download_url = :download_url, 
            technologies = :technologies, 
            job_type = :job_type, 
            client_name = :client_name, 
            duration = :duration, 
            sort_order = :sort_order,
            updated_at = NOW() 
            WHERE id = :id";
    
    $data['id'] = $id;
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

function deleteProject($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

// Experience Functions
function getExperience($status = 'active') {
    $db = getDB();
    $sql = "SELECT * FROM experience WHERE status = :status ORDER BY sort_order ASC, start_date DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

function addExperience($data) {
    $db = getDB();
    $sql = "INSERT INTO experience (company_name, position, job_type, start_date, end_date, is_current, description, responsibilities, location, company_url, sort_order) 
            VALUES (:company_name, :position, :job_type, :start_date, :end_date, :is_current, :description, :responsibilities, :location, :company_url, :sort_order)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Education Functions
function getEducation($status = 'active') {
    $db = getDB();
    $sql = "SELECT * FROM education WHERE status = :status ORDER BY sort_order ASC, end_year DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

function addEducation($data) {
    $db = getDB();
    $sql = "INSERT INTO education (degree_title, institution_name, start_year, end_year, is_current, description, highlights, grade, location, institution_url, sort_order) 
            VALUES (:degree_title, :institution_name, :start_year, :end_year, :is_current, :description, :highlights, :grade, :location, :institution_url, :sort_order)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Skills Functions
function getSkills($status = 'active') {
    $db = getDB();
    $sql = "SELECT * FROM skills WHERE status = :status ORDER BY sort_order ASC, created_at ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

function addSkill($data) {
    $db = getDB();
    $sql = "INSERT INTO skills (category_name, skills, icon_class, custom_icon, sort_order) 
            VALUES (:category_name, :skills, :icon_class, :custom_icon, :sort_order)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Social Links Functions
function getSocialLinks($status = 'active') {
    $db = getDB();
    $sql = "SELECT * FROM social_links WHERE status = :status ORDER BY sort_order ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['status' => $status]);
    return $stmt->fetchAll();
}

function addSocialLink($data) {
    $db = getDB();
    $sql = "INSERT INTO social_links (platform_name, url, icon_class, custom_icon, sort_order) 
            VALUES (:platform_name, :url, :icon_class, :custom_icon, :sort_order)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Color Themes Functions
function getColorThemes() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM color_themes ORDER BY created_at ASC");
    return $stmt->fetchAll();
}

function getActiveTheme() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM color_themes WHERE is_active = 1 LIMIT 1");
    $theme = $stmt->fetch();
    
    if (!$theme) {
        // Return default theme if no active theme
        $stmt = $db->query("SELECT * FROM color_themes WHERE is_default = 1 LIMIT 1");
        $theme = $stmt->fetch();
    }
    
    return $theme;
}

function setActiveTheme($id) {
    $db = getDB();
    
    // Deactivate all themes
    $db->query("UPDATE color_themes SET is_active = 0");
    
    // Activate selected theme
    $stmt = $db->prepare("UPDATE color_themes SET is_active = 1 WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function addColorTheme($data) {
    $db = getDB();
    $sql = "INSERT INTO color_themes (theme_name, primary_color, secondary_color, accent_color, gradient_start, gradient_end) 
            VALUES (:theme_name, :primary_color, :secondary_color, :accent_color, :gradient_start, :gradient_end)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// SEO Functions
function getSEOSettings($page_type = 'home') {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM seo_settings WHERE page_type = :page_type LIMIT 1");
    $stmt->execute(['page_type' => $page_type]);
    return $stmt->fetch();
}

function updateSEOSettings($page_type, $data) {
    $db = getDB();
    
    // Check if SEO settings exist for this page type
    $existing = getSEOSettings($page_type);
    
    if ($existing) {
        $sql = "UPDATE seo_settings SET 
                meta_title = :meta_title, 
                meta_description = :meta_description, 
                meta_keywords = :meta_keywords, 
                og_title = :og_title, 
                og_description = :og_description, 
                og_image = :og_image,
                updated_at = NOW() 
                WHERE page_type = :page_type";
    } else {
        $sql = "INSERT INTO seo_settings (page_type, meta_title, meta_description, meta_keywords, og_title, og_description, og_image) 
                VALUES (:page_type, :meta_title, :meta_description, :meta_keywords, :og_title, :og_description, :og_image)";
    }
    
    $data['page_type'] = $page_type;
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

// Site Settings Functions
function getSiteSetting($key) {
    $db = getDB();
    $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = :key");
    $stmt->execute(['key' => $key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['setting_value'] : null;
}

function getDefaultPersonalInfo() {
    return [
        'name' => getSiteSetting('default_name') ?: 'Bijoy Kumar Nath',
        'title' => getSiteSetting('default_title') ?: 'Full-Stack & Mobile App Developer',
        'description' => getSiteSetting('default_description') ?: 'Experienced Full Stack App Developer with 5+ years of expertise in Android, iOS, and Web development.',
        'email' => getSiteSetting('default_email') ?: 'bijoyknath999@gmail.com',
        'phone' => getSiteSetting('default_phone') ?: '+8801831980819',
        'location' => getSiteSetting('default_location') ?: 'Dhaka, Bangladesh',
        'profile_image' => 'bijoy.png',
        'resume_file' => 'Resume of Bijoy Kumar Nath.pdf',
        'availability_text' => 'Available for Remote work worldwide & Onsite opportunities in Bangladesh.'
    ];
}

function updateSiteSetting($key, $value) {
    $db = getDB();
    
    try {
        // First try to update existing setting
        $stmt = $db->prepare("UPDATE site_settings SET setting_value = :value, updated_at = NOW() WHERE setting_key = :key");
        $stmt->execute(['key' => $key, 'value' => $value]);
        
        // If no rows were affected, insert new setting
        if ($stmt->rowCount() === 0) {
            $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :value)");
            $stmt->execute(['key' => $key, 'value' => $value]);
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Error updating site setting: " . $e->getMessage());
        return false;
    }
}

// Contact Messages Functions
function saveContactMessage($data) {
    $db = getDB();
    $sql = "INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) 
            VALUES (:name, :email, :subject, :message, :ip_address, :user_agent)";
    
    $stmt = $db->prepare($sql);
    return $stmt->execute($data);
}

function getContactMessages($status = null) {
    $db = getDB();
    
    if ($status) {
        $sql = "SELECT * FROM contact_messages WHERE status = :status ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['status' => $status]);
    } else {
        $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
        $stmt = $db->query($sql);
    }
    
    return $stmt->fetchAll();
}

// File Upload Functions
function uploadFile($file, $directory = 'uploads') {
    $uploadDir = __DIR__ . '/../' . $directory . '/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $directory . '/' . $fileName;
    }
    
    return false;
}

// Utility Functions
function sanitizeInput($data) {
    return strip_tags(trim($data));
}

function generateSlug($text) {
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
}

function formatDate($date, $format = 'M Y') {
    return date($format, strtotime($date));
}

// Authentication Functions
function authenticateAdmin($username, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return false;
}

function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
?>
