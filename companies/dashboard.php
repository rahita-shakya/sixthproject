<?php
session_start();
require_once '../core/database.php';

$company_login_id = $_SESSION['company_id'] ?? null;
if (!$company_login_id) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit;
}

// ✅ Get company name from company_login table
$stmtid = $conn->prepare("SELECT name FROM companies_login WHERE id = ?");
$stmtid->bind_param("i", $company_login_id);
$stmtid->execute();
$resultid = $stmtid->get_result();

if ($row = $resultid->fetch_assoc()) {
    $company_name = $row['name'];

    // Still need to get the company_id from `companies` table for job listing
    $stmtCompany = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
    $stmtCompany->bind_param("i", $company_login_id);
    $stmtCompany->execute();
    $resCompany = $stmtCompany->get_result();
    $companyData = $resCompany->fetch_assoc();
    $company_id = $companyData['id'] ?? 0;

    // 📋 Fetch jobs posted by this company
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
    <title>Company Dashboard - Posted Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="../index.php" class="btn btn-secondary mb-3">🔙 Go Back</a>
    <h3 class="mb-4">Posted Jobs - <?= htmlspecialchars($company_name) ?></h3>
    <a href="post_job.php" class="btn btn-success mb-4">➕ Post New Job</a>
    <a href="edit_profile.php" class="btn btn-info mb-4">✏️ Edit Profile</a>

    <?php if ($result->num_rows > 0) { ?>
        <?php while ($job = $result->fetch_assoc()) { 
            $applicantsRequired = $job['applicants_required'] ?? 0;
        ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                    <p class="text-muted">📍 Location: <?= htmlspecialchars($job['location']) ?></p>
                    <p class="text-muted">🕒 Posted on: <?= $job['created_at'] ?></p>
                    <p>
                        🗓️ Start Date: <?= htmlspecialchars($job['start_date'] ?? 'N/A') ?> |
                        🗓️ End Date: <?= htmlspecialchars($job['end_date'] ?? 'N/A') ?>
                    </p>

                    <span class="badge bg-<?= ($job['status'] === 'approved') ? 'success' : 'warning' ?>">
                        <?= ucfirst($job['status']) ?>
                    </span>

                    <p class="mt-2">👥 Applicants Required: <strong><?= htmlspecialchars($applicantsRequired) ?></strong></p>

                    <div class="mt-3">
                        <a href="view_applicants.php?job_id=<?= $job['id'] ?>" class="btn btn-warning btn-sm">👁️ View Applicants</a>
                        <a href="edit_job.php?id=<?= $job['id'] ?>" class="btn btn-primary btn-sm">✏️ Edit</a>
                        <a href="delete_job.php?id=<?= $job['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job?')">🗑️ Delete</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-info">You haven't posted any jobs yet.</div>
    <?php } ?>
</div>
</body>
</html>
