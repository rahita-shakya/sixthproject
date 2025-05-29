<?php
session_start();
require_once '../core/database.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if company is logged in
if (!isset($_SESSION['company_id']) || empty($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$company_login_id = $_SESSION['company_id'];

// Retrieve the actual company ID using the login ID
$stmtid = $conn->prepare("SELECT id, name FROM companies WHERE company_login_id = ?");
$stmtid->bind_param("i", $company_login_id);
$stmtid->execute();
$resultid = $stmtid->get_result();

if ($row = $resultid->fetch_assoc()) {
    $company_id = $row['id'];
    $company_name = $row['name'];
} else {
    echo "<script>alert('Company not found.'); window.location.href='login.php';</script>";
    exit;
}

// Validate job_id from GET parameters
$job_id = $_GET['job_id'] ?? 0;
if (!$job_id) {
    echo "<script>alert('Invalid job ID.'); window.location.href='dashboard.php';</script>";
    exit;
}

// Verify that the job belongs to the logged-in company
$stmt = $conn->prepare("SELECT title FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$job_result = $stmt->get_result();

if ($job_row = $job_result->fetch_assoc()) {
    $job_title = $job_row['title'];
} else {
    echo "<script>alert('Job not found or access denied.'); window.location.href='dashboard.php';</script>";
    exit;
}

// Fetch applicants for the specified job
// Fetch applicants for the specified job
// Fetch applicants for the specified job
$stmt = $conn->prepare("
    SELECT 
        u.name AS applicant_name,
        u.email AS applicant_email,
        a.message,
        a.resume,
        a.address,
        a.applied_at
    FROM applications a
    JOIN applicants u ON a.applicant_id = u.id
    WHERE a.job_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$applicants_result = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applicants for <?= htmlspecialchars($job_title) ?> - <?= htmlspecialchars($company_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Applicants for <em><?= htmlspecialchars($job_title) ?></em></h3>
    <a href="dashboard.php" class="btn btn-secondary mb-4">← Back to Dashboard</a>

    <?php if ($applicants_result->num_rows > 0): ?>
        <div class="list-group">
            <?php while ($applicant = $applicants_result->fetch_assoc()): ?>
                <div class="list-group-item mb-3">
                    <h5 class="mb-1"><?= htmlspecialchars($applicant['applicant_name']) ?></h5>
                    <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($applicant['applicant_email']) ?></p>
                    <p class="mb-1"><strong>Message:</strong> <?= nl2br(htmlspecialchars($applicant['message'])) ?></p>
                    <?php if (!empty($applicant['resume'])): ?>
    <p class="mb-1">
        <strong>Resume:</strong> 
        <a href="../uploads/resumes/<?= htmlspecialchars($applicant['resume']) ?>" target="_blank">View Resume</a>
    </p>
<?php else: ?>
    <p class="mb-1"><strong>Resume:</strong> Not provided</p>
<?php endif; ?>

                    <small class="text-muted">Applied on: <?= date("F j, Y, g:i a", strtotime($applicant['applied_at'])) ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No applicants have applied for this job yet.</div>
    <?php endif; ?>
</div>
</body>
</html>
