<?php
session_start(); // Start the session

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
    // Make sure to use 'passwd' here to match the form input name
    if (isset($_POST['email']) && isset($_POST['passwd'])) {
        $email = $_POST['email'];
        $password = $_POST['passwd'];

        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT password FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($storedPassword);
            $stmt->fetch();

            // Check if the password matches the stored password
            if ($password === $storedPassword) {
                $_SESSION['email'] = $email; // Store email in session
                header("Location: dest.php"); // Redirect to dest.php
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }

        $stmt->close();
    } else {
        echo "Please enter both email and password.";
    }
}

$conn->close();
?>
