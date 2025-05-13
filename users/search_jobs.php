<?php
require_once '../core/database.php';
require_once '../core/functions.php';
session_start();
checkLogin();  // Ensure the user is logged in

// ✅ Only show admin-approved jobs
$result = $conn->query("SELECT jobs.*, companies.name AS company_name, companies.location AS company_location 
                        FROM jobs 
                        JOIN companies ON jobs.company_id = companies.id
                        WHERE jobs.status = 'approved'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff4e50, #fc913a);
            font-family: 'Poppins', sans-serif;
        }
        .job-listing-container {
            max-width: 1100px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .job-listing-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #ff4e50;
            text-align: center;
            margin-bottom: 30px;
        }
        .card {
            border-radius: 15px;
            transition: 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        }
        .card-title {
            font-weight: bold;
            color: #ff4e50;
        }
        .btn-outline-warning {
            color: #fc913a;
            border-color: #fc913a;
        }
        .btn-outline-warning:hover {
            background-color: #fc913a;
            color: white;
        }
        .job-description {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<div class="job-listing-container">
    <h2>Available Job Listings</h2>

    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($job = $result->fetch_assoc()) {
                echo "<div class='col-md-6 mb-4'>
                        <div class='card shadow-sm h-100'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$job['title']}</h5>
                                <h6 class='card-subtitle mb-2 text-muted'>{$job['company_name']} - {$job['company_location']}</h6>
                                <button class='btn btn-outline-warning btn-sm mt-2 show-description' data-job-id='{$job['id']}'>View Description</button>
                                <div class='job-description mt-3' id='job-description-{$job['id']}' style='display:none;'>
                                    <p>{$job['description']}</p>
                                    <a href='apply_job.php?job_id={$job['id']}' class='btn btn-sm btn-primary'>Apply Now</a>
                                </div>
                            </div>
                        </div>
                      </div>";
            }
        } else {
            echo "<p>No approved jobs available at the moment.</p>";
        }
        ?>
    </div>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle description visibility
    document.querySelectorAll('.show-description').forEach(function(button) {
        button.addEventListener('click', function() {
            const jobId = this.getAttribute('data-job-id');
            const descriptionDiv = document.getElementById('job-description-' + jobId);
            descriptionDiv.style.display = descriptionDiv.style.display === 'block' ? 'none' : 'block';
        });
    });
</script>

</body>
</html>