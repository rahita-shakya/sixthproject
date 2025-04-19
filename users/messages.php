<?php
require_once '../core/database.php';
require_once '../core/functions.php'; // This file contains checkLogin()

session_start();
checkLogin();

$applicant_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM messages WHERE applicant_id = $applicant_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff4e50, #fc913a);
            font-family: 'Poppins', sans-serif;
        }
        .message-container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 50px;
        }
        .message-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #ff4e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .message {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .message-time {
            font-size: 0.9rem;
            color: gray;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="message-container">
        <h2><i class="fa fa-envelope"></i> Your Messages</h2>
        
        <?php
        if ($result->num_rows > 0) {
            while ($msg = $result->fetch_assoc()) {
                echo "<div class='message'>
                        <p>{$msg['message']}</p>
                        <p class='message-time'>Sent At: {$msg['sent_at']}</p>
                    </div>";
            }
        } else {
            echo "<p>No messages found.</p>";
        }
        ?>

        <div class="back-link">
            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
