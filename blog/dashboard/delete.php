<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('HTTP/1.1 400 Bad Request');
    die('Invalid article ID.');
}

$article_id = intval($_GET['id']);

// Check if article exists
$stmt = $pdo->prepare("SELECT id FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    header('HTTP/1.1 404 Not Found');
    die('Article not found.');
}

// Delete article
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$article_id]);

$_SESSION['message'] = 'Article deleted successfully!';
header('Location: /blog/dashboard/');
exit;
?>