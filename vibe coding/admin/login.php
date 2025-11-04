<?php
session_start();
require '../includes/db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Secure query using prepared statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? AND role = 'admin' AND is_active = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = 'admin';
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['profile_pic'] = $user['profile_picture'];
            $_SESSION['last_activity'] = time();

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin account not found or inactive.";
    }
}
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-center mb-4">Admin Login</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Student ID</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
