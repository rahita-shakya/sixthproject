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
if (!$res) {
    die("DB error getting skills: " . $conn->error);
}
while ($row = $res->fetch_assoc()) {
    $skills[] = strtolower($row['skill_name']);
}

// 2. Create job vectors for Content-Based Filtering
$jobVectors = [];
$res = $conn->query("SELECT job_id, skill_name FROM skills");
if (!$res) {
    die("DB error getting job skills: " . $conn->error);
}
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
if (!$stmt) {
    die("Prepare failed (user skills): " . $conn->error);
}
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

// 4. Content-Based Recommendations - dot product similarity
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
arsort($recommendedJobs);

// 5. Collaborative Filtering preparation: build user-skills matrix
$userSkillsMatrix = [];
$res = $conn->query("SELECT user_id, skill FROM user_skills");
if (!$res) {
    die("DB error getting all user skills: " . $conn->error);
}
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

// 6. Cosine similarity function
function cosine_similarity($vec1, $vec2) {
    $dot = 0; $normA = 0; $normB = 0;
    for ($i = 0; $i < count($vec1); $i++) {
        $dot += $vec1[$i] * $vec2[$i];
        $normA += pow($vec1[$i], 2);
        $normB += pow($vec2[$i], 2);
    }
    return ($normA && $normB) ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
}

// 7. Calculate similarity scores between current user and others
$similarityScores = [];
foreach ($userSkillsMatrix as $uid => $vector) {
    if ($uid == $user_id) continue;
    $similarity = cosine_similarity($userVector, $vector);
    if ($similarity > 0) {
        $similarityScores[$uid] = $similarity;
    }
}
arsort($similarityScores);

