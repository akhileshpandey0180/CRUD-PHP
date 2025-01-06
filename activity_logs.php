<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check user role
if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to access this page.";
    exit();
}

include "db_conn.php";

// Pagination variables
$limit = 10; // Number of logs to show per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch total number of logs with search
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user_activity_logs WHERE action LIKE '%$search%'");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

// Fetch logs with pagination and search
$sql = "SELECT * FROM user_activity_logs WHERE action LIKE '%$search%' ORDER BY timestamp DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom styles -->
    <title>Activity Logs</title>
    <style>
        body {
            background-color: #121212; /* Dark background for a futuristic feel */
            color: #e0e0e0; /* Light text color for contrast */
            font-family: 'Roboto', sans-serif; /* Modern font */
        }
        .container {
            margin-top: 40px; /* Space above the container */
        }
        .table {
            background-color: #1e1e1e; /* Dark table background */
            border-radius: 10px; /* Rounded corners for table */
        }
        .table th, .table td {
            color: #e0e0e0; /* Light text color for table */
        }
        .table th {
            background-color: #00bcd4; /* Bright cyan background for headers */
        }
        .highlight-error {
            background-color: #f44336; /* Red background for errors */
            color: white; /* White text for contrast */
        }
        .pagination {
            justify-content: center; /* Center pagination */
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">User Activity Logs</h3>
        
        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by action" class="form-control">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="<?php echo (strpos($row['action'], 'error') !== false) ? 'highlight-error' : ''; ?>">
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['action']); ?></td>
                        <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
