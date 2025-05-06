<?php
require_once '../core/database.php';

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM applicants WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            // Check if user has selected any skills
            $checkSkillStmt = $conn->prepare("SELECT COUNT(*) AS count FROM user_skills WHERE user_id = ?");
            $checkSkillStmt->bind_param("i", $id);
            $checkSkillStmt->execute();
            $skillResult = $checkSkillStmt->get_result();
            $skillRow = $skillResult->fetch_assoc();

            if ($skillRow['count'] == 0) {
                // No skills selected yet
                echo "<script>alert('Welcome! Please select your skills first.'); window.location.href='select_skills.php';</script>";
            } else {
                // Skills found, go to dashboard
                echo "<script>alert('Login Successful! Redirecting to Dashboard...'); window.location.href='dashboard.php';</script>";
            }

        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #36D1DC, #5B86E5);
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .login-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #5B86E5;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: none;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: #5B86E5;
            box-shadow: 0px 0px 10px rgba(91, 134, 229, 0.3);
        }
        .btn-login {
            background: #5B86E5;
            border: none;
            font-size: 1.2rem;
            padding: 10px;
            border-radius: 50px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-login:hover {
            background: #36D1DC;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
        .alert {
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-container">
        <h2><i class="fa fa-sign-in-alt"></i> Login</h2>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <p class="register-link mt-3">Don't have an account? <a href="register.php" class="text-primary fw-bold">Register here</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
