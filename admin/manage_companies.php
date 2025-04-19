<?php
// Start session and database connection
session_start();
$host = "localhost"; // Change if needed
$user = "root";
$password = "";
$database = "jobselect"; // Your DB name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch companies
$sql = "SELECT name, email, contact_number FROM companies_login";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Companies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .top-bar button {
            background-color: #007BFF;
            color: white;
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .top-bar button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 15px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .heading {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <form method="post" action="dashboard.php">
        <button type="submit">Go Back</button>
    </form>
</div>

<h2 class="heading">Registered Companies</h2>

<table>
    <tr>
        <th>#</th>
        <th>Company Name</th>
        <th>Email</th>
        <th>Contact Number</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        $count = 1;
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $count++ . "</td>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["email"]) . "</td>
                    <td>" . htmlspecialchars($row["contact_number"]) . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No companies found.</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>
</html>
