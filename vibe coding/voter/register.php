<?php
session_start();
require '../includes/db.php';

$error = '';
$success = '';

function isValidPhone($phone) {
    return preg_match('/^(078|073)\d{7}$/', $phone);
}

function isStrongPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if (isset($_POST['register'])) {
    $student_id = trim($_POST['student_id']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $picture = $_FILES['picture'];

    // Validation
    if (!$student_id || !$firstname || !$lastname || !$email || !$phone || !$password || !$confirm || !$picture['name']) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (!isValidPhone($phone)) {
        $error = "Phone must be 10 digits starting with 078 or 073.";
    } elseif (!isStrongPassword($password)) {
        $error = "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
    } elseif (stripos($password, $firstname) !== false || stripos($password, $lastname) !== false || stripos($password, $student_id) !== false) {
        $error = "Password should not contain your name or student ID.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($picture['type'] !== 'image/jpeg') {
        $error = "Profile picture must be a JPG file.";
    } elseif ($picture['size'] > 51200) {
        $error = "Profile picture must be less than 50KB.";
    } else {
        // Check uniqueness
        $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ? OR email = ?");
        $stmt->bind_param("ss", $student_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Student ID or email already registered.";
        } else {
            // Save image
            $filename = uniqid() . ".jpg";
            $target = "../uploads/" . $filename;
            move_uploaded_file($picture['tmp_name'], $target);

            // Insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (student_id, firstname, lastname, email, phone, password, profile_picture, role, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'student', 1)");
            $stmt->bind_param("sssssss", $student_id, $firstname, $lastname, $email, $phone, $hash, $filename);
            if ($stmt->execute()) {
                $success = "Registration successful. Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<?php include '../templates/header.php'; ?>
<?php include '../templates/navbar.php'; ?>

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="text-center mb-4">Student Registration</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Student ID</label>
                    <input type="text" name="student_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Firstname</label>
                    <input type="text" name="firstname" class="form-control" required pattern="[A-Za-z]+">
                </div>
                <div class="mb-3">
                    <label class="form-label">Lastname</label>
                    <input type="text" name="lastname" class="form-control" required pattern="[A-Za-z]+">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" required pattern="^(078|073)\d{7}$">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Picture (JPG, max 50KB)</label>
                    <input type="file" name="picture" class="form-control" accept=".jpg" required>
                </div>
                <button type="submit" name="register" class="btn btn-success w-100">Register</button>
            </form>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
