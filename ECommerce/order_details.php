<?php
session_start();
require_once 'config.php';

// Check if the user is logged in
if (!isset($_SESSION["userID"])) {
    // User is not logged in, redirect to the login page or display an error message
    header("Location: login.php");
    exit();
}

// Check if the order ID is provided
if (!isset($_GET['order_id'])) {
    // Order ID is not provided, redirect to the transaction history page or display an error message
    header("Location: history.php");
    exit();
}

// Get the order ID from the query parameter
$orderID = $_GET['order_id'];

// Fetch the order details based on the order ID and user ID
$sql = "SELECT * FROM orders WHERE order_id = $orderID AND user_id = {$_SESSION['userID']}";
$result = mysqli_query($mysqli, $sql);

if ($result && mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $orderTotal = $row['total'];
    $orderAddress = $row['address'];
    $orderPaymentMethod = $row['payment_method'];
    $orderPurchasedOn = $row['purchased_on'];

    // Fetch the item details from the order_items table
    $itemQuery = "SELECT item.item_id, item.name, item.description, item.price, order_items.quantity FROM item
                  INNER JOIN order_items ON item.item_id = order_items.item_id
                  WHERE order_items.order_id = $orderID";
    $itemResult = mysqli_query($mysqli, $itemQuery);

    $items = array();
    while ($itemRow = mysqli_fetch_assoc($itemResult)) {
        $item = array(
            'item_id' => $itemRow['item_id'],
            'name' => $itemRow['name'],
            'description' => $itemRow['description'],
            'price' => $itemRow['price'],
            'quantity' => $itemRow['quantity']
        );

        $items[] = $item;
    }
} else {
    // Invalid order ID or unauthorized access, redirect to the transaction history page or display an error message
    header("Location: history.php");
    exit();
}

// Function to rename payment method
function renamePaymentMethod($paymentMethod) {
    if ($paymentMethod === 'cod') {
        return 'Cash On Delivery';
    } elseif ($paymentMethod === 'ewallet') {
        return 'E-Wallet';
    } elseif ($paymentMethod === 'creditDebit') {
        return 'Credit/Debit Card';
    } else {
        return 'Unknown';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/order_details.css">
    <title>Order Details</title>
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
            <h2>Order Details - Order ID: <?php echo $orderID; ?></h2>
            <div class="order-info">
                <h3>Order Summary</h3>
                <p><strong>Total:</strong> P <?php echo $orderTotal; ?></p>
                <p><strong>Address:</strong> <?php echo $orderAddress; ?></p>
                <p><strong>Payment Method:</strong> <?php echo renamePaymentMethod($orderPaymentMethod); ?></p> 
                <p><strong>Purchased On:</strong> <?php echo $orderPurchasedOn; ?></p>
            </div>
            <div class="item-list">
                <h3>Items Purchased</h3>
                <?php if (count($items) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <?php
                                    $itemName = strtolower(str_replace(' ', '_', $item['name']));
                                    $image = 'img/apparel/' . $itemName . '.jpg';
                                ?>
                                <tr>
                                    <td><img src="<?php echo $image; ?>" alt="<?php echo $item['name']; ?>"></td>
                                    <td><?php echo $item['description']; ?></td>
                                    <td>P <?php echo $item['price']; ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No items purchased.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
