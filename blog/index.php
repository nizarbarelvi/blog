<?php
require_once 'includes/header.php';
require_once 'includes/functions.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$articles = get_articles($page);
$total_articles = get_total_articles();
$total_pages = ceil($total_articles / ARTICLES_PER_PAGE);
?>

<h1>Latest Articles</h1>

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

<?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="current"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="/blog/?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>