<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session and redirect to login
session_destroy();
header('Location: login.php');
exit();
?>
