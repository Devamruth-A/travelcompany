<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trave";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all bookings from the database, sorted by booking_id in ascending order
$sql = "SELECT b.booking_id, c.f_name, c.l_name, c.email, c.phone, 
               d.dest_name, b.travel_date, b.return_date
        FROM bookings b
        INNER JOIN customers c ON b.cust_id = c.cust_id
        INNER JOIN destinations d ON b.dest_id = d.dest_id
        ORDER BY b.booking_id ASC";  // Sorting by booking_id in ascending order

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <style>
        body {
            background-image: url('tkr.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: white;
        }
        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: black;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            font-size: 18px;
        }
        th {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            color: black; /* Set the text color for the header */
        }
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
            color: black; /* Set the text color for the data cells */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Bookings</h1>
        <table>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Destination</th>
                <th>Travel Date</th>
                <th>Return Date</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['booking_id']}</td>
                            <td>{$row['f_name']} {$row['l_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['dest_name']}</td>
                            <td>{$row['travel_date']}</td>
                            <td>{$row['return_date']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No bookings found.</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
