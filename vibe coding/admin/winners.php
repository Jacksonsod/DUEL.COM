<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// Fetch winners per position
$query = "
SELECT c.*, p.position_name, COUNT(v.vote_id) AS total_votes
FROM candidates c
JOIN positions p ON c.position_id = p.position_id
LEFT JOIN votes v ON c.candidate_id = v.candidate_id
GROUP BY c.candidate_id
HAVING total_votes = (
  SELECT MAX(vote_count) FROM (
    SELECT COUNT(*) AS vote_count
    FROM votes v2
    WHERE v2.position_id = c.position_id
    GROUP BY v2.candidate_id
  ) AS sub
)
ORDER BY p.position_name ASC
";

$result = mysqli_query($conn, $query);
$winners = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Election Winners</h3>
        <button onclick="window.print()" class="btn btn-outline-primary">🖨️ Print</button>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
            <tr>
                <th>Position</th>
                <th>Candidate</th>
                <th>Party</th>
                <th>Course</th>
                <th>Votes</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($winners as $winner): ?>
                <tr>
                    <td><?= htmlspecialchars($winner['position_name']) ?></td>
                    <td><?= htmlspecialchars($winner['firstname'] . ' ' . $winner['lastname']) ?></td>
                    <td><?= htmlspecialchars($winner['party_id']) ?></td>
                    <td><?= htmlspecialchars($winner['course_id']) ?></td>
                    <td><?= $winner['total_votes'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
