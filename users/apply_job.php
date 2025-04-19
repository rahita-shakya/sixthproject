<?php
require_once '../core/database.php';
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
checkLogin();

$job_id = intval($_GET['job_id']);
$applicant_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = trim($_POST['address']);
    $message = trim($_POST['message']);

    // Handle file upload
    $resume = $_FILES['resume']['name'];
    $target_dir = "../uploads/resumes/";
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        // Insert into applications
        $stmt = $conn->prepare("INSERT INTO applications (applicant_id, job_id, resume, address, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $applicant_id, $job_id, $resume, $address, $message);
        
        if ($stmt->execute()) {
            // Track view count for recommendation
            $conn->query("INSERT INTO job_views (applicant_id, job_id, view_count) VALUES ($applicant_id, $job_id, 1) 
                          ON DUPLICATE KEY UPDATE view_count = view_count + 1");

            echo "<script>alert('Application submitted successfully!'); window.location.href='dashboard.php';</script>";
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Failed to upload resume.";
    }
}
?>

<!-- HTML Form with Bootstrap -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h3 class="text-center mb-4">Apply for Job</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="resume" class="form-label">Resume File (PDF only):</label>
                <input type="file" class="form-control" name="resume" accept=".pdf" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Your Address:</label>
                <input type="text" class="form-control" name="address" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Write a message..."></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
