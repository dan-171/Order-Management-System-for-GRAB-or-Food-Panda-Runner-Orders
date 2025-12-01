<?php
session_start();
$role = $_GET['role'] ?? 'admin';
$returnToLoginLink = "{$role}/login.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Credentials</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/resetCredentials.css">
</head>
<body>
<h1>Reset Credentials</h1>
<a id="return" href="<?= $returnToLoginLink ?>">Return to Login</a>
<div id="forgot-box">
  <p>Choose one</p>
  <nav id="forgot-option-nav">
    <div class="forgot-option" id="forgot-id">
      <p>Forgot ID</p>
    </div>
    <div class="forgot-option" id="forgot-pw">
      <p>Forgot Password</p>
    </nav>
</div>
</div>
<div id="user-details-box">
  <p>Please enter your following details</p>
  <form method="post">
    <input type="email" placeholder="Email"/>
    <input  id="pw-input" type="password" placeholder="Password"/>
    <input  id="id-input" type="id" placeholder="<?= ucfirst($role) ?> ID"/>
  </form>
</div>
<script>
  const fOption = document.querySelectorAll(".forgot-option");
  const userDetailsBox = document.getElementById("user-details-box");
  const fillPW = document.getElementById("pw-input");
  const fillID = document.getElementById("id-input");

  //highlight selected option (forgot id, forgot pw) & show input box accordingly
  for (let i = 0; i < fOption.length; i++){
    let option = fOption[i];
    option.addEventListener("click", function(){
      for(let j = 0; j < fOption.length; j++){
        fOption[j].classList.remove("active");
      }
      option.classList.add("active");
      if (option.id === "forgot-id") {
        fillPW.classList.add("active");
        fillID.classList.remove("active");
      }
      else if (option.id === "forgot-pw"){
        fillID.classList.add("active");
        fillPW.classList.remove("active");
      } 
      userDetailsBox.classList.add("active");
    })
  }
</script>
</body>
</html>