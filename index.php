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

// Check if this is a destination update (from pass2.html)
if (isset($_POST['dest_id']) && isset($_POST['dest_name']) && isset($_POST['description']) && isset($_POST['loc'])) {
    // Destination update process
    $dest_id = $_POST['dest_id'];
    $dest_name = $_POST['dest_name'];
    $description = $_POST['description'];
    $loc = $_POST['loc'];

    // Update the destination in the database
    $stmt = $conn->prepare("UPDATE destinations SET dest_name = ?, description = ?, loc = ? WHERE dest_id = ?");
    $stmt->bind_param("sssi", $dest_name, $description, $loc, $dest_id);

    if ($stmt->execute()) {
        echo "<p>Destination updated successfully.</p>";
    } else {
        echo "<p>Error updating destination: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Check if this is a customer and booking submission (from index.html)
if (isset($_POST['f_name']) && isset($_POST['l_name']) && isset($_POST['email']) && isset($_POST['phone']) &&
    isset($_POST['dest_id']) && isset($_POST['travel_date']) && isset($_POST['return_date'])) {

    // Get form data
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dest_id = $_POST['dest_id'];
    $travel_date = $_POST['travel_date'];
    $return_date = $_POST['return_date'];

    // Insert data into the customer table
    $stmt = $conn->prepare("INSERT INTO customers (f_name, l_name, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $f_name, $l_name, $email, $phone);
    if ($stmt->execute()) {
        // Get the last inserted customer ID
        $cust_id = $stmt->insert_id;
        echo "<p>New customer record created successfully.</p>";
        echo "<p>Last inserted Customer ID is: " . $cust_id . "</p>";
    } else {
        echo "<p>Error creating customer: " . $conn->error . "</p>";
    }
    $stmt->close();

    // Insert data into the booking table
    $stmt = $conn->prepare("INSERT INTO bookings (cust_id, dest_id, travel_date, return_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $cust_id, $dest_id, $travel_date, $return_date);
    if ($stmt->execute()) {
        // Get the last inserted booking ID
        $booking_id = $stmt->insert_id;
        echo "<p>New booking record created successfully.</p>";
        echo "<p>Last inserted Booking ID is: " . $booking_id . "</p>";

        // Fetch and display total cost based on the destination and travel duration
        $stmt = $conn->prepare("SELECT (DATEDIFF(return_date, travel_date) + 1) * cost AS Total_cost
                                 FROM bookings
                                 INNER JOIN destinations ON bookings.dest_id = destinations.dest_id
                                 WHERE bookings.booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_cost = $row['Total_cost'];

        // Display total cost in a centered, enlarged HTML table
        echo "        <div style='text-align:center; margin-top:20px;'>
            <table border='1' style='width: 50%; margin: 0 auto; font-size: 20px;'>
                <tr>
                    <th>Booking ID</th>
                    <th>Total Cost</th>
                </tr>
                <tr>
                    <td>$booking_id</td>
                    <td>$total_cost</td>
                </tr>
            </table>
        </div>";
    } else {
        echo "<p>Error creating booking: " . $conn->error . "</p>";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            background-image: url('tkr.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            width: 60%;
            margin: auto;
            margin-top: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h4, p {
            text-align: center;
            color:white;
        }
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
            font-size: 18px;
            color:white;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        .cont{
            text-align:center;
            color:white;
        }
    </style>
</head>
<body>
    <div class="cont">
        <h4>Your Booking Confirmed</h4>
        <!-- Success messages and table will be injected here by PHP -->
    </div>
    <p style="text-align: center; margin-top: 20px;">
    <a href="view_bookings.php" style="color: white; text-decoration: underline; font-size: 18px;">View All Bookings</a></p>
</body>
</html>
