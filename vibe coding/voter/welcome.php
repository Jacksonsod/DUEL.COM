<?php
require '../includes/session_check.php';
requireRole('student'); // Only students can access this page

$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
$profile_pic = $_SESSION['profile_pic'] ?? 'default.jpg';
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="../uploads/<?= htmlspecialchars($profile_pic) ?>" class="rounded-circle mb-3" width="120" height="120" alt="Profile Picture" style="object-fit: cover;">
                    <h3>Welcome, <?= htmlspecialchars($firstname . ' ' . $lastname) ?>!</h3>
                    <p class="text-muted">You're logged in as a <strong>student</strong>.</p>

                    <div class="d-grid gap-2 col-6 mx-auto mt-4">
                        <a href="vote.php" class="btn btn-success btn-lg">Cast Your Vote</a>
                        <a href="results.php" class="btn btn-outline-primary">View Election Results</a>
                        <a href="profile.php" class="btn btn-outline-secondary">Edit Profile</a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
