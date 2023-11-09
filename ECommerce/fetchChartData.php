<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registered";

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch item_ids and quantities from order_items table
    $query = "SELECT oi.item_id, oi.quantity, o.purchased_on FROM order_items oi INNER JOIN orders o ON oi.order_id = o.order_id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize arrays for item data, sales data, and total sales data
    $itemData = [];
    $dailySales = [];
    $weeklySales = [];
    $monthlySales = [];
    $yearlySales = [];
    $totalSales = [];

    // Iterate over the fetched results
    foreach ($result as $row) {
        $itemId = $row['item_id'];
        $quantity = $row['quantity'];
        $purchasedOn = $row['purchased_on'];

        // Update item data array with quantity sold per item
        if (isset($itemData[$itemId])) {
            $itemData[$itemId] += $quantity;
        } else {
            $itemData[$itemId] = $quantity;
        }

        // Update sales data arrays with sales on a daily, weekly, monthly, and yearly basis
        $date = date("Y-m-d", strtotime($purchasedOn));

        if (isset($dailySales[$date])) {
            $dailySales[$date] += 1;
        } else {
            $dailySales[$date] = 1;
        }

        $week = date("Y-W", strtotime($purchasedOn));
        if (isset($weeklySales[$week])) {
            $weeklySales[$week] += 1;
        } else {
            $weeklySales[$week] = 1;
        }

        $month = date("Y-m", strtotime($purchasedOn));
        if (isset($monthlySales[$month])) {
            $monthlySales[$month] += 1;
        } else {
            $monthlySales[$month] = 1;
        }

        $year = date("Y", strtotime($purchasedOn));
        if (isset($yearlySales[$year])) {
            $yearlySales[$year] += 1;
        } else {
            $yearlySales[$year] = 1;
        }
    }

    // Fetch total sales data from orders table
    $totalSalesQuery = "SELECT DATE_FORMAT(purchased_on, '%Y-%m') AS month, SUM(total) AS revenue FROM orders GROUP BY month";
    $totalSalesStmt = $conn->prepare($totalSalesQuery);
    $totalSalesStmt->execute();
    $totalSalesResult = $totalSalesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Update total sales array with the fetched data
    foreach ($totalSalesResult as $totalRow) {
        $month = $totalRow['month'];
        $revenue = $totalRow['revenue'];

        $totalSales[$month] = $revenue;
    }

    // Fetch item data from the item table
    $itemQuery = "SELECT item_id, name FROM item";
    $itemStmt = $conn->prepare($itemQuery);
    $itemStmt->execute();
    $itemResult = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

    // Update item data array with new items or quantities if they exist in the item table
    foreach ($itemResult as $itemRow) {
        $itemId = $itemRow['item_id'];

        if (!isset($itemData[$itemId])) {
            $itemData[$itemId] = 0;
        }
    }

    // Sort the item data array by item ID
    ksort($itemData);

    // Prepare the item data in the required format for the front-end
    $itemDataFormatted = [];
    foreach ($itemData as $itemId => $quantitySold) {
        $itemDataFormatted[] = [
            'item_id' => $itemId,
            'quantity_sold' => $quantitySold
        ];
    }

    // Prepare the sales data in the required format for the front-end
    $salesData = [
        'daily' => $dailySales,
        'weekly' => $weeklySales,
        'monthly' => $monthlySales,
        'yearly' => $yearlySales
    ];

    // Initialize the total sales data array
    $totalSalesData = [];

    // Iterate over the total sales result
    foreach ($totalSalesResult as $totalRow) {
        $month = $totalRow['month'];
        $revenue = $totalRow['revenue'];

        // Add the month and revenue to the total sales data array
        $totalSalesData[] = [
            'month' => $month,
            'revenue' => $revenue
        ];
    }


    // Prepare the response object
    $response = [
        'item_data' => $itemDataFormatted,
        'sales_data' => $salesData,
        'total_sales' => $totalSalesData
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Error: " . $e->getMessage();
}
