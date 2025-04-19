<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$company_id = $_SESSION['company_id'];
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($job_id <= 0) {
    echo "Invalid Job ID.";
    exit();
}

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';

    $stmt = $conn->prepare("UPDATE jobs SET title=?, description=?, location=? WHERE id=? AND company_id=?");
    $stmt->bind_param("sssii", $title, $description, $location, $job_id, $company_id);
    $stmt->execute();

    echo "<script>alert('Job updated successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}

// Fetch existing job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found.";
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
</body>
</html>
