<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('img/bg2.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: black;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
        }

        h1 {
            font-size: 1.5em;
            margin: 0;
        }

        #navigation {
            margin-top: 10px;
            margin-right: 30px;
            padding: 10px;
        }

        #navigation a {
            position: relative;
            text-decoration: none;
            color: white;
            font-weight: 500;
            margin-right: 20px;
        }

        #navigation a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 2px;
            background: red;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform .3s;
        }

        #navigation a:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }

        .dashboard {
            margin-top: 300px; 
            text-align: center;
            background-color: rgba(192, 192, 192, 0.7); 
            padding: 20px;
            border-radius: 10px;
            max-width: 700px; 
            margin-left: auto;
            margin-right: auto;
        }

        .dashboard h2 {
            margin-bottom: 20px;
            color: black;
        }

        .dashboard a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .dashboard a:hover {
            background-color: #45a049;
        }

        .logout {
            margin-right: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="logout.php" class="logout">LOGOUT</a>
        </div>
    </div>

    <div class="dashboard">
        <h2>Welcome to the Admin Dashboard</h2>
        <a href="createItem.php">Create Item</a>
        <a href="viewUsers.php">View Users</a>
        <a href="transactionHistory.php">Transaction History</a>
        <a href="analytics.php">Analytics</a>
    </div>
</body>
</html>
