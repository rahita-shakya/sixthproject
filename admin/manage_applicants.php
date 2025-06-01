<?php
// Start session
session_start();



// Include your database connection file
require_once '../core/database.php';

// Delete Applicant
if (isset($_GET['delete'])) {
    $applicant_id = intval($_GET['delete']);
    $conn->query("DELETE FROM applicants WHERE id = $applicant_id");
    echo "<script>alert('Applicant deleted successfully!');</script>";
}

// Show all applicants
$result = $conn->query("SELECT * FROM applicants");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applicants - JobSelect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
         <a href="dashboard.php" class="btn btn-secondary go-back-btn">Go Back</a>
        <h3 class="text-center mb-4">Manage Applicants</h3>

        <div class="list-group">
            <?php while ($app = $result->fetch_assoc()) { ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($app['name']) ?></h5>
                        <p class="mb-1"><?= htmlspecialchars($app['email']) ?></p>
                    </div>
                    <a href="?delete=<?= $app['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this applicant?')">Delete</a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, for any interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
