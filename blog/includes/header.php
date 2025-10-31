<?php
// Fix the relative paths for includes
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitize_output($page_title) . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/blog/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1 class="logo"><a href="/blog/"><?php echo SITE_NAME; ?></a></h1>
            <nav class="nav">
                <form class="search-form" action="/blog/search.php" method="GET">
                    <input type="search" name="q" placeholder="Search articles..." required>
                    <button type="submit">Search</button>
                </form>
                <ul class="nav-menu">
                    <li><a href="/blog/">Home</a></li>
                    <?php if (is_logged_in()): ?>
                        <?php if (is_admin()): ?>
                            <li><a href="/blog/dashboard/">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/blog/logout.php">Logout (<?php echo sanitize_output($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li><a href="/blog/login.php">Login</a></li>
                        <li><a href="/blog/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="container">