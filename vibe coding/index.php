<?php
session_start();
require 'includes/db.php';
include 'templates/header.php';
include 'templates/navbar.php';
?>

<div class="container text-center my-5">
    <img src="assets/images/logo.png" alt="Voting Logo" width="100" class="mb-3">
    <h1>Automated Voting System</h1>
    <p class="lead">Welcome to the IPRC Karongi Student Union Voting Platform</p>

    <div class="mt-4">
        <a href="voter/login.php" class="btn btn-primary me-2">Student Login</a>
        <a href="voter/register.php" class="btn btn-success me-2">Register</a>
        <a href="admin/login.php" class="btn btn-dark">Admin Login</a>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
