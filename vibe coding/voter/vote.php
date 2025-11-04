<?php
session_start();
require '../includes/db.php';

// Check login and voting status
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Check if already voted
$check = mysqli_query($conn, "SELECT * FROM votes WHERE voter_id = {$_SESSION['user_id']}");
if (mysqli_num_rows($check) > 0) {
    echo "<div class='container my-5'><h3>You have already voted. Thank you!</h3></div>";
    exit();
}

// Fetch positions and candidates
$positions = mysqli_query($conn, "SELECT * FROM positions ORDER BY position_order ASC");
$position_data = [];
while ($pos = mysqli_fetch_assoc($positions)) {
    $pid = $pos['position_id'];
    $candidates = mysqli_query($conn, "SELECT * FROM candidates WHERE position_id = $pid");
    $position_data[] = [
        'id' => $pid,
        'name' => $pos['position_name'],
        'limit' => $pos['vote_limit'],
        'candidates' => mysqli_fetch_all($candidates, MYSQLI_ASSOC)
    ];
}
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <h3 class="mb-4 text-center">Cast Your Vote</h3>
    <form method="POST" action="submit_vote.php" id="voteForm">
        <ul class="nav nav-tabs" id="voteTabs" role="tablist">
            <?php foreach ($position_data as $index => $pos): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $index === 0 ? 'active' : '' ?>" id="tab<?= $pos['id'] ?>" data-bs-toggle="tab" data-bs-target="#pane<?= $pos['id'] ?>" type="button" role="tab">
                        <?= $pos['name'] ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content border p-3" id="voteTabContent">
            <?php foreach ($position_data as $index => $pos): ?>
                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="pane<?= $pos['id'] ?>" role="tabpanel">
                    <h5>Select up to <?= $pos['limit'] ?> candidate(s)</h5>
                    <div class="row">
                        <?php foreach ($pos['candidates'] as $cand): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="../uploads/<?= $cand['photo'] ?>" class="card-img-top" alt="Candidate" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= $cand['fullname'] ?></h6>
                                        <p class="card-text"><?= $cand['manifesto'] ?></p>
                                        <div class="form-check">
                                            <input class="form-check-input vote-check" type="checkbox" name="vote[<?= $pos['id'] ?>][]" value="<?= $cand['candidate_id'] ?>" data-limit="<?= $pos['limit'] ?>" data-group="group<?= $pos['id'] ?>">
                                            <label class="form-check-label">Select</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 text-center">
            <button type="button" class="btn btn-outline-primary" onclick="reviewBallot()">Review Ballot</button>
            <button type="submit" class="btn btn-success ms-2">Submit Vote</button>
        </div>
    </form>
</div>

<!-- Ballot Review Modal -->
<div class="modal fade" id="ballotModal" tabindex="-1" aria-labelledby="ballotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="ballotModalLabel">Review Your Ballot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="ballotContent">
                <!-- Filled by JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Edit</button>
                <button type="submit" form="voteForm" class="btn btn-success">Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.vote-check').forEach(cb => {
        cb.addEventListener('change', function () {
            const group = this.dataset.group;
            const limit = parseInt(this.dataset.limit);
            const selected = document.querySelectorAll(`input[data-group="${group}"]:checked`);
            if (selected.length > limit) {
                this.checked = false;
                alert(`You can only select up to ${limit} candidate(s) for this position.`);
            }
        });
    });

    function reviewBallot() {
        const selections = document.querySelectorAll('.vote-check:checked');
        let html = '<ul class="list-group">';
        if (selections.length === 0) {
            html += '<li class="list-group-item">No selections made.</li>';
        } else {
            selections.forEach(cb => {
                const label = cb.closest('.card-body').querySelector('.card-title').innerText;
                const position = cb.closest('.tab-pane').querySelector('h5').innerText;
                html += `<li class="list-group-item"><strong>${position}:</strong> ${label}</li>`;
            });
        }
        html += '</ul>';
        document.getElementById('ballotContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('ballotModal')).show();
    }
</script>

<?php include '../templates/footer.php'; ?>
