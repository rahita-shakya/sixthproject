<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

// Step 1: Get the logged-in company's login ID from session
$company_login_id = $_SESSION['company_id'];

// Step 2: Get the actual company ID from companies table
$stmtid = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
$stmtid->bind_param("i", $company_login_id);
$stmtid->execute();
$resultid = $stmtid->get_result();

if ($row = $resultid->fetch_assoc()) {
    $company_id = $row['id'];
} else {
    echo "<script>alert('Company not found.'); window.location.href='dashboard.php';</script>";
    exit();
}

// Step 3: Validate and sanitize job ID
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($job_id <= 0) {
    echo "<script>alert('Invalid job ID.'); window.location.href='dashboard.php';</script>";
    exit();
}

// Step 4: Securely delete the job only if it belongs to the company
$stmt = $conn->prepare("DELETE FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);

if ($stmt->execute()) {
    echo "<script>alert('Job deleted successfully!'); window.location.href='dashboard.php';</script>";
} else {
    echo "<script>alert('Failed to delete job.'); window.location.href='dashboard.php';</script>";
}

exit();
?>
