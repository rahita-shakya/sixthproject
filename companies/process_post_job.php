<?php
session_start();
require_once '../core/database.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);
    $skills = $_POST['skills'];
    $applicants_required = intval($_POST['applicants_required']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $company_login_id = $_SESSION['company_id']; // This is company_login_id

    if (empty($title) || empty($description) || empty($location) || empty($category) || empty($skills) || 
        $applicants_required < 1 || empty($start_date) || empty($end_date)) {
        $_SESSION['error'] = "All fields are required, including number of applicants and dates.";
        header("Location: post_job.php");
        exit();
    }

    // Fetch actual company_id from companies table
    $stmtCompany = $conn->prepare("SELECT id FROM companies WHERE company_login_id = ?");
    $stmtCompany->bind_param("i", $company_login_id);
    $stmtCompany->execute();
    $resultCompany = $stmtCompany->get_result();

    if ($row = $resultCompany->fetch_assoc()) {
        $company_id = $row['id'];

        // Insert into jobs - fixed typo and added missing params to bind_param
        $stmt = $conn->prepare("INSERT INTO jobs (title, description, location, category, company_id, applicants_required, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssiiss", 
            $title, 
            $description, 
            $location, 
            $category, 
            $company_id, 
            $applicants_required, 
            $start_date, 
            $end_date
        );

        if ($stmt->execute()) {
            $job_id = $stmt->insert_id;

            // Insert skills
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
            $_SESSION['error'] = "Error while posting job: " . $stmt->error;
            header("Location: post_job.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Company not found.";
        header("Location: post_job.php");
        exit();
    }
}

?>