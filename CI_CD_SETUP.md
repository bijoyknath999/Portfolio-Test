# 🚀 CI/CD Setup Guide - Auto Deploy to cPanel

This guide will help you set up automatic deployment to your cPanel hosting using GitHub Actions and FTP.

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [Step 1: Get cPanel FTP Credentials](#step-1-get-cpanel-ftp-credentials)
4. [Step 2: Configure GitHub Secrets](#step-2-configure-github-secrets)
5. [Step 3: Push to GitHub](#step-3-push-to-github)
6. [Step 4: Verify Deployment](#step-4-verify-deployment)
7. [Manual Deployment](#manual-deployment)
8. [Troubleshooting](#troubleshooting)
9. [Security Best Practices](#security-best-practices)

---

## 🎯 Overview

### What is CI/CD?

**Continuous Integration/Continuous Deployment (CI/CD)** automatically deploys your code to production whenever you push changes to GitHub.

### How It Works

```
You Push Code → GitHub Actions Triggers → Validates PHP → Deploys via FTP → Live on cPanel! 🎉
```

### Benefits

✅ **Automatic Deployment** - No manual file uploads  
✅ **Validation** - Checks PHP syntax before deployment  
✅ **Version Control** - Track all changes via Git  
✅ **Rollback** - Easily revert to previous versions  
✅ **Time Saving** - Deploy in seconds, not minutes  
✅ **Error Prevention** - Automated testing before deployment  

---

## ✅ Prerequisites

Before setting up CI/CD, ensure you have:

- [ ] GitHub repository for your portfolio
- [ ] cPanel hosting account
- [ ] FTP access to your cPanel account
- [ ] Basic knowledge of Git and GitHub

---

## 🔧 Step 1: Get cPanel FTP Credentials

### Option A: Create FTP Account in cPanel

1. **Login to cPanel**
2. **Navigate to:** Files → FTP Accounts
3. **Create FTP Account:**
   - **Login:** `portfolio_deploy` (or any name you like)
   - **Password:** Generate a strong password
   - **Directory:** `/public_html` (or your website directory)
   - **Quota:** Unlimited
4. **Save the following details:**
   - FTP Server: `ftp.yourdomain.com` or your server IP
   - FTP Username: `portfolio_deploy@yourdomain.com`
   - FTP Password: (the password you created)
   - Server Directory: `/public_html/` or `/` (depends on your setup)

### Option B: Use Main cPanel Account

You can also use your main cPanel FTP credentials:

- **FTP Server:** `ftp.yourdomain.com`
- **Username:** Your cPanel username
- **Password:** Your cPanel password
- **Directory:** `/public_html/`

### Finding Your FTP Server

Common formats:
- `ftp.yourdomain.com`
- `yourdomain.com`
- Your server IP address (e.g., `123.45.67.89`)

**To find your FTP server in cPanel:**
1. Go to cPanel → Files → FTP Accounts
2. Look for "FTP Server" or "Host" information

---

## 🔐 Step 2: Configure GitHub Secrets

GitHub Secrets securely store your FTP credentials. They're encrypted and never exposed in logs.

### 2.1 Navigate to Repository Settings

1. Go to your GitHub repository
2. Click **Settings** (top right)
3. In the left sidebar, click **Secrets and variables** → **Actions**
4. Click **New repository secret**

### 2.2 Add Required Secrets

Add these **4 secrets** one by one:

#### Secret 1: FTP_SERVER
```
Name: FTP_SERVER
Value: ftp.yourdomain.com
```
(Your FTP server hostname or IP address)

#### Secret 2: FTP_USERNAME
```
Name: FTP_USERNAME
Value: portfolio_deploy@yourdomain.com
```
(Your FTP username - usually includes domain name)

#### Secret 3: FTP_PASSWORD
```
Name: FTP_PASSWORD
Value: your_ftp_password_here
```
(Your FTP password - use a strong password)

#### Secret 4: FTP_SERVER_DIR
```
Name: FTP_SERVER_DIR
Value: /public_html/
```
(Your website directory - common values: `/`, `/public_html/`, `/www/`, `/htdocs/`)

### 2.3 Verify Secrets

After adding all secrets, you should see:

```
✓ FTP_SERVER
✓ FTP_USERNAME
✓ FTP_PASSWORD
✓ FTP_SERVER_DIR
```

---

## 📤 Step 3: Push to GitHub

### 3.1 Initialize Git Repository (If Not Already)

```bash
# Navigate to your portfolio directory
cd /path/to/portfolio

# Initialize Git repository
git init

# Add all files
git add .

# Create first commit
git commit -m "Initial commit: Portfolio with CI/CD"

# Add remote repository
git remote add origin https://github.com/yourusername/your-repo.git

# Push to GitHub
git push -u origin main
```

### 3.2 Push Changes (For Updates)

```bash
# Make your changes to files

# Stage changes
git add .

# Commit changes
git commit -m "Update portfolio content"

# Push to GitHub (triggers automatic deployment)
git push
```

### 3.3 Automatic Deployment Trigger

The workflow automatically runs when you push to:
- `main` branch
- `master` branch

**You'll see:**
1. GitHub Actions badge showing deployment status
2. Deployment happening in real-time in the Actions tab
3. Your changes live on cPanel in ~1-2 minutes!

---

## ✅ Step 4: Verify Deployment

### 4.1 Check GitHub Actions

1. Go to your GitHub repository
2. Click the **Actions** tab
3. You should see your workflow running: `🚀 Deploy to cPanel via FTP`
4. Click on it to see detailed logs
5. Wait for all steps to complete (green checkmarks ✅)

### 4.2 Workflow Steps

The deployment process includes:

1. **📥 Checkout Code** - Gets your latest code
2. **📦 Setup PHP** - Prepares PHP environment
3. **🧪 Validate PHP Syntax** - Checks for errors
4. **🔒 Create Production Config** - Prepares config files
5. **📋 Prepare Deployment Package** - Excludes unnecessary files
6. **🚀 Deploy to cPanel via FTP** - Uploads files
7. **🧹 Cleanup** - Removes temporary files
8. **✅ Deployment Complete** - Shows success message

### 4.3 Verify Your Website

1. **Visit your website:** `https://yourdomain.com`
2. **Check admin panel:** `https://yourdomain.com/admin/`
3. **Verify changes:** Ensure your updates are live

### 4.4 First Time Deployment

If this is your **first deployment** to cPanel:

1. Visit `https://yourdomain.com/setup.php`
2. Run the setup wizard (see INSTALLATION.txt)
3. Delete setup files after completion

---

## 🎮 Manual Deployment

You can also trigger deployment manually without pushing code:

1. Go to GitHub repository
2. Click **Actions** tab
3. Click **🚀 Deploy to cPanel via FTP** workflow
4. Click **Run workflow** button
5. Select branch (usually `main`)
6. Click **Run workflow**

This is useful for:
- Re-deploying the same code
- Testing the deployment process
- Deploying without new commits

---

## 🔧 Troubleshooting

### ❌ Deployment Failed - FTP Connection Error

**Problem:** Cannot connect to FTP server

**Solutions:**
1. ✅ Verify FTP_SERVER secret (correct hostname/IP)
2. ✅ Check FTP_USERNAME and FTP_PASSWORD
3. ✅ Ensure FTP port 21 is open (or use port 22 for SFTP)
4. ✅ Try using server IP instead of domain name
5. ✅ Check if your hosting allows FTP connections
6. ✅ Verify firewall settings in cPanel

### ❌ Deployment Failed - Authentication Error

**Problem:** FTP credentials rejected

**Solutions:**
1. ✅ Double-check FTP_USERNAME (include @domain.com if required)
2. ✅ Verify FTP_PASSWORD is correct
3. ✅ Try creating a new FTP account in cPanel
4. ✅ Check if FTP account is active and not suspended
5. ✅ Ensure password doesn't contain special characters that need escaping

### ❌ Files Not Uploading to Correct Directory

**Problem:** Files uploaded to wrong location

**Solutions:**
1. ✅ Check FTP_SERVER_DIR secret
2. ✅ Common values: `/`, `/public_html/`, `/www/`, `/htdocs/`
3. ✅ Login via FTP client to verify correct path
4. ✅ Ensure directory has trailing slash: `/public_html/`

### ❌ PHP Syntax Validation Failed

**Problem:** PHP files have syntax errors

**Solutions:**
1. ✅ Check the error message in GitHub Actions logs
2. ✅ Fix the syntax error in your PHP file
3. ✅ Test locally with: `php -l yourfile.php`
4. ✅ Commit and push the fix

### ❌ Workflow Not Triggering

**Problem:** Push to GitHub but workflow doesn't run

**Solutions:**
1. ✅ Ensure you're pushing to `main` or `master` branch
2. ✅ Check `.github/workflows/deploy.yml` file exists
3. ✅ Verify YAML syntax is correct
4. ✅ Check repository Actions settings are enabled

### 📊 Viewing Detailed Logs

To see what went wrong:

1. Go to **Actions** tab in GitHub
2. Click on the failed workflow run
3. Click on the **🌐 Deploy to Production** job
4. Expand each step to see detailed logs
5. Look for red ❌ marks indicating errors

---

## 🔐 Security Best Practices

### ✅ DO's

✅ **Use GitHub Secrets** for all credentials  
✅ **Create dedicated FTP account** for deployment  
✅ **Use strong FTP passwords** (20+ characters)  
✅ **Limit FTP account** to specific directory  
✅ **Enable 2FA** on GitHub account  
✅ **Review deployment logs** regularly  
✅ **Delete setup.php** after first deployment  
✅ **Keep secrets updated** if passwords change  

### ❌ DON'Ts

❌ **Never commit** FTP credentials to Git  
❌ **Don't use** main cPanel password (use dedicated FTP account)  
❌ **Don't share** GitHub secrets publicly  
❌ **Don't disable** PHP validation step  
❌ **Don't skip** security updates  
❌ **Don't leave** debug files on production  

### 🔒 Additional Security

1. **Enable SSL/TLS for FTP** (FTPS) if available
2. **Use SFTP** instead of FTP when possible
3. **Restrict FTP access** by IP address if your IP is static
4. **Monitor deployment logs** for suspicious activity
5. **Rotate FTP passwords** periodically
6. **Backup before deployment** (automatic in workflow)

---

## 📁 Files Excluded from Deployment

The workflow automatically excludes these files/folders:

```
.git/              # Git repository data
.github/           # GitHub workflows
.gitignore         # Git ignore file
node_modules/      # Node.js dependencies
vendor/            # PHP dependencies
.env               # Environment variables
.DS_Store          # macOS system files
*.log              # Log files
test_*.php         # Test files
debug_*.php        # Debug files
.setup_complete    # Setup marker
```

---

## 🎯 Workflow Customization

### Change Deployment Branch

Edit `.github/workflows/deploy.yml`:

```yaml
on:
  push:
    branches:
      - production  # Change to your branch name
```

### Add Slack Notifications

Add this step to receive Slack notifications:

```yaml
- name: 📢 Notify Slack
  uses: 8398a7/action-slack@v3
  with:
    status: ${{ job.status }}
    text: 'Deployment completed!'
    webhook_url: ${{ secrets.SLACK_WEBHOOK }}
  if: always()
```

### Deploy to Multiple Environments

Create separate workflow files for staging/production:
- `.github/workflows/deploy-staging.yml`
- `.github/workflows/deploy-production.yml`

---

## 📚 Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [FTP Deploy Action](https://github.com/SamKirkland/FTP-Deploy-Action)
- [cPanel FTP Documentation](https://docs.cpanel.net/cpanel/files/ftp-accounts/)
- [Git Basic Commands](https://git-scm.com/docs)

---

## 🎉 Success!

Your portfolio now has **automatic deployment**! 🚀

Every time you push code to GitHub:
1. Code is validated ✅
2. Files are deployed automatically 🚀
3. Your website is updated instantly ⚡

**Happy deploying!** 🎨

---

## 📞 Support

If you encounter issues:

1. Check the [Troubleshooting](#troubleshooting) section
2. Review GitHub Actions logs for error details
3. Verify all secrets are configured correctly
4. Test FTP connection manually using FileZilla or similar
5. Check cPanel error logs for server-side issues

---

**Last Updated:** 2025-10-27  
**Version:** 1.0.0

