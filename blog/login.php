<?php
require_once 'includes/header.php';
require_once 'includes/auth.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: /blog/');
    exit;
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validate_csrf_token($csrf_token)) {
        $errors[] = 'Invalid CSRF token.';
    }
    
    // Validate input
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    
    // Authenticate user
    if (empty($errors)) {
        global $pdo;
        
        $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            
            header('Location: /blog/');
            exit;
        } else {
            $errors[] = 'Invalid username or password.';
        }
    }
}
?>

<div class="form-container">
    <h1>Login</h1>
    
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
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo sanitize_output($username); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    
    <p style="margin-top: 1rem;">
        Don't have an account? <a href="/blog/register.php">Register here</a>.
    </p>
</div>

<?php require_once 'includes/footer.php'; ?>