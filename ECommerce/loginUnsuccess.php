<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Unsuccessful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            background-color: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('img/bg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }
        .box {
            width: 500px;
            height: 200px;
            background-color: white;
            color: black;
            text-align: center;
            padding: 20px;
            border-radius: 20px;
        }

        .box h1 {
            margin-top: 40px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;

            width: 50%;
            color: black;
            font-size: 1em;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            border: none;
            outline: auto;
            border-radius: 40px;
            text-decoration: none;
            margin-top: 20px;
        }
        .button:hover {
            color: white;
            background: black;
        }
    </style>

</head>
<body>
    <div class="box">
        <h1 style="color: black;">Invalid Email or Password!</h1>
        <a class="button" href="login.php">Try Again</a>
    </div>
</body>
</html>
