<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['company_id'])) {
    header("Location: login.php");
    exit();
}

$company_login_id = $_SESSION['company_id'];

// Get company_id from companies table
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

$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($job_id <= 0) {
    echo "Invalid Job ID.";
    exit();
}

// If form submitted, update the job and skills
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $category = $_POST['category'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $applicants_required = $_POST['applicants_required'] ?? 1;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Update jobs table (without skills column)
    $stmt = $conn->prepare("UPDATE jobs 
        SET title = ?, description = ?, location = ?, category = ?, applicants_required = ?, start_date = ?, end_date = ? 
        WHERE id = ? AND company_id = ?");
    $stmt->bind_param("ssssissii", $title, $description, $location, $category, $applicants_required, $start_date, $end_date, $job_id, $company_id);

    if ($stmt->execute()) {
        // Delete old skills for this job
        $delStmt = $conn->prepare("DELETE FROM skills WHERE job_id = ?");
        $delStmt->bind_param("i", $job_id);
        $delStmt->execute();

        // Insert new skills
        $skill_list = explode(',', $skills);
        foreach ($skill_list as $skill) {
            $skill = trim($skill);
            if (!empty($skill)) {
                $skill_stmt = $conn->prepare("INSERT INTO skills (job_id, skill_name) VALUES (?, ?)");
                $skill_stmt->bind_param("is", $job_id, $skill);
                $skill_stmt->execute();
            }
        }

        echo "<script>alert('Job updated successfully!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "Error updating job: " . $stmt->error;
    }
}

// Fetch existing job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? AND company_id = ?");
$stmt->bind_param("ii", $job_id, $company_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo "Job not found or you do not have permission to edit this job.";
    exit();
}

// Fetch existing skills for this job
$skill_names = [];
$skillStmt = $conn->prepare("SELECT skill_name FROM skills WHERE job_id = ?");
$skillStmt->bind_param("i", $job_id);
$skillStmt->execute();
$skillResult = $skillStmt->get_result();
while ($rowSkill = $skillResult->fetch_assoc()) {
    $skill_names[] = $rowSkill['skill_name'];
}
$skills_str = implode(", ", $skill_names);
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
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($job['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($job['location']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <select name="category" class="form-control" required>
                <option value="">-- Select a category --</option>
                <?php
                $categories = [
                    "Backend developer",
                    "Marketing",
                    "Accounting",
                    "Frontend Developer",
                    "Fullstack developer",
                    "Manager",
                    "Front Desk officer"
                ];
                foreach ($categories as $cat) {
                    $selected = $job['category'] === $cat ? 'selected' : '';
                    echo "<option value=\"$cat\" $selected>$cat</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Required Skills</label>
            <input type="text" name="skills" class="form-control" value="<?= htmlspecialchars($skills_str) ?>" required>
            <small class="text-muted">Separate multiple skills with commas.</small>
        </div>
        <div class="mb-3">
            <label>Number of Applicants Required</label>
            <input type="number" name="applicants_required" class="form-control" value="<?= htmlspecialchars($job['applicants_required']) ?>" min="1" required>
        </div>
        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($job['start_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($job['end_date']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
</div>

<script>
    // Set minimum selectable date to today
    const today = new Date().toISOString().split('T')[0];
    const start = document.querySelector('input[name="start_date"]');
    const end = document.querySelector('input[name="end_date"]');
    start.setAttribute('min', today);
    end.setAttribute('min', today);

    start.addEventListener('change', function() {
        end.setAttribute('min', this.value);
    });
</script>

</body>
</html>
