<?php
session_start();
require_once 'functions.php';

// Check if user needs to be logged in
function requireLogin() {
    if (!isLoggedIn()) {
        redirect(SITE_URL . '/login.php', 'Please login to access that page.');
    }
}

// Check if user is admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        redirect(SITE_URL . '/index.php', 'Access denied. Admin privileges required.');
    }
}

// Check article ownership
function isArticleOwner($article_id, $pdo) {
    $stmt = $pdo->prepare("SELECT author_id FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();
    
    return $article && ($article['author_id'] == $_SESSION['user_id'] || isAdmin());
}
?>
