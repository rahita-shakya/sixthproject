<?php
session_start();
require_once '../core/database.php';

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if company is logged in
if (!isset($_SESSION['company_id'])) {
    echo "<script>alert('Please login to post a job.'); window.location.href='company_login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $company_id = $_SESSION['company_id'];
    echo "Session company_id: " . $company_id; 


    // Optional: if you want to include category_id later
    // $category_id = $_POST['category_id'];

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO jobs (company_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iss", $company_id, $title, $description);


    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
