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
        // If accepted, check if required number of applicants is reached
        if ($status === 'accepted') {
            // Get total accepted applicants for this job
            $acceptedStmt = $conn->prepare("SELECT COUNT(*) AS total_accepted FROM applications WHERE job_id = ? AND status = 'accepted'");
            $acceptedStmt->bind_param("i", $job_id);
            $acceptedStmt->execute();
            $acceptedResult = $acceptedStmt->get_result();
            $acceptedCount = $acceptedResult->fetch_assoc()['total_accepted'];
            $acceptedStmt->close();

            // Get the required number of applicants from the job table
            $requiredStmt = $conn->prepare("SELECT applicants_required FROM jobs WHERE id = ?");
            $requiredStmt->bind_param("i", $job_id);
            $requiredStmt->execute();
            $requiredResult = $requiredStmt->get_result();
            $requiredNumber = $requiredResult->fetch_assoc()['applicants_required'];
            $requiredStmt->close();

            // If required number is reached, reject all remaining pending/null applications
            if ($acceptedCount >= $requiredNumber) {
                $autoRejectStmt = $conn->prepare("
                    UPDATE applications 
                    SET status = 'rejected' 
                    WHERE job_id = ? 
                    AND (status IS NULL OR status = '' OR status = 'pending')
                ");
                $autoRejectStmt->bind_param("i", $job_id);
                $autoRejectStmt->execute();
                $autoRejectStmt->close();
            }
        }

        echo "<script>alert('Applicant has been $status.'); window.history.back();</script>";
    } else {
        echo "<script>alert('Failed to update application status.'); window.history.back();</script>";
    }

    $stmt->close();
}
?>
