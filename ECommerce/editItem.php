<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Retrieve the item details for editing
function getItemDetails($itemId)
{
    global $mysqli;

    $sql = "SELECT * FROM item WHERE item_id = '$itemId'";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

// Handle item update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $itemId = $_GET["id"]; // Retrieve the item ID from the query string
    $itemName = $_POST["item_name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Perform the logic for updating the item details
    $sql = "UPDATE item SET name = '$itemName', category = '$category', description = '$description', price = '$price', quantity = '$quantity' WHERE item_id = '$itemId'";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        // Redirect back to createItem.php after updating the item
        header("Location: createItem.php");
        exit();
    } else {
        // Handle the error if item update fails
        $errorMessage = "Item update failed.";
    }
}

// Check if the item ID is provided in the query string
if (isset($_GET["id"])) {
    $itemId = $_GET["id"];
    $itemDetails = getItemDetails($itemId);

    if (!$itemDetails) {
        echo "<h2>Item not found.</h2>";
        exit();
    }
} else {
    echo "<h2>Invalid request.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/editItem.css">
    <title>Edit Item</title>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="adminIndex">HOME</a>
        </div>
    </div>

    <h2>Edit Item:</h2>
    <form action="editItem.php?id=<?php echo $itemId; ?>" method="post">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" value="<?php echo $itemDetails["name"]; ?>" required>
        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Accessories" <?php if ($itemDetails["category"] === "Accessories") echo "selected"; ?>>Accessories</option>
            <option value="T-Shirt" <?php if ($itemDetails["category"] === "T-Shirt") echo "selected"; ?>>T-Shirt</option>
            <option value="Hoodies" <?php if ($itemDetails["category"] === "Hoodies") echo "selected"; ?>>Hoodies</option>
            <option value="Sweaters" <?php if ($itemDetails["category"] === "Sweaters") echo "selected"; ?>>Sweaters</option>
        </select>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $itemDetails["description"]; ?></textarea>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo $itemDetails["price"]; ?>" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $itemDetails["quantity"]; ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
