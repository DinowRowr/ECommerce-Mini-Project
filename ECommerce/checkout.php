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
    $userAddress = $row['address'];
}

// Retrieve the user ID from the session
$userID = $_SESSION["userID"];

// Function to calculate the total price of items in the cart
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $price = $item['price'];
        $quantity = $item['quantity'];
        $total += $price * $quantity;
    }
    return $total;
}

// Process the checkout
if (isset($_POST['checkout'])) {
    // Get the cart items from the session
    $cartItems = $_SESSION['cart'];

    // Check if the cart is not empty
    if (!empty($cartItems)) {
        // Calculate the total price
        $totalPrice = calculateTotal($cartItems);

        // Prepare the insert query
        $insertQuery = "INSERT INTO orders (user_id, total, address, payment_method) VALUES ";

        // Get the selected payment option
        $paymentOption = $_POST['payment_option'];

        // Validate and set the payment option value
        if ($paymentOption === 'cash_on_delivery') {
            $paymentMethod = 'cod';
        } elseif ($paymentOption === 'e_wallet') {
            $paymentMethod = 'ewallet';
        } elseif ($paymentOption === 'credit_debit_card') {
            $paymentMethod = 'creditDebit';
        }

        // Escape the address and payment method values
        $escapedAddress = mysqli_real_escape_string($mysqli, $userAddress);
        $escapedPaymentMethod = mysqli_real_escape_string($mysqli, $paymentMethod);

        // Execute the insert query
        if (mysqli_query($mysqli, $insertQuery . "($userID, $totalPrice, '$escapedAddress', '$escapedPaymentMethod')")) {
            // Get the order ID
            $orderID = mysqli_insert_id($mysqli);

            // Insert item details into order_items table
            foreach ($cartItems as $itemID => $item) {
                $itemQuantity = $item['quantity'];
                $itemTotal = $item['price'] * $itemQuantity;

                // Escape the item ID and quantity values
                $escapedItemID = mysqli_real_escape_string($mysqli, $itemID);
                $escapedQuantity = mysqli_real_escape_string($mysqli, $itemQuantity);

                // Insert item details into order_items table
                mysqli_query($mysqli, "INSERT INTO order_items (order_id, item_id, quantity, total) VALUES ($orderID, $escapedItemID, $escapedQuantity, $itemTotal)");
            }

            // Subtract the purchased quantities from the available quantity of each item
            foreach ($cartItems as $itemID => $item) {
                $itemQuantity = $item['quantity'];

                // Retrieve the current quantity of the item from the database
                $fetchQuery = "SELECT quantity FROM item WHERE item_id = $itemID";
                $result = mysqli_query($mysqli, $fetchQuery);
                $row = mysqli_fetch_assoc($result);
                $currentQuantity = $row['quantity'];

                // Calculate the new quantity after subtracting the purchased quantity
                $newQuantity = $currentQuantity - $itemQuantity;

                // Update the item quantity in the items table
                $updateQuery = "UPDATE item SET quantity = $newQuantity WHERE item_id = $itemID";
                mysqli_query($mysqli, $updateQuery);
            }

            // Clear the cart after successful checkout
            $_SESSION['cart'] = array();

            // Redirect to a thank you page or order summary page
            header('Location: purchase_success.php');
            exit();
        } else {
            // Handle the error case
            echo "Error: " . mysqli_error($mysqli);
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/checkout.css">
    <title>Checkout</title>
</head>
<body>
    <div id="container">
        <div id="header">
            <h1>PEP - SQUAD APPARELS</h1>
            <!-- Header navigation links -->
            <div id="navigation">
                <ul>
                    <li><a href="index.php" class="home-button">HOME</a></li>
                    <li><a href="cart.php"><ion-icon name="cart-outline"></ion-icon></a></li>
                </ul>
            </div>
        </div>
        <div id="main">
            <h2>Checkout</h2>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $itemID => $item): ?>
                            <?php
                            $itemImage = $item['image'];
                            // Fetch the item description from the database
                            $sql = "SELECT description FROM item WHERE item_id = '$itemID'";
                            $result = $mysqli->query($sql);
                            $row = $result->fetch_assoc();
                            $itemDescription = $row['description'];
                            $itemPrice = $item['price'];
                            $itemQuantity = $item['quantity'];
                            $itemTotal = $itemPrice * $itemQuantity;
                            ?>
                            <tr>
                                <td><img src="<?php echo $itemImage; ?>" alt="<?php echo $itemDescription; ?>"></td>
                                <td><?php echo $itemDescription; ?></td>
                                <td><p>P <?php echo $itemPrice; ?></p></td>
                                <td><?php echo $itemQuantity; ?></td>
                                <td><p>P <?php echo $itemTotal; ?></p></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <h3>Total</h3>
                <br>
                <p class="total">P <?php echo calculateTotal($_SESSION['cart']); ?></p>
                
                <div class="shipping-address">
                    <h3>Shipping Address</h3>
                    <!-- Display the user's name and address -->
                    <p><strong>Name: </strong><?php echo $username; ?></p>
                    <p><strong>Address: </strong><?php echo $userAddress; ?></p>
                </div>
                
                <div class="payment-method">
                    <h3>Payment Method</h3>
                    <form action="" method="post">
                        <input type="radio" id="cash_on_delivery" name="payment_option" value="cash_on_delivery" checked>
                        <label for="cash_on_delivery">Cash on Delivery</label><br>
                        <input type="radio" id="e_wallet" name="payment_option" value="e_wallet">
                        <label for="e_wallet">E-Wallet</label><br>
                        <input type="radio" id="credit_debit_card" name="payment_option" value="credit_debit_card">
                        <label for="credit_debit_card">Credit/Debit Card</label><br><br>
                        
                        <button type="submit" name="checkout">Purchase Order</button>
                    </form>
                </div>
                
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
