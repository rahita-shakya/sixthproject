<?php
session_start();
require_once '../core/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all job applications for this user
$stmt = $conn->prepare("
    SELECT 
        a.*, 
        j.title AS job_title, 
        c.name AS company_name, 
        c.location AS company_location 
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN companies c ON j.company_id = c.id
    WHERE a.applicant_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Applications</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Go Back to Dashboard</a>
    <h3 class="mb-4">My Job Applications</h3>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">You have not applied for any jobs yet.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Job Title</th>
                    <th>Company</th>
                    <!-- <th>Location</th> -->
                    <th>Resume</th>
                    <th>Status</th>
                    <th>Applied Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                        $status = strtolower($row['status']);
                        $statusClass = 'status-pending';
                        if ($status === 'approved') $statusClass = 'status-approved';
                        elseif ($status === 'rejected') $statusClass = 'status-rejected';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['job_title']) ?></td>
                        <td><?= htmlspecialchars($row['company_name']) ?></td>
                        <!-- <td><?= htmlspecialchars($row['company_location']) ?></td> -->
                       <td>
    <?php if (!empty($row['resume'])): ?>
        <a href="../uploads/resumes/<?= htmlspecialchars($row['resume']) ?>" target="_blank">View</a>
    <?php else: ?>
        Not Uploaded
    <?php endif; ?>
</td>

                        <td class="<?= $statusClass ?>"><?= ucfirst($status) ?></td>
                        <td><?= htmlspecialchars($row['applied_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
