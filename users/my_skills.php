<?php
session_start();
require_once '../core/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle skill update when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_skills = $_POST['skills'] ?? [];
    $other_skills_raw = $_POST['other_skills'] ?? '';

    // Clean and split other skills if any (comma separated)
    $other_skills = array_filter(array_map('trim', explode(',', $other_skills_raw)));

    // Delete old skills for this user
    $deleteStmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ?");
    $deleteStmt->bind_param("i", $user_id);
    $deleteStmt->execute();

    // Insert selected skills
    $insertStmt = $conn->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");

    foreach ($selected_skills as $skill) {
        $skill = htmlspecialchars(trim($skill));
        $insertStmt->bind_param("is", $user_id, $skill);
        $insertStmt->execute();
    }

    // Insert other skills
    foreach ($other_skills as $skill) {
        $skill = htmlspecialchars(trim($skill));
        if ($skill !== '') {
            $insertStmt->bind_param("is", $user_id, $skill);
            $insertStmt->execute();
        }
    }

    $message = "Skills updated successfully!";
}

// Fetch user's selected skills
$userSkills = [];
$skillStmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$skillStmt->bind_param("i", $user_id);
$skillStmt->execute();
$result = $skillStmt->get_result();
while ($row = $result->fetch_assoc()) {
    $userSkills[] = $row['skill'];
}

// Fetch skills from the skills table
$companySkills = [];
$result = $conn->query("SELECT DISTINCT skill_name FROM skills ORDER BY skill_name ASC");
while ($row = $result->fetch_assoc()) {
    $companySkills[] = $row['skill_name'];
}

// Merge skills from database and user, ensuring uniqueness
$allSkills = array_unique(array_merge($companySkills, $userSkills));
sort($allSkills);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Skills - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f7f7f7;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-check-label {
            text-transform: capitalize;
        }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4 text-center">My Skills</h3>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <?php if (empty($allSkills)): ?>
                <p class="text-muted">No skills available yet.</p>
            <?php else: ?>
                <?php foreach ($allSkills as $skill): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="skills[]" value="<?= htmlspecialchars($skill) ?>"
                            <?= in_array($skill, $userSkills) ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= htmlspecialchars($skill) ?></label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="other_skills" class="form-label">Add Other Skills (comma separated):</label>
            <input type="text" class="form-control" id="other_skills" name="other_skills" placeholder="e.g. Python, Photoshop, JavaScript">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Skills</button>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </form>
</div>
</body>
</html>
