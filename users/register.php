<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - JobSelect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff4e50, #fc913a);
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .register-container h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #ff4e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
            box-shadow: none;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: #ff4e50;
            box-shadow: 0px 0px 10px rgba(255, 78, 80, 0.3);
        }
        .btn-register {
            background: #ff4e50;
            border: none;
            font-size: 1.2rem;
            padding: 10px;
            border-radius: 50px;
            transition: 0.3s;
            width: 100%;
        }
        .btn-register:hover {
            background: #fc913a;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="register-container">
        <h2><i class="fa fa-user-plus"></i> Create Account</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-user"></i> Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-envelope"></i> Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><i class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>
            <button type="submit" class="btn btn-register">Register</button>
        </form>
        <p class="login-link mt-3">Already have an account? <a href="login.php" class="text-primary fw-bold">Login here</a></p>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Database connection
$host = '127.0.0.1';
$dbname = 'jobselect';
$username = 'root'; // Change if needed
$password = ''; // Change if needed

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypting password

    $stmt = $conn->prepare("INSERT INTO applicants (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
