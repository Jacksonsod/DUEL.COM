<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';

// Handle candidate deletion
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $cid = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM candidates WHERE candidate_id = $cid");
    header("Location: manage_candidates.php");
    exit();
}

// Fetch candidates with user info and position
$query = "
SELECT c.candidate_id, c.manifesto, c.photo, c.vote_count,
       u.student_id, u.firstname, u.lastname, u.email, u.phone,
       p.position_name
FROM candidates c
JOIN users u ON c.user_id = u.user_id
JOIN positions p ON c.position_id = p.position_id
ORDER BY p.position_name ASC, c.vote_count DESC
";
$candidates = mysqli_query($conn, $query);
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Manage Candidates</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
            <tr>
                <th>Photo</th>
                <th>Student ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Manifesto</th>
                <th>Votes</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($c = mysqli_fetch_assoc($candidates)): ?>
                <tr>
                    <td><img src="../uploads/<?= htmlspecialchars($c['photo']) ?>" width="60" height="60" style="object-fit: cover;" class="rounded-circle" alt="Candidate"></td>
                    <td><?= htmlspecialchars($c['student_id']) ?></td>
                    <td><?= htmlspecialchars($c['firstname'] . ' ' . $c['lastname']) ?></td>
                    <td><?= htmlspecialchars($c['position_name']) ?></td>
                    <td><?= nl2br(htmlspecialchars($c['manifesto'])) ?></td>
                    <td><span class="badge bg-primary"><?= $c['vote_count'] ?></span></td>
                    <td>
                        <small>Email: <?= htmlspecialchars($c['email']) ?><br>
                            Phone: <?= htmlspecialchars($c['phone']) ?></small>
                    </td>
                    <td>
                        <a href="?delete=1&id=<?= $c['candidate_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this candidate?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
