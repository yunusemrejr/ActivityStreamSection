<?php
/**
 * Activity Stream - Main Page
 * 
 * Displays activity logs with pagination
 */

require_once 'functions.php';

// Get current page number
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Get activity logs and pagination data
$activityLogs = getActivityLogs($page, POSTS_PER_PAGE);
$totalLogs = getTotalLogCount();
$totalPages = ceil($totalLogs / POSTS_PER_PAGE);

// Ensure current page doesn't exceed total pages
if ($page > $totalPages && $totalPages > 0) {
    $page = $totalPages;
    $activityLogs = getActivityLogs($page, POSTS_PER_PAGE);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Activity Stream - Recent activities and updates from Yunus Emre Vurgun">
    <title>Activity Stream - Yunus Emre Vurgun</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <a href="https://yunusemrevurgun.com/" class="logo-link" aria-label="Go to homepage">
                    <img src="https://yunusemrevurgun.com/logo.webp" alt="Logo" class="logo" width="55" height="55">
                </a>
                <h1 class="title">Activity Stream</h1>
            </div>
            <p class="subtitle">Latest updates and activities</p>
        </header>

        <main class="main-content">
            <?php if (empty($activityLogs)): ?>
                <div class="empty-state">
                    <svg class="empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h2>No activities yet</h2>
                    <p>Check back later for updates!</p>
                </div>
            <?php else: ?>
                <div class="activity-grid">
                    <?php foreach ($activityLogs as $log): ?>
                        <article class="activity-card">
                            <div class="activity-header">
                                <time class="activity-date" datetime="<?php echo sanitizeOutput($log['date']); ?>">
                                    <?php echo sanitizeOutput(date('M d, Y', strtotime($log['date']))); ?>
                                </time>
                                <time class="activity-time" datetime="<?php echo sanitizeOutput($log['time']); ?>">
                                    <?php echo sanitizeOutput(date('g:i A', strtotime($log['time']))); ?>
                                </time>
                            </div>
                            <h2 class="activity-title">
                                <?php echo sanitizeOutput($log['title']); ?>
                            </h2>
                            <div class="activity-body">
                                <?php echo linkifyUrls(sanitizeOutput($log['body'])); ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="pagination" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo ($page - 1); ?>" class="pagination-btn pagination-prev" aria-label="Previous page">
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Previous
                            </a>
                        <?php endif; ?>

                        <div class="pagination-numbers">
                            <?php
                            // Show max 7 page numbers with ellipsis
                            $range = 2;
                            $start = max(1, $page - $range);
                            $end = min($totalPages, $page + $range);

                            if ($start > 1): ?>
                                <a href="?page=1" class="pagination-number">1</a>
                                <?php if ($start > 2): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif;
                            endif;

                            for ($i = $start; $i <= $end; $i++):
                                if ($i == $page): ?>
                                    <span class="pagination-number pagination-current" aria-current="page"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>" class="pagination-number"><?php echo $i; ?></a>
                                <?php endif;
                            endfor;

                            if ($end < $totalPages):
                                if ($end < $totalPages - 1): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="?page=<?php echo $totalPages; ?>" class="pagination-number"><?php echo $totalPages; ?></a>
                            <?php endif; ?>
                        </div>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo ($page + 1); ?>" class="pagination-btn pagination-next" aria-label="Next page">
                                Next
                                <svg viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> Yunus Emre Vurgun. All rights reserved.</p>
            <a href="admin.php" class="admin-link">Admin Panel</a>
        </footer>
    </div>
</body>
</html>
