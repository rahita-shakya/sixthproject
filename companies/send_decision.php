<?php
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
    $applicant_id = isset($_POST['applicant_id']) ? (int)$_POST['applicant_id'] : 0;

    $action = $_POST['action'] ?? '';
    $message = trim($_POST['company_message'] ?? '');

    if ($job_id <= 0 || $applicant_id <= 0 || !in_array($action, ['accept', 'reject'])) {
        echo "<script>alert('Invalid input.'); window.history.back();</script>";
        exit;
    }

    // Set status based on action
    $status = ($action === 'accept') ? 'accepted' : 'rejected';

    // Update the application status in database
    $stmt = $conn->prepare("UPDATE applications SET status = ?, company_message = ? WHERE applicant_id = ? AND job_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssii", $status, $message, $applicant_id, $job_id);

    if ($stmt->execute()) {
        // Optional: send email notification here if you have applicant's email
        /*
        // Fetch applicant email
        $emailStmt = $conn->prepare("SELECT email FROM applicants WHERE id = ?");
        $emailStmt->bind_param("i", $applicant_id);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();
        if ($emailRow = $emailResult->fetch_assoc()) {
            $email = $emailRow['email'];
            $subject = "Job Application Update";
            $emailMessage = "Your application for job ID $job_id has been $status.\n\nMessage from company:\n$message";
            mail($email, $subject, $emailMessage);
        }
        $emailStmt->close();
        */

        echo "<script>alert('Applicant has been $status.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Failed to update application status.'); window.history.back();</script>";
    }
    $stmt->close();
}
?>
