<?php
require_once '../core/database.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Job Listings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 40px;
        }
        .job-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .job-title {
            font-size: 20px;
            font-weight: bold;
            color: #ff4b2b;
        }
        .btn-custom {
            border: 1px solid orange;
            color: orange;
        }
        .btn-custom:hover {
            background: orange;
            color: #fff;
        }
        h2 {
            text-align: center;
            color: #ff4b2b;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Available Job Listings</h2>

    <div class="row">
        <?php
       $sql = "SELECT 
            jobs.id, jobs.title, jobs.start_date, jobs.end_date,
            jobs.applicants_required,
            companies.name AS company_name, companies.location AS company_location
        FROM jobs
        JOIN companies ON jobs.company_id = companies.id
        WHERE jobs.status = 'approved'";


        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-6">
                    <div class="job-card">
                        <div class="job-title"><?php echo htmlspecialchars($row['title']); ?></div>
                        <p><strong>Company:</strong> <?php echo htmlspecialchars($row['company_name']); ?></p>
                        <p><strong>Location:</strong> <?php echo $row['company_location'] ?? 'Not specified'; ?></p>
                        <p><strong>Start Date:</strong> <?php echo $row['start_date'] ?? 'Not set'; ?></p>
                        <p><strong>End Date:</strong> <?php echo $row['end_date'] ?? 'Not set'; ?></p>
                        <p><strong>Applicants Required:</strong> <?php echo $row['applicants_required']; ?></p>

                        <a href="view_job.php?job_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-custom">View Description</a>

                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-white'>No job listings found.</p>";
        }
        ?>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
