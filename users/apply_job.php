<?php
require_once '../core/database.php';
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_GET['job_id'] ?? null;

if (!$job_id) {
    echo "<div class='alert alert-danger'>Invalid job ID.</div>";
    exit();
}

// ✅ Check if the user has already applied for this job
$stmt = $conn->prepare("SELECT id FROM job_interactions WHERE user_id = ? AND job_id = ? AND action = 'apply'");
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='alert alert-info'>You have already applied for this job.</div>";
    echo "<a href='search_jobs.php' class='btn btn-primary'>Back to Job Listings</a>";
    exit();
}

// ✅ Handle form submission (Resume upload and application submission)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address = trim($_POST['address']);
    $message = trim($_POST['message']);

    // Handle file upload (Resume)
    $resume = $_FILES['resume']['name'];
    $target_dir = "../uploads/resumes/";
    $target_file = $target_dir . basename($resume);
    $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($file_ext != 'pdf') {
        echo "<div class='alert alert-danger'>Only PDF files are allowed for resume upload.</div>";
    } elseif (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        // ✅ Insert into applications table
        $stmt = $conn->prepare("INSERT INTO applications (applicant_id, job_id, resume, address, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $job_id, $resume, $address, $message);
        
        if ($stmt->execute()) {
            // ✅ Track job view for recommendations (Increment view count)
            $conn->query("INSERT INTO job_views (applicant_id, job_id, view_count) 
                          VALUES ($user_id, $job_id, 1) 
                          ON DUPLICATE KEY UPDATE view_count = view_count + 1");

            // ✅ Log the apply action for future reference (Avoid duplicate apply actions)
            $stmt = $conn->prepare("INSERT INTO job_interactions (user_id, job_id, action) VALUES (?, ?, 'apply')");
            $stmt->bind_param("ii", $user_id, $job_id);
            $stmt->execute();

            // ✅ Success message
            echo "<div class='alert alert-success text-center mt-5'>You have successfully applied for this job!</div>";
            echo "<a href='search_jobs.php' class='btn btn-primary'>Back to Job Listings</a>";
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error submitting your application: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Failed to upload resume. Please try again.</div>";
    }
}
?>

<!-- HTML Form to apply for a job -->
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
                <label for="resume" class="form-label">Resume (PDF only):</label>
                <input type="file" class="form-control" name="resume" accept=".pdf" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Your Address:</label>
                <input type="text" class="form-control" name="address" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Write a message to the employer..."></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="search_jobs.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
