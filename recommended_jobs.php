<?php
require_once 'core/database.php';
session_start();

$user_id = $_SESSION['user_id'];
$userSkills = [];

// Step 1: Get user skills
$stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $userSkills[] = strtolower(trim($row['skill']));
}

// Step 2: Get all approved jobs with company name using JOIN
$jobQuery = $conn->query("
   SELECT jobs.*, companies.name AS company_name

    FROM jobs 
    JOIN companies ON jobs.company_id = companies.id 
    WHERE jobs.status = 'approved'
");

$recommendedJobs = [];

// Step 3: Define matching logic
function getMatchScore($userSkills, $roleSkills) {
    if (empty($roleSkills)) return 0;

    $roleSkills = array_map('strtolower', array_map('trim', $roleSkills));
    $matched = array_intersect($userSkills, $roleSkills);

    return count($roleSkills) > 0 ? (count($matched) / count($roleSkills)) * 100 : 0;
}

// Step 4: Match jobs and calculate scores
while ($job = $jobQuery->fetch_assoc()) {
    $role = $job['title'];

    // Get skills required for this role
    $stmt = $conn->prepare("SELECT skill FROM job_role_skills WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $skillResult = $stmt->get_result();

    $roleSkills = [];
    while ($skillRow = $skillResult->fetch_assoc()) {
        $roleSkills[] = strtolower(trim($skillRow['skill']));
    }

    $score = getMatchScore($userSkills, $roleSkills);
    if ($score > 0) {
        $job['match_score'] = round($score);
        $job['skills_required'] = implode(', ', $roleSkills); // For display
        $recommendedJobs[] = $job;
    }
}

// Step 5: Sort by match score
usort($recommendedJobs, function ($a, $b) {
    return $b['match_score'] <=> $a['match_score'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommended Jobs - JobSelect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Recommended Jobs for You</h2>

        <?php if (empty($recommendedJobs)): ?>
            <div class="alert alert-info text-center">No matching jobs found for your skills. Try updating your skills.</div>
        <?php else: ?>
            <?php foreach ($recommendedJobs as $job): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company_name']) ?></h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                        <p><strong>Required Skills:</strong> <?= htmlspecialchars($job['skills_required']) ?></p>
                        <p><strong>Match:</strong> <?= $job['match_score'] ?>%</p>
                        <a href="job_details.php?id=<?= $job['id'] ?>" class="btn btn-primary">View Job</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
