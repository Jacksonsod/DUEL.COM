<?php
session_start();
require '../includes/session_check.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
    $file = $_FILES['sql_file']['tmp_name'];

    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'voting_system';

    $cmd = "mysql -h $db_host -u $db_user --password=$db_pass $db_name < $file";
    exec($cmd, $output, $result);

    echo $result === 0 ? "<p>Restore successful.</p>" : "<p>Restore failed. Code: $result</p>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Upload SQL File:</label>
    <input type="file" name="sql_file" accept=".sql" required>
    <button type="submit">Restore</button>
</form>
