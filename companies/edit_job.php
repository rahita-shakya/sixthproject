<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

// Step 1: Get company_login_id from session
$company_login_id = $_SESSION['company_id'];

// Step 2: Retrieve actual company_id from companies table
$stmtid = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
$stmtid->bind_param("i", $company_login_id);
$stmtid->execute();
$resultid = $stmtid->get_result();

if ($row = $resultid->fetch_assoc()) {
    $company_id = $row['id'];
} else {
    echo "Company not found.";
    exit();
}

// Step 3: Get job ID from query string
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($job_id <= 0) {
    echo "Invalid Job ID.";
    exit();
}

// Step 4: If form submitted, update the job
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';

    $stmt = $conn->prepare("UPDATE jobs SET title = ?, description = ?, location = ? WHERE id = ? AND company_id = ?");
    $stmt->bind_param("sssii", $title, $description, $location, $job_id, $company_id);

    if ($stmt->execute()) {
        echo "<script>alert('Job updated successfully!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating job: " . $stmt->error;
    }
}

// Step 5: Fetch existing job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found or you do not have permission to edit this job.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container">
        <h2>Edit Job</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Job Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" required><?php echo htmlspecialchars($job['description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label>Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
    </div>
</body>
</html>
