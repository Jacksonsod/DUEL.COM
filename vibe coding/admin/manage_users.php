<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';

// Handle activation/deactivation
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $uid = intval($_GET['id']);
    $action = $_GET['toggle'] === 'activate' ? 1 : 0;
    mysqli_query($conn, "UPDATE users SET is_active = $action WHERE user_id = $uid");
    header("Location: manage_users.php");
    exit();
}

// Fetch all student users
$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'student' ORDER BY created_at DESC");
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Manage Student Users</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($u = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?= htmlspecialchars($u['student_id']) ?></td>
                    <td><?= htmlspecialchars($u['firstname'] . ' ' . $u['lastname']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['phone']) ?></td>
                    <td>
              <span class="badge <?= $u['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                <?= $u['is_active'] ? 'Active' : 'Inactive' ?>
              </span>
                    </td>
                    <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td>
                        <a href="?toggle=<?= $u['is_active'] ? 'deactivate' : 'activate' ?>&id=<?= $u['user_id'] ?>" class="btn btn-sm btn-outline-<?= $u['is_active'] ? 'danger' : 'success' ?>">
                            <?= $u['is_active'] ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
