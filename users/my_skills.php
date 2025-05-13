<?php
session_start();
require_once '../core/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle skill update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_skills = $_POST['skills'] ?? [];

    // Clear old skills
    $stmt = $conn->prepare("DELETE FROM user_skills WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Insert new skills
    $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill) VALUES (?, ?)");
    foreach ($selected_skills as $skill) {
        $stmt->bind_param("is", $user_id, $skill);
        $stmt->execute();
    }

    $message = "Skills updated successfully!";
}

// Fetch all available skills
$skillSet = [];
$result = $conn->query("SELECT DISTINCT skill FROM job_role_skills");
while ($row = $result->fetch_assoc()) {
    $skillSet[] = $row['skill'];
}

// Fetch user's selected skills
$userSkills = [];
$stmt = $conn->prepare("SELECT skill FROM user_skills WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $userSkills[] = $row['skill'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Skills</title>
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
    <h3 class="mb-4">My Skills</h3>

    <?php if (!empty($message)) : ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <?php foreach ($skillSet as $skill): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $skill ?>" 
                        <?= in_array($skill, $userSkills) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= htmlspecialchars($skill) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Skills</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>
</body>
</html>
