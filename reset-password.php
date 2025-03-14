<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Import Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        /* Centered Container */
        .container {
            background-color: #ffdde1; /* Light pink background */
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 50px auto; /* Center the container */
        }

        h2 {
            color: #d63384; /* Dark pink text */
            font-weight: 600;
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #d63384;
            box-shadow: 0 0 5px rgba(214, 51, 132, 0.5);
        }

        button {
            background-color: #d63384; /* Pink button */
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #b2226e;
        }
    </style>
</head>
<body>

    <form class="container" method="POST" action="update-password.php">
        <h2>Set New Password</h2>
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <input type="password" name="new_password" required placeholder="New Password">
        <button type="submit">Reset Password</button>
    </form>

</body>
</html>