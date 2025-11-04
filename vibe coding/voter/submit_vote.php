<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$voter_id = $_SESSION['user_id'];

// Check if already voted
$check = mysqli_query($conn, "SELECT * FROM votes WHERE voter_id = $voter_id");
if (mysqli_num_rows($check) > 0) {
    echo "<div class='container my-5'><h3>You have already voted. Thank you!</h3></div>";
    exit();
}

// Validate and process votes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    $votes = $_POST['vote'];

    // Fetch position limits
    $limits = [];
    $res = mysqli_query($conn, "SELECT position_id, vote_limit FROM positions");
    while ($row = mysqli_fetch_assoc($res)) {
        $limits[$row['position_id']] = $row['vote_limit'];
    }

    $errors = [];
    $valid_votes = [];

    foreach ($votes as $position_id => $candidate_ids) {
        $limit = isset($limits[$position_id]) ? $limits[$position_id] : 1;
        if (count($candidate_ids) > $limit) {
            $errors[] = "Too many selections for position ID $position_id.";
        } else {
            foreach ($candidate_ids as $cid) {
                $valid_votes[] = [
                    'voter_id' => $voter_id,
                    'candidate_id' => intval($cid),
                    'position_id' => intval($position_id)
                ];
            }
        }
    }

    if (!empty($errors)) {
        echo "<div class='container my-5'><h3>Vote submission failed:</h3><ul>";
        foreach ($errors as $e) echo "<li>$e</li>";
        echo "</ul></div>";
        exit();
    }

    // Save votes
    foreach ($valid_votes as $vote) {
        $stmt = $conn->prepare("INSERT INTO votes (voter_id, candidate_id, position_id) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $vote['voter_id'], $vote['candidate_id'], $vote['position_id']);
        $stmt->execute();

        // Update candidate vote count
        mysqli_query($conn, "UPDATE candidates SET vote_count = vote_count + 1 WHERE candidate_id = {$vote['candidate_id']}");
    }

    echo "<div class='container my-5'><h3>Thank you! Your vote has been recorded.</h3></div>";
} else {
    echo "<div class='container my-5'><h3>No vote data received.</h3></div>";
}
?>
