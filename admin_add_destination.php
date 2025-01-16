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
    } elseif (isset($_POST['remove_cust_id'])) {
        $remove_cust_id = $_POST['remove_cust_id'];

        // Delete the customer from the database
        $stmt = $conn->prepare("DELETE FROM customers WHERE cust_id = ?");
        $stmt->bind_param("i", $remove_cust_id);

        if ($stmt->execute()) {
            echo "Customer removed successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch all customers to display in the table
$result = $conn->query("SELECT customers.cust_id, customers.f_name, customers.l_name, bookings.travel_date, (DATEDIFF(bookings.return_date, bookings.travel_date) + 1) * destinations.cost AS Total_cost FROM customers INNER JOIN bookings ON customers.cust_id = bookings.cust_id INNER JOIN destinations ON bookings.dest_id = destinations.dest_id");

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(\'tkr.jpg\');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Customer Management</h1>
        <h2>Customers</h2>
        <table>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Booking Date</th>
                <th>Total Amount</th>
                <th>Action</th>
            </tr>';

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['cust_id']}</td>
            <td>{$row['f_name']}</td>
            <td>{$row['l_name']}</td>
            <td>{$row['travel_date']}</td>
            <td>{$row['Total_cost']}</td>
            <td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='remove_cust_id' value='{$row['cust_id']}'>
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
