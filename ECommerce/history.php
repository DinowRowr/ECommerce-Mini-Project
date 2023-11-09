<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["userID"])) {
    // User is not logged in, redirect to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID from the session
$loggedInUserID = $_SESSION["userID"];

// Fetch the user information based on the user ID
$sql = "SELECT * FROM users WHERE user_id = $loggedInUserID";
$result = mysqli_query($mysqli, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $username = $row['firstName'] . ' ' . $row['lastName'];
}

// Fetch the user's transaction history
$historyQuery = "SELECT * FROM orders WHERE user_id = $loggedInUserID ORDER BY purchased_on DESC";
$historyResult = mysqli_query($mysqli, $historyQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/history.css">
    <title>Transaction History</title>
    <style>
        #noTrans{
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="container">
        <div id="header">
            <h1>PEP - SQUAD APPARELS</h1>
            <!-- Header navigation links -->
            <div id="navigation">
                <ul>
                    <li><a href="index.php" class="home-button">HOME</a></li>
                </ul>
            </div>
        </div>
        <div id="main">
            <h2>Transaction History</h2>
            <?php if (mysqli_num_rows($historyResult) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Total</th>
                            <th>Address</th>
                            <th>Payment Method</th>
                            <th>Purchased On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($historyResult)): ?>
                            <tr>
                                <td><?php echo $row['order_id']; ?></td>
                                <td>P <?php echo $row['total']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td>
                                    <?php
                                        $paymentMethod = '';
                                        switch ($row['payment_method']) {
                                            case 'cod':
                                                $paymentMethod = 'Cash On Delivery';
                                                break;
                                            case 'ewallet':
                                                $paymentMethod = 'E-Wallet';
                                                break;
                                            case 'creditDebit':
                                                $paymentMethod = 'Credit/Debit Card';
                                                break;
                                            default:
                                                $paymentMethod = 'Unknown Payment Method';
                                                break;
                                        }
                                        echo $paymentMethod;
                                    ?>
                                </td>
                                <td><?php echo $row['purchased_on']; ?></td>
                                <td><a href="order_details.php?order_id=<?php echo $row['order_id']; ?>" class="view-details">View Details</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p id="noTrans">No transaction history found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>

