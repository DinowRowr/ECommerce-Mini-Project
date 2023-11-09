<?php
require_once 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION["userID"])) {
    // User is not logged in, redirect to the login page or display an error message
    header("Location: login.php");;
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
        $password = $_POST["password"];
        $phoneNum = $_POST["phoneNum"];
        $address = $_POST['address'];
        $bday = $_POST["bday"];

        // Update the user's information in the database
        $updateSql = "UPDATE users SET
            firstName = '$firstName',
            lastName = '$lastName',
            email = '$email',
            password = '$password',
            phoneNum = '$phoneNum',
            address = '$address',
            bday = '$bday'
            WHERE user_id = $loggedInUserID";

        if ($mysqli->query($updateSql) === TRUE) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating user information: " . $mysqli->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/user_profiles.css">
    <title>User Profile</title>
    <style>
        body {
            background-image: url('img/bg3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
           
        }
    </style>
</head>
<body>
    
    <form class="box" method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <h1>User Profile</h1>

        <div class="column">
            <label for="firstName">First Name:</label>
            <span><?php echo $row["firstName"]; ?></span><br>
            <div class="gap"></div>

            <label for="lastName">Last Name:</label>
            <span><?php echo $row["lastName"]; ?></span><br>
            <div class="gap"></div>
        </div>
        <label for="email">Email Address:</label>
        <span><?php echo $row["email"]; ?></span><br>
        <div class="gap"></div>

        <label for="phoneNum">Contact Number:</label>
        <span><?php echo $row["phoneNum"]; ?></span><br>
        <div class="gap"></div>

        <label for="bday">Address:</label>
        <span><?php echo $row["address"]; ?></span><br>
        <div class="gap"></div>

        <label for="bday">Birthday:</label>
        <span><?php echo $row["bday"]; ?></span><br>
        <div class="gap"></div>

        <div class="button-container">
            <a href="editProfile.php" class="edit">Edit Profile</a>
        </div>

        <div class="main">
            <p>Back to <a href="index.php">Main Page</a></p>
        </div>
        

    </form>
</body>
</html>

<?php
} else {
    echo "User not found.";
}

$mysqli->close();
?>
