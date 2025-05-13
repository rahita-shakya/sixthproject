<?php
require_once '../core/database.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 0;
$job_id = $_GET['job_id'] ?? 0;

if (!$user_id || !$job_id) {
    die("Invalid request.");
}

// Step 1: Get job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

if (!$job) {
    die("Job not found.");
}

$category = $job['category'] ?? '';

// Step 2: Get user's skills
$stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$skills_result = $stmt->get_result();

// Step 3: Log interest based on each skill
while ($row = $skills_result->fetch_assoc()) {
    $skill = $row['skill'];

    $conn->query("
        INSERT INTO skill_job_interest (skill, job_category, interest_count)
        VALUES ('$skill', '$category', 1)
        ON DUPLICATE KEY UPDATE interest_count = interest_count + 1
    ");
}
?>

<h2>Job Details</h2>
<p><strong>Title:</strong> <?= htmlspecialchars($job['title']) ?></p>
<p><strong>Category:</strong> <?= htmlspecialchars($job['category']) ?></p>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($job['description'])) ?></p>

<a href="search_jobs.php">← Back to job list</a>
