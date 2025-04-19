<?php
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page if user is not logged in
        header('Location: login.php');
        exit();
    }
}

function checkAdminLogin() {
    // Check if the admin session exists
    if (!isset($_SESSION['admin_id'])) {
        // Redirect to the login page if the admin is not logged in
        header("Location: login.php");
        exit();
    }
}
?>
