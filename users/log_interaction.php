// log_interaction.php
require_once '../core/database.php';
session_start();



$user_id = $_SESSION['user_id']; // Assuming user_id is stored in session after login
$job_id = $_POST['job_id'];
$action = $_POST['action'];  // The action could be 'view' or any other interaction you want to track

// Validate and sanitize inputs
$job_id = (int)$job_id;
$action = htmlspecialchars($action);

if ($action == 'view' && $job_id > 0) {
    // Insert the interaction into the database
    $stmt = $conn->prepare("INSERT INTO job_interactions (user_id, job_id, action, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $job_id, $action);
    
    if ($stmt->execute()) {
        echo "Interaction logged successfully!";
    } else {
        echo "Error logging interaction.";
    }
    
    $stmt->close();
}
