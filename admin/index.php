<?php
require_once '../core/database.php';
session_start();

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #4e73df, #1c3d8d);
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
            color: #4e73df;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: none;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0px 0px 10px rgba(78, 115, 223, 0.3);
        }
        .btn-login {
            background: #4e73df;
            border: none;
            font-size: 1.2rem;
            padding: 10px;
            border-radius: 50px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-login:hover {
            background: #1c3d8d;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-container">
        <h2><i class="fa fa-sign-in-alt"></i> Admin Login</h2>
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

        <!-- Registration Link -->
        <div class="register-link mt-3">
            <p>Don't have an account? <a href="register.php" class="text-primary fw-bold">Register here</a></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
