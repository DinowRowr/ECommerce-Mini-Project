<?php
session_start();
require_once 'config.php';

// Check if the item ID is provided in the URL
if (!isset($_GET['id'])) {
    // Redirect the user back to the index.php page if no item ID is specified
    header('Location: index.php');
    exit();
}

// Get the item ID from the URL
$itemID = $_GET['id'];

// Construct the SQL query to fetch the item details
$sql = "SELECT * FROM item WHERE item_id = '$itemID'";

// Execute the SQL query
$result = $mysqli->query($sql);

// Check if the item exists
if ($result->num_rows === 0) {
    // Redirect the user back to the index.php page if the item does not exist
    header('Location: index.php');
    exit();
}

// Fetch the item details
$row = $result->fetch_assoc();

// Get the item name and image
$itemTitle = $row['description'];
$itemName = $row['name'];
$itemImage = "img/apparel/{$itemName}.jpg";
$itemPrice = $row['price'];
$itemQuantity = $row['quantity'];

// Handle Add to Cart functionality
if (isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $cartItem = [
        'item_id' => $itemID,
        'name' => $itemName,
        'price' => $itemPrice,
        'quantity' => $quantity,
        'image' => $itemImage
    ];

    // Check if the cart is already set in the session
    if (isset($_SESSION['cart'])) {
        // Add the item to the existing cart
        $_SESSION['cart'][$itemID] = $cartItem;
    } else {
        // Create a new cart and add the item to it
        $_SESSION['cart'] = [$itemID => $cartItem];
    }

    // Redirect the user to the cart.php page
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/itemDetails.css">
    <title>Item Details</title>
    <style>
        .quantity-wrapper {
            margin-bottom: 10px;
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
                    <li><a href="cart.php" class="cart-button"><ion-icon name="cart-outline"></ion-icon></a></li>
                </ul>
            </div>
        </div>
        <div id="main">
            <div id="item-details">
                <div id="item-image">
                    <img src="<?php echo $itemImage; ?>" alt="<?php echo $itemName; ?>">
                </div>
                <div id="item-info">
                    <h2><?php echo $itemTitle; ?></h2>
                    <p>Price: P<?php echo $itemPrice; ?></p>
                    <p>Quantity: <?php echo $itemQuantity; ?></p>
                    <?php if ($itemQuantity > 0) : ?>
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?php echo $itemID; ?>">
                            <div class="quantity-wrapper">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" min="1" max="<?php echo $itemQuantity; ?>" value="1">
                            </div>
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    <?php else : ?>
                        <p>Sold out</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
