<?php
require_once '../core/database.php';
session_start();

// Check if logged-in user is a company or admin
if (isset($_SESSION['company_logged_in']) && $_SESSION['company_logged_in'] === true) {
    $company_id = $_SESSION['company_id']; // Assuming company ID is stored in session
    $query = "SELECT jobs.*, companies.name AS company_name 
              FROM jobs 
              JOIN categories ON jobs.category_id = categories.id 
              JOIN companies ON jobs.company_id = companies.id 
              WHERE jobs.company_id = $company_id";
}
 elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Admin can see all jobs, with their status
    $query = "SELECT jobs.*, companies.name AS company_name 
              FROM jobs 
              JOIN companies ON jobs.company_id = companies.id";
} else {
    // Redirect if not logged in
    header('Location: login.php');
    exit();
}

// Add Job
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $category_id = intval($_POST['category_id']);
    $company_id = intval($_POST['company_id']);
    $description = sanitize($_POST['description']);

    // Prepare and bind for job insertion
    $stmt = $conn->prepare("INSERT INTO jobs (title, category_id, company_id, description, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("siis", $title, $category_id, $company_id, $description);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Job added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// Delete Job
if (isset($_GET['delete'])) {
    $job_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Job deleted!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// Approve or Reject Job
if (isset($_GET['approve'])) {
    $job_id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE jobs SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Job approved!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

if (isset($_GET['reject'])) {
    $job_id = intval($_GET['reject']);
    $stmt = $conn->prepare("UPDATE jobs SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Job rejected!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}

// Fetch Jobs
$result = $conn->query($query);
if (!$result) {
    echo "Error: " . $conn->error;  // Add error reporting to identify the issue
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Jobs - JobSelect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .go-back-btn {
            position: absolute;
            top: 10px;
            right: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Go Back Button -->
    <a href="dashboard.php" class="btn btn-secondary go-back-btn">Go Back</a>
    
    <h1 class="mb-4">Manage Jobs</h1>

    <!-- Job Add Form (for company only) -->
    <?php if (isset($_SESSION['company_logged_in']) && $_SESSION['company_logged_in'] === true): ?>
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="title" class="form-label">Job Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="list-group-item">
    <h4><?php echo htmlspecialchars($job['title']); ?></h4>
    <p><strong>Status:</strong> <?php echo ucfirst($job['status']); ?></p>
    <!-- <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category_name']); ?></p> -->
    <p><?php echo htmlspecialchars($job['description']); ?></p>

        
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <?php
                $categories = $conn->query("SELECT * FROM categories");
                while ($cat = $categories->fetch_assoc()) {
                    echo "<option value='{$cat['id']}'>{$cat['category_name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Job Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Job</button>
    </form>
    <?php endif; ?>

    <!-- Job List -->
    <h2>Existing Jobs</h2>
    <div class="list-group">
        <?php 
        if ($result->num_rows > 0):  // Check if any jobs were fetched
            while ($job = $result->fetch_assoc()): ?>
            <div class="list-group-item">
                <h4><?php echo htmlspecialchars($job['title']); ?></h4>
                <p><strong>Status:</strong> <?php echo ucfirst($job['status']); ?></p>
                <!-- <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category_name']); ?></p> -->
                <p><?php echo htmlspecialchars($job['description']); ?></p>
                
                <?php if (isset($_SESSION['company_logged_in']) && $_SESSION['company_logged_in'] === true): ?>
                    <!-- Only the company that posted the job can delete -->
                    <a href="?delete=<?php echo $job['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                <?php elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <!-- Admin can approve or reject jobs -->
                    <?php if ($job['status'] == 'pending'): ?>
                        <a href="?approve=<?php echo $job['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                        <a href="?reject=<?php echo $job['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No jobs available to display.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
