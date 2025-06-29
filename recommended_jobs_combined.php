<?php
session_start();
require_once 'core/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Get all unique skills (lowercase)
$skills = [];
$res = $conn->query("SELECT DISTINCT skill_name FROM skills");
while ($row = $res->fetch_assoc()) {
    $skills[] = strtolower($row['skill_name']);
}

// 2. Create job vectors for Content-Based Filtering
$jobVectors = [];
$res = $conn->query("SELECT job_id, skill_name FROM skills");
while ($row = $res->fetch_assoc()) {
    $job_id = $row['job_id'];
    $skill = strtolower($row['skill_name']);
    $index = array_search($skill, $skills);
    if (!isset($jobVectors[$job_id])) {
        $jobVectors[$job_id] = array_fill(0, count($skills), 0);
    }
    if ($index !== false) {
        $jobVectors[$job_id][$index] = 1;
    }
}

// 3. Create current user's skill vector
$userVector = array_fill(0, count($skills), 0);
$stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $skill = strtolower($row['skill']);
    $index = array_search($skill, $skills);
    if ($index !== false) {
        $userVector[$index] = 1;
    }
}
$stmt->close();

// 4. Content-Based Recommendations
$recommendedJobs = [];
foreach ($jobVectors as $job_id => $jobVector) {
    $score = 0;
    for ($i = 0; $i < count($skills); $i++) {
        $score += $jobVector[$i] * $userVector[$i];
    }
    if ($score > 0) {
        $recommendedJobs[$job_id] = $score;
    }
}

// 5. Collaborative Filtering
$userSkillsMatrix = [];
$res = $conn->query("SELECT user_id, skill FROM user_skills");
while ($row = $res->fetch_assoc()) {
    $uid = $row['user_id'];
    $skill = strtolower($row['skill']);
    $index = array_search($skill, $skills);
    if (!isset($userSkillsMatrix[$uid])) {
        $userSkillsMatrix[$uid] = array_fill(0, count($skills), 0);
    }
    if ($index !== false) {
        $userSkillsMatrix[$uid][$index] = 1;
    }
}

function cosine_similarity($vec1, $vec2) {
    $dot = 0; $normA = 0; $normB = 0;
    for ($i = 0; $i < count($vec1); $i++) {
        $dot += $vec1[$i] * $vec2[$i];
        $normA += pow($vec1[$i], 2);
        $normB += pow($vec2[$i], 2);
    }
    return ($normA && $normB) ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
}

$similarityScores = [];
foreach ($userSkillsMatrix as $uid => $vector) {
    if ($uid == $user_id) continue;
    $similarity = cosine_similarity($userVector, $vector);
    if ($similarity > 0) {
        $similarityScores[$uid] = $similarity;
    }
}
arsort($similarityScores);

$collabRecommendedJobs = [];
foreach ($similarityScores as $otherUser => $simScore) {
    $stmt = $conn->prepare("SELECT DISTINCT s.job_id FROM skills s JOIN user_skills us ON us.skill = s.skill_name WHERE us.user_id = ?");
    $stmt->bind_param("i", $otherUser);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $job_id = $row['job_id'];
        if (!isset($collabRecommendedJobs[$job_id])) {
            $collabRecommendedJobs[$job_id] = 0;
        }
        $collabRecommendedJobs[$job_id] += $simScore;
    }
    $stmt->close();
}

// 6. Hybrid Combination
$hybridScores = [];
foreach ($recommendedJobs as $job_id => $contentScore) {
    $hybridScores[$job_id] = $contentScore;
}
foreach ($collabRecommendedJobs as $job_id => $collabScore) {
    if (!isset($hybridScores[$job_id])) {
        $hybridScores[$job_id] = 0;
    }
    $hybridScores[$job_id] += $collabScore;
}
arsort($hybridScores);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hybrid Job Recommendations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .job-description { display: none; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Recommended Jobs for you</h3>
    <?php if (empty($hybridScores)) : ?>
        <div class="alert alert-warning">No hybrid job recommendations found.</div>
    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($hybridScores as $job_id => $score) : ?>
                <?php
                $stmt = $conn->prepare("SELECT j.title, j.description, j.applicants_required, j.start_date, j.end_date, c.name AS company_name FROM jobs j JOIN companies c ON j.company_id = c.id WHERE j.id = ? AND j.status = 'approved'");
                $stmt->bind_param("i", $job_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $row = $result->fetch_assoc()) :
                    $title = htmlspecialchars($row['title']);
                    $descriptionFull = htmlspecialchars($row['description']);
                    $description = htmlspecialchars(substr($row['description'], 0, 100)) . '...';
                    $applicants = htmlspecialchars($row['applicants_required']);
                    $start = htmlspecialchars($row['start_date']);
                    $end = htmlspecialchars($row['end_date']);
                    $company = htmlspecialchars($row['company_name']);
                ?>
                    <li class="list-group-item">
                        <h5><?= $title ?> <small class="text-muted">at <?= $company ?></small></h5>
                        <p><?= $description ?></p>
                        <button class="btn btn-sm btn-primary mt-2 show-description" data-job-id="<?= $job_id ?>">View Description</button>
                        <div class="job-description mt-3" id="job-description-<?= $job_id ?>">
                            <p><strong>Applicants Required:</strong> <?= $applicants ?></p>
                            <p><strong>Start Date:</strong> <?= $start ?> <br> <strong>End Date:</strong> <?= $end ?></p>
                            <p><?= $descriptionFull ?></p>
                            <a href="users/apply_job.php?job_id=<?= $job_id ?>" class="btn btn-sm btn-primary">Apply Now</a>
                        </div>
                    </li>
                <?php endif; $stmt->close(); ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script>
    document.querySelectorAll('.show-description').forEach(button => {
        button.addEventListener('click', () => {
            const jobId = button.getAttribute('data-job-id');
            const descDiv = document.getElementById('job-description-' + jobId);
            if (!descDiv) return;
            if (descDiv.style.display === 'block') {
                descDiv.style.display = 'none';
                button.textContent = 'View Description';
            } else {
                descDiv.style.display = 'block';
                button.textContent = 'Hide Description';
            }
        });
    });
</script>
</body>
</html>
