<?php
require '../includes/session_check.php';
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    $result = mysqli_query($conn, "SELECT password FROM users WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);

    if (password_verify($current, $row['password'])) {
        if ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password = '$hashed' WHERE user_id = $user_id");
            $success = "Password updated successfully.";
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<div class="container my-5">
    <h3 class="mb-4">Change Password</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="col-md-6 mx-auto">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
