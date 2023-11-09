<?php
session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION["userType"]) || $_SESSION["userType"] !== "admin") {
    header("Location: index.php"); // Redirect to index.php if not logged in as admin
    exit();
}

require_once 'config.php';

// Retrieve and display the list of users
function displayUsers()
{
    global $mysqli;

    
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $searchName = isset($_GET['searchName']) ? $_GET['searchName'] : '';

        $whereClause = "";

        if (!empty($searchName)) {
            $searchName = mysqli_real_escape_string($mysqli, $searchName);
            $whereClause .= "(firstName LIKE '%$searchName%' OR lastName LIKE '%$searchName%')";
        }

    $sql = "SELECT * FROM users WHERE user_type = 'user'";

    if (!empty($whereClause)) {
        $sql .= " AND $whereClause";
    }

    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["user_id"] . "</td>";
            echo "<td>" . $row["firstName"] . "</td>";
            echo "<td>" . $row["lastName"] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td>" . $row["phoneNum"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["bday"] . "</td>";
            echo "<td>" . $row["gender"] . "</td>";
            echo "<td><a href='viewTransactions.php?user_id=" . $row["user_id"] . "'>View Transactions</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9'>No users found.</td></tr>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Users</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background: black;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1;
        }

        h1 {
            font-size: 1.5em;
            margin: 0;
        }

        #navigation {
            margin-top: 10px;
            margin-right: 30px;
            padding: 10px;
        }

        #navigation a {
            position: relative;
            text-decoration: none;
            color: white;
            font-weight: 500;
            margin-right: 20px;
        }

        #navigation a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 100%;
            height: 2px;
            background: red;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform .3s;
        }

        #navigation a:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }

        h2 {
            margin-top: 150px; /* Account for the fixed header */
            text-align: center;
        }
        h3 {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .details-link {
            display: inline-block;
            padding: 6px 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .details-link:hover {
            background-color: #45a049;
        }
        #search {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEP - SQUAD APPARELS (ADMIN)</h1>
        <div id="navigation">
            <a href="adminIndex.php" class="adminIndex">HOME</a>
        </div>
    </div>

    <h2>User List</h2>
    <form id="search" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="searchName">Search by Name:</label>
        <input type="text" name="searchName" value="<?php echo isset($_GET['searchName']) ? $_GET['searchName'] : ''; ?>">
        <input type="submit" value="Search">
        <button type="reset" onclick="window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>'">Clear</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Transactions</th>
            </tr>
        </thead>
        <tbody>
            <?php displayUsers(); ?>
        </tbody>
    </table>
</body>
</html>
