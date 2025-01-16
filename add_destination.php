<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Use your MySQL password if set
$dbname = "trave";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If the request is to add a destination
    if (isset($_POST['dest_name'])) {
        $dest_name = $_POST['dest_name'];
        $description = $_POST['description'];
        $loc = $_POST['loc'];
        $cost = $_POST['cost'];

        // Insert the new destination into the database
        $stmt = $conn->prepare("INSERT INTO destinations (dest_name, description, loc, cost) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $dest_name, $description, $loc, $cost);

        if ($stmt->execute()) {
            echo "Destination added successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } 
    // If the request is to remove a customer
    elseif (isset($_POST['remove_cust_id'])) {
        $remove_cust_id = $_POST['remove_cust_id'];
        $stmt = $conn->prepare("DELETE FROM customers WHERE cust_id = ?");
        $stmt->bind_param("i", $remove_cust_id);
        if ($stmt->execute()) {
            echo "Customer removed successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } 
    // If the request is to remove a destination
    elseif (isset($_POST['remove_dest_id'])) {
        $remove_dest_id = $_POST['remove_dest_id'];

        // First, delete related bookings from the bookings table
        $stmt = $conn->prepare("DELETE FROM bookings WHERE dest_id = ?");
        $stmt->bind_param("i", $remove_dest_id);
        $stmt->execute();

        // Then, delete the destination from the destinations table
        $stmt = $conn->prepare("DELETE FROM destinations WHERE dest_id = ?");
        $stmt->bind_param("i", $remove_dest_id);
        if ($stmt->execute()) {
            echo "Destination removed successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all destinations to display in the table
$result = $conn->query("SELECT dest_id, dest_name FROM destinations");

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destination Management</title>
    <link rel="stylesheet" href="css/add.css">
</head>
<body>
    <div class="container">
        <h1>Destination Management</h1>
        <h2>Destinations</h2>
        <table>
            <tr>
                <th>Destination ID</th>
                <th>Destination Name</th>
                <th>Action</th>
            </tr>';

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['dest_id']}</td>
            <td>{$row['dest_name']}</td>
            <td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='remove_dest_id' value='{$row['dest_id']}'>
                    <button type='submit' class='btn'>Remove</button>
                </form>
            </td>
          </tr>";
}

echo '</table>
    </div>
</body>
</html>';

$conn->close();
?>