// 8. Collaborative Recommendations: get jobs related to skills of similar users
$collabRecommendedJobs = [];
foreach ($similarityScores as $otherUser => $simScore) {
    $stmt = $conn->prepare("
        SELECT DISTINCT s.job_id 
        FROM skills s
        JOIN user_skills us ON us.skill = s.skill_name
        WHERE us.user_id = ?
    ");
    if (!$stmt) {
        die("Prepare failed (collab jobs): " . $conn->error);
    }
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
arsort($collabRecommendedJobs);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommended Jobs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">

    <h3 class="mb-4">Recommended Jobs (Content-Based)</h3>
    <?php if (empty($recommendedJobs)) : ?>
        <div class="alert alert-warning">No matching jobs found based on your skills.</div>
    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($recommendedJobs as $job_id => $score) : ?>
                <?php
                $stmt = $conn->prepare("SELECT title, description FROM jobs WHERE id = ? AND status = 'approved'");
                $stmt->bind_param("i", $job_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $row = $result->fetch_assoc()) :
                    $title = htmlspecialchars($row['title']);
                    $description = htmlspecialchars(substr($row['description'], 0, 100)) . '...';

                    // Calculate percentage match score
                    $totalScore = array_sum($jobVectors[$job_id]);
                    $percentage = ($score / $totalScore) * 100;

                    // Fetch required skills for this job
                    $skillsStmt = $conn->prepare("SELECT skill_name FROM skills WHERE job_id = ?");
                    $skillsStmt->bind_param("i", $job_id);
                    $skillsStmt->execute();
                    $skillsResult = $skillsStmt->get_result();
                    $requiredSkills = [];
                    while ($skillRow = $skillsResult->fetch_assoc()) {
                        $requiredSkills[] = htmlspecialchars($skillRow['skill_name']);
                    }
                    $skillsStmt->close();
                ?>
                    <li class="list-group-item">
                        <h5><?= $title ?></h5>
                        <p><?= $description ?></p>
                        <p><strong>Required Skills:</strong> <?= implode(', ', $requiredSkills) ?></p>
                        <small>Match Score: <?= round($percentage, 2) ?>%</small><br>
                        <a href="apply.php?job_id=<?= $job_id ?>" class="btn btn-sm btn-primary mt-2">Apply Now</a>
                    </li>
                <?php endif;
                $stmt->close();
                ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr class="my-5">
    <?php
$collabRecommendedJobs = [];

$currentUserId = $_SESSION['user_id'] ?? null;

if ($currentUserId) {
    // 1. Fetch all users and approved jobs
    $users = [];
    $jobs = [];

    $result = $conn->query("SELECT DISTINCT user_id FROM job_interactions");
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['user_id'];
    }

    $result = $conn->query("SELECT DISTINCT id FROM jobs WHERE status = 'approved'");
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row['id'];
    }

    // 2. Initialize User-Job matrix with zeros
    $matrix = [];
    foreach ($users as $user) {
        $matrix[$user] = [];
        foreach ($jobs as $job) {
            $matrix[$user][$job] = 0;
        }
    }

    // 3. Fill in job interactions
    $result = $conn->query("SELECT user_id, job_id FROM job_interactions");
    while ($row = $result->fetch_assoc()) {
        $user = $row['user_id'];
        $job = $row['job_id'];
        if (isset($matrix[$user][$job])) {
            $matrix[$user][$job] = 1;
        }
    }

    // 4. Cosine similarity function (unchanged)
    function cosineSimilarity($vecA, $vecB) {
        $dot = 0;
        $normA = 0;
        $normB = 0;
        foreach ($vecA as $i => $a) {
            $b = $vecB[$i] ?? 0;
            $dot += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }
        if ($normA == 0 || $normB == 0) return 0;
        return $dot / (sqrt($normA) * sqrt($normB));
    }

    // 5. Compute similarity between current user and others
    $similarities = [];
    foreach ($users as $user) {
        if ($user == $currentUserId) continue;
        // Safety: check if vectors exist
        if (isset($matrix[$currentUserId]) && isset($matrix[$user])) {
            $similarities[$user] = cosineSimilarity($matrix[$currentUserId], $matrix[$user]);
        }
    }

    // 6. Sort and get top 5 similar users
    arsort($similarities);
    $topSimilarUsers = array_slice($similarities, 0, 5, true);

    // 7. Calculate weighted job scores from top similar users
    $jobScores = [];
    foreach ($jobs as $jobId) {
        // Skip if current user already applied/viewed
        if (!isset($matrix[$currentUserId][$jobId]) || $matrix[$currentUserId][$jobId] == 1) continue;

        $score = 0;
        foreach ($topSimilarUsers as $otherUser => $sim) {
            if (isset($matrix[$otherUser][$jobId])) {
                $score += $sim * $matrix[$otherUser][$jobId];
            }
        }

        if ($score > 0) {
            $jobScores[$jobId] = $score;
        }
    }

    // 8. Sort recommended jobs by score descending
    arsort($jobScores);
    $collabRecommendedJobs = $jobScores;
}
?>




    <h3 class="mb-4">Recommended Jobs (Collaborative Filtering)</h3>
<?php if (empty($collabRecommendedJobs)) : ?>
    <div class="alert alert-warning">No collaborative job recommendations found.</div>
<?php else : ?>
    <ul class="list-group">
        <?php foreach ($collabRecommendedJobs as $job_id => $score) : ?>
            <?php
            $stmt = $conn->prepare("
                SELECT j.title, j.description, c.name AS company_name 
                FROM jobs j 
                JOIN companies c ON j.company_id = c.id 
                WHERE j.id = ? AND j.status = 'approved'
            ");
            $stmt->bind_param("i", $job_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $row = $result->fetch_assoc()) :
                $title = htmlspecialchars($row['title']);
                $description = htmlspecialchars(substr($row['description'], 0, 100)) . '...';
                $companyName = htmlspecialchars($row['company_name']);
            ?>
                <li class="list-group-item">
                    <h5><?= $title ?></h5>
                    <p><?= $description ?></p>
                    <small>Company: <?= $companyName ?></small><br>
                    <small>Similarity Score: <?= round($score, 3) ?></small><br>
                    <a href="apply.php?job_id=<?= $job_id ?>" class="btn btn-sm btn-success mt-2">Apply Now</a>
                </li>
            <?php endif;
            $stmt->close();
            ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


</div>
</body>
</html>
