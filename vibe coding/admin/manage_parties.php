<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['party_name'])) {
    $party_name = trim($_POST['party_name']);
    if (!empty($party_name)) {
        $stmt = $conn->prepare("INSERT INTO parties (party_name) VALUES (?)");
        $stmt->bind_param("s", $party_name);
        $stmt->execute();
    }
}

// Fetch all parties
$result = mysqli_query($conn, "SELECT * FROM parties");
$parties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <h3 class="mb-4">Manage Political Parties</h3>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="party_name" class="form-control" placeholder="Enter new party name" required>
            <button class="btn btn-primary" type="submit">Add Party</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Party Name</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($parties as $index => $party): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($party['party_name']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../templates/footer.php'; ?>
