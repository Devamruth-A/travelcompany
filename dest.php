<?php include 'load_destinations.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome To the Joy of Travel: Heavenly Trips</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            
            font-family: Arial, sans-serif;
            background-color: ;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .navbar {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: hidden;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px 20px;
            position: fixed;
            top: 0;
        }
        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 16px;
        }
        .navbar a:first-child {
            font-size: 24px;
            font-weight: bold;
        }
        .navbar a:hover:not(:first-child) {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        .container {
            background:black;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            opacity:0.9;
        }
        h1 {
            margin-bottom: 20px;
        }
        .input {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body style="background-image: url('sa.jpg'); background-size: cover; background-position: center; color: white; font-family: Arial, sans-serif;">
<div class="navbar">
        <a>TravelCompanion</a>
        <a href="ind.php">Home</a>
        <a href="admin.html">Admin Login</a>
        <a href="login.html">User Login</a>
    </div>
    <div class="container">
        <h1>Welcome To the Joy of Travel: Heavenly Trips</h1>
        <form action="book_trip.php" method="post" class="form">
            <p>Enter your booking details to confirm your participation in the trip</p>
            <select name="dest_id" id="dest_id" class="input" required>
                <option value="">Select Destination</option>
                <?php loadDestinations(); ?>
            </select>
            <input type="date" name="travel_date" id="travel_date" class="input" placeholder="Enter your Travelling Date" required>
            <input type="date" name="return_date" id="return_date" class="input" placeholder="Enter your Return Date" required>
            <button class="btn" type="submit">Submit</button>
        </form>
    </div>
</body>
</html>