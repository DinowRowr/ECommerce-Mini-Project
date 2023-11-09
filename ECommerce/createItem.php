<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Handle item creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create"])) {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Check if the item already exists
    $checkItemSql = "SELECT item_id FROM item WHERE name = '$name'";
    $checkItemResult = mysqli_query($mysqli, $checkItemSql);

    if ($checkItemResult && mysqli_num_rows($checkItemResult) > 0) {
        // Item already exists, show error message
        $errorMessage = "Item already exists.";
    } else {
        // Item does not exist, proceed with creation
        $createItemSql = "INSERT INTO item (name, category, description, price, quantity) VALUES ('$name', '$category', '$description', '$price', '$quantity')";
        $createItemResult = mysqli_query($mysqli, $createItemSql);

        if ($createItemResult) {
            // Item created successfully
            // Perform any additional actions or redirection if needed
            header("Location: createItem.php");
            exit();
        } else {
            // Handle the error if item creation fails
            $errorMessage = "Item creation failed.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remove"])) {
    $itemId = $_POST["item_id"];
    $orderId = $_POST["order_id"] ?? ''; // Get the order ID from the hidden field, or set it to an empty string if not present

    // Delete related records in the order_items table
    $deleteOrderItemsSql = "DELETE FROM order_items WHERE item_id = '$itemId'";
    $deleteOrderItemsResult = mysqli_query($mysqli, $deleteOrderItemsSql);

    // Perform the logic for removing the item from the database
    $sql = "DELETE FROM item WHERE item_id = '$itemId'";
    $result = mysqli_query($mysqli, $sql);

    if ($result) {
        // Redirect back to createItem.php after removing the item
        header("Location: createItem.php");
        exit();
    } else {
        // Handle the error if item removal fails
        $errorMessage = "Item removal failed.";
    }

    // Call the deleteOrderIfNoItems function to check and delete the order if no corresponding items are found
    deleteOrderIfNoItems($orderId, $itemId);
}

// Function to delete order from orders table if no corresponding items in order_items table
function deleteOrderIfNoItems($orderId, $itemId)
{
    global $mysqli;

    // Check if the order has corresponding items in order_items table
    $checkOrderItemsSql = "SELECT order_id FROM order_items WHERE order_id = '$orderId'";
    $checkOrderItemsResult = mysqli_query($mysqli, $checkOrderItemsSql);

    if ($checkOrderItemsResult && mysqli_num_rows($checkOrderItemsResult) == 0) {
        // No corresponding items in order_items table, delete the row from orders table
        $deleteOrderSql = "DELETE FROM orders WHERE order_id = '$orderId'";
        $deleteOrderResult = mysqli_query($mysqli, $deleteOrderSql);

        if ($deleteOrderResult) {
            // Row deleted successfully
            // Perform any additional actions or redirection if needed
        } else {
            // Handle the error if the row deletion fails
            $errorMessage = "Order deletion failed.";
        }
    }
}

// Retrieve and display the list of items
function displayItems()
{
    global $mysqli;

    $sql = "SELECT * FROM item";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["item_id"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["category"] . "</td>";
            echo "<td>" . $row["description"] . "</td>";
            echo "<td>P" . $row["price"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td><img src='img/apparel/" . $row["name"] . ".jpg' alt='Image'></td>";
            echo "<td><a href='editItem.php?id=" . $row["item_id"] . "'>Edit</a></td>";
            echo "<td>";
            echo "<form action='createItem.php' method='post'>";
            echo "<input type='hidden' name='item_id' value='" . $row["item_id"] . "'>";
            echo "<input type='hidden' name='order_id' value='" . ($row["order_id"] ?? '') . "'>"; // Add the order ID as a hidden field, if present in the row
            echo "<button type='submit' name='remove'>Remove</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            // Call the deleteOrderIfNoItems function for each row
            deleteOrderIfNoItems($row["order_id"] ?? '', $row["item_id"]);
        }
    } else {
        echo "<tr><td colspan='9'>No items found.</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/createItem.css">
    <title>Create Item</title>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation"> 
            <a href="adminIndex.php" class="transactionHistory">HOME</a>
        </div>
    </div>

    <h2>Item List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Edit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php displayItems(); ?>
        </tbody>
    </table>

    <div class="item-creation-form">
        <h2>Create Item</h2>
        <form action="createItem.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Accessories">Accessories</option>
                    <option value="T-Shirt">T-Shirt</option>
                    <option value="Hoodies">Hoodies</option>
                    <option value="Sweaters">Sweaters</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="0" required>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png" required>
            </div>
            <button type="submit" name="create">Create</button>
        </form>
        <?php if (isset($errorMessage)) { ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php } ?>
    </div>
</body>
</html>
