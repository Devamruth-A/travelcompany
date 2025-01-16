<?php
session_start(); // Start the session to use session variables

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
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $dest_id = $_POST['dest_id'];
        $travel_date = $_POST['travel_date'];
        $return_date = $_POST['return_date'];

        // Fetch the user's ID from the customers table
        $stmt = $conn->prepare("SELECT cust_id FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($cust_id);
        $stmt->fetch();
        $stmt->close();

        if ($cust_id) {
            // Insert booking data into the bookings table
            $stmt = $conn->prepare("INSERT INTO bookings (cust_id, dest_id, travel_date, return_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $cust_id, $dest_id, $travel_date, $return_date);
            if ($stmt->execute()) {
                // Calculate the total cost for the user, ensuring positive values
                $stmt = $conn->prepare("SELECT IFNULL(SUM((ABS(DATEDIFF(return_date, travel_date)) + 1) * cost), 0) AS Total_cost, COUNT(*) AS Booking_count FROM bookings INNER JOIN destinations ON bookings.dest_id = destinations.dest_id WHERE cust_id = ?");
                $stmt->bind_param("i", $cust_id);
                $stmt->execute();
                $stmt->bind_result($total_cost, $booking_count);
                $stmt->fetch();

                // Display total cost, number of bookings, customer ID, and a link to redirect to ind.php
                echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            background-image: url("login.jpg"); /* Adjust the path */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 80%;
            max-width: 500px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            color: white;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        .btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking successfully added.</h1>
        <h2>Your Total Bookings</h2>
        <table>
            <tr>
                <th>Customer ID</th>
                <th>Total Cost</th>
                <th>Number of Bookings</th>
            </tr>
            <tr>
                <td>' . $cust_id . '</td>
                <td>' . $total_cost . '</td>
                <td>' . $booking_count . '</td>
            </tr>
        </table>
        <br>
        <a href="ind.php" class="btn">Go to Home</a>
    </div>
</body>
</html>';
                $stmt->close();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "User ID not found.";
        }
    } else {
        echo "You are not logged in.";
    }
}

$conn->close();
?>
