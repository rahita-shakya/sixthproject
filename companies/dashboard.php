<?php
session_start();
require_once '../core/database.php';

if (!isset($_SESSION['company_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit;
}

$company_id = $_SESSION['company_id'];
$stmt = $conn->prepare("SELECT title, description, is_approved, created_at FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Posted Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Posted Jobs (<?= $_SESSION['company_name'] ?>)</h3>
    <a href="post_job.php" class="btn btn-success mb-3">Post New Job</a>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <p class="text-muted">Posted on: <?= $row['created_at'] ?></p>
                <span class="badge bg-<?= $row['is_approved'] ? 'success' : 'warning' ?>">
                    <?= $row['is_approved'] ? 'Approved' : 'Pending Approval' ?>
                </span>
                <div class="mt-3">
                    <!-- Edit button -->
                    <a href="edit_job.php?job_id=<?= $row['job_id'] ?>" class="btn btn-primary btn-sm">Edit</a>

                    <!-- Delete button -->
                    <a href="delete_job.php?job_id=<?= $row['job_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>
