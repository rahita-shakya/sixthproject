<?php
session_start();
require_once '../core/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome to JobSelect Dashboard!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSelect Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff4e50, #fc913a);
            font-family: 'Poppins', sans-serif;
        }
        .dashboard-container {
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .dashboard-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #ff4e50;
            margin-bottom: 30px;
        }
        .btn-dashboard {
            background: #ff4e50;
            border: none;
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 50px;
            transition: 0.3s;
            width: 100%;
            margin: 10px 0;
        }
        .btn-dashboard:hover {
            background: #fc913a;
        }
        .link-container a {
            font-size: 1.1rem;
            color: #ff4e50;
            text-decoration: none;
            font-weight: bold;
        }
        .link-container a:hover {
            color: #fc913a;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="dashboard-container">
        <h2><i class="fa fa-tachometer-alt"></i> JobSelect Dashboard</h2>
        <p class="mb-4">Welcome to your dashboard! Choose an action below.</p>
        
        <a href="search_jobs.php" class="btn btn-dashboard">Search Jobs</a>
        <a href="../recommended_jobs.php" class="btn btn-dashboard">Recommended Jobs</a>
        <a href="messages.php" class="btn btn-dashboard">Messages</a>
        <a href="logout.php" class="btn btn-dashboard">Logout</a>

        <div class="link-container mt-3">
            <p>Need help? <a href="help.php">Contact Support</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
