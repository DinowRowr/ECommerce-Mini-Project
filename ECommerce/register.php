<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/register.css">
    <title>Register</title>
    <style>
        body {
            background-image: url('img/bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
           
        }
        .error {
          text-align: center;
          margin-top: 20px;
          color: red;
        }
    </style>
</head>
<body>

    <section class="container">
      <header><h2>Register</h2></header>

      <?php
      require_once 'config.php';

      function isEmailRegistered($email)
      {
          global $mysqli;

          $sql = "SELECT * FROM users WHERE email = '$email'";
          $result = $mysqli->query($sql);

          return ($result->num_rows > 0);
      }

      // Check if the form is submitted
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Retrieve form data
          $firstName = $_POST['firstName'];
          $lastName = $_POST['lastName'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $confirmPassword = $_POST['confirm_password'];
          $phoneNum = $_POST['phoneNum'];
          $address = $_POST['address'];
          $bday = $_POST['bday'];
          $gender = $_POST['gender'];

          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

          // Check if email is already registered
          if (isEmailRegistered($email)) {
              $error = "Email already registered. Please choose a different email.";
          } else {
              // Insert data into the database
              $sql = "INSERT INTO users (firstName, lastName, email, password, phoneNum, address, bday, gender)
                      VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$phoneNum', '$address', '$bday', '$gender')";

              if (mysqli_query($mysqli, $sql)) {
                  header("Location: successReg.php");
                  exit();
              } else {
                  echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
              }
          }
      }
      ?>
      <?php if (isset($error)) : ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
      <?php endif; ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form">

          <div class="column">
            <div class="input-box">
              <label>First Name</label>
              <input type="text" placeholder="Enter First name" name="firstName" required />
            </div>
            <div class="input-box">
              <label>Last Name</label>
              <input type="text" placeholder="Enter Last name" name="lastName" required />
            </div>
          </div>
            
          <div class="input-box">
            <label>Email Address</label>
            <input type="email" placeholder="Enter email address" name="email" required />
          </div>

          <div class="column">
            <div class="input-box">
              <label>Password</label>
              <input type="password" placeholder="Enter password" name="password" id="password" required />
            </div>
            <div class="input-box input-error">
              <label>Confirm Password</label>
              <input type="password" placeholder="Confirm password" name="confirm_password" id="confirm_password" required />
            </div>
          </div>
  
          <div class="column">
            <div class="input-box">
              <label>Phone Number</label>
              <input type="text" placeholder="Enter phone number" name="phoneNum" required />
            </div>
            <div class="input-box">
              <label>Birth Date</label>
              <input type="date" placeholder="Enter birth date" name="bday" required />
            </div>
          </div>
          <div class="input-box">
              <label>Address</label>
              <input type="text" placeholder="Enter full address" name="address" required />
            </div>

          <div class="gender-box">
            <h3>Gender</h3>
            <div class="gender-option">
              <select name="gender" required>
                <option value="male" >Male</option>
                <option value="female" >Female</option>
                <option value="other" >Others</option>
              </select>
            </div>
          </div>

          <button>Submit</button>

          <div class="login">
            <p>Already Have an account? <a href="login.php">Login</a></p>
          </div>
        </form>
    </section>

    <script>
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirm_password');
    
      function validatePassword() {
        if (passwordInput.value !== confirmPasswordInput.value) {
          confirmPasswordInput.setCustomValidity("Passwords do not match");
        } else {
          confirmPasswordInput.setCustomValidity('');
        }
      }
    
      passwordInput.addEventListener('input', validatePassword);
      confirmPasswordInput.addEventListener('input', validatePassword);
    </script>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>