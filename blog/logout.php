<?php
require_once 'includes/config.php';

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to homepage
header('Location: /blog/');
exit;
?>