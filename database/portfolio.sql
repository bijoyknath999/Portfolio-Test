-- Portfolio Database Schema
-- Run this SQL to create the database structure

CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Admin users table
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site settings table
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'json', 'boolean', 'number') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Personal info table
CREATE TABLE personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    location VARCHAR(100),
    profile_image VARCHAR(255),
    resume_file VARCHAR(255),
    availability_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    download_url VARCHAR(255),
    technologies JSON,
    job_type ENUM('freelance', 'fulltime', 'internship', 'personal') DEFAULT 'personal',
    client_name VARCHAR(100),
    duration VARCHAR(50),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Experience table
CREATE TABLE experience (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(200) NOT NULL,
    position VARCHAR(200) NOT NULL,
    job_type ENUM('fulltime', 'parttime', 'freelance', 'internship', 'contract') DEFAULT 'fulltime',
    start_date DATE,
    end_date DATE,
    is_current BOOLEAN DEFAULT FALSE,
    description TEXT,
    responsibilities JSON,
    location VARCHAR(100),
    company_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Education table
CREATE TABLE education (
    id INT PRIMARY KEY AUTO_INCREMENT,
    degree_title VARCHAR(200) NOT NULL,
    institution_name VARCHAR(200) NOT NULL,
    start_year YEAR,
    end_year YEAR,
    is_current BOOLEAN DEFAULT FALSE,
    description TEXT,
    highlights JSON,
    grade VARCHAR(20),
    location VARCHAR(100),
    institution_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills table
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    skills JSON,
    icon_class VARCHAR(100),
    custom_icon VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Social links table
CREATE TABLE social_links (
    id INT PRIMARY KEY AUTO_INCREMENT,
    platform_name VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_class VARCHAR(100),
    custom_icon VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Color themes table
CREATE TABLE color_themes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    theme_name VARCHAR(50) NOT NULL,
    primary_color VARCHAR(7) NOT NULL,
    secondary_color VARCHAR(7) NOT NULL,
    accent_color VARCHAR(7) NOT NULL,
    gradient_start VARCHAR(7) NOT NULL,
    gradient_end VARCHAR(7) NOT NULL,
    is_active BOOLEAN DEFAULT FALSE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SEO settings table
CREATE TABLE seo_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_type ENUM('home', 'projects', 'experience', 'education', 'skills', 'contact') DEFAULT 'home',
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    og_title VARCHAR(255),
    og_description TEXT,
    og_image VARCHAR(255),
    canonical_url VARCHAR(255),
    robots_txt TEXT,
    schema_markup JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Visitors tracking table
CREATE TABLE visitors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    country VARCHAR(100),
    city VARCHAR(100),
    browser VARCHAR(50),
    os VARCHAR(50),
    device VARCHAR(50),
    referrer TEXT,
    page_visited VARCHAR(255),
    visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip (ip_address),
    INDEX idx_date (visit_date)
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.com');

-- Insert default personal info
INSERT INTO personal_info (name, title, description, email, phone, location, availability_text) VALUES 
('Bijoy Kumar Nath', 'Full-Stack & Mobile App Developer', 'Experienced Full Stack App Developer with 5+ years of expertise in Android, iOS, and Web development. Specializing in Laravel, Firebase, Flutter, and Linux. Based in Dhaka, Bangladesh.', 'bijoyknath999@gmail.com', '+8801831980819', 'Dhaka, Bangladesh', 'Available for Remote work worldwide & Onsite opportunities in Bangladesh.');

-- Insert default color themes
INSERT INTO color_themes (theme_name, primary_color, secondary_color, accent_color, gradient_start, gradient_end, is_default) VALUES 
('Purple Gradient', '#667eea', '#764ba2', '#f093fb', '#667eea', '#764ba2', TRUE),
('Pink Sunset', '#f093fb', '#f5576c', '#ff6b9d', '#f093fb', '#f5576c', FALSE),
('Ocean Blue', '#4facfe', '#00f2fe', '#43e97b', '#4facfe', '#00f2fe', FALSE),
('Green Nature', '#43e97b', '#38f9d7', '#4facfe', '#43e97b', '#38f9d7', FALSE),
('Warm Sunset', '#fa709a', '#fee140', '#ff9a9e', '#fa709a', '#fee140', FALSE),
('Soft Pastel', '#a8edea', '#fed6e3', '#fbc2eb', '#a8edea', '#fed6e3', FALSE),
('Rose Gold', '#ff9a9e', '#fecfef', '#fbc2eb', '#ff9a9e', '#fecfef', FALSE),
('Purple Pink', '#a18cd1', '#fbc2eb', '#ff9a9e', '#a18cd1', '#fbc2eb', FALSE),
('Peach Dream', '#ffecd2', '#fcb69f', '#ff8a80', '#ffecd2', '#fcb69f', FALSE),
('Sky Blue', '#89f7fe', '#66a6ff', '#667eea', '#89f7fe', '#66a6ff', FALSE);

-- Insert default SEO settings
INSERT INTO seo_settings (page_type, meta_title, meta_description, meta_keywords) VALUES 
('home', 'Bijoy Kumar Nath - Full-Stack & Mobile App Developer | Portfolio', 'Experienced Full Stack App Developer with 5+ years expertise in Android, iOS, Web development. Specializing in Laravel, Firebase, Flutter, Linux. Available for remote work worldwide.', 'full-stack developer, mobile app developer, flutter developer, laravel developer, android developer, web developer, firebase, bangladesh developer');

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES 
('site_name', 'Bijoy Kumar Nath Portfolio', 'text'),
('site_tagline', 'Full-Stack & Mobile App Developer', 'text'),
('google_analytics_id', '', 'text'),
('contact_form_email', 'bijoyknath999@gmail.com', 'text'),
('maintenance_mode', 'false', 'boolean'),
('show_theme_selector', 'true', 'boolean'),
('telegram_bot_token', '', 'text'),
('telegram_chat_id', '', 'text'),
('telegram_notifications_enabled', 'false', 'boolean');
