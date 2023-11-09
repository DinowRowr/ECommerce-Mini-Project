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
            header("Location: user_profiles.php");
            exit();
        } else {
            echo "Error updating user information: " . $mysqli->error;
        }
    }
} else {
    echo "User not found.";
}

$mysqli->close();
?>
