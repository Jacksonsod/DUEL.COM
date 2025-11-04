<?php
session_start();
require '../includes/session_check.php';
requireRole('admin');

$timestamp = date('Ymd_His');
$filename = "../backups/db_backup_$timestamp.sql";

// Database credentials
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'voting_system';

// Run mysqldump
$cmd = "mysqldump -h $db_host -u $db_user --password=$db_pass $db_name > $filename";
exec($cmd, $output, $result);

if ($result === 0) {
    echo "<p>Backup successful: <a href='$filename' download>Download SQL File</a></p>";
} else {
    echo "<p>Backup failed. Code: $result</p>";
}
?>
