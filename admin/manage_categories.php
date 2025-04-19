<?php
require_once '../core/database.php';
session_start();
checkAdminLogin();

// Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = sanitize($_POST['category_name']);
    $conn->query("INSERT INTO categories (category_name) VALUES ('$category_name')");
    echo "Category added!";
}

// Delete Category
if (isset($_GET['delete'])) {
    $category_id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id = $category_id");
    echo "Category deleted!";
}

// Show all categories
$result = $conn->query("SELECT * FROM categories");
while ($cat = $result->fetch_assoc()) {
    echo "<p>{$cat['category_name']} <a href='?delete={$cat['id']}'>Delete</a></p>";
}
?>

<form method="POST">
    Category Name: <input type="text" name="category_name" required>
    <button type="submit">Add Category</button>
</form>
