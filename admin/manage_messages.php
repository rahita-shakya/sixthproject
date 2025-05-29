<?php
require_once '../core/database.php';
session_start();

// ✅ Define checkAdminLogin here
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php"); // Make sure login.php exists in /admin
        exit();
    }
}

checkAdminLogin(); // ✅ Now the function is available

// ✅ Fetch and display messages
$result = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");
while ($msg = $result->fetch_assoc()) {
    echo "<p>{$msg['message']} - Sent At: {$msg['sent_at']}</p><hr>";
}
?>
