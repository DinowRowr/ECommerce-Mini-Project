<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page
    header("Location: login.php");
    exit();
} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/index.css">
    <script src="script/index.js" defer></script>
    <title>Dashboard</title>
</head>
<body>
    <div id="container">
        <div id="header">
            <div id="logo">
                <img src="img/logo.png" alt="logo">
            </div>     
            <div id="navigation">
                <ul>
                    <li><a href="user_profiles.php">PROFILE</a></li>
                    <li><a href="history.php">HISTORY</a></li>
                    <li><a href="logout.php">LOGOUT</a></li>
                    <li><a href="cart.php"><ion-icon name="cart-outline"></ion-icon></a></li>
                </ul>
            </div>
        </div>
        <div id="main">
            <div class="carousel" data-carousel>
                <button class="carousel-button prev" data-carousel-button="prev">&#8656;</button>
                <button class="carousel-button next" data-carousel-button="next">&#8658;</button>
                <ul data-slides>
                    <li class="slide" data-active>
                        <img src="img/ads/ads1.jpg" alt="Ads 1">
                    </li>
                    <li class="slide">
                        <img src="img/ads/ads2.jpg" alt="Ads 2">
                    </li>
                    <li class="slide">
                        <img src="img/ads/ads3.jpg" alt="Ads 3">
                    </li>
                    <li class="slide">
                        <img src="img/ads/ads4.jpg" alt="Ads 4">
                    </li>
                </ul>
            </div>
            <?php
            // Include the database connection code (config.php)
            require_once 'config.php';

            // Retrieve the selected category from the URL parameter
            $category = $_GET['category'] ?? 'all';

            // Construct the SQL query to fetch items based on the category
            $sql = "SELECT * FROM item";
            if ($category !== 'all') {
                $sql .= " WHERE category = '$category'";
            }

            // Execute the SQL query
            $result = $mysqli->query($sql);

            // Create a div element for each item
            $itemsHtml = '<div id="items">'; // Open the #items div

            while ($row = $result->fetch_assoc()) {
                $itemName = $row['name'];
                $itemImage = "img/apparel/{$itemName}.jpg";
                $itemTitle = $row['description'];

                $itemsHtml .= <<<HTML
                    <div>
                        <a href="itemDetails.php?id={$row['item_id']}">
                            <img src="$itemImage" alt="$itemName">
                            <div class="item-title">$itemTitle</div>
                        </a>
                    </div>
            HTML;
            }
            $itemsHtml .= '</div>'; // Close the #items div
            ?>
            <!-- HTML content -->
            <div id="secHeader">
                <div id="categories">
                    <ul>
                        <li><a href="?category=all">ALL</a></li>
                        <li><a href="?category=t-shirt">T-SHIRT</a></li>
                        <li><a href="?category=hoodies">HOODIES</a></li>
                        <li><a href="?category=sweaters">SWEATERS</a></li>
                        <li><a href="?category=accessories">ACCESSORIES</a></li>
                    </ul>
                </div>
            </div>
            <div id="section">
                <?php echo $itemsHtml; ?>
            </div>
        </div>
        <div id="footer">
            <p id="contact">
                <a href="contact.php">Contact Us</a> <br><br>
                <ion-icon name="call-outline"></ion-icon> 0912-345-6789 <br>
                <ion-icon name="mail-outline"></ion-icon> pepSquadApparel@gmail.com
            </p>
            <ul id="about">
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="faq.php">F.A.Q.</a></li>
            </ul>
            <p id="seeUs">
                <ul>
                See us at: <br><br>
                <li><a href="#"><ion-icon name="logo-facebook"></ion-icon></a></li>
                <li><a href="#"><ion-icon name="logo-instagram"></ion-icon></a></li>
                <li><a href="#"><ion-icon name="logo-twitter"></ion-icon></a></li>
                <li><a href="#"><ion-icon name="logo-tiktok"></ion-icon></a></li>
                </ul>
            </p>
        </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
