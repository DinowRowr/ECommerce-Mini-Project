<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Retrieve and display the transaction history for a specific user
function displayTransactions($userId)
{
    global $mysqli;

    $sql = "SELECT * FROM orders WHERE user_id = '$userId'";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["order_id"] . "</td>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>P" . $row["total"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . getPaymentMethodLabel($row["payment_method"]) . "</td>";
            echo "<td>" . $row["purchased_on"] . "</td>";
            echo "<td><a href=\"viewDetails.php?id=" . $row["order_id"] . "\">View Details</a></td>"; // Added View Details button
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'><center>No transactions found for this user.</center></td></tr>";
    }
}
// Function to get the payment method label
function getPaymentMethodLabel($paymentMethod)
{
    switch ($paymentMethod) {
        case 'cod':
            return 'Cash On Delivery';
        case 'ewallet':
            return 'E-Wallet';
        case 'creditDebit':
            return 'Credit/Debit Card';
        default:
            return 'Unknown';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Transactions</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
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

        h2 {
            margin-top: 150px;
            text-align: center;
        }
        h3 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .details-link {
            display: inline-block;
            padding: 6px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .details-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="adminIndex">HOME</a>
            <a href="transactionHistory.php" class="transactionHistory">HISTORY</a>
        </div>
    </div>

    <h2>Transaction History</h2>
    <?php
    if (isset($_GET["user_id"])) {
        $userId = $_GET["user_id"];
        echo "<h3>Transactions for User ID: $userId</h3>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>User ID</th>";
        echo "<th>Total Amount</th>";
        echo "<th>Address</th>";
        echo "<th>Payment Method</th>";
        echo "<th>Transaction Date</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        displayTransactions($userId);
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No user ID specified.</p>";
    }
    ?>
</body>
</html>
