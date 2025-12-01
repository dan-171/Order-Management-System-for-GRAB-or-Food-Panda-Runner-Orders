<?php
  session_start();
  $_SESSION["userType"] = "staff";
  include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/sralogin.css">
</head>
<body>
  <div id="banner-top">
      <img src="../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Staff Login</h2>
    <form method="post">
        <input id="id-input" type="text" name="id" placeholder="Staff ID" required>
        <input id="pw-input" type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a id="reset-credentials" href="../resetCredentials.php?role=staff">Forgot ID or Password</a>
  </div>
</body>
</html>