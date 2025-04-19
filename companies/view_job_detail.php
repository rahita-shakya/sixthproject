<?php
session_start();
if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../core/database.php';

$job_id = $_GET['id'] ?? null;
$company_id = $_SESSION['company_id'];

if (!$job_id) {
    echo "Invalid Job ID.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found or access denied.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4><?php echo htmlspecialchars($job['title']); ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
            <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
