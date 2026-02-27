# Activity Stream - Installation Guide

This guide will walk you through the complete installation process for the Activity Stream application.

## 📋 Prerequisites

Before you begin, ensure you have:

- ✅ PHP 7.4 or higher installed
- ✅ MySQL 5.7 or higher installed
- ✅ A web server (Apache or Nginx)
- ✅ Access to MySQL command line or phpMyAdmin
- ✅ Basic knowledge of PHP and MySQL

## 🚀 Step-by-Step Installation

### Step 1: Download and Extract Files

1. Download all project files to your web server directory
2. Typical locations:
   - **Apache (Linux)**: `/var/www/html/stream/`
   - **Apache (Windows/XAMPP)**: `C:\xampp\htdocs\stream\`
   - **Nginx**: `/usr/share/nginx/html/stream/`

### Step 2: Create the Database

#### Option A: Using MySQL Command Line

```bash
# Login to MySQL
mysql -u root -p

# Run the schema file
source /path/to/schema.sql

# Or manually create:
CREATE DATABASE yunusNowHasAcoolActivityLogSystem CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yunusNowHasAcoolActivityLogSystem;

CREATE TABLE activitylogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    time TIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date_time (date DESC, time DESC),
    INDEX idx_created_at (created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin in your browser
2. Click "Import" tab
3. Choose `schema.sql` file
4. Click "Go"

### Step 3: Create Database User (Recommended)

For security, create a dedicated database user:

```sql
-- Create user
CREATE USER 'activitystream_user'@'localhost' IDENTIFIED BY 'your_secure_password';

-- Grant privileges
GRANT SELECT, INSERT, DELETE ON yunusNowHasAcoolActivityLogSystem.* TO 'activitystream_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
```

### Step 4: Configure Database Connection

1. Open `config.php` in a text editor
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'yunusNowHasAcoolActivityLogSystem');
define('DB_USER', 'activitystream_user');  // Your database username
define('DB_PASS', 'your_secure_password');  // Your database password
```

### Step 5: Generate Admin Password Hash

#### Option A: Using the Web Interface

1. Navigate to `http://your-domain.com/stream/generate_password_hash.php`
2. Enter your desired admin password
3. Click "Generate Hash"
4. Copy the generated hash
5. **Important**: Delete `generate_password_hash.php` after use!

#### Option B: Using PHP CLI

```bash
php generate_password_hash.php YourSecurePassword123
```

#### Option C: Using PHP Directly

```php
<?php
echo password_hash('YourSecurePassword123', PASSWORD_DEFAULT);
?>
```

### Step 6: Update Admin Password Hash

1. Open `config.php`
2. Find the line with `ADMIN_PASSWORD_HASH`
3. Replace with your generated hash:

```php
define('ADMIN_PASSWORD_HASH', '$2y$10$your_generated_hash_here');
```

### Step 7: Set File Permissions

#### Linux/Unix:

```bash
# Navigate to project directory
cd /var/www/html/stream/

# Set directory permissions
chmod 755 .

# Set file permissions
chmod 644 *.php *.css *.md *.sql

# Make sure web server can read files
chown -R www-data:www-data .
```

#### Windows:

- Right-click the folder
- Properties → Security
- Ensure IIS_IUSRS or IUSR has Read & Execute permissions

### Step 8: Configure Web Server

#### Apache (.htaccess)

Create a `.htaccess` file in the project root:

```apache
# Enable rewrite engine
RewriteEngine On

# Prevent directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "^(config\.php|functions\.php|\.git.*|\.env)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Set default charset
AddDefaultCharset UTF-8

# Enable compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>

# Browser caching
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
</IfModule>
```

#### Nginx

Add to your server block:

```nginx
location /stream/ {
    index index.php;
    try_files $uri $uri/ =404;
    
    # Protect sensitive files
    location ~ ^/stream/(config|functions)\.php$ {
        deny all;
    }
    
    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### Step 9: Test the Installation

1. **Test Main Page**:
   - Navigate to `http://your-domain.com/stream/index.php`
   - You should see the activity stream (may be empty initially)

2. **Test Admin Login**:
   - Navigate to `http://your-domain.com/stream/admin.php`
   - Enter your admin password
   - You should be logged in successfully

