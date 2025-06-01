<?php
session_start();
require_once '../core/database.php';

$applicant_id = $_SESSION['user_id'] ?? 0;
if ($applicant_id <= 0) {
    die("You must be logged in to view this page.");
}

// Validate job_id from GET
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
if ($job_id <= 0) {
    die("Invalid or missing job ID.");
}

$stmt = $conn->prepare("
    SELECT 
        a.status, 
        a.company_message 
    FROM applications a
    WHERE a.job_id = ? AND a.applicant_id = ?
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ii", $job_id, $applicant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    $company_message = $row['company_message'];
    
    echo "<h3>Application Status: " . htmlspecialchars(ucfirst($status)) . "</h3>";
    
    if (!empty($company_message)) {
        echo "<p><strong>Message from company:</strong><br>" . nl2br(htmlspecialchars($company_message)) . "</p>";
    }
} else {
    echo "<p>No application found for this job.</p>";
}

$stmt->close();
?>
