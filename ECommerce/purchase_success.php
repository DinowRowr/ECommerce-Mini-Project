<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Successful</title>
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
            background-image: url('img/bg3.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }
        .box {
            width: 500px;
            height: 250px;
            background-color: white;
            color: black;
            text-align: center;
            padding: 20px;
            border-radius: 20px;
        }
  

        .box h1 {
            margin-top: 30px;
        }

        a {
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
        a:hover {
            color: white;
            background: black;
        }
        ul{
            list-style: none;
        }

    </style>
</head>
<body>
    <div class="box">
    <h1>Purchase Successful</h1>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="history.php">View Order History</a></li>
    </ul>
    </div>
</body>
</html>
