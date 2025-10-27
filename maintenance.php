<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .maintenance-container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
        }
        
        .maintenance-icon {
            font-size: 5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .maintenance-subtitle {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .maintenance-description {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            opacity: 0.8;
        }
        
        .admin-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .admin-link:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .loading-animation {
            margin-top: 40px;
        }
        
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: white;
            margin: 0 2px;
            opacity: 0.4;
            animation: loading 1.4s infinite ease-in-out both;
        }
        
        .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
        .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots span:nth-child(3) { animation-delay: 0s; }
        
        @keyframes loading {
            0%, 80%, 100% {
                transform: scale(0.8);
                opacity: 0.4;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .social-links {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        
        .social-link {
            color: white;
            font-size: 1.5rem;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .social-link:hover {
            opacity: 1;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1 class="maintenance-title">Under Maintenance</h1>
        
        <p class="maintenance-subtitle">We're currently working on something awesome!</p>
        
        <p class="maintenance-description">
            Our website is temporarily unavailable due to scheduled maintenance. 
            We're working hard to improve your experience and will be back online shortly.
        </p>
        
        <div class="loading-animation">
            <div class="loading-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        
        <div class="social-links">
            <a href="mailto:bijoyknath999@gmail.com" class="social-link" title="Email">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="https://linkedin.com/in/bijoyknath" class="social-link" title="LinkedIn" target="_blank">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="https://github.com/bijoyknath" class="social-link" title="GitHub" target="_blank">
                <i class="fab fa-github"></i>
            </a>
        </div>
        
        <div style="margin-top: 40px;">
            <a href="admin/login.php" class="admin-link">
                <i class="fas fa-user-shield"></i>
                Admin Access
            </a>
        </div>
    </div>
</body>
</html>
