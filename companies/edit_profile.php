<?php
session_start();
require_once '../core/database.php';

$company_login_id = $_SESSION['company_id'] ?? null;
if (!$company_login_id) {
    echo "<script>alert('Please login first.'); window.location.href='login.php';</script>";
    exit;
}

// Get current login info from company_login
$stmt = $conn->prepare("SELECT * FROM companies_login WHERE id = ?");
$stmt->bind_param("i", $company_login_id);
$stmt->execute();
$result = $stmt->get_result();

if ($company = $result->fetch_assoc()) {

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        // Basic validation
        if (empty($name) || empty($email)) {
            $error = "Name and Email are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $update_stmt = $conn->prepare("UPDATE companies_login SET name = ?, email = ?, contact_number = ? WHERE id = ?");
            $update_stmt->bind_param("sssi", $name, $email, $phone, $company_login_id);
            if ($update_stmt->execute()) {
                $success = "Profile updated successfully.";
                // Update local copy
                $company['name'] = $name;
                $company['email'] = $email;
                $company['phone'] = $phone;
            } else {
                $error = "Failed to update profile.";
            }
        }
    }

} else {
    echo "<script>alert('Company not found.'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Company Login Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="dashboard.php" class="btn btn-secondary mb-3">🔙 Back to Dashboard</a>
    <h3>Edit Profile - <?= htmlspecialchars($company['name'] ?? '') ?></h3>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)) : ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="name" class="form-label">Company Name *</label>
            <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($company['name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($company['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>
</div>
</body>
</html>
