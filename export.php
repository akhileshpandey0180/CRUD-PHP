<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db_conn.php";

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch user data with search
$sql = "SELECT * FROM `crud` WHERE `first_name` LIKE '%$search%' OR `last_name` LIKE '%$search%' OR `email` LIKE '%$search%'";
$result = mysqli_query($conn, $sql);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');

// Open output stream
$output = fopen('php://output', 'w');

// Add column headers
fputcsv($output, ['ID', 'First Name', 'Last Name', 'Email', 'Gender', 'Age', 'City', 'Phone']);

// Fetch and write data to CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close output stream
fclose($output);
exit();
?>
