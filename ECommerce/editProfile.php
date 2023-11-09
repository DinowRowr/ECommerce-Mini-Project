<?php
require_once 'config.php';
session_start();

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

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Get updated user information from the form
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $phoneNum = $_POST["phoneNum"];
        $address = $_POST['address'];
        $bday = $_POST["bday"];

        // Update the user's information in the database
        $updateSql = "UPDATE users SET
            firstName = '$firstName',
            lastName = '$lastName',
            email = '$email',
            phoneNum = '$phoneNum',
            address = '$address',
            bday = '$bday'
            WHERE user_id = $loggedInUserID";

        if ($mysqli->query($updateSql) === TRUE) {
            header("Location: user_profile.php");
            exit();
        } else {
            echo "Error updating user information: " . $mysqli->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/editProfile.css">
    <style>
        body {
            background-image: url('img/bg3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
           
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Edit Profile</h1>
        <form method="POST" action="updateProfile.php">
            <div>
                <label for="firstName">First Name:</label>
                <br>
                <input type="text" name="firstName" value="<?php echo $row["firstName"]; ?>">
            </div>
            <div class="gap"></div>
            <div>
                <label for="lastName">Last Name:</label>
                <br>
                <input type="text" name="lastName" value="<?php echo $row["lastName"]; ?>">
            </div>
            <div class="gap"></div>
            <div>
                <label for="email">Email Address:</label>
                <br>
                <input type="email" name="email" value="<?php echo $row["email"]; ?>">
            </div>
            <div class="gap"></div>
            <div>
                <label for="phoneNum">Contact Number:</label>
                <br>
                <input type="tel" name="phoneNum" value="<?php echo $row["phoneNum"]; ?>">
            </div>
            <div class="gap"></div>
            <div>
                <label for="address">Address:</label>
                <br>
                <input type="text" name="address" value="<?php echo $row["address"]; ?>">
            </div>
            <div class="gap"></div>
            <div>
                <label for="bday">Birthday:</label>
                <br>
                <input type="date" name="bday" value="<?php echo $row["bday"]; ?>">
            </div>
            <div class="gap"></div>
            <div class="button-container">
                <button type="submit" class="edit">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>


<?php
} else {
    echo "User not found.";
}

mysqli_close($mysqli);
?>

