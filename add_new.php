<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check user role
if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to perform this action.";
    exit();
}

include "db_conn.php";

if (isset($_POST["submit"])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO `crud`(`first_name`, `last_name`, `email`, `gender`, `age`, `city`, `phone`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiis", $first_name, $last_name, $email, $gender, $age, $city, $phone);

    if ($stmt->execute()) {
        // Log the action
        $stmt_log = $conn->prepare("INSERT INTO user_activity_logs (user_id, action) VALUES (?, ?)");
        $stmt_log->bind_param("is", $_SESSION['user_id'], $action);
        $action = "Added new user: " . $first_name . " " . $last_name;
        $stmt_log->execute();
        $stmt_log->close();

        header("Location: index.php?msg=New record created successfully");
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
    <title>Add New User</title>
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
        <h3 class="text-center">Add New User</h3>
        <form action="" method="post" style="width: 50vw; min-width: 300px; margin: auto;">
            <div class="mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" class="form-control" name="first_name" placeholder="Albert" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Last Name:</label>
                <input type="text" class="form-control" name="last_name" placeholder="Einstein" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" placeholder="name@example.com" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Gender:</label><br>
                <input type="radio" class="form-check-input" name="gender" value="male" required> Male
                <input type="radio" class="form-check-input" name="gender" value="female" required> Female
            </div>
            <div class="mb-3">
                <label class="form-label">Age:</label>
                <input type="number" class="form-control" name="age" placeholder="36" required>
            </div>
            <div class="mb-3">
                <label class="form-label">City:</label>
                <input type="text" class="form-control" name="city" placeholder="Delhi" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" class="form-control" name="phone" placeholder="9830013111" required>
            </div>
            <button type="submit" class="btn btn-success" name="submit">Save</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
