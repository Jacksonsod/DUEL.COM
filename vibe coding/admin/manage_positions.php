<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// Handle new position submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['position_name'])) {
    $position_name = trim($_POST['position_name']);
    if (!empty($position_name)) {
        $stmt = $conn->prepare("INSERT INTO positions (position_name) VALUES (?)");
        $stmt->bind_param("s", $position_name);
        $stmt->execute();
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM positions WHERE position_id = $delete_id");
    header("Location: manage_positions.php");
    exit;
}

// Fetch all positions
$result = mysqli_query($conn, "SELECT * FROM positions ORDER BY position_name ASC");
$positions = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <h3 class="mb-4">Manage Election Positions</h3>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="position_name" class="form-control" placeholder="Enter new position name" required>
            <button class="btn btn-primary" type="submit">Add Position</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Position Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($positions as $index => $position): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($position['position_name']) ?></td>
                    <td>
                        <a href="?delete=<?= $position['position_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this position?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
