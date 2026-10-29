<?php
session_start();
require "dbconnection.php";


if (isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}
$error_message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user){

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();

        }else{
            $error_message = "Invalid username or password.";
        }

    }else{
        $error_message = "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: helvetica, sans-serif;
            background-color: #c9c9c9;

           
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
        }

        .login-container {
           
            padding: 100px;
            border: 1px solid #ddd;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(60,60,60);
            max-width: 500px;
            width: 100%; 
            box-sizing: border-box; 
            text-align: center; 
        }

        h1 {
            text-align: center;
            color: #666;
            margin-top: 0;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%; 
            padding: 8px 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; 
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545; /* Bootstrap red for errors */
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>User Login</h1>
        <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password">


            <input type="submit" value="Login">

        </form>
        
        <div class="login-link">
            Don't have an account? <a href="Register.php">Register here</a>            
            <?php
            echo nl2br(" "); 
            echo nl2br(" ");
?>
            <div class="forgot"><a href="#">Forgot password?</a></div>
        </div>
    </div>

</body>
</html>
