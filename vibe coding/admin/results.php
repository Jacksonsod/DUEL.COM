<?php
session_start();
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

// 🔐 Admin access check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../voter/login.php");
    exit();
}

// 📊 Fetch positions and candidates with vote counts
$positions = mysqli_query($conn, "SELECT * FROM positions");
$position_data = [];

while ($pos = mysqli_fetch_assoc($positions)) {
    $pid = $pos['position_id'];
    $candidates = mysqli_query($conn, "
        SELECT 
            CONCAT(u.firstname, ' ', u.lastname) AS fullname,
            c.photo,
            COUNT(v.vote_id) AS votes
        FROM candidates c
        JOIN users u ON c.user_id = u.user_id
        LEFT JOIN votes v ON c.candidate_id = v.candidate_id
        WHERE c.position_id = $pid
        GROUP BY c.candidate_id
    ");
    $position_data[] = [
            'name' => $pos['position_name'],
            'candidates' => mysqli_fetch_all($candidates, MYSQLI_ASSOC)
    ];
}

// 📈 Voter stats
$total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'student'");
$voted = mysqli_query($conn, "SELECT COUNT(DISTINCT voter_id) AS voted FROM votes");

$total_voters = mysqli_fetch_assoc($total)['total'];
$voted_count = mysqli_fetch_assoc($voted)['voted'];
$not_voted = $total_voters - $voted_count;
?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Live Election Results</h3>

    <?php foreach ($position_data as $pos): ?>
        <div class="mb-5">
            <h5 class="text-center"><?= htmlspecialchars($pos['name']) ?></h5>
            <canvas id="bar_<?= md5($pos['name']) ?>"></canvas>
            <canvas id="pie_<?= md5($pos['name']) ?>" class="mt-3"></canvas>
        </div>
    <?php endforeach; ?>

    <div class="mb-5">
        <h5 class="text-center">Voter Participation</h5>
        <canvas id="voterStats"></canvas>
    </div>

    <div class="text-center">
        <button onclick="window.print()" class="btn btn-outline-primary">Print Results</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php foreach ($position_data as $pos):
    $labels = [];
    $votes = [];
    foreach ($pos['candidates'] as $c) {
        $labels[] = '"' . addslashes($c['fullname']) . '"';
        $votes[] = $c['votes'];
    }
    $max = max($votes);
    $bar_id = "bar_" . md5($pos['name']);
    $pie_id = "pie_" . md5($pos['name']);
    ?>
    new Chart(document.getElementById("<?= $bar_id ?>"), {
        type: 'bar',
        data: {
            labels: [<?= implode(',', $labels) ?>],
            datasets: [{
                label: 'Votes',
                data: [<?= implode(',', $votes) ?>],
                backgroundColor: [<?= implode(',', array_map(fn($v) => $v == $max ? "'#0d6efd'" : "'#6c757d'", $votes)) ?>]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: '<?= addslashes($pos['name']) ?> Results' }
            }
        }
    });

    new Chart(document.getElementById("<?= $pie_id ?>"), {
        type: 'pie',
        data: {
            labels: [<?= implode(',', $labels) ?>],
            datasets: [{
                data: [<?= implode(',', $votes) ?>],
                backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: '<?= addslashes($pos['name']) ?> Vote Share' }
            }
        }
    });
    <?php endforeach; ?>

    new Chart(document.getElementById("voterStats"), {
        type: 'pie',
        data: {
            labels: ['Voted', 'Not Voted'],
            datasets: [{
                data: [<?= $voted_count ?>, <?= $not_voted ?>],
                backgroundColor: ['#198754', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Voter Participation (<?= $total_voters ?> total)' }
            }
        }
    });
</script>

<?php include '../templates/footer.php'; ?>
