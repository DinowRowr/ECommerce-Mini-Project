<?php
session_start();
require_once 'config.php';

// Check if the item ID is provided
if (!isset($_POST['item_id'])) {
    header('Location: cart.php');
    exit();
}

$itemID = $_POST['item_id'];

// Check if the item exists in the cart
if (isset($_SESSION['cart'][$itemID])) {
    // Remove the item from the cart
    unset($_SESSION['cart'][$itemID]);
}

header('Location: cart.php');
exit();
?>
