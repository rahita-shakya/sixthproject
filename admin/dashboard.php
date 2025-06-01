<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - JobSelect</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .dashboard-header {
            background: #4e73df;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .dashboard-header h1 {
            margin: 0;
        }
        .nav-links {
            display: flex;
            justify-content: space-evenly;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .nav-item {
            background: #4e73df;
            color: white;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 1.1rem;
            text-align: center;
            width: 200px;
            margin: 10px;
            transition: 0.3s;
        }
        .nav-item:hover {
            background: #1c3d8d;
            cursor: pointer;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            text-align: center;
            width: 150px;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-header">
        <h1>Welcome, Admin!</h1>
        <p>Manage your platform efficiently with the options below</p>
    </div>

    <div class="nav-links">
        <div class="nav-item">
            <a href="manage_jobs.php" style="color: white; text-decoration: none;">
                <i class="fa fa-briefcase"></i><br>Manage Jobs
            </a>
        </div>
     
        <div class="nav-item">
            <a href="manage_companies.php" style="color: white; text-decoration: none;">
                <i class="fa fa-building"></i><br>Manage Companies
            </a>
        </div>
        <div class="nav-item">
            <a href="manage_applicants.php" style="color: white; text-decoration: none;">
                <i class="fa fa-users"></i><br>Manage Applicants
            </a>
        </div>
        <div class="nav-item">
            <a href="manage_messages.php" style="color: white; text-decoration: none;">
                <i class="fa fa-envelope"></i><br>Job Lists
            </a>
        </div>
        <div class="nav-item">
            <a href="view_application.php" style="color: white; text-decoration: none;">
                <i class="fa fa-envelope"></i><br>View Applications
            </a>
        </div>
    </div>
     
</div>
    

    <div class="text-center mt-5">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