3. **Test Adding Activity**:
   - In the admin panel, add a test activity
   - Title: "Test Activity"
   - Body: "This is a test post to verify the installation."
   - Click "Add Activity"
   - Check if it appears on the main page

4. **Test Deleting Activity**:
   - Note the ID of your test activity
   - In the admin panel, enter the ID in the delete form
   - Click "Delete Activity"
   - Verify it's removed from the main page

### Step 10: Security Hardening (Production)

1. **Delete Sensitive Files**:
   ```bash
   rm generate_password_hash.php
   rm schema.sql  # Optional, keep a backup elsewhere
   ```

2. **Use Environment Variables** (Recommended):
   
   Create a `.env` file (not in web root):
   ```
   DB_HOST=localhost
   DB_NAME=yunusNowHasAcoolActivityLogSystem
   DB_USER=activitystream_user
   DB_PASS=your_secure_password
   ADMIN_PASSWORD_HASH=$2y$10$your_hash_here
   ```

3. **Enable HTTPS**:
   - Obtain an SSL certificate (Let's Encrypt is free)
   - Configure your web server to use HTTPS
   - Redirect HTTP to HTTPS

4. **Update PHP Settings** (`php.ini`):
   ```ini
   display_errors = Off
   log_errors = On
   error_log = /var/log/php_errors.log
   session.cookie_httponly = 1
   session.cookie_secure = 1  # If using HTTPS
   session.use_strict_mode = 1
   ```

5. **Regular Backups**:
   ```bash
   # Backup database
   mysqldump -u root -p yunusNowHasAcoolActivityLogSystem > backup_$(date +%Y%m%d).sql
   
   # Backup files
   tar -czf stream_backup_$(date +%Y%m%d).tar.gz /path/to/stream/
   ```

## 🔧 Troubleshooting

### Database Connection Failed

**Error**: "We are experiencing technical difficulties..."

**Solutions**:
1. Verify database credentials in `config.php`
2. Check if MySQL service is running: `sudo service mysql status`
3. Test database connection:
   ```bash
   mysql -u your_user -p your_database
   ```
4. Check MySQL error logs: `/var/log/mysql/error.log`

### Admin Login Not Working

**Error**: "Invalid password"

**Solutions**:
1. Regenerate password hash using `generate_password_hash.php`
2. Verify hash is correctly copied to `config.php`
3. Clear browser cookies and cache
4. Check PHP session directory permissions:
   ```bash
   ls -la /var/lib/php/sessions/
   ```

### Blank Page or 500 Error

**Solutions**:
1. Enable error display temporarily:
   ```php
   // Add to top of index.php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
2. Check PHP error logs
3. Verify file permissions
4. Check web server error logs:
   - Apache: `/var/log/apache2/error.log`
   - Nginx: `/var/log/nginx/error.log`

### Styling Not Loading

**Solutions**:
1. Clear browser cache (Ctrl+F5)
2. Verify CSS files exist and have correct permissions
3. Check browser console for 404 errors
4. Verify file paths in HTML are correct

### Session Timeout Issues

**Solutions**:
1. Increase session timeout in `admin.php`:
   ```php
   // Change from 1800 (30 min) to 3600 (60 min)
   if (time() - $_SESSION['admin_login_time'] > 3600) {
   ```
2. Check PHP session settings in `php.ini`:
   ```ini
   session.gc_maxlifetime = 3600
   ```

## 📞 Getting Help

If you encounter issues:

1. Check the troubleshooting section above
2. Review PHP and web server error logs
3. Verify all installation steps were completed
4. Check file and directory permissions
5. Ensure PHP and MySQL versions meet requirements

## ✅ Post-Installation Checklist

- [ ] Database created and accessible
- [ ] Database user created with proper permissions
- [ ] `config.php` updated with correct credentials
- [ ] Admin password hash generated and configured
- [ ] File permissions set correctly
- [ ] Web server configured properly
- [ ] Main page loads without errors
- [ ] Admin login works
- [ ] Can add activities successfully
- [ ] Can delete activities successfully
- [ ] Activities display on main page
- [ ] Pagination works (if you have 20+ activities)
- [ ] `generate_password_hash.php` deleted
- [ ] HTTPS enabled (production)
- [ ] Backups configured

## 🎉 Success!

If all tests pass, your Activity Stream is ready to use! Visit the main page and start posting activities.

---

**Need to customize?** Check out the main README.md for customization options and advanced features.
