<?php
session_start();
require '../includes/db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if account is active
        if (!$user['is_active']) {
            $error = "Your account is inactive. Please contact the administrator.";
        }
        // Verify password
        elseif (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['initiated'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['profile_pic'] = $user['profile_picture'];
            $_SESSION['last_activity'] = time();

            // Redirect based on role
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: welcome.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Student ID not found.";
    }
}
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-center mb-4">Login</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
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
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="mt-3 text-center">
                <a href="recover.php">Forgot Password?</a> |
                <a href="register.php">Create Account</a>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
