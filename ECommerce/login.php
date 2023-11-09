<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
    <style>
        section {
            background-image: url('img/bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
           
        }
    </style>
</head>
<body>
    <section>
        <div class = "formBox">
            <div class = "formValue">

            <?php
            require_once 'config.php';
            session_start();

            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Retrieve the submitted email and password
                $email = $_POST["email"];
                $password = $_POST["password"];

                // Retrieve the stored hashed password, user_type, and user_id from the database
                $sql = "SELECT password, user_type, user_id FROM users WHERE BINARY email = '$email'";
                $result = mysqli_query($mysqli, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $storedHashedPassword = $row['password'];
                    $userType = $row['user_type'];
                    $userID = $row['user_id'];

                    // Verify the submitted password against the stored hashed password
                    if (password_verify($password, $storedHashedPassword)) {
                        // Password is correct, proceed with login
                        // Store the user's email, user_type, and userID in the session for later use
                        $_SESSION["email"] = $email;
                        $_SESSION["userType"] = $userType;
                        $_SESSION["userID"] = $userID;

                        // Redirect the user based on user_type
                        if ($userType === "user") {
                            header("Location: index.php");
                        } elseif ($userType === "admin") {
                            header("Location: adminIndex.php");
                        }
                        exit();
                    } else {
                        // Incorrect password
                        header("Location: loginUnsuccess.php");
                        exit();
                    }
                } else {
                    // User not found
                    header("Location: loginUnsuccess.php");
                    exit();
                }
            }

            // Display the error message if login is not successful
            if (isset($errorMessage)) {
                echo $errorMessage;
            }
            ?>

                <form action="login.php" method="post" class="form">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" name="email" required>
                        <label for="">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" required>
                        <label for="">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><a href="forgotPassword.php">Forgot Password?</a></label>                        
                    </div>
                    <button type="submit">Log in</button>
                    <div class="register">
                        <p>Don't Have an account? <a href="register.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>