<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// In add_new.php or delete.php
if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to perform this action.";
    exit();
}

include "db_conn.php";
$id = $_GET["id"];

// Prepared statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM `crud` WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
        // Log the action
    $stmt_log = $conn->prepare("INSERT INTO user_activity_logs (user_id, action) VALUES (?, ?)");
    $stmt_log->bind_param("is", $_SESSION['user_id'], $action);
    $action = "Deleted user with ID: " . $id;
    $stmt_log->execute();
    $stmt_log->close();
    
    header("Location: index.php?msg=Data deleted successfully");
} else {
    echo "Failed: " . $stmt->error;
}
$stmt->close();


?>

