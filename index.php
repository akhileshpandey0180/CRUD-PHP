<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit(); // Stop further execution
}

// Check user role
if ($_SESSION['role'] !== 'admin') {
    // If the user is not an admin, restrict access to certain functionalities
    echo "You do not have permission to access this page.";
    exit();
}

include "db_conn.php";

// Pagination variables
$limit = 6; // Number of entries to show in a page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Sorting functionality
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Fetch total number of records with search
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `crud` WHERE `first_name` LIKE '%$search%' OR `last_name` LIKE '%$search%' OR `email` LIKE '%$search%' OR `gender` LIKE '%$search%' OR `age` LIKE '%$search%' OR `city` LIKE '%$search%' OR `phone` LIKE '%$search%'");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

// Fetch user data with pagination, search, and sorting
$sql = "SELECT * FROM `crud` WHERE `first_name` LIKE '%$search%' OR `last_name` LIKE '%$search%' OR `email` LIKE '%$search%' OR `gender` LIKE '%$search%' OR `age` LIKE '%$search%' OR `city` LIKE '%$search%' OR `phone` LIKE '%$search%' ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
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
    <title>Futuristic PHP CRUD Application</title>
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
        .card {
            background-color: #1e1e1e; /* Dark card background */
            border: 1px solid #00bcd4; /* Bright cyan border for cards */
            border-radius: 10px; /* Rounded corners for cards */
            box-shadow: 0 0 20px rgba(0, 188, 212, 0.5); /* Bright cyan shadow */
            transition: transform 0.2s, box-shadow 0.2s; /* Smooth transition for hover effects */
        }
        .card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 0 30px rgba(0, 188, 212, 0.7); /* Deeper bright cyan shadow on hover */
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
        .btn-warning {
            background-color: #ff9800; /* Orange background for warning button */
        }
        .btn-warning:hover {
            background-color: #f57c00; /* Darker orange on hover */
        }
        .btn-danger {
            background-color: #f44336; /* Red background for danger button */
        }
        .btn-danger:hover {
            background-color: #c62828; /* Darker red on hover */
        }
        .pagination {
            justify-content: center; /* Center pagination */
        }
        .pagination .page-item.active .page-link {
            background-color: #00bcd4; /* Active page link color */
            border-color: #00bcd4; /* Active page link border color */
        }
        .pagination .page-link {
            color: #00bcd4; /* Color for pagination links */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Futuristic CRUD Application</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php
        if (isset($_GET["msg"])) {
            $msg = htmlspecialchars($_GET["msg"]);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $msg . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>

        <!-- Navigation Links -->
        <div class="mb-3">
            <a href="add_new.php" class="btn btn-success">Add New User</a>
            <a href="register.php" class="btn btn-primary">Register</a>
            <a href="import.php" class="btn btn-info">Import File</a>
            <a href="export.php" class="btn btn-warning">Export CSV</a>
            <a href="?sort=first_name&order=<?php echo ($sort == 'first_name' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="btn btn-link">Sort by First Name</a>
            <a href="?sort=last_name&order=<?php echo ($sort == 'last_name' && $order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="btn btn-link">Sort by Last Name</a>
        </div>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search" class="form-control">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            
                            <h5 class="card-title"><?php echo htmlspecialchars($row["first_name"]) . ' ' . htmlspecialchars($row["last_name"]); ?></h5>
                            <p class="card-text">Email: <?php echo htmlspecialchars($row["email"]); ?></p>
                            <p class="card-text">Gender: <?php echo htmlspecialchars($row["gender"]); ?></p>
                            <p class="card-text">Age: <?php echo htmlspecialchars($row["age"]); ?></p>
                            <p class="card-text">City: <?php echo htmlspecialchars($row["city"]); ?></p>
                            <p class="card-text">Phone: <?php echo htmlspecialchars($row["phone"]); ?></p>
                            <a href="edit.php?id=<?php echo $row["id"]; ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <a href="delete.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>       
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
