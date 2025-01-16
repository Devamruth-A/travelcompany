<?php
function loadDestinations() {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trave";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch destinations from the database
    $sql = "SELECT dest_id, dest_name FROM destinations";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["dest_id"] . "'>" . $row["dest_name"] . "</option>";
        }
    } else {
        echo "<option value=''>No destinations available</option>";
    }

    // Close the connection
    $conn->close();
}
?>
