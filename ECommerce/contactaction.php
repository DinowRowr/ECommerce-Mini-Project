<?php
require_once 'config.php'; // Include the configuration file

$username = $_POST['username'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$message = $_POST['message'];

$sql = "INSERT INTO contact11 (username, email, contact, address, message) VALUES ('$username', '$email', '$contact', '$address', '$message')";
$result = mysqli_query($mysqli, $sql);

if ($result) {
    header('Location: ./index.php');
}
?>