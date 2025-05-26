<?php
require_once 'core/database.php';
session_start();

$user_id = $_SESSION['user_id'];

// Step 1: Fetch all approved jobs with company name (Content Section)
$jobQuery = $conn->query("
    SELECT jobs.*, companies.name AS company_name 
    FROM jobs 
    JOIN companies ON jobs.company_id = companies.id 
    WHERE jobs.status = 'approved'
");

$jobs = [];
while ($job = $jobQuery->fetch_assoc()) {
    // You can add skills to each job in this section if you need
    $job['skills_required'] = ''; // Placeholder for the skills
    $jobs[] = $job;
}

// Step 2: Get all unique skills from the job_role_skills table for collaborative filtering
$skillSet = [];
$result = $conn->query("SELECT DISTINCT skill FROM job_role_skills");
while ($row = $result->fetch_assoc()) {
    $skillSet[] = strtolower(trim($row['skill']));
}

// Map skills to index
$skillIndex = array_flip($skillSet); // e.g., ['php' => 0, 'mysql' => 1, ...]
$userVector = array_fill(0, count($skillSet), 0);

// Step 3: Create user's skill vector (Collaborative Filtering Section)
$stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$userSkillsResult = $stmt->get_result();
while ($row = $userSkillsResult->fetch_assoc()) {
    $skill = strtolower(trim($row['skill']));
    if (isset($skillIndex[$skill])) {
        $userVector[$skillIndex[$skill]] = 1;
    }
}



$recommendedJobs = [];

// Step 4: Compute recommendations using matrix multiplication (Collaborative Filtering)
foreach ($jobs as $job) {
    $role = $job['title'];

    // Create job skill vector
    $jobVector = array_fill(0, count($skillSet), 0);
    $stmt = $conn->prepare("SELECT skill FROM job_role_skills WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $jobSkillsResult = $stmt->get_result();

    $jobSkills = [];
    while ($skillRow = $jobSkillsResult->fetch_assoc()) {
        $skill = strtolower(trim($skillRow['skill']));
        $jobSkills[] = $skill;
        if (isset($skillIndex[$skill])) {
            $jobVector[$skillIndex[$skill]] = 1;
        }
    }


    // Matrix multiplication: Dot product
    $matchScore = 0;
    for ($i = 0; $i < count($skillSet); $i++) {
        $matchScore += $userVector[$i] * $jobVector[$i];
    }

  

    $totalJobSkills = array_sum($jobVector);
    $score = ($totalJobSkills > 0) ? ($matchScore / $totalJobSkills) * 100 : 0;

    if ($score > 0) {
        $job['match_score'] = round($score);
        $job['skills_required'] = implode(', ', $jobSkills);
        $recommendedJobs[] = $job;
    }
}

// Step 5: Sort jobs by match score
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
        <h2 class="mb-4 text-center">Recommended Jobs (Matrix Match)</h2>

        <?php if (empty($recommendedJobs)): ?>
            <div class="alert alert-info text-center">No matching jobs found. Update your skills for better matches.</div>
        <?php else: ?>
            <?php foreach ($recommendedJobs as $job): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company_name']) ?></h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                        <p><strong>Required Skills:</strong> <?= htmlspecialchars($job['skills_required']) ?></p>
                        <p><strong>Match Score:</strong> <?= $job['match_score'] ?>%</p>
                 <a href="users/view_job.php?job_id=<?= $job['id'] ?>" class="btn btn-primary">View Job</a>


                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
