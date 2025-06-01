<?php
require_once '../core/database.php';
session_start();

// Define checkAdminLogin() if not using functions.php
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit();
    }
}

checkAdminLogin();

$query = "SELECT 
            applicants.name AS user_name,
            jobs.title AS job_title,
            companies.name AS company_name,
            applications.applied_at
          FROM applications
          JOIN applicants ON applications.applicant_id = applicants.id
          JOIN jobs ON applications.job_id = jobs.id
          JOIN companies ON jobs.company_id = companies.id
          ORDER BY applications.applied_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Applications - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
     <a href="dashboard.php" class="btn btn-secondary go-back-btn">Go Back</a>
    <h2>User Applications</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Job Title</th>
                <th>Company Name</th>
                <th>Applied At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['job_title']) ?></td>
                    <td><?= htmlspecialchars($row['company_name']) ?></td>
                    <td><?= htmlspecialchars($row['applied_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
