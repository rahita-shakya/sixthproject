<?php
require_once 'core/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ------------------- COLLABORATIVE FILTERING -------------------

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
if (isset($userJobMatrix[$user_id])) {
    foreach ($userJobMatrix as $uid => $vector) {
        if ($uid != $user_id) {
            $similarities[$uid] = cosineSimilarity($userJobMatrix[$user_id], $vector);
        }
    }
}

// Step 4: Get job recommendations from top similar users
arsort($similarities);
$topUsers = array_slice(array_keys($similarities), 0, 5); // top 5 similar users

$recommendedJobs = [];
foreach ($topUsers as $simUser) {
    foreach ($userJobMatrix[$simUser] as $jobId => $applied) {
        if ($applied && (!isset($userJobMatrix[$user_id][$jobId]) || $userJobMatrix[$user_id][$jobId] == 0)) {
            $recommendedJobs[$jobId] = true;
        }
    }
}

// Step 5: Fetch job details for collaborative filtering
$collabJobs = [];
if (!empty($recommendedJobs)) {
    $ids = implode(',', array_keys($recommendedJobs));
    $result = $conn->query("
        SELECT jobs.*, companies.name AS company_name 
        FROM jobs 
        JOIN companies ON jobs.company_id = companies.id 
        WHERE jobs.id IN ($ids) AND jobs.status = 'approved'
    ");
    while ($row = $result->fetch_assoc()) {
        // Add skills required and match score based on user's skills for each job
        $jobId = $row['id'];
        $title = $row['title'];

        // Fetch required skills for this job
        $jobSkills = [];
        $jobVector = [];

        // Get all distinct skills from job_role_skills for indexing
        $skillSet = [];
        $resSkills = $conn->query("SELECT DISTINCT skill FROM job_role_skills");
        while ($r = $resSkills->fetch_assoc()) {
            $skillSet[] = strtolower(trim($r['skill']));
        }
        $skillIndex = array_flip($skillSet);
        $jobVector = array_fill(0, count($skillSet), 0);

        $stmt = $conn->prepare("SELECT skill FROM job_role_skills WHERE role = ?");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $resJobSkills = $stmt->get_result();
        while ($skillRow = $resJobSkills->fetch_assoc()) {
            $skill = strtolower(trim($skillRow['skill']));
            $jobSkills[] = $skill;
            if (isset($skillIndex[$skill])) {
                $jobVector[$skillIndex[$skill]] = 1;
            }
        }

        // Create user skill vector for scoring
        $userVector = array_fill(0, count($skillSet), 0);
        $stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $userSkillsResult = $stmt->get_result();
        while ($usrSkill = $userSkillsResult->fetch_assoc()) {
            $usk = strtolower(trim($usrSkill['skill']));
            if (isset($skillIndex[$usk])) {
                $userVector[$skillIndex[$usk]] = 1;
            }
        }

        // Calculate match score (dot product / total job skills)
        $matchScore = 0;
        for ($i = 0; $i < count($skillSet); $i++) {
            $matchScore += $userVector[$i] * $jobVector[$i];
        }
        $totalJobSkills = array_sum($jobVector);
        $score = ($totalJobSkills > 0) ? ($matchScore / $totalJobSkills) * 100 : 0;

        $row['skills_required'] = implode(', ', $jobSkills);
        $row['match_score'] = round($score);

        $collabJobs[] = $row;
    }
}

// ------------------- CONTENT-BASED FILTERING -------------------

// Step 1: Get user skills (from user_skills table)
$userSkills = [];
$res = $conn->query("SELECT skill FROM user_skills WHERE user_id = $user_id");
while ($row = $res->fetch_assoc()) {
    $userSkills[] = strtolower($row['skill']);
}

// Step 2: Get all distinct skills from job_role_skills for indexing
$skillSet = [];
$resSkills = $conn->query("SELECT DISTINCT skill FROM job_role_skills");
while ($r = $resSkills->fetch_assoc()) {
    $skillSet[] = strtolower(trim($r['skill']));
}
$skillIndex = array_flip($skillSet);

// Prepare user vector
$userVector = array_fill(0, count($skillSet), 0);
foreach ($userSkills as $usk) {
    if (isset($skillIndex[$usk])) {
        $userVector[$skillIndex[$usk]] = 1;
    }
}

// Step 3: Get jobs with required skills (from skills table) + compute match score
$contentJobs = [];
if (!empty($userSkills)) {
    $likeClauses = [];
    foreach ($userSkills as $skill) {
        $likeClauses[] = "LOWER(s.skill_name) LIKE '%" . $conn->real_escape_string($skill) . "%'";
    }
    $whereClause = implode(" OR ", $likeClauses);

    $sql = "
        SELECT DISTINCT jobs.*, companies.name AS company_name
        FROM jobs 
        JOIN companies ON jobs.company_id = companies.id 
        JOIN skills s ON s.job_id = jobs.id
        WHERE jobs.status = 'approved' AND ($whereClause)
    ";

    $res = $conn->query($sql);
    if (!$res) {
        die("Query failed: " . $conn->error);
    }

    while ($row = $res->fetch_assoc()) {
        $title = $row['title'];

        // Fetch job role skills for this title
        $jobSkills = [];
        $jobVector = array_fill(0, count($skillSet), 0);

        $stmt = $conn->prepare("SELECT skill FROM job_role_skills WHERE role = ?");
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $resJobSkills = $stmt->get_result();

        while ($skillRow = $resJobSkills->fetch_assoc()) {
            $skill = strtolower(trim($skillRow['skill']));
            $jobSkills[] = $skill;
            if (isset($skillIndex[$skill])) {
                $jobVector[$skillIndex[$skill]] = 1;
            }
        }

        // Calculate match score
        $matchScore = 0;
        for ($i = 0; $i < count($skillSet); $i++) {
            $matchScore += $userVector[$i] * $jobVector[$i];
        }
        $totalJobSkills = array_sum($jobVector);
        $score = ($totalJobSkills > 0) ? ($matchScore / $totalJobSkills) * 100 : 0;

        $row['skills_required'] = implode(', ', $jobSkills);
        $row['match_score'] = round($score);

        $contentJobs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommended Jobs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Recommended Jobs</h2>

    <h4 class="text-success">Matched Jobs – Based on Your Skills (Content-Based Filtering)</h4>
    <?php if (empty($contentJobs)): ?>
        <div class="alert alert-info">No content-based recommendations found. Add more skills in your profile.</div>
    <?php else: ?>
        <?php foreach ($contentJobs as $job): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company_name']) ?></h6>
                    <p><strong>Required Skills:</strong> <?= htmlspecialchars($job['skills_required']) ?></p>
                    <p><strong>Match Score:</strong> <?= $job['match_score'] ?>%</p>
                    <a href="users/view_job.php?job_id=<?= $job['id'] ?>" class="btn btn-success">View Job</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr>

    <h4 class="text-primary">Recommended for You – Based on Users Like You (Collaborative Filtering)</h4>
    <?php if (empty($collabJobs)): ?>
        <div class="alert alert-info">No collaborative recommendations yet. Try applying to some jobs first.</div>
    <?php else: ?>
        <?php foreach ($collabJobs as $job): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company_name']) ?></h6>
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

