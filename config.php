<?php
/**
 * Database Configuration
 * 
 * This file contains database connection settings.
 * For production, use environment variables or a separate .env file.
 */

// Database configuration - Update these values for your environment
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'yunusNowHasAcoolActivityLogSystem');
define('DB_USER', getenv('DB_USER') ?: 'thesupercooladmin123');
define('DB_PASS', getenv('DB_PASS') ?: 'someones-super-special-password-idk');

// Admin authentication - Store hashed password
// Generate hash using: password_hash('your_password', PASSWORD_DEFAULT)
define('ADMIN_PASSWORD_HASH', getenv('ADMIN_PASSWORD_HASH') ?: '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

// Pagination settings
define('POSTS_PER_PAGE', 20);

// Timezone
date_default_timezone_set('UTC');

/**
 * Get database connection
 * 
 * @return mysqli Database connection object
 * @throws Exception if connection fails
 */
function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        throw new Exception("We are experiencing technical difficulties. Please try again later.");
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}
