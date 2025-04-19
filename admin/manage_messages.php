<?php
require_once '../core/database.php';
session_start();
checkAdminLogin();

$result = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");
while ($msg = $result->fetch_assoc()) {
    echo "<p>{$msg['message']} - Sent At: {$msg['sent_at']}</p><hr>";
}
?>
