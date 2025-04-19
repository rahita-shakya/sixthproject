<?php
session_start();
require_once '../core/database.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .form-container {
            max-width: 600px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #4e73df;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2>Post a New Job</h2>

        <form action="process_post_job.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter job title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Write a short description..." required></textarea>
            </div>

            <!-- Uncomment if you want category dropdown later
            <div class="mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <?php
                    // $result = $conn->query("SELECT id, name FROM categories");
                    // while ($row = $result->fetch_assoc()) {
                    //     echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    // }
                    ?>
                </select>
            </div>
            -->

            <button type="submit" class="btn btn-primary w-100">Post Job</button>
        </form>
    </div>
</div>

</body>
</html>
