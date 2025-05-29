<?php
require_once 'core/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Step 1: Get all users who applied for jobs
$applicationData = [];
$allUsers = [];
$allJobs = [];
$result = $conn->query("SELECT applicant_id AS user_id, job_id FROM applications");

while ($row = $result->fetch_assoc()) {
    $uid = $row['user_id'];
    $jid = $row['job_id'];
    $applicationData[$uid][$jid] = 1;
    $allUsers[$uid] = true;
    $allJobs[$jid] = true;
}

// Step 2: Create user-job matrix
$jobList = array_keys($allJobs);
$userJobMatrix = [];
foreach ($allUsers as $uid => $_) {
    foreach ($jobList as $jid) {
        $userJobMatrix[$uid][$jid] = isset($applicationData[$uid][$jid]) ? 1 : 0;
    }
}

// Step 3: Calculate similarity between current user and others (cosine similarity)
function cosineSimilarity($vec1, $vec2) {
    $dot = 0;
    $normA = 0;
    $normB = 0;
    foreach ($vec1 as $k => $v) {
        $dot += $v * $vec2[$k];
        $normA += $v * $v;
        $normB += $vec2[$k] * $vec2[$k];
    }
    return ($normA && $normB) ? ($dot / (sqrt($normA) * sqrt($normB))) : 0;
}

$similarities = [];
foreach ($userJobMatrix as $uid => $vector) {
    if ($uid != $user_id) {
        $similarities[$uid] = cosineSimilarity($userJobMatrix[$user_id], $vector);
    }
}

// Step 4: Get job recommendations from top similar users
arsort($similarities);
$topUsers = array_slice(array_keys($similarities), 0, 5); // top 5 similar users

$recommendedJobs = [];
foreach ($topUsers as $simUser) {
    foreach ($userJobMatrix[$simUser] as $jobId => $applied) {
        if ($applied && !$userJobMatrix[$user_id][$jobId]) {
            $recommendedJobs[$jobId] = true;
        }
    }
}

// Step 5: Fetch job details
$jobs = [];
if (!empty($recommendedJobs)) {
    $ids = implode(',', array_keys($recommendedJobs));
    $result = $conn->query("
        SELECT jobs.*, companies.name AS company_name 
        FROM jobs 
        JOIN companies ON jobs.company_id = companies.id 
        WHERE jobs.id IN ($ids) AND jobs.status = 'approved'
    ");
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommended Jobs (Collaborative)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Recommended Jobs (Collaborative Filtering)</h2>
    <?php if (empty($jobs)): ?>
        <div class="alert alert-info text-center">No recommendations available. Apply to some jobs first.</div>
    <?php else: ?>
        <?php foreach ($jobs as $job): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company_name']) ?></h6>
                    <p class="card-text"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                    <a href="users/view_job.php?job_id=<?= $job['id'] ?>" class="btn btn-primary">View Job</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
