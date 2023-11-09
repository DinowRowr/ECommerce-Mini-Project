<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Fetch all orders from the database
$sql = "SELECT orders.*, users.firstName, users.lastName 
        FROM orders
        INNER JOIN users ON orders.user_id = users.user_id";
$name = isset($_GET['name']) ? $_GET['name'] : '';
if (!empty($name)) {
    $sql .= " WHERE CONCAT(users.firstName, ' ', users.lastName) LIKE '%$name%'";
}
$result = mysqli_query($mysqli, $sql);

if (!$result) {
    echo "Error retrieving orders: " . mysqli_error($mysqli);
    exit();
}

// Handle row deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    $orderId = $_POST["order_id"];

    // Delete the row from the orders table
    $deleteOrderSql = "DELETE FROM orders WHERE order_id = '$orderId'";
    $deleteOrderResult = mysqli_query($mysqli, $deleteOrderSql);

    if ($deleteOrderResult) {
        // Redirect back to the transaction history page after deleting the row
        header("Location: transactionHistory.php");
        exit();
    } else {
        // Handle the error if row deletion fails
        $errorMessage = "Failed to delete the order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/transactionHistory.css">
    <title>Transaction History</title>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="adminIndex">HOME</a>
        </div>
    </div>
    <div id="main">
    <h2>Transaction History</h2>

    <form id="search" action="" method="GET">
        <label for="name">Search by Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>">
        <button type="submit">Search</button>
        <button type="button" onclick="clearSearch()">Clear</button>
    </form>

    <script>
        function clearSearch() {
            document.getElementById("name").value = "";
            document.querySelector("form").submit();
        }
    </script>

    <table>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>User Name</th>
            <th>Address</th>
            <th>Payment Method</th>
            <th>Purchased On</th>
            <th></th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo $row["order_id"]; ?></td>
                <td><?php echo $row["user_id"]; ?></td>
                <td><?php echo $row["firstName"] . ' ' . $row["lastName"]; ?></td>
                <td><?php echo $row["address"]; ?></td>
                <td>
                    <?php
                        $paymentMethod = $row["payment_method"];
                        if ($paymentMethod === "cod") {
                            echo "Cash On Delivery";
                        } elseif ($paymentMethod === "ewallet") {
                            echo "E-Wallet";
                        } elseif ($paymentMethod === "creditDebit") {
                            echo "Credit/Debit Card";
                        } else {
                            echo $paymentMethod;
                        }
                    ?>
                </td>
                <td><?php echo $row["purchased_on"]; ?></td>
                <td>
                    <div class="button-group">
                        <a class="details-button" href="viewDetails.php?id=<?php echo $row["order_id"]; ?>">View Details</a>
                        <form class="delete-form" action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            <input type="hidden" name="order_id" value="<?php echo $row["order_id"]; ?>">
                            <button class="delete-button" type="submit" name="delete">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    </div>
</body>
</html>
