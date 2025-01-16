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
    $username = $_POST['username'];
    $passwd = $_POST['passwd']; // Changed to match HTML

    // Fetch the user from the database
    $stmt = $conn->prepare("SELECT passwd FROM administrator WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();
        
        // Verify the password
        if ($passwd === $storedPassword) { // Changed to plain comparison
            echo "Login successful! Redirecting...";
            header("Location: admin_add_destination.html"); // Redirect to your desired page
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>
