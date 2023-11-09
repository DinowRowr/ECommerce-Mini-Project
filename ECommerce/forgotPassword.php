<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wag mo kasi kakalimutan</title>
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    video {
      max-width: 100%;
      max-height: 100%;
    }

    .login-button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <video autoplay controls>
    <source src="img/forgotPassword.mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <button class="login-button" onclick="location.href='login.php'">Login Again</button>
</body>
</html>
