<?php
session_start();
include "db_conn.php";

if (isset($_POST["register"])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role']; // Get the role from the form
    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    
    if ($stmt->execute()) {
        header("Location: login.php?msg=Registration successful");
    } else {
        echo "Failed: " . $stmt->error;
    }
    $stmt->close();
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
    <title>Register</title>
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
        .btn-primary {
            background-color: #007bff; /* Blue background for primary button */
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .alert {
            margin-top: 20px; /* Space above alerts */
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
        <h3 class="text-center">Register</h3>
        <form action="" method="post" style="width: 50vw; min-width: 300px; margin: auto;">
            <div class="mb-3">
                <label class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role:</label>
                <select class="form-select" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="register">Register</button>
            <a href="login.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
