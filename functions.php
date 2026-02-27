<?php
/**
 * Activity Stream Functions
 * 
 * This file contains all database operations and utility functions
 */

require_once 'config.php';

/**
 * Get activity logs with pagination
 * 
 * @param int $page Current page number
 * @param int $perPage Number of posts per page
 * @return array Array of activity logs
 */
function getActivityLogs($page = 1, $perPage = POSTS_PER_PAGE) {
    try {
        $conn = getDbConnection();
        
        $offset = ($page - 1) * $perPage;
        
        $stmt = $conn->prepare("SELECT id, date, time, title, body FROM activitylogs ORDER BY date DESC, time DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $perPage, $offset);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $rows = [];
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $stmt->close();
        $conn->close();
        
        return $rows;
    } catch (Exception $e) {
        error_log("Error fetching activity logs: " . $e->getMessage());
        return [];
    }
}

/**
 * Get total count of activity logs
 * 
 * @return int Total number of logs
 */
function getTotalLogCount() {
    try {
        $conn = getDbConnection();
        
        $result = $conn->query("SELECT COUNT(*) AS totalLogs FROM activitylogs");
        $totalLogs = $result->fetch_assoc()['totalLogs'];
        
        $conn->close();
        
        return $totalLogs;
    } catch (Exception $e) {
        error_log("Error getting total log count: " . $e->getMessage());
        return 0;
    }
}

/**
 * Add a new activity log
 * 
 * @param string $title Activity title
 * @param string $body Activity body
 * @return bool Success status
 */
function addActivity($title, $body) {
    try {
        $conn = getDbConnection();
        
        $date = date('Y-m-d');
        $time = date('H:i:s');
        
        $stmt = $conn->prepare("INSERT INTO activitylogs (date, time, title, body) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $date, $time, $title, $body);
        $result = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
        return $result;
    } catch (Exception $e) {
        error_log("Error adding activity: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete an activity log by ID
 * 
 * @param int $id Activity ID
 * @return bool Success status
 */
function deleteActivity($id) {
    try {
        $conn = getDbConnection();
        
        $stmt = $conn->prepare("DELETE FROM activitylogs WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        
        $stmt->close();
        $conn->close();
        
        return $result;
    } catch (Exception $e) {
        error_log("Error deleting activity: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all activity logs for admin panel
 * 
 * @return array Array of all activity logs
 */
function getAllActivities() {
    try {
        $conn = getDbConnection();
        
        $result = $conn->query("SELECT id, date, time, title, body FROM activitylogs ORDER BY date DESC, time DESC");
        $rows = [];
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        
        $conn->close();
        
        return $rows;
    } catch (Exception $e) {
        error_log("Error fetching all activities: " . $e->getMessage());
        return [];
    }
}

/**
 * Authenticate admin user
 * 
 * @param string $password Password to verify
 * @return bool Authentication status
 */
function authenticateAdmin($password) {
    return password_verify($password, ADMIN_PASSWORD_HASH);
}

/**
 * Convert URLs in text to clickable links
 * 
 * @param string $text Text to process
 * @return string Processed text with clickable links
 */
function linkifyUrls($text) {
    $pattern = '/\b(?:https?:\/\/|www\.)\S+\b/i';
    $replacement = '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>';
    return preg_replace($pattern, $replacement, $text);
}

/**
 * Sanitize output for HTML display
 * 
 * @param string $text Text to sanitize
 * @return string Sanitized text
 */
function sanitizeOutput($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool Verification status
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
