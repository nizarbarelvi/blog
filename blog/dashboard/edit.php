<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('HTTP/1.1 400 Bad Request');
    die('Invalid article ID.');
}

$article_id = intval($_GET['id']);
global $pdo;

// Get existing article data
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    header('HTTP/1.1 404 Not Found');
    die('Article not found.');
}

$errors = [];
$title = $article['title'];
$content = $article['content'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validate_csrf_token($csrf_token)) {
        $errors[] = 'Invalid CSRF token.';
    }
    
    // Validate input
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    if (empty($content)) {
        $errors[] = 'Content is required.';
    }
    
    // Update article
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        
        if ($stmt->execute([$title, $content, $article_id])) {
            $_SESSION['message'] = 'Article updated successfully!';
            header('Location: /blog/dashboard/');
            exit;
        } else {
            $errors[] = 'Failed to update article. Please try again.';
        }
    }
}

$page_title = 'Edit Article';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="form-container">
    <h1>Edit Article</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="message error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo sanitize_output($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo sanitize_output($title); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?php echo sanitize_output($content); ?></textarea>
        </div>
        
        <div class="actions">
            <button type="submit" class="btn btn-primary">Update Article</button>
            <a href="/blog/dashboard/" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>