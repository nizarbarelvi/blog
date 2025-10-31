<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    header('Location: /blog/');
    exit;
}

$query = trim($_GET['q']);
$articles = search_articles($query);
$page_title = "Search Results for \"$query\"";
?>

<h1>Search Results for "<?php echo sanitize_output($query); ?>"</h1>

<?php if (empty($articles)): ?>
    <p>No articles found matching your search.</p>
<?php else: ?>
    <div class="articles-grid">
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <h2>
                    <a href="/blog/article.php?slug=<?php echo sanitize_output($article['slug']); ?>">
                        <?php echo sanitize_output($article['title']); ?>
                    </a>
                </h2>
                <div class="article-meta">
                    By <?php echo sanitize_output($article['author_name']); ?> 
                    on <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                </div>
                <div class="article-content">
                    <?php echo sanitize_output(get_content_snippet($article['content'])); ?>
                </div>
                <a href="/blog/article.php?slug=<?php echo sanitize_output($article['slug']); ?>" class="read-more">
                    Read More →
                </a>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>