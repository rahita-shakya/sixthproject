<?php
session_start();
require_once '../core/database.php';
require_once '../core/functions.php';
checkLogin();


// Get approved jobs
$result = $conn->query("SELECT jobs.*, companies.name AS company_name, companies.location AS company_location 
                        FROM jobs 
                        JOIN companies ON jobs.company_id = companies.id
                        WHERE jobs.status = 'approved'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JobSelect Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff4e50, #fc913a);
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: white;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }
        .navbar a {
            font-weight: bold;
            color: #ff4e50;
        }
        .navbar a:hover {
            color: #fc913a;
        }
        .dashboard-heading {
            text-align: center;
            color:rgb(255, 245, 245);
            margin: 30px 0;
        }
        .job-listing-container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 15px;
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">JobSelect</a>
        <div class="d-flex gap-3">
            <a class="nav-link" href="../recommended_jobs_combined.php">Recommended Jobs</a>
            <a href="message.php?job_id=<?= $job_id ?>" style="text-decoration: none;">View Status</a>


            <a class="nav-link" href="my_skills.php">My Skills</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<h2 class="dashboard-heading"><i class="fa fa-tachometer-alt"></i> Welcome to your Dashboard</h2>

<div class="job-listing-container">
    <h3 class="text-center mb-4">Available jobs</h3>
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
<p>
    <strong>Applicants Required:</strong> {$job['applicants_required']}<br>
    <strong>Start Date:</strong> {$job['start_date']}<br>
    <strong>End Date:</strong> {$job['end_date']}
</p>
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
</div>

<script>
    document.querySelectorAll('.show-description').forEach(function(button) {
        button.addEventListener('click', function () {
            const jobId = this.getAttribute('data-job-id');
            const descriptionDiv = document.getElementById('job-description-' + jobId);
            const isVisible = descriptionDiv.style.display === 'block';
            descriptionDiv.style.display = isVisible ? 'none' : 'block';
        });
    });
</script>

</body>
</html>
