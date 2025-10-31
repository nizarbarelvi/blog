<?php
// Use absolute paths for includes from dashboard
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$articles = get_articles(1, 100); // Get all articles for admin
$page_title = 'Admin Dashboard';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="dashboard-header">
    <h1>Admin Dashboard</h1>
    <a href="/blog/dashboard/create.php" class="btn btn-primary">Create New Article</a>
</div>

<div class="articles-list">
    <?php foreach ($articles as $article): ?>
        <div class="article-item">
            <div class="article-info">
                <h3><?php echo sanitize_output($article['title']); ?></h3>
                <div class="article-meta">
                    By <?php echo sanitize_output($article['author_name']); ?> 
                    on <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                    <?php if ($article['updated_at'] != $article['created_at']): ?>
                        (Updated: <?php echo date('F j, Y', strtotime($article['updated_at'])); ?>)
                    <?php endif; ?>
                </div>
            </div>
            <div class="article-actions">
                <a href="/blog/dashboard/edit.php?id=<?php echo $article['id']; ?>" class="btn btn-secondary">Edit</a>
                <a href="/blog/dashboard/delete.php?id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>