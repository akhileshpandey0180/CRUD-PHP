<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db_conn.php";

if (isset($_POST["import"])) {
    $file = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file_handle = fopen($file, "r");
        // Skip the first line if it contains headers
        fgetcsv($file_handle, 1000, ",");
        
        while (($row = fgetcsv($file_handle, 1000, ",")) !== FALSE) {
            $first_name = $row[1];
            $last_name = $row[2];
            $email = $row[3];
            $gender = $row[4];
            $age = isset($row[5]) ? $row[5] : null; // Optional field
            $city = isset($row[6]) ? $row[6] : null; // Optional field
            $phone = isset($row[7]) ? $row[7] : null; // Optional field
           
            // Prepared statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO `crud`(`first_name`, `last_name`, `email`, `gender`, `age`, `city`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssisi", $first_name, $last_name, $email, $gender, $age, $city, $phone);
            $stmt->execute();
        }
        fclose($file_handle);
        header("Location: index.php?msg=Data imported successfully");
    } else {
        echo "Failed to upload file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom styles -->
    <title>Import Users</title>
    <style>
        body {
            background-color: #121212; /* Dark background for a futuristic feel */
            color: #e0e0e0; /* Light text color for contrast */
            font-family: 'Roboto', sans-serif; /* Modern font */
        }
        .navbar {
            background-color: #1f1f1f; /* Darker navbar background */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Subtle shadow for depth */
        }
        .navbar-brand {
            color: #00bcd4; /* Bright cyan text for the brand */
            font-weight: bold; /* Bold brand text */
        }
        .container {
            margin-top: 40px; /* Space above the container */
        }
        .form-label {
            color: #00bcd4; /* Bright cyan color for labels */
        }
        .btn {
            border-radius: 5px; /* Rounded corners for buttons */
            transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transition for hover effects */
        }
        .btn-success {
            background-color: #4caf50; /* Green background for success button */
        }
        .btn-success:hover {
            background-color: #388e3c; /* Darker green on hover */
        }
        .btn-danger {
            background-color: #f44336; /* Red background for danger button */
        }
        .btn-danger:hover {
            background-color: #c62828; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Futuristic CRUD Application</a>
        </div>
    </nav>
    <div class="container">
        <h3 class="text-center">Import Users from CSV</h3>
        <form action="" method="post" enctype="multipart/form-data" style="width: 50vw; min-width: 300px; margin: auto;">
            <div class="mb-3">
                <label class="form-label">Choose CSV File:</label>
                <input type="file" class="form-control" name="file" accept=".csv" required>
            </div>
            <button type="submit" class="btn btn-success" name="import">Import</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
