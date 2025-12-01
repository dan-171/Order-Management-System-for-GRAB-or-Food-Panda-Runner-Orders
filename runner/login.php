<?php
  session_start();
  include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Runner Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/sralogin.css">
<body>
  <div id="banner-top">
      <img src="../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Runner Login</h2>
    <form method="post">
        <input id="id-input" type="text" name="id" placeholder="Runner ID" required>
        <input id="pw-input" type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p><a href="../forgotCredentials.php">Forgot ID or Password</a></p>
  </div>
  <div id="delivery-platforms">
    <img src="../images/grab-logo.webp" alt="grab"/>
    <img src="../images/foodpanda-logo.webp" alt="grab"/>
  </div>
</body>
</html>