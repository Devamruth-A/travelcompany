<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Bookings</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('login.jpg');
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
        <h1>Customer Bookings</h1>

        <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "trave";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle customer deletion
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cust_id'])) {
            $cust_id = $conn->real_escape_string($_POST['cust_id']);
            $deleteBookingsQuery = "DELETE FROM bookings WHERE cust_id='$cust_id'";
            $deleteCustomerQuery = "DELETE FROM customers WHERE cust_id='$cust_id'";

            // Delete bookings and then customer to maintain foreign key constraints
            if ($conn->query($deleteBookingsQuery) === TRUE && $conn->query($deleteCustomerQuery) === TRUE) {
                echo "<p>Customer and related bookings removed successfully.</p>";
            } else {
                echo "<p>Error removing customer: " . $conn->error . "</p>";
            }
        }

        // Fetch customer bookings and display in the table
        $result = $conn->query("SELECT customers.cust_id, customers.f_name, customers.l_name, SUM((DATEDIFF(bookings.return_date, bookings.travel_date) + 1) * destinations.cost) AS Total_cost FROM customers INNER JOIN bookings ON customers.cust_id = bookings.cust_id INNER JOIN destinations ON bookings.dest_id = destinations.dest_id GROUP BY customers.cust_id");

        echo "<table>
                <tr>
                    <th>Customer ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['cust_id']}</td>
                    <td>{$row['f_name']}</td>
                    <td>{$row['l_name']}</td>
                    <td>{$row['Total_cost']}</td>
                    <td>
                        <form method='POST' action='' style='display:inline;'>
                            <input type='hidden' name='cust_id' value='{$row['cust_id']}'>
                            <button type='submit' class='btn' onclick=\"return confirm('Are you sure you want to remove this customer?')\">Remove</button>
                        </form>
                    </td>
                  </tr>";
        }
        
        echo "</table>";
        $conn->close();
        ?>
    </div>
</body>
</html>
