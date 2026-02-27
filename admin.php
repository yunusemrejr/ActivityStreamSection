<?php
/**
 * Activity Stream - Admin Panel
 * 
 * Manage activity logs (add/delete)
 */

session_start();

require_once 'functions.php';

$error = '';
$success = '';

// Handle login
if (isset($_POST['login'])) {
    $password = $_POST['password'] ?? '';
    
    if (!empty($password) && authenticateAdmin($password)) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_login_time'] = time();
        generateCsrfToken();
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid password. Please try again.';
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}

// Check if admin is authenticated
$isAuthenticated = isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;

// Session timeout (30 minutes)
if ($isAuthenticated && isset($_SESSION['admin_login_time'])) {
    if (time() - $_SESSION['admin_login_time'] > 1800) {
        session_destroy();
        header('Location: admin.php');
        exit;
    }
    $_SESSION['admin_login_time'] = time(); // Refresh session time
}

// Handle authenticated actions
if ($isAuthenticated) {
    // Handle add activity
    if (isset($_POST['add'])) {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = 'Invalid security token. Please try again.';
        } else {
            $title = trim($_POST['title'] ?? '');
            $body = trim($_POST['body'] ?? '');
            
            if (empty($title) || empty($body)) {
                $error = 'Title and body are required.';
            } else {
                if (addActivity($title, $body)) {
                    $success = 'Activity added successfully!';
                } else {
                    $error = 'Failed to add activity. Please try again.';
                }
            }
        }
    }
    
    // Handle delete activity
    if (isset($_POST['delete'])) {
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $error = 'Invalid security token. Please try again.';
        } else {
            $id = $_POST['id'] ?? '';
            
            if (empty($id) || !is_numeric($id)) {
                $error = 'Invalid activity ID.';
            } else {
                if (deleteActivity((int)$id)) {
                    $success = 'Activity deleted successfully!';
                } else {
                    $error = 'Failed to delete activity. Please check the ID and try again.';
                }
            }
        }
    }
    
    // Get all activities for display
    $allActivities = getAllActivities();
}

$csrfToken = $isAuthenticated ? generateCsrfToken() : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isAuthenticated ? 'Admin Panel' : 'Admin Login'; ?> - Activity Stream</title>
    <link rel="stylesheet" href="stylesADMIN.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php if ($isAuthenticated): ?>
        <!-- Authenticated Admin Panel -->
        <div class="admin-container">
            <header class="admin-header">
                <div class="header-content">
                    <h1>Activity Stream Admin</h1>
                    <div class="header-actions">
                        <a href="index.php" class="btn btn-secondary">View Site</a>
                        <a href="?logout" class="btn btn-danger" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
                    </div>
                </div>
            </header>

            <main class="admin-main">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo sanitizeOutput($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo sanitizeOutput($success); ?>
                    </div>
                <?php endif; ?>

                <div class="admin-grid">
                    <!-- Add Activity Form -->
                    <section class="admin-card">
                        <h2 class="card-title">Add New Activity</h2>
                        <form method="POST" action="admin.php" class="admin-form">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            
                            <div class="form-group">
                                <label for="title" class="form-label">Title</label>
                                <input 
                                    type="text" 
                                    name="title" 
                                    id="title" 
                                    class="form-input" 
                                    placeholder="Enter activity title"
                                    required
                                    maxlength="255"
                                >
                            </div>
                            
                            <div class="form-group">
                                <label for="body" class="form-label">Body</label>
                                <textarea 
                                    name="body" 
                                    id="body" 
                                    class="form-textarea" 
                                    placeholder="Enter activity description"
                                    required
                                    rows="5"
                                ></textarea>
                            </div>
                            
                            <button type="submit" name="add" class="btn btn-primary btn-block">
                                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Add Activity
                            </button>
                        </form>
                    </section>

                    <!-- Delete Activity Form -->
                    <section class="admin-card">
                        <h2 class="card-title">Delete Activity</h2>
                        <form method="POST" action="admin.php" class="admin-form" onsubmit="return confirm('Are you sure you want to delete this activity? This action cannot be undone.');">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                            
                            <div class="form-group">
                                <label for="id" class="form-label">Activity ID</label>
                                <input 
                                    type="number" 
                                    name="id" 
                                    id="id" 
                                    class="form-input" 
                                    placeholder="Enter activity ID to delete"
                                    required
                                    min="1"
                                >
                                <p class="form-help">Find the ID in the activity list below</p>
                            </div>
                            
                            <button type="submit" name="delete" class="btn btn-danger btn-block">
                                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Delete Activity
                            </button>
                        </form>
                    </section>
                </div>

                <!-- Activity List -->
                <section class="admin-card activity-list-card">
                    <h2 class="card-title">All Activities (<?php echo count($allActivities); ?>)</h2>
                    
                    <?php if (empty($allActivities)): ?>
                        <div class="empty-state">
                            <p>No activities found. Add your first activity above!</p>
                        </div>
                    <?php else: ?>
                        <div class="activity-table-wrapper">
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Title</th>
                                        <th>Body</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allActivities as $activity): ?>
                                        <tr>
                                            <td class="id-cell"><?php echo sanitizeOutput($activity['id']); ?></td>
                                            <td><?php echo sanitizeOutput(date('M d, Y', strtotime($activity['date']))); ?></td>
                                            <td><?php echo sanitizeOutput(date('g:i A', strtotime($activity['time']))); ?></td>
                                            <td class="title-cell"><?php echo sanitizeOutput($activity['title']); ?></td>
                                            <td class="body-cell"><?php echo sanitizeOutput(substr($activity['body'], 0, 100)) . (strlen($activity['body']) > 100 ? '...' : ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    <?php else: ?>
        <!-- Login Form -->
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <svg class="login-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                    </svg>
                    <h1>Admin Login</h1>
                    <p>Enter your password to access the admin panel</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo sanitizeOutput($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="admin.php" class="login-form">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-input" 
                            placeholder="Enter your password"
                            required
                            autofocus
                        >
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary btn-block">
                        <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Login
                    </button>
                </form>

                <div class="login-footer">
                    <a href="index.php" class="back-link">← Back to Activity Stream</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
