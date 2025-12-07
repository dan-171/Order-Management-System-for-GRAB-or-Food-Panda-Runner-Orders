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
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/resetCredentials.css">
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
    </div>
  </nav>
</div>
</div>
<div id="user-details-box">
  <p>Please enter your following details</p>
  <form  id = "user-details-form" method="post">
    <input type="email" id="email" name="email" placeholder="Email"/>
    <input id="pw-input" name="pw" type="password" placeholder="Password"/>
    <input id="id-input" name="id" type="id" placeholder="<?= ucfirst($role) ?> ID"/>
    <input id="submit-btn" type="submit">
  </form>
</div>
<script>
  const fOption = document.querySelectorAll(".forgot-option");
  const userDetailsBox = document.getElementById("user-details-box");
  const email = document.getElementById("email");
  const password = document.getElementById("pw-input");
  const id = document.getElementById("id-input");
  const submitBtn = document.getElementById("submit-btn");
  let pwOrIdInput = null;
  let forgotValue = null;

  //highlight selected option (forgot id, forgot pw) & show input box accordingly
  for (let i = 0; i < fOption.length; i++){
    let option = fOption[i];
    option.addEventListener("click", function(){
      for(let j = 0; j < fOption.length; j++){
        fOption[j].classList.remove("active");
      }
      option.classList.add("active");
      email.value="";

      if (option.id === "forgot-id") {
        password.classList.add("active");
        id.value="";
        id.classList.remove("active");
        submitBtn.value="Send ID";
      }
      else if (option.id === "forgot-pw"){
        id.classList.add("active");
        password.value="";
        password.classList.remove("active");
        submitBtn.value="Send Temporary Password";
      } 
      userDetailsBox.classList.add("active");
    })
    pwOrIdInput = document.getElementById("pw-input") ?? document.getElementById("id-input");
    forgotValue = (option.id === "forgot-id");
  }
  
  //check if input exists
  const userDetailsForm = document.getElementById("user-details-form");
  function rejectEmptyInput(pwOrIdInput, forgotValue){
    if (pwOrIdInput.value.trim() == ""){
      if (forgotValue) alert("⚠️ Please enter your password");
      else alert("⚠️ Please enter your ID"); 
      pwOrIdInput.focus();
      return;
    }
  }
  userDetailsForm.addEventListener("submit", function(e){
    e.preventDefault();
    if (userDetailsForm.email.value.trim() == ""){
      alert("⚠️ Email must not be left blank");
      document.getElementById("email").focus();
      return;
    }
    else {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(userDetailsForm.email.value.trim())) {
        alert("⚠️ Invalid email address!");
        document.getElementById("email").focus();
        return;
      } 
    }
    rejectEmptyInput(pwOrIdInput, forgotValue);
  })
</script>
</body>
</html>