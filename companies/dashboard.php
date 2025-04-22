<?php
session_start();
require_once '../core/database.php';

// Step 1: Make sure user is logged in
if (!isset($_SESSION['company_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit;
}

// Step 2: Get company_login_id from session
$company_login_id = $_SESSION['company_id'];

// Step 3: Get the actual company ID from companies table
$stmtid = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
$stmtid->bind_param("i", $company_login_id);
$stmtid->execute();
$resultid = $stmtid->get_result();

if ($row = $resultid->fetch_assoc()) {
    $company_id = $row['id'];

    // Step 4: Fetch jobs using correct company_id
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    echo "<script>alert('Company not found.'); window.location.href='login.php';</script>";
    exit;
}
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
    <h3 class="mb-4">Posted Jobs (<?= htmlspecialchars($_SESSION['company_name']) ?>)</h3>
    <a href="post_job.php" class="btn btn-success mb-3">Post New Job</a>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                <p class="text-muted">Posted on: <?= $row['created_at'] ?></p>
                
                <!-- Status badge -->
                <span class="badge bg-<?= ($row['status'] === 'approved') ? 'success' : 'warning' ?>">
                    <?= ucfirst($row['status']) ?>
                </span>

                <div class="mt-3">
                    <a href="edit_job.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete_job.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job?')">Delete</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>
