<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

if (!isset($_GET['slug'])) {
    header('HTTP/1.1 404 Not Found');
    die('Article not found.');
}

$article = get_article_by_slug($_GET['slug']);

if (!$article) {
    header('HTTP/1.1 404 Not Found');
    die('Article not found.');
}

$page_title = $article['title'];
?>

<article class="article-single">
    <header class="article-header">
        <h1><?php echo sanitize_output($article['title']); ?></h1>
        <div class="article-meta">
            By <?php echo sanitize_output($article['author_name']); ?> 
            on <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
            <?php if ($article['updated_at'] != $article['created_at']): ?>
                (Updated on <?php echo date('F j, Y', strtotime($article['updated_at'])); ?>)
            <?php endif; ?>
        </div>
    </header>
    
    <div class="article-content">
        <?php echo nl2br(sanitize_output($article['content'])); ?>
    </div>
</article>

<?php require_once 'includes/footer.php'; ?>