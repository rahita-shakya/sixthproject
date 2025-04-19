<?php
require_once '../core/database.php';
session_start();
checkAdminLogin();

// Delete Applicant
if (isset($_GET['delete'])) {
    $applicant_id = intval($_GET['delete']);
    $conn->query("DELETE FROM applicants WHERE id = $applicant_id");
    echo "Applicant deleted!";
}

// Show all applicants
$result = $conn->query("SELECT * FROM applicants");
while ($app = $result->fetch_assoc()) {
    echo "<p>{$app['name']} ({$app['email']}) <a href='?delete={$app['id']}'>Delete</a></p>";
}
?>
