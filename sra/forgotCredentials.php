<?php
session_start();
include "../config.php";
$role = $_GET['role'] ?? 'admin';
$returnToLoginLink = "{$role}/login.php";

$forgotMsg = $_SESSION["forgotMsg"] ?? "";
unset($_SESSION["forgotMsg"]);

if($_SERVER["REQUEST_METHOD"] === "POST"){
  
  $dbRole = ($role === "admin") ? $role : "runners";
  $email = isset($_POST["email"]) ? $_POST["email"] : null;
  $password = isset($_POST["pw"]) ? $_POST["pw"] : null;
  $id = isset($_POST["id"]) ? $_POST["id"] : null;
  $match = false;
  $condition = 1;

  if(!empty($email) && !empty($password)){
    $validate = $pdo->prepare("SELECT * FROM `$dbRole` WHERE Email = ?");
    $validate->execute([$email]);
    $possibleMatch = $validate->fetch(PDO::FETCH_ASSOC);
    $match = password_verify($password, $possibleMatch["Password"]);
  } else if (!empty($email) && !empty($id)){
    $condition = 2;
    $validate = $pdo->prepare("SELECT * FROM `$dbRole` WHERE ID = ?");
    $validate->execute([$id]);
    $possibleMatch = $validate->fetch(PDO::FETCH_ASSOC);
    $match = !empty($possibleMatch);
  } else{
    $_SESSION["forgotMsg"] = "Please fill in all the required details";
    header("Location: forgotCredentials.php?role={$role}");
    exit;
  }
  
  if($match){
    if($condition === 1)
      $_SESSION["forgotMsg"] = "Your ID is {$possibleMatch['ID']}\nRedirecting to login...";
    else{
      $tempPW = genTempPW();
      $hashedTempPW = password_hash($tempPW, PASSWORD_DEFAULT);
      $updatePWToTemp = $pdo->prepare("UPDATE `$dbRole` SET Password = ? WHERE Email = ? AND ID = ?");
      $updatePWToTemp->execute([$hashedTempPW, $email, $id]);
      $_SESSION["forgotMsg"] = "Your temporary password is {$tempPW}\nPlease reset your password as soon as possible\nRedirecting to login...";
      $_SESSION["tempPW"] = $tempPW;
    }
    header("Location: {$role}/login.php?role={$role}");
  } else{
    $_SESSION["forgotMsg"] = "Your credentials do not match!";
    header("Location: forgotCredentials.php?role={$role}");
  }
  exit;
}

function genTempPW($length = 12) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
  $password = '';
  $maxIndex = strlen($characters) - 1;

  for ($i = 0; $i < $length; $i++)
    $password .= $characters[random_int(0, $maxIndex)];
  return $password;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Credentials</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/forgotCredentials.css">
</head>
<body>
<h1>Forgot Credentials</h1>
<a id="return" href="<?= htmlspecialchars($returnToLoginLink) ?>">Return to Login</a>
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
  <form  id="user-details-form" method="post" novalidate>
    <input type="email" id="email" name="email" placeholder="Email"/>
    <input id="pw-input" name="pw" type="password" placeholder="Password"/>
    <input id="id-input" name="id" type="id" placeholder="<?= ucfirst(htmlspecialchars($role)) ?> ID"/>
    <input id="submit-btn" type="submit">
  </form>
</div>
<div id="forgot-msg"><?= $forgotMsg ?></div>
<script>
  const fOption = document.querySelectorAll(".forgot-option");
  const userDetailsBox = document.getElementById("user-details-box");
  const email = document.getElementById("email");
  const password = document.getElementById("pw-input");
  const id = document.getElementById("id-input");
  const submitBtn = document.getElementById("submit-btn");
  const forgotMsg = document.getElementById("forgot-msg");
  let pwOrIdInput = null;  // currently active input (password or ID)
  let forgotValue = null;   // true if forgot password, false if forgot ID

  // highlight selected option (forgot id, forgot pw) & show input box accordingly
  for (let i = 0; i < fOption.length; i++) {
    let option = fOption[i];
    option.addEventListener("click", function () {
      for (let j = 0; j < fOption.length; j++) {
        fOption[j].classList.remove("active");
      }

      option.classList.add("active");
      email.value = "";

      if (option.id === "forgot-id") {
        password.classList.add("active");
        password.value = "";
        id.classList.remove("active");
        id.value = "";

        pwOrIdInput = password;
        forgotValue = true;
        submitBtn.value = "Send ID";
      } else if (option.id === "forgot-pw") {
        id.classList.add("active");
        id.value = "";
        password.classList.remove("active");
        password.value = "";

        pwOrIdInput = id;
        forgotValue = false;
        submitBtn.value = "Send Temporary Password";
      }

      userDetailsBox.classList.add("active");
    });
  }

  function rejectEmptyInput(input, isPassword) {
    if (!input || input.value.trim() === "") {
      if (isPassword) forgotMsg.textContent = "Password cannot be blank!";
      else forgotMsg.textContent = "ID cannot be blank!";
      input?.focus();
      return false;
    }
    return true;
  }

  const userDetailsForm = document.getElementById("user-details-form");
  userDetailsForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // validate email
    const emailValue = userDetailsForm.email.value.trim();
    if (emailValue === "") {
      forgotMsg.textContent = "Email cannot be blank!";
      email.focus();
      return;
    } else {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(emailValue)) {
         forgotMsg.textContent = "Invalid email address!";
        email.focus();
        return;
      }
    }

    // validate either password or ID based on selection
    if (!rejectEmptyInput(pwOrIdInput, forgotValue)) return;

    userDetailsForm.submit();
  });
</script>
</body>
</html>