<?php
  session_start();
  include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/sralogin.css">
<body>
  <div id="banner-top">
      <img src="../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Admin Login</h2>
    <form method="post">
        <input id="id-input" type="text" name="id" placeholder="Admin ID" required>
        <input id="pw-input" type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Forgot ID or Password</p>
  </div>
</body>
</html>