# Modern Portfolio Website

A beautiful, responsive portfolio website for full-stack and mobile app developers. Features 10 different color themes, smooth animations, and a modern design that's perfect for showcasing your work to recruiters and potential clients.

## Features

✨ **Modern Design**: Clean, professional layout with vibrant colors and smooth animations
🎨 **10 Color Themes**: Choose from 10 beautiful gradient color schemes
📱 **Fully Responsive**: Looks great on all devices (desktop, tablet, mobile)
🚀 **Interactive Elements**: Smooth scrolling, hover effects, and typing animations
📧 **Contact Form**: Functional contact form with validation
🎯 **SEO Friendly**: Optimized for search engines
⚡ **Fast Loading**: Optimized performance with minimal dependencies

## Sections Included

1. **Hero Section**: Introduction with profile picture and animated tagline
2. **Projects**: Showcase your work with project cards, tech stacks, and job types
3. **Work Experience**: Timeline-based experience section
4. **Skills**: Categorized technical skills with icons
5. **Contact**: Contact form and social media links

## Quick Start

1. **Download the files** to your computer
2. **Open `index.html`** in your web browser
3. **Customize the content** by editing the HTML file
4. **Choose your color theme** using the theme selector on the right side
5. **Deploy** to your hosting platform

## Customization Guide

### 1. Personal Information

Edit the following in `index.html`:

```html
<!-- Update your name -->
<h1 class="hero-title">Hi, I'm <span class="highlight">Your Name</span></h1>

<!-- Update profile picture -->
<img src="your-photo.jpg" alt="Profile Picture" class="profile-img">

<!-- Update contact information -->
<p>your.email@example.com</p>
<p>+1 (555) 123-4567</p>
```

### 2. Projects Section

Add your projects by duplicating the project card structure:

```html
<div class="project-card">
    <div class="project-image">
        <img src="project-image.jpg" alt="Project Name">
        <div class="project-overlay">
            <div class="project-links">
                <a href="github-link" class="project-link"><i class="fab fa-github"></i></a>
                <a href="live-demo-link" class="project-link"><i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
    </div>
    <div class="project-content">
        <h3 class="project-title">Project Name</h3>
        <p class="project-description">Project description...</p>
        <div class="project-tech">
            <span class="tech-tag">Technology 1</span>
            <span class="tech-tag">Technology 2</span>
        </div>
        <div class="project-meta">
            <span class="job-type freelance">Job Type</span>
        </div>
    </div>
</div>
```

### 3. Work Experience

Update the timeline items with your experience:

```html
<div class="timeline-item">
    <div class="timeline-marker"></div>
    <div class="timeline-content">
        <div class="experience-card">
            <div class="experience-header">
                <h3 class="company-name">Company Name</h3>
                <span class="job-type fulltime">Job Type</span>
            </div>
            <h4 class="role-title">Your Role</h4>
            <p class="duration">Start Date - End Date</p>
            <ul class="responsibilities">
                <li>Responsibility 1</li>
                <li>Responsibility 2</li>
            </ul>
        </div>
    </div>
</div>
```

### 4. Skills Section

Customize the skills by editing the skill categories and items:

```html
<div class="skill-category">
    <h3 class="category-title">Category Name</h3>
    <div class="skills-list">
        <div class="skill-item">
            <i class="fab fa-icon-name"></i>
            <span>Skill Name</span>
        </div>
    </div>
</div>
```

### 5. Color Themes

The website includes 10 pre-designed color themes:

1. **Theme 1**: Purple Gradient (Default)
2. **Theme 2**: Pink to Red
3. **Theme 3**: Blue to Cyan
4. **Theme 4**: Green to Cyan
5. **Theme 5**: Pink to Yellow
6. **Theme 6**: Mint to Pink
7. **Theme 7**: Pink to Light Pink
8. **Theme 8**: Purple to Pink
9. **Theme 9**: Peach Gradient
10. **Theme 10**: Light Blue Gradient

To change the default theme, modify the CSS variables in `styles.css` or use the theme selector.

### 6. Adding New Themes

To add a new color theme, add the following to `styles.css`:

```css
[data-theme="theme-new"] {
    --primary-color: #your-primary-color;
    --secondary-color: #your-secondary-color;
    --accent-color: #your-accent-color;
    --gradient: linear-gradient(135deg, #color1 0%, #color2 100%);
}
```

Then add a new theme button in the HTML.

## Job Type Styling

The website supports three job types with different colors:

- **Freelance**: Blue (`job-type freelance`)
- **Full-time**: Green (`job-type fulltime`)
- **Internship**: Orange (`job-type internship`)

## Icons

The website uses Font Awesome icons. You can find icons at [fontawesome.com](https://fontawesome.com/icons) and replace the icon classes in the HTML.

## Deployment

### GitHub Pages
1. Create a new repository on GitHub
2. Upload all files to the repository
3. Go to Settings > Pages
4. Select source as "Deploy from a branch"
5. Choose "main" branch and "/" (root) folder
6. Your site will be available at `https://yourusername.github.io/repository-name`

### Netlify
1. Drag and drop the project folder to [netlify.com](https://netlify.com)
2. Your site will be deployed automatically
3. You can customize the domain name in the site settings

### Vercel
1. Connect your GitHub repository to [vercel.com](https://vercel.com)
2. Deploy with one click
3. Automatic deployments on every push

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Performance Tips

1. **Optimize Images**: Compress your project images before uploading
2. **Use WebP Format**: Convert images to WebP for better performance
3. **Minimize CSS/JS**: Use minified versions for production
4. **Enable Caching**: Configure proper caching headers on your server

## Future Enhancements

The website is designed to be easily extensible. You can add:

- Blog section
- Testimonials
- Certifications
- Dark/Light mode toggle
- Multi-language support
- Database integration for dynamic content
- Admin panel for easy content management

## Troubleshooting

### Images Not Loading
- Check file paths are correct
- Ensure images are in the same directory structure
- Use relative paths for local development

### Animations Not Working
- Check if JavaScript is enabled
- Ensure all CSS and JS files are properly linked
- Check browser console for errors

### Mobile Menu Not Working
- Verify JavaScript is loaded
- Check for console errors
- Ensure hamburger menu HTML structure is correct

## Support

If you need help customizing the website or encounter any issues, you can:

1. Check the browser console for errors
2. Validate your HTML and CSS
3. Test in different browsers
4. Review the code comments for guidance

## License

This portfolio template is free to use for personal and commercial projects. No attribution required, but appreciated!

---

**Happy coding! 🚀**

Remember to replace all placeholder content with your actual information and customize the design to match your personal brand.
