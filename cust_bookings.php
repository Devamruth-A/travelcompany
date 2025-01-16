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

// Get the logged-in user's email from the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch bookings for the logged-in user
    $stmt = $conn->prepare("SELECT bookings.booking_id, destinations.dest_name, bookings.travel_date, bookings.return_date 
    FROM bookings 
    JOIN destinations ON bookings.dest_id = destinations.dest_id 
    JOIN customers ON bookings.cust_id = customers.cust_id 
    WHERE customers.email = ?");
$stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are bookings
    if ($result->num_rows > 0) {
        echo "<h1>Your Bookings</h1>";
        echo "<table border='1'>
                <tr>
                    <th>Booking ID</th>
                    <th>Destination</th>
                    <th>Travel Date</th>
                    <th>Return Date</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["booking_id"] . "</td>
                    <td>" . $row["dest_name"] . "</td>
                    <td>" . $row["travel_date"] . "</td>
                    <td>" . $row["return_date"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No bookings found.";
    }

    $stmt->close();
} else {
    echo "You are not logged in. Please log in to view your bookings.";
}

$conn->close();
?>
