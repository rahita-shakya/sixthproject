<?php
require_once '../core/database.php';
require_once '../core/functions.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$jobId = $data['jobId'];
$userId = $_SESSION['user_id'];  // Ensure this is set at login

// Avoid duplicates
$stmt = $conn->prepare("SELECT * FROM job_interest WHERE user_id = ? AND job_id = ?");
$stmt->bind_param("ii", $userId, $jobId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insert the user's job interest
    $stmt = $conn->prepare("INSERT INTO job_interest (user_id, job_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $jobId);
    $stmt->execute();
}

// Collaborative Filtering Logic (Item-Item Collaborative Filtering)
$recommendedJobs = [];

// Get jobs that are most often interacted with by users who also interacted with the same job
$stmt = $conn->prepare("
    SELECT j.job_id, COUNT(*) AS interest_count
    FROM job_interest ji
    JOIN job_interest ji2 ON ji2.user_id = ji.user_id
    JOIN jobs j ON j.id = ji2.job_id
    WHERE ji.job_id = ? AND j.id != ?
    GROUP BY j.job_id
    ORDER BY interest_count DESC
    LIMIT 5
");
$stmt->bind_param("ii", $jobId, $jobId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $recommendedJobs[] = $row['job_id'];  // Collect recommended job IDs
}

// Fetch the recommended job details
$jobDetails = [];
if (!empty($recommendedJobs)) {
    $inClause = implode(',', $recommendedJobs);
    $stmt = $conn->prepare("SELECT id, job_title, job_description FROM jobs WHERE id IN ($inClause)");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jobDetails[] = $row;
    }
}

echo json_encode(['status' => 'success', 'recommendedJobs' => $jobDetails]);

?>
