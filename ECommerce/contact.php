<?php
// Start the session
session_start();

// Include the configuration file
require_once 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize the input data
    $message = trim($_POST["message"]);

    // Validate input data
    if (empty($message)) {
        $error = "The message field is required.";
    } else {
        // Create a MySQLi instance
        $mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

        // Check for connection errors
        if ($mysqli->connect_errno) {
            $error = "Failed to connect to MySQL: " . $mysqli->connect_error;
        } else {
            // Get the email from the session if available
            $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

            // Fetch the user_id based on the email
            $stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];

                // Prepare and execute the SQL statement
                $stmt = $mysqli->prepare("INSERT INTO contacts (email, message, user_id) VALUES (?, ?, ?)");
                $stmt->bind_param("ssi", $email, $message, $user_id);
                $stmt->execute();

                // Redirect to a success page or display a success message
                header("Location: index.php");
                exit();
            } else {
                $error = "User not found.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 30px 50px;
            background: black;
            display: flex;
            justify-content: space-between;
        }

        h1 {
            font-size: 2em;
            color: white;
            user-select: none;
            margin: 0;
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
            bottom: -6px;
            width: 100%;
            height: 3px;
            background: red;
            border-radius: 5px;
            transform-origin: right;
            transform: scaleX(0);
            transition: transform .5s;
        }

        #navigation a:hover::after {
            transform-origin: left;
            transform: scaleX(1);
        }

        #navigation li {
            display: inline;
            padding: 25px;
            margin-right: 80px;
        }
        #contact {
          margin-left: 570px;
        }
        h2{
          margin-top: 150px;
          text-align: center;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
        }

        .form-container h1 {
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container textarea {
            width: 100%;
            height: 100px;
            resize: vertical;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
        }
    </style>
</head>

<body>
        <div id="header">
            <h1>PEP - SQUAD APPARELS</h1>
            <div id="navigation">
                <ul>
                    <li><a href="index.php" class="home-button">HOME</a></li>
                </ul>
            </div>
        </div>
        <h2>Contact Us</h2>
  <div id="contact">
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br>

        <input type="submit" value="Submit">
    </form>
  </div>
</body>

</html>
