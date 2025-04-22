<?php
session_start();
require_once '../core/database.php';

if (!isset($_SESSION['company_id'])) {
    $_SESSION['error'] = "You must be logged in to post a job.";
    header("Location: login.php");
    exit();
}

$company_login_id = $_SESSION['company_id'];

// Step 1: Ensure a row exists in `companies` table
$check_company_sql = "SELECT id FROM companies WHERE company_login_id = ?";
$check_company_stmt = $conn->prepare($check_company_sql);
$check_company_stmt->bind_param("i", $company_login_id);
$check_company_stmt->execute();
$company_result = $check_company_stmt->get_result();

if ($company_result->num_rows === 0) {
    $get_company_login_sql = "SELECT name, email, address FROM companies_login WHERE id = ?";
    $get_company_login_stmt = $conn->prepare($get_company_login_sql);
    $get_company_login_stmt->bind_param("i", $company_login_id);
    $get_company_login_stmt->execute();
    $company_login_result = $get_company_login_stmt->get_result();

    if ($company_login_result->num_rows > 0) {
        $company_login_data = $company_login_result->fetch_assoc();

        $description = "Company profile";
        $address = $company_login_data['address'] ?? '';
        $category = $_POST['category'] ?? '';

        $insert_company_sql = "INSERT INTO companies (name, description, company_login_id, location, category) VALUES (?, ?, ?, ?, ?)";
        $insert_company_stmt = $conn->prepare($insert_company_sql);
        $insert_company_stmt->bind_param("ssiss", $company_login_data['name'], $description, $company_login_id, $address, $category);

        if ($insert_company_stmt->execute()) {
            $company_id = $conn->insert_id;

            $update_company_login_sql = "UPDATE companies_login SET company_id = ? WHERE id = ?";
            $update_company_login_stmt = $conn->prepare($update_company_login_sql);
            $update_company_login_stmt->bind_param("ii", $company_id, $company_login_id);
            $update_company_login_stmt->execute();
        } else {
            $_SESSION['error'] = "Failed to create company profile: " . $conn->error;
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Company login details not found.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    $company_data = $company_result->fetch_assoc();
    $company_id = $company_data['id'];
}

// Step 2: Insert job post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $category = trim($_POST['category'] ?? '');

    if (empty($title) || empty($description) || empty($location) || empty($category)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: post_job.php");
        exit();
    }

    $insert_job_sql = "INSERT INTO jobs (title, description, company_id, location, status, category) VALUES (?, ?, ?, ?, 'pending', ?)";
    $insert_job_stmt = $conn->prepare($insert_job_sql);
    $insert_job_stmt->bind_param("ssiss", $title, $description, $company_id, $location, $category);

    if ($insert_job_stmt->execute()) {
        $_SESSION['success'] = "Job posted successfully! It will be reviewed by an admin.";
        header("Location: post_job.php");
        exit();
    } else {
        $_SESSION['error'] = "Error posting job: " . $conn->error;
        header("Location: post_job.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: dashboard.php");
    exit();
}
