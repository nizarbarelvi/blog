<?php
require_once __DIR__ . '/config.php';

function get_articles($page = 1, $per_page = ARTICLES_PER_PAGE) {
    global $pdo;
    
    $offset = ($page - 1) * $per_page;
    $stmt = $pdo->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        JOIN users u ON a.author_id = u.id 
        ORDER BY a.created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':limit', (int)$per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function get_article_by_slug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        JOIN users u ON a.author_id = u.id 
        WHERE a.slug = ?
    ");
    $stmt->execute([$slug]);
    
    return $stmt->fetch();
}

function search_articles($query) {
    global $pdo;
    
    $search_term = "%$query%";
    $stmt = $pdo->prepare("
        SELECT a.*, u.username as author_name 
        FROM articles a 
        JOIN users u ON a.author_id = u.id 
        WHERE a.title LIKE ? OR a.content LIKE ? 
        ORDER BY a.created_at DESC
    ");
    $stmt->execute([$search_term, $search_term]);
    
    return $stmt->fetchAll();
}

function get_total_articles() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM articles");
    return $stmt->fetch()['count'];
}

function get_content_snippet($content, $length = 150) {
    $content = strip_tags($content);
    if (strlen($content) > $length) {
        $content = substr($content, 0, $length) . '...';
    }
    return $content;
}
?>