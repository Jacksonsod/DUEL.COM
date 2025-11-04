<?php
session_start();

// Regenerate session ID on login
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Timeout after 10 minutes (600 seconds)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
    session_unset();
    session_destroy();
    header("Location: ../voter/login.php?timeout=1");
    exit();
}
$_SESSION['last_activity'] = time();

// Role-based access control
function requireRole($role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header("Location: ../voter/login.php?unauthorized=1");
        exit();
    }
}
