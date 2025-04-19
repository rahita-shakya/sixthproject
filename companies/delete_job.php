<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['company_id'];
$job_id = $_GET['id'] ?? null;

if (!$job_id) {
    echo "Invalid Job ID.";
    exit();
}

// Secure delete only if the job belongs to the logged-in company
$stmt = $conn->prepare("DELETE FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();

echo "<script>alert('Job deleted successfully!'); window.location.href='dashboard.php';</script>";
exit();
?>
