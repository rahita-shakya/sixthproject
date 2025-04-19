<?php
session_start();
require_once '../core/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $contact = trim($_POST['contact']);
    $address = trim($_POST['address']);
    $created_at = date("Y-m-d H:i:s");

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt_check = $conn->prepare("SELECT id FROM companies_login WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Email already registered. Please use a different email.');</script>";
    } else {
        // Insert into companies_login
        $stmt1 = $conn->prepare("INSERT INTO companies_login (name, email, password, contact_number, address, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("ssssss", $name, $email, $hashed_password, $contact, $address, $created_at);

        if ($stmt1->execute()) {
            $company_login_id = $conn->insert_id;

            // Insert into companies table
            $stmt2 = $conn->prepare("INSERT INTO companies (name, description, company_login_id) VALUES (?, ?, ?)");
            $stmt2->bind_param("sss", $name, $description, $company_login_id);

            if ($stmt2->execute()) {
                echo "<script>alert('Registered successfully! You can now login.'); window.location.href='login.php';</script>";
            } else {
                echo "Error inserting into companies: " . $stmt2->error;
            }

        } else {
            echo "Error inserting into companies_login: " . $stmt1->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg p-4">
                <h3 class="text-center mb-4">Register Your Company</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
