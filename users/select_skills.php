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
    if (!empty($_POST['skills'])) {
        $skills = $_POST['skills'];

        // Remove existing skills before inserting new ones (optional, to avoid duplicates)
        $deleteStmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ?");
        $deleteStmt->bind_param("i", $user_id);
        $deleteStmt->execute();

        $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");

        foreach ($skills as $skill) {
            $skill = htmlspecialchars(trim($skill));
            $stmt->bind_param("is", $user_id, $skill);
            $stmt->execute();
        }

        $success = "Skills saved successfully!";
        echo "<script>alert('Skills saved successfully! Redirecting to Dashboard...'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        $error = "Please select at least one skill.";
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

        <?php if ($error) { echo "<div class='alert alert-danger text-center'>$error</div>"; } ?>
        <?php if ($success) { echo "<div class='alert alert-success text-center'>$success</div>"; } ?>

        <form method="POST">
            <div class="row">
                <?php
                // Example skill options (you can fetch from a DB table if preferred)
                $availableSkills = ["PHP", "JavaScript", "Java", "Python", "HTML", "CSS", "React", "MySQL", "Laravel", "C++",
                "Excel","Word","Powerpoint","Photoshop", "Figma","Canva","Video Editing"];
                foreach ($availableSkills as $index => $skill) {
                    echo '
                    <div class="col-md-6 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="'. $skill .'" id="skill'.$index.'">
                            <label class="form-check-label" for="skill'.$index.'">'. $skill .'</label>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-save px-5">Save Skills</button>
            </div>
        </form>
    </div>
</body>
</html>
