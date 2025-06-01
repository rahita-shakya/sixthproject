<?php
session_start();
require_once '../core/database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);
    $skills = $_POST['skills'];
    $company_login_id = $_SESSION['company_id']; // This is company_login_id

    if (empty($title) || empty($description) || empty($location) || empty($category) || empty($skills)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: post_job.php");
        exit();
    }

    // ✅ Fetch actual company_id from companies table
    $stmtCompany = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
    $stmtCompany->bind_param("i", $company_login_id);
    $stmtCompany->execute();
    $resultCompany = $stmtCompany->get_result();

    if ($row = $resultCompany->fetch_assoc()) {
        $company_id = $row['id'];

        // ✅ Now insert into jobs
        $stmt = $conn->prepare("INSERT INTO jobs (title, description, location, category, company_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $title, $description, $location, $category, $company_id);

        if ($stmt->execute()) {
            $job_id = $stmt->insert_id;

            // ✅ Insert skills
            $skill_list = explode(',', $skills);
            foreach ($skill_list as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $skill_stmt = $conn->prepare("INSERT INTO skills (job_id, skill_name) VALUES (?, ?)");
                    $skill_stmt->bind_param("is", $job_id, $skill);
                    $skill_stmt->execute();
                }
            }

            $_SESSION['success'] = "Job posted successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Error while posting job.";
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Company not found.";
        header("Location: post_job.php");
        exit();
    }
}
?>