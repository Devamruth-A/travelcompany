<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('tkr.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: auto;
            text-align: center;
        }
        .navbar {
            overflow: hidden;
            background-color: #333;
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        #bookings {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 80%;
            margin: auto;
            margin-top: 50px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="ind.php">Home</a>
        <a href=""></a>
        <a href="login.html">Login</a>
    </div>
    <div id="bookings">
        <h1>View Bookings</h1>
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Name</th>
                    <th>Destination</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php
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

                // Fetch bookings data
                $sql = "SELECT customers.cust_id, CONCAT(customers.f_name, ' ', customers.l_name) AS Name, destinations.dest_name, (DATEDIFF(bookings.return_date, bookings.travel_date) + 1) * destinations.cost AS Total_cost
                        FROM bookings
                        JOIN customers ON bookings.cust_id = customers.cust_id
                        JOIN destinations ON bookings.dest_id = destinations.dest_id";

                $result = $conn->query($sql);

                // Check if there are results
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["cust_id"] . "</td>";
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td>" . $row["dest_name"] . "</td>";
                        echo "<td>" . $row["Total_cost"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No bookings found.</td></tr>";
                }

                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
