# Activity Stream

A modern, professional activity stream application for displaying and managing activity logs. Think of it as a personal Twitter-like feed with a powerful admin panel.

![Version](https://img.shields.io/badge/version-2.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4+-purple)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)

## 🌟 Features

- **Modern UI/UX**: Beautiful gradient backgrounds, smooth animations, and card-based layouts
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Pagination**: Efficient browsing with 20 posts per page
- **Auto URL Linking**: Automatically converts URLs in posts to clickable links
- **Admin Panel**: Secure interface for creating and deleting posts
- **Session Management**: 30-minute session timeout for security
- **CSRF Protection**: Built-in security against cross-site request forgery
- **XSS Prevention**: All output is sanitized to prevent cross-site scripting
- **Professional Styling**: Modern CSS with custom properties, smooth transitions, and animations

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## 🚀 Installation

### 1. Database Setup

Create a MySQL database and table:

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
);
```

### 2. Configuration

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'yunusNowHasAcoolActivityLogSystem');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Admin Password Setup

Generate a password hash using PHP:

```php
<?php
echo password_hash('your_secure_password', PASSWORD_DEFAULT);
?>
```

Update the `ADMIN_PASSWORD_HASH` in `config.php` with the generated hash.

### 4. Environment Variables (Optional but Recommended)

For production, use environment variables instead of hardcoded values:

```bash
export DB_HOST="localhost"
export DB_NAME="yunusNowHasAcoolActivityLogSystem"
export DB_USER="your_username"
export DB_PASS="your_password"
export ADMIN_PASSWORD_HASH="your_hashed_password"
```

### 5. File Permissions

Ensure proper file permissions:

```bash
chmod 644 *.php
chmod 644 *.css
chmod 755 .
```

## 📁 Project Structure

```
activity-stream/
├── config.php          # Database and application configuration
├── functions.php       # Core functions and database operations
├── index.php          # Main activity stream page
├── admin.php          # Admin panel for managing activities
├── styles.css         # Main page styles
├── stylesADMIN.css    # Admin panel styles
├── admn.php           # Legacy file (deprecated, use admin.php)
├── README.md          # This file
├── LICENSE            # License information
└── .gitignore         # Git ignore rules
```

## 🎨 Design Features

### Main Page (index.php)
- **Gradient Header**: Eye-catching purple gradient with grid pattern overlay
- **Activity Cards**: Clean card-based layout with hover effects
- **Smart Pagination**: Intelligent page number display with ellipsis
- **Responsive Grid**: Adapts to all screen sizes
- **Professional Typography**: Inter font family for modern look

### Admin Panel (admin.php)
- **Secure Login**: Password-based authentication with session management
- **Dual Forms**: Separate cards for adding and deleting activities
- **Activity Table**: Comprehensive view of all activities with ID, date, time, title, and body
- **Alert System**: Success and error messages with icons
- **CSRF Protection**: Security tokens on all forms

## 🔒 Security Features

1. **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
2. **CSRF Tokens**: All forms include CSRF protection
3. **XSS Prevention**: All output is sanitized with `htmlspecialchars()`
4. **SQL Injection Protection**: Prepared statements for all database queries
5. **Session Timeout**: Automatic logout after 30 minutes of inactivity
6. **Input Validation**: Server-side validation for all user inputs

## 🎯 Usage

### Viewing Activities
1. Navigate to `index.php`
2. Browse activities with pagination
3. Click URLs in activity bodies to visit them

### Managing Activities (Admin)
1. Navigate to `admin.php`
2. Enter your admin password
3. **Add Activity**: Fill in title and body, click "Add Activity"
4. **Delete Activity**: Enter the activity ID, click "Delete Activity"
5. **View All**: Scroll down to see the complete activity table
6. **Logout**: Click the logout button when done

## 🔧 Customization

### Changing Colors
Edit CSS variables in `styles.css` or `stylesADMIN.css`:

```css
:root {
  --primary-color: #6366f1;
  --secondary-color: #8b5cf6;
  --accent-color: #ec4899;
  /* ... more variables */
}
```

### Adjusting Posts Per Page
Edit `config.php`:

```php
define('POSTS_PER_PAGE', 20); // Change to your preferred number
```

### Changing Timezone
Edit `config.php`:

```php
date_default_timezone_set('America/New_York'); // Your timezone
```

## 🐛 Troubleshooting

### Database Connection Errors
- Verify database credentials in `config.php`
- Ensure MySQL service is running
- Check database user permissions

### Admin Login Issues
- Verify password hash is correctly set in `config.php`
- Clear browser cookies and try again
- Check PHP session configuration

### Styling Issues
- Clear browser cache
- Verify CSS files are properly linked
- Check browser console for errors

## 📝 Changelog

### Version 2.0 (Current)
- ✨ Complete UI/UX redesign with modern aesthetics
- 🔒 Enhanced security (CSRF, XSS protection, session management)
- 🏗️ Refactored code structure (config, functions, presentation separation)
- 📱 Improved responsive design
- 🎨 Modern CSS with custom properties and animations
- 🐛 Fixed bugs in original admin.php (typos, function calls)
- 📊 Better admin panel with activity table
- ♿ Improved accessibility features

### Version 1.0 (Original)
- Basic activity stream functionality
- Simple admin panel
- Pagination support
- URL auto-linking

## ⚠️ Important Notes

- **Security**: This application includes basic security measures but is not recommended for enterprise use without additional hardening
- **Backup**: Always backup your database before making changes
- **Testing**: Test thoroughly in a development environment before deploying to production
- **Updates**: Keep PHP and MySQL updated to the latest stable versions

## 🤝 Contributing

This is a personal project, but suggestions and improvements are welcome!

## 📄 License

See LICENSE file for details.

## 👤 Author

**Yunus Emre Vurgun**
- Website: [yunusemrevurgun.com](https://yunusemrevurgun.com)
- Activity Stream: [yunusemrevurgun.com/stream/](https://yunusemrevurgun.com/stream/)

## 🙏 Acknowledgments

- Original development assisted by ChatGPT (GPT-3.5)
- Modernization and refactoring completed with advanced AI assistance
- Inter font family by Rasmus Andersson
- Icons: Heroicons (embedded as SVG)

---

**Note**: The legacy `admn.php` file has been deprecated. Please use `admin.php` instead.
