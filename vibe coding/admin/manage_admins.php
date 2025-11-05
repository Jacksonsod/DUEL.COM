<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// Handle activation toggle
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $toggle = $_GET['toggle'] === 'activate' ? 1 : 0;
    mysqli_query($conn, "UPDATE users SET status = $toggle WHERE user_id = $id AND role = 'admin'");
    header("Location: manage_admins.php");
    exit;
}

// Fetch all admin users
$result = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' ORDER BY lastname ASC");
$admins = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <h3 class="mb-4">Manage Admin Accounts</h3>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Admin ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $index => $admin): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($admin['student_id']) ?></td>
                    <td><?= htmlspecialchars($admin['firstname'] . ' ' . $admin['lastname']) ?></td>
                    <td>
                        <?= isset($admin['status']) && $admin['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?>
                    </td>
                    <td>
                        <?php if (isset($admin['status']) && $admin['status']): ?>
                            <a href="?toggle=deactivate&id=<?= $admin['user_id'] ?>" class="btn btn-sm btn-outline-danger">Deactivate</a>
                        <?php else: ?>
                            <a href="?toggle=activate&id=<?= $admin['user_id'] ?>" class="btn btn-sm btn-outline-success">Activate</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
