<?php
require_once '../core/database.php';

// Get all jobs with company name
$result = $conn->query("SELECT jobs.*, companies.name AS company_name 
                        FROM jobs 
                        JOIN companies ON jobs.company_id = companies.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Job Listings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            padding: 30px;
        }

        .table-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background-color: #ff4e50;
            color: white;
        }

        .table thead th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }

        .table tbody td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tbody tr:hover {
            background-color: #ffe3e3;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>

<div class="table-container">
     <a href="dashboard.php" class="btn btn-secondary go-back-btn">Go Back</a>
    <h2>All Job Posts</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Company Name</th>
                <th>Status</th>
                <th>Posted Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($job = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($job['title']) ?></td>
                    <td><?= htmlspecialchars($job['company_name']) ?></td>
                    <td><?= htmlspecialchars($job['status']) ?></td>
                    <td><?= htmlspecialchars($job['created_at']) ?></td>
                </tr>
                <?php } ?>
            <?php else: ?>
                <tr><td colspan="4">No job listings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
