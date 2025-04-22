<?php
session_start();
if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../core/database.php';  // Make sure this path is correct

$job_id = $_GET['id'] ?? null;
$company_login_id = $_SESSION['company_id'];
 

// First, get the company_id from companies_login
$stmt = $conn->prepare("SELECT company_id FROM companies_login WHERE id = ?");
$stmt->bind_param("i", $company_login_id);
$stmt->execute();
$result = $stmt->get_result();
$company_login = $result->fetch_assoc();

if (!$company_login || !$company_login['company_id']) {
    // If no company_id found, check if there's a company record with this company_login_id
    $stmt = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
    $stmt->bind_param("i", $company_login_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $company = $result->fetch_assoc();
    
    if (!$company) {
        echo "Company profile not found. Please complete your company profile first.";
        exit;
    }
    
    $company_id = $company['id'];
} else {
    $company_id = $company_login['company_id'];
}

// Now get the job details
$stmt = $conn->prepare("SELECT j.*, c.name AS company_name, cat.category_name AS category 
                       FROM jobs j 
                       LEFT JOIN companies c ON j.company_id = c.id 
                       LEFT JOIN categories cat ON j.category_id = cat.id 
                       WHERE j.id = ? AND j.company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    header("Location: dashboard.php");
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
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($job['company_name'] ?? 'Not available'); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location'] ?? 'Not specified'); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category'] ?? 'Not categorized'); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($job['status'] ?? 'unknown')); ?></p>
            <p><strong>Posted:</strong> <?php echo date('F j, Y', strtotime($job['created_at'])); ?></p>
            
            <div class="mt-4">
                <a href="edit_job.php?id=<?php echo $job_id; ?>" class="btn btn-warning">Edit Job</a>
                <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>