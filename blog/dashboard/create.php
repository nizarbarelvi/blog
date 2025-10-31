<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

$errors = [];
$title = '';
$content = '';

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
    
    // Create article
    if (empty($errors)) {
        global $pdo;
        
        $slug = generate_slug($title);
        
        // Check if slug already exists
        $stmt = $pdo->prepare("SELECT id FROM articles WHERE slug = ?");
        $stmt->execute([$slug]);
        
        if ($stmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, author_id, slug) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$title, $content, $_SESSION['user_id'], $slug])) {
            $_SESSION['message'] = 'Article created successfully!';
            header('Location: /blog/dashboard/');
            exit;
        } else {
            $errors[] = 'Failed to create article. Please try again.';
        }
    }
}

$page_title = 'Create Article';
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="form-container">
    <h1>Create New Article</h1>
    
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
            <button type="submit" class="btn btn-primary">Create Article</button>
            <a href="/blog/dashboard/" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>