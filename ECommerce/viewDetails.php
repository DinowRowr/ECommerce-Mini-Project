<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Check if the order ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: transactionHistory.php"); // Redirect to transactionHistory.php if order ID is not provided
    exit();
}

$orderID = $_GET['id'];

// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    // Delete the item from the database
    $deleteSql = "DELETE FROM order_items WHERE order_id = '$orderID'";
    $deleteResult = mysqli_query($mysqli, $deleteSql);

    if ($deleteResult) {
        $message = "Item has been deleted successfully.";
    } else {
        $message = "Error deleting item: " . mysqli_error($mysqli);
    }
}

// Fetch the order details from the database
$sql = "SELECT * FROM orders WHERE order_id = '$orderID'";
$result = mysqli_query($mysqli, $sql);

if (!$result) {
    echo "Error retrieving order details: " . mysqli_error($mysqli);
    exit();
}

$order = mysqli_fetch_assoc($result);

// Fetch the user details from the users table
$userID = $order['user_id'];
$userSql = "SELECT firstName, lastName FROM users WHERE user_id = '$userID'";
$userResult = mysqli_query($mysqli, $userSql);

if (!$userResult) {
    echo "Error retrieving user details: " . mysqli_error($mysqli);
    exit();
}

$user = mysqli_fetch_assoc($userResult);
$firstName = $user['firstName'];
$lastName = $user['lastName'];

// Fetch the items purchased by the user
$itemSql = "SELECT oi.item_id, oi.quantity, i.description, i.price, i.name
            FROM order_items oi
            JOIN item i ON oi.item_id = i.item_id
            WHERE oi.order_id = '$orderID'";
$itemResult = mysqli_query($mysqli, $itemSql);

if (!$itemResult) {
    echo "Error retrieving item details: " . mysqli_error($mysqli);
    exit();
}

$items = [];
$total = $order['total']; // Store the initial total

while ($item = mysqli_fetch_assoc($itemResult)) {
    $items[] = $item;

    // Check if the item has been deleted and subtract its price from the total
    if (isset($item['deleted']) && $item['deleted']) {
        $itemPrice = $item['price'];
        $total -= $itemPrice;
    }
}

// Update the total in the orders table
$updateTotalSql = "UPDATE orders SET total = $total WHERE order_id = '$orderID'";
$updateTotalResult = mysqli_query($mysqli, $updateTotalSql);

if (!$updateTotalResult) {
    echo "Error updating total: " . mysqli_error($mysqli);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/viewDetails.css">
    <title>Order Details</title>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="adminIndex">HOME</a>
        </div>
    </div>

    <h2>Order Details</h2>
    <p><strong>Order ID: </strong><?php echo $order['order_id']; ?></p>
    <p><strong>User ID: </strong><?php echo $order['user_id']; ?></p>
    <p><strong>Name: </strong><?php echo $firstName . ' ' . $lastName; ?></p> 
    <p><strong>Address: </strong><?php echo $order['address']; ?></p>
    <p>
        <strong>Payment Method: </strong>
        <?php
            $paymentMethod = $order['payment_method'];
            $displayPaymentMethod = '';

            if ($paymentMethod === 'cod') {
                $displayPaymentMethod = 'Cash On Delivery';
            } elseif ($paymentMethod === 'ewallet') {
                $displayPaymentMethod = 'E-Wallet';
            } elseif ($paymentMethod === 'creditDebit') {
                $displayPaymentMethod = 'Credit/Debit Card';
            } else {
                $displayPaymentMethod = 'Unknown';
            }

            echo $displayPaymentMethod;
        ?>
    </p>

    <h2>Items Purchased:</h2>
    <?php if (isset($message)) : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <table>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Image</th>
        </tr>
        <?php foreach ($items as $item) : ?>
            <tr>
                <td><?php echo $item['item_id']; ?></td>
                <td><?php echo $item['description']; ?></td>
                <td>P<?php echo $item['price']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>
                    P<?php
                    $subtotal = $item['price'] * $item['quantity'];
                    echo $subtotal;
                    ?>
                </td>
                <td><img src="img/apparel/<?php echo $item['name']; ?>.jpg" alt="<?php echo $item['name']; ?>"></td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($items)) : ?>
            <tr>
                <td colspan="6">Item has been deleted.</td>
            </tr>
        <?php endif; ?>

        <p><strong>Total: </strong>P<?php echo $total; ?></p>

    </table>
</body>
</html>
