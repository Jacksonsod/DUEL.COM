<?php
session_start();
require '../includes/db.php';

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Total records
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role_id = 2");
$total = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total / $limit);

// Fetch records
$result = mysqli_query($conn, "SELECT * FROM users WHERE role_id = 2 LIMIT $limit OFFSET $offset");
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5">
    <h3 class="mb-4">Manage Voters</h3>
    <form method="POST" id="bulkForm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><input type="checkbox" name="selected[]" value="<?= $row['user_id'] ?>"></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['firstname'] . ' ' . $row['lastname'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td>
                            <a href="edit_voter.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            <a href="details_voter.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-info-circle"></i></a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDelete" data-id="<?= $row['user_id'] ?>"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" formaction="edit_selected.php" class="btn btn-outline-primary btn-sm">Edit Selected</button>
                <button type="submit" formaction="delete_selected.php" class="btn btn-outline-danger btn-sm">Delete Selected</button>
            </div>
            <div>
                <span>Total Records: <?= $total ?></span>
                <span class="ms-3">Page <?= $page ?> of <?= $total_pages ?></span>
            </div>
        </div>

        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </form>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="delete_voter.php">
            <input type="hidden" name="delete_id" id="deleteId">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this voter?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name="selected[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('confirmDelete').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-id');
        document.getElementById('deleteId').value = userId;
    });
</script>

<?php include '../templates/footer.php'; ?>
