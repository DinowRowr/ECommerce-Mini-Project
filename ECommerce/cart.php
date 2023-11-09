<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the cart session is not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // Initialize an empty cart array
}

// Include the database connection code (config.php)
require_once 'config.php';

// Function to calculate the total amount in the cart
function calculateTotal($cartItems) {
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Process the quantity updates if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $itemId => $quantity) {
        // Update the quantity in the cart session and the database
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]['quantity'] = $quantity;

            // Update the quantity in the database (order_items table)
            $orderId = $_SESSION['order_id'];
            $updateSql = "UPDATE order_items SET quantity = $quantity WHERE order_id = $orderId AND item_id = $itemId";
            $mysqli->query($updateSql);
        }
    }
}




// Remove item from the cart
if (isset($_POST['remove_item'])) {
    $itemId = $_POST['id'];
    unset($_SESSION['cart'][$itemId]);
}

// Retrieve the items in the cart from the database
$cartItems = array();
if (!empty($_SESSION['cart'])) {
    // Get the item IDs from the cart session
    $itemIds = array_keys($_SESSION['cart']);

    // Construct the SQL query to fetch items from the database
    $itemIdsString = implode(',', $itemIds);
    $sql = "SELECT * FROM item WHERE item_id IN ($itemIdsString)";

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Create an array of items with quantity from the cart session
    while ($row = $result->fetch_assoc()) {
        $itemId = $row['item_id'];
        $itemName = $row['name'];
        $itemImage = "img/apparel/{$itemName}.jpg";
        $itemTitle = $row['description'];
        $itemPrice = $row['price'];
        $itemQuantity = $_SESSION['cart'][$itemId]['quantity'];

        $cartItems[] = array(
            'id' => $itemId,
            'name' => $itemName,
            'image' => $itemImage,
            'title' => $itemTitle,
            'price' => $itemPrice,
            'quantity' => $itemQuantity
        );
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/cart.css">
    <title>Cart</title>
</head>
<body>
    <div id="container">
        <div id="header">
            <h1>PEP - SQUAD APPARELS</h1>
            <div id="navigation">
                <ul>
                    <li><a href="index.php">HOME</a></li>
                </ul>
            </div>
        </div>
        <div id="main">
            <?php if (!empty($cartItems)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                    <div class="item-title"><?php echo $item['title']; ?></div>
                                </td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo $item['price']; ?></td>
                                <td><?php echo $item['price'] * $item['quantity']; ?></td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="remove_item">Remove</button>
                                    </form> <br>
                                    <form action="itemDetails.php" method="GET">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="edit_quantity">Edit Quantity</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div id="checkout">
                    <p>Total: <?php echo calculateTotal($cartItems); ?></p> <br>
                    <button onclick="redirectToCheckout()">Proceed to Checkout</button>
                </div>
            <?php else: ?>
                <p>Your Cart is Empty.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
    function redirectToCheckout() {
        window.location.href = "checkout.php";
    }
</script>
</body>
</html>
