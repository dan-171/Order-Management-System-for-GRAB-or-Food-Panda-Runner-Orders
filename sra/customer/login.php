<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT full_name, password FROM customer WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($full_name, $stored_password);
        $stmt->fetch();

        if ($password === $stored_password) {
            // SAVE BOTH NAME AND UNIQUE USERNAME
            $_SESSION['user_name'] = $full_name;
            $_SESSION['username_unique'] = $username; // <--- ADD THIS LINE

            echo "<script>
                    sessionStorage.setItem('isLoggedIn', 'true');
                    alert('Login Successful!');
                    window.location.href = 'customerpage.php'; 
                  </script>";
            exit();
        } else {
            echo "<script>alert('Username or Password incorrect');</script>";
        }
    } else {
        echo "<script>alert('Username or Password incorrect');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .loginContainer {
            width: 350px; margin: 80px auto; padding: 30px;
            background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            border-radius: 8px; text-align: center;
        }
        label { display: block; text-align: left; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box;
        }
        .loginButtons { display: flex; gap: 10px; }
        .loginButtons input, .loginButtons a { flex: 1; padding: 10px; text-align: center; }
        .forgot { margin-top: 10px; display: block; font-size: 14px; }
    </style>
</head>
<body>

<div class="loginContainer">
    <h2>Customer Login</h2>

    <form action="" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <div class="loginButtons">
            <input type="submit" value="Login">
            <a href="register.php" class="navButton">Register</a>
        </div>

        <a href="forgotPassword.php" class="forgot">Forgot Password?</a>
    </form>
</div>

</body>
</html>