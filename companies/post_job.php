<?php
session_start();
require_once '../core/database.php';

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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form action="process_post_job.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Job Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">-- Select a category --</option>
                    <option value="Backend developer">Backend developer</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Frontend Developer">Frontend Developer</option>
                    <option value="Fullstack developer">Fullstack developer</option>
                    <option value="Manager">Project Manager</option>
                    <option value="Front Desk officer">Front desk officer</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="skills" class="form-label">Required Skills</label>
                <input type="text" name="skills" id="skills" class="form-control" placeholder="e.g., PHP, MySQL, React" required>
                <small class="text-muted">Separate multiple skills with commas.</small>
            </div>

            <button type="submit" class="btn btn-primary w-100">Post Job</button>
        </form>
    </div>
</div>

</body>
</html>
