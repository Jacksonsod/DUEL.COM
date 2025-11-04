<?php
require '../includes/session_check.php';
requireRole('admin'); // Only admins can access

require '../includes/db.php';

// Fetch counts
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'student'"))['total'];
$total_candidates = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM candidates"))['total'];
$total_votes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM votes"))['total'];
$total_positions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM positions"))['total'];
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Admin Dashboard</h3>
    <div class="row text-center">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Registered Students</h5>
                    <h2><?= $total_users ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Candidates</h5>
                    <h2><?= $total_candidates ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Votes Cast</h5>
                    <h2><?= $total_votes ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Positions</h5>
                    <h2><?= $total_positions ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="manage_users.php" class="btn btn-outline-primary me-2">Manage Users</a>
        <a href="manage_candidates.php" class="btn btn-outline-success me-2">Manage Candidates</a>
        <a href="results.php" class="btn btn-outline-dark me-2">View Results</a>
        <a href="backup.php" class="btn btn-outline-warning">Backup Database</a>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
