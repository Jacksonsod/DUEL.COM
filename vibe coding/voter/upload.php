<?php
session_start();

// Upload config
$maxSize = 51200; // 50KB
$allowedMime = 'image/jpeg';
$allowedExt = 'jpg';
$uploadDir = __DIR__ . '/../uploads/';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['photo'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload failed. Error code: " . $file['error'];
    } elseif ($file['size'] > $maxSize) {
        $error = "File too large. Max size is 50KB.";
    } elseif (mime_content_type($file['tmp_name']) !== $allowedMime) {
        $error = "Invalid MIME type. Only JPG allowed.";
    } elseif (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== $allowedExt) {
        $error = "Invalid file extension. Only .jpg allowed.";
    } else {
        // Encrypt filename
        $encryptedName = bin2hex(random_bytes(16)) . '.jpg';
        $targetPath = $uploadDir . $encryptedName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $success = "File uploaded successfully.";
            // Save $encryptedName to database if needed
        } else {
            $error = "Failed to save file.";
        }
    }
}
?>

<?php include 'templates/header.php'; ?>
<?php include 'templates/navbar.php'; ?>

<div class="container my-5" style="max-width: 500px;">
    <h3 class="mb-4">Secure File Upload</h3>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Upload JPG (max 50KB)</label>
            <input type="file" name="photo" class="form-control" accept=".jpg" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Upload</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
