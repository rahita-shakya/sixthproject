<?php
session_start();
require_once 'database.php';
require_once 'recommendations_functions.php';




$applicant_id = $_SESSION['user_id'] ?? null;


if (!$applicant_id) {
    echo "Please log in first.";
    exit;
}

// Get recommended job IDs
$recommended_job_ids = generateRecommendations($applicant_id, $conn);

// Display job info
echo "<h2>Recommended Jobs for You</h2>";

if (empty($recommended_job_ids)) {
    echo "<p>No recommendations found at the moment.</p>";
} else {
    foreach ($recommended_job_ids as $job_id) {
        // Use prepared statement
        $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $job_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $job = $result->fetch_assoc();

        if ($job) {
            echo "<div style='border:1px solid #ccc; margin:10px; padding:10px;'>";
            echo "<h4>" . htmlspecialchars($job['title']) . "</h4>";
            echo "<p><strong>Company:</strong> " . htmlspecialchars($job['company_name']) . "</p>";
            echo "<p>" . htmlspecialchars($job['description']) . "</p>";
            echo "</div>";
        }
    }
}
?>
