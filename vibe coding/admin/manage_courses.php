<?php
require '../includes/session_check.php';
requireRole('admin');
require '../includes/db.php';
include '../templates/header.php';
include '../templates/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_name'])) {
    $course_name = trim($_POST['course_name']);
    if (!empty($course_name)) {
        $stmt = $conn->prepare("INSERT INTO courses (course_name) VALUES (?)");
        $stmt->bind_param("s", $course_name);
        $stmt->execute();
    }
}

$result = mysqli_query($conn, "SELECT * FROM courses");
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container my-5">
    <h3 class="mb-4">Manage Courses</h3>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="course_name" class="form-control" placeholder="Enter new course name" required>
            <button class="btn btn-primary" type="submit">Add Course</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Course Name</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $index => $course): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($course['course_name']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../templates/footer.php'; ?>
