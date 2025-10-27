# 🎨 Dynamic Portfolio Website

A modern, fully customizable portfolio website with an advanced admin panel and automated CI/CD deployment to cPanel.

[![Deploy to cPanel](https://github.com/bijoyknath999/Portfolio-Test/actions/workflows/deploy.yml/badge.svg)](https://github.com/bijoyknath999/Portfolio-Test/actions/workflows/deploy.yml)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4.svg?style=flat&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1.svg?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## ✨ Features

### 🎨 Modern Design
- **Glassmorphism UI** with vibrant gradients
- **Smooth animations** and transitions
- **Fully responsive** design for all devices
- **Multiple color themes** to choose from
- **Dynamic typing animation** in hero section

### ⚙️ Admin Panel
- **Easy-to-use dashboard** with statistics
- **Manage all content** dynamically without touching code
- **Upload images** (profile, logos) with preview
- **CV/Resume upload** and download functionality
- **SEO management** (meta tags, Open Graph, JSON-LD)
- **Theme customization** from admin panel
- **Contact form** message management

### 🚀 Automated Deployment
- **CI/CD pipeline** with GitHub Actions
- **Automatic deployment** to cPanel via FTP
- **PHP validation** before deployment
- **One-click deployment** from GitHub
- **Zero-downtime** updates

### 🔒 Security
- **Secure authentication** with password hashing
- **PDO prepared statements** to prevent SQL injection
- **Session management** for admin access
- **File upload validation** and sanitization
- **XSS protection** on all inputs

---

## 🛠️ Technology Stack

| Technology | Purpose |
|-----------|---------|
| **PHP 7.4+** | Backend logic and server-side processing |
| **MySQL 5.7+** | Database for dynamic content |
| **PDO** | Secure database interactions |
| **HTML5/CSS3** | Modern, semantic markup and styling |
| **JavaScript (Vanilla)** | Interactive features and AJAX |
| **GitHub Actions** | CI/CD automation |
| **FTP** | Deployment to cPanel hosting |

---

## 📋 Quick Start

### 1️⃣ Installation (Automated Setup)

The easiest way to get started:

1. **Upload files** to your cPanel hosting
2. **Visit** `https://yourdomain.com/setup.php`
3. **Follow the wizard:**
   - Enter database credentials
   - Import database automatically
   - Create admin account
4. **Done!** Delete `setup.php` for security

📖 **Detailed Guide:** See [INSTALLATION.txt](INSTALLATION.txt)

### 2️⃣ CI/CD Setup (Automated Deployment)

Deploy automatically whenever you push to GitHub:

1. **Get FTP credentials** from cPanel
2. **Add GitHub Secrets:**
   - `FTP_SERVER`
   - `FTP_USERNAME`
   - `FTP_PASSWORD`
   - `FTP_SERVER_DIR`
3. **Push to GitHub** - Auto-deploys! 🚀

📖 **Detailed Guide:** See [CI_CD_SETUP.md](CI_CD_SETUP.md)  
📝 **Quick Reference:** See [QUICK_CICD_SETUP.txt](QUICK_CICD_SETUP.txt)

---

## 📁 Project Structure

```
portfolio/
├── .github/
│   └── workflows/
│       └── deploy.yml          # CI/CD workflow for auto-deployment
│
├── admin/                       # Admin panel
│   ├── dashboard.php           # Admin dashboard with stats
│   ├── personal-info.php       # Manage personal details & CV
│   ├── projects.php            # Manage portfolio projects
│   ├── experience.php          # Manage work experience
│   ├── education.php           # Manage education
│   ├── skills.php              # Manage skills
│   ├── settings.php            # Site settings & logo
│   ├── themes.php              # Theme customization
│   ├── seo.php                 # SEO meta tags
│   ├── messages.php            # Contact form messages
│   ├── login.php               # Admin authentication
│   └── admin-styles.css        # Modern admin UI styles
│
├── config/
│   └── database.php            # Database configuration (auto-created)
│
├── database/
│   └── portfolio.sql           # Database schema & sample data
│
├── includes/
│   └── functions.php           # Core PHP functions
│
├── uploads/                     # User-uploaded files
│   ├── profile images
│   ├── resumes/CVs
│   └── logos
│
├── index.php                    # Main portfolio page
├── styles.css                   # Frontend styles (glassmorphism)
├── script.js                    # Interactive features
├── setup.php                    # Automated installation wizard
├── contact_handler.php          # Contact form processing
├── theme_handler.php            # Theme switching logic
├── upload_handler.php           # File upload processing
├── 404.html                     # Custom error page
├── .htaccess                    # Apache configuration
└── .gitignore                   # Git ignore rules
```

---

## 🎯 Key Features Breakdown

### 🖥️ Frontend Portfolio

- **Hero Section** with dynamic typing animation
- **Projects Showcase** with filtering and search
- **Work Experience** timeline
- **Education** display
- **Skills** with proficiency levels
- **Contact Form** with email notifications
- **Responsive Navigation** with smooth scrolling
- **Theme Switcher** with 6+ color themes

### 🔧 Admin Panel Capabilities

| Feature | Description |
|---------|-------------|
| **Dashboard** | Overview with stats and recent activities |
| **Personal Info** | Name, title, bio, profile picture, CV upload, typing roles |
| **Projects** | Add/edit/delete projects with images |
| **Experience** | Manage work history timeline |
| **Education** | Manage educational background |
| **Skills** | Add skills with proficiency levels |
| **Settings** | Site name, logo, contact email, timezone |
| **Themes** | Choose from multiple color schemes |
| **SEO** | Meta tags, Open Graph, Twitter Cards, JSON-LD |
| **Messages** | View contact form submissions |

---

## 🚀 Deployment Options

### Option 1: Automated CI/CD (Recommended)

✅ Push to GitHub → Auto-deploys to cPanel  
✅ Validates code before deployment  
✅ Zero manual intervention  
✅ Track all changes with Git  

**Setup Time:** 5 minutes  
**Guide:** [CI_CD_SETUP.md](CI_CD_SETUP.md)

### Option 2: Manual Upload

1. Upload files via cPanel File Manager or FTP
2. Run `setup.php` to configure database
3. Delete setup files
4. Done!

**Setup Time:** 10 minutes  
**Guide:** [INSTALLATION.txt](INSTALLATION.txt)

---

## 📊 Requirements

### Server Requirements

- ✅ **PHP** 7.4 or higher
- ✅ **MySQL** 5.7+ or MariaDB 10.2+
- ✅ **Apache** with mod_rewrite (or Nginx)
- ✅ **PDO Extension** enabled
- ✅ **GD Library** for image processing
- ✅ **File uploads** enabled (min 10MB)

### Development Requirements

- Git (for version control)
- GitHub account (for CI/CD)
- Text editor (VS Code, Sublime, etc.)
- FTP client (optional, for manual deployment)

---

## 🎨 Customization

### Change Colors/Theme

**Via Admin Panel:**
- Login → Themes → Choose color scheme

**Via Code:**
Edit CSS variables in `styles.css`:

```css
:root {
    --primary: #667eea;
    --secondary: #764ba2;
    --accent: #f093fb;
}
```

### Add New Sections

1. Add HTML in `index.php`
2. Style in `styles.css`
3. Add interactivity in `script.js`
4. Create admin page in `admin/` folder
5. Add database tables if needed

---

## 🔐 Security Best Practices

✅ **Change default admin password** after setup  
✅ **Delete setup.php** after installation  
✅ **Use strong passwords** (20+ characters)  
✅ **Keep PHP updated** to latest version  
✅ **Regular backups** of database and files  
✅ **Monitor error logs** for suspicious activity  
✅ **Use HTTPS** for production (SSL certificate)  
✅ **Limit file upload** sizes and types  

---

## 🆘 Troubleshooting

### Installation Issues

| Problem | Solution |
|---------|----------|
| Database connection failed | Verify credentials in setup wizard |
| 500 Internal Server Error | Check file permissions (755/644) |
| Cannot import database | Ensure MySQL user has CREATE privileges |
| Setup already completed | Delete `.setup_complete` file |

### Deployment Issues

| Problem | Solution |
|---------|----------|
| FTP connection failed | Verify FTP credentials in GitHub secrets |
| Files in wrong directory | Check `FTP_SERVER_DIR` value |
| Workflow not triggering | Ensure pushing to `main` or `master` branch |
| PHP validation failed | Fix syntax errors shown in logs |

📖 **Full Troubleshooting:** See [CI_CD_SETUP.md](CI_CD_SETUP.md#troubleshooting)

---

## 📚 Documentation

| Document | Description |
|----------|-------------|
| [INSTALLATION.txt](INSTALLATION.txt) | Complete installation guide |
| [CI_CD_SETUP.md](CI_CD_SETUP.md) | Detailed CI/CD setup with GitHub Actions |
| [QUICK_CICD_SETUP.txt](QUICK_CICD_SETUP.txt) | Quick reference for CI/CD setup |
| [PRODUCTION_CHECKLIST.txt](PRODUCTION_CHECKLIST.txt) | Pre-deployment checklist |
| [DYNAMIC_README.md](DYNAMIC_README.md) | Technical documentation |

---

## 🤝 Contributing

Contributions are welcome! Here's how:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🎉 Features Highlights

### ✨ What Makes This Portfolio Special?

- 🎨 **Modern UI/UX** - Glassmorphism design with smooth animations
- 🚀 **Fast Deployment** - CI/CD pipeline for instant updates
- 🔧 **Easy Management** - No coding required to update content
- 📱 **Mobile-First** - Perfect on all screen sizes
- 🔍 **SEO Optimized** - Built-in SEO tools and meta tags
- 🎭 **Multi-Theme** - 6+ color themes to choose from
- 📊 **Analytics Ready** - Google Analytics integration
- 💌 **Contact Form** - Email notifications for inquiries
- 📄 **CV Download** - Easy resume/CV upload and download
- ⚡ **Performance** - Optimized for speed and efficiency

---

## 📞 Support

Need help? Here's how to get support:

1. 📖 **Check Documentation** - Most questions are answered in guides
2. 🔍 **GitHub Issues** - Open an issue for bugs or features
3. 💬 **Discussions** - Ask questions in GitHub Discussions
4. 📧 **Email** - Contact the maintainer directly

---

## 🌟 Show Your Support

If you like this project, please give it a ⭐️ on GitHub!

---

## 🗺️ Roadmap

### Upcoming Features

- [ ] Dark mode toggle
- [ ] Blog section
- [ ] Testimonials management
- [ ] Analytics dashboard
- [ ] Email template customization
- [ ] Multi-language support
- [ ] Social media auto-posting
- [ ] Advanced SEO tools
- [ ] Performance monitoring
- [ ] A/B testing capabilities

---

## 📸 Screenshots

> Add your portfolio screenshots here after deployment!

---

## 🔗 Links

- **Live Demo:** https://bijoyknath.top
- **Admin Panel:** https://bijoyknath.top/admin/
- **Documentation:** [View All Docs](/)
- **GitHub:** [Repository](https://github.com/bijoyknath999/Portfolio-Test)

---

<div align="center">

**Made with ❤️ and lots of ☕**

**[⬆ Back to Top](#-dynamic-portfolio-website)**

</div>

