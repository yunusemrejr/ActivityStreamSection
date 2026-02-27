# Quick Start Guide

Get your Activity Stream up and running in 5 minutes!

## ⚡ Prerequisites

- PHP 7.4+ and MySQL 5.7+ installed
- Web server running (Apache/Nginx)
- 5 minutes of your time

## 🚀 Installation (5 Steps)

### 1️⃣ Create Database (1 minute)

```bash
mysql -u root -p
```

```sql
CREATE DATABASE yunusNowHasAcoolActivityLogSystem;
USE yunusNowHasAcoolActivityLogSystem;

CREATE TABLE activitylogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    time TIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    INDEX idx_date_time (date DESC, time DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

EXIT;
```

**Or use the schema file:**
```bash
mysql -u root -p < schema.sql
```

### 2️⃣ Configure Database (30 seconds)

Edit `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'yunusNowHasAcoolActivityLogSystem');
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', 'your_password');  // Your MySQL password
```

### 3️⃣ Generate Admin Password (1 minute)

**Option A - Web Interface:**
1. Visit: `http://localhost/stream/generate_password_hash.php`
2. Enter password → Click "Generate Hash"
3. Copy the hash

**Option B - Command Line:**
```bash
php generate_password_hash.php MySecurePassword123
```

### 4️⃣ Set Admin Password (30 seconds)

Edit `config.php` and paste your hash:

```php
define('ADMIN_PASSWORD_HASH', '$2y$10$paste_your_hash_here');
```

### 5️⃣ Test It! (1 minute)

1. **Main Page**: `http://localhost/stream/index.php`
2. **Admin Panel**: `http://localhost/stream/admin.php`
3. Login with your password
4. Add a test activity
5. View it on the main page

## ✅ Done!

Your Activity Stream is ready! 🎉

## 🔒 Security (Important!)

After installation:

```bash
# Delete the password generator
rm generate_password_hash.php

# Set proper permissions (Linux)
chmod 644 *.php *.css
chmod 755 .
```

## 🎨 Customization

### Change Colors

Edit `styles.css`:

```css
:root {
  --primary-color: #6366f1;    /* Change to your color */
  --secondary-color: #8b5cf6;  /* Change to your color */
}
```

### Change Posts Per Page

Edit `config.php`:

```php
define('POSTS_PER_PAGE', 20);  // Change to your preferred number
```

## 🐛 Troubleshooting

### Can't connect to database?
- Check credentials in `config.php`
- Verify MySQL is running: `sudo service mysql status`

### Admin login not working?
- Regenerate password hash
- Clear browser cookies
- Check hash in `config.php`

### Blank page?
- Check PHP error logs
- Enable errors: Add to top of `index.php`:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

## 📚 Need More Help?

- **Full Installation Guide**: See `INSTALLATION.md`
- **Features & Customization**: See `README.md`
- **What Changed**: See `CHANGELOG.md`

## 🎯 Next Steps

1. ✅ Add your first real activity
2. ✅ Customize colors to match your brand
3. ✅ Set up HTTPS for production
4. ✅ Configure regular database backups
5. ✅ Share your activity stream with the world!

---

**Enjoy your modern Activity Stream!** 🚀
