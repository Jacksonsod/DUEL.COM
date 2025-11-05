<?php
session_start();
require '../includes/session_check.php';
requireRole('student');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// Assume $_SESSION['ballot'] holds position_id => candidate_id
$ballot = $_SESSION['ballot'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['student_id'];
    foreach ($ballot as $position_id => $candidate_id) {
        $stmt = $conn->prepare("INSERT INTO votes (student_id, position_id, candidate_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $student_id, $position_id, $candidate_id);
        $stmt->execute();
    }
    mysqli_query($conn, "UPDATE users SET voted = 1 WHERE student_id = '$student_id'");
    unset($_SESSION['ballot']);
    header("Location: thank_you.php");
    exit;
}
?>

<div class="container my-5">
    <h3 class="mb-4">Review Your Ballot</h3>
    <form method="POST">
        <ul class="list-group mb-4">
            <?php foreach ($ballot as $position_id => $candidate_id):
                $position = mysqli_fetch_assoc(mysqli_query($conn, "SELECT position_name FROM positions WHERE position_id = $position_id"));
                $candidate = mysqli_fetch_assoc(mysqli_query($conn, "SELECT firstname, lastname FROM candidates WHERE candidate_id = $candidate_id"));
                ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($position['position_name']) ?>:</strong>
                    <?= htmlspecialchars($candidate['firstname'] . ' ' . $candidate['lastname']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="submit" class="btn btn-success">Confirm & Submit Vote</button>
        <a href="vote.php" class="btn btn-secondary">Go Back & Edit</a>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
