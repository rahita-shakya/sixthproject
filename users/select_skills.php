<?php
require_once '../core/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in first!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_skills = isset($_POST['skills']) ? $_POST['skills'] : [];
    $other_skill = isset($_POST['other_skill']) ? trim($_POST['other_skill']) : '';

    if (empty($selected_skills) && empty($other_skill)) {
        $error = "Please select or enter at least one skill.";
    } else {
        // Clear previous skills
        $deleteStmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ?");
        $deleteStmt->bind_param("i", $user_id);
        $deleteStmt->execute();

        // Prepare insert statement
        $insertStmt = $conn->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");

        // Insert selected skills
        foreach ($selected_skills as $skill) {
            $skill = htmlspecialchars(trim($skill));
            $insertStmt->bind_param("is", $user_id, $skill);
            $insertStmt->execute();
        }

        // Insert 'Other' skill if provided
        if (!empty($other_skill)) {
            $clean_skill = htmlspecialchars($other_skill);
            $insertStmt->bind_param("is", $user_id, $clean_skill);
            $insertStmt->execute();
        }

        echo "<script>alert('Skills saved successfully! Redirecting to Dashboard...'); window.location.href='dashboard.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Skills - JobSelect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #36D1DC, #5B86E5);
            font-family: 'Poppins', sans-serif;
        }
        .skills-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            border: 2px solid #5B86E5;
            background-color: white;
            box-shadow: none;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #5B86E5;
            border-color: #5B86E5;
        }
        .btn-save {
            background-color: #5B86E5;
            color: white;
            border-radius: 50px;
        }
        .btn-save:hover {
            background-color: #36D1DC;
        }
    </style>
</head>
<body>
    <div class="skills-container">
        <h2 class="text-center mb-4">Select Your Skills</h2>

        <?php if ($error) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <?php if ($success) echo "<div class='alert alert-success text-center'>$success</div>"; ?>

        <form method="POST">
            <div class="row">
                <?php
                // Fetch distinct skills from the database
                $result = $conn->query("SELECT DISTINCT skill_name FROM skills ORDER BY skill_name ASC");
                if ($result->num_rows > 0) {
                    $index = 0;
                    while ($row = $result->fetch_assoc()) {
                        $skill = htmlspecialchars($row['skill_name']);
                        echo '
                        <div class="col-md-6 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="skills[]" value="'. $skill .'" id="skill'.$index.'">
                                <label class="form-check-label" for="skill'.$index.'">'. $skill .'</label>
                            </div>
                        </div>';
                        $index++;
                    }
                } else {
                    echo "<p>No skills available from companies yet.</p>";
                }
                ?>
            </div>

            <!-- Other Skill input -->
            <div class="mt-4">
                <label for="other_skill">Other Skill (if not listed):</label>
                <input type="text" name="other_skill" id="other_skill" class="form-control" placeholder="Enter other skill">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-save px-5">Save Skills</button>
            </div>
        </form>
    </div>
</body>
</html>
