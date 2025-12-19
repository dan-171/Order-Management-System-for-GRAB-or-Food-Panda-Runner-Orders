<?php
  session_start();
  include '../../config.php';
  $role = $_GET['role'] ?? 'runner';
  $returnToLoginLink = "login.php";

  // fetch max existing runner id, if not exists, initialize R01
  $fetchMaxRunnerID = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(ID,2) AS UNSIGNED)) AS maxID FROM runners");
  $fetchMaxRunnerID->execute();
  $row = $fetchMaxRunnerID->fetch(PDO::FETCH_ASSOC);
  $maxID = $row['maxID'] ?? 0;
  $newRunnerID = "R" . str_pad($maxID + 1, 2, "0", STR_PAD_LEFT);

  $msg = $_SESSION["msg"] ?? null;
  unset($_SESSION["msg"]);

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["signUp"])){
      $rname = trim($_POST["rname"]);
      $bDate = trim($_POST["birthDate"]);
      $tel = trim($_POST["tel"]);
      $platform = trim($_POST["platform"]);
      $plate = trim($_POST["plate"]);
      $email = trim($_POST["email"]);
      $pw = trim($_POST["pw"]);
      $pwRe = trim($_POST["pwRe"]);
      // reject empty field
      if(empty($rname) || empty($bDate) || empty($tel)
        || empty($platform) || empty($plate)
        || empty($email) || empty($pw) || empty($pwRe))
          $_SESSION["msg"] = "Please fill in all required fields.";
      else{
        // input validation
        if (!isEligibleAge($bDate))
          $_SESSION["msg"] = "Ineligible age!";
        else if (!isValidTel($tel))
          $_SESSION["msg"] = "Invalid phone number!";
        else if (!isValidEmail($email))
          $_SESSION["msg"] = "Invalid email address!";
        else if (!isMatchingPW($pw, $pwRe))
          $_SESSION["msg"] = "Your retyped password doesn’t match.";
        else{
          // add runner to db
          $pw = password_hash($pw, PASSWORD_DEFAULT);
          $newRunner = $pdo->prepare("INSERT INTO runners(ID, Password, Name, BirthDate, Tel, Email, Platform, Plate) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
          $newRunner->execute([$newRunnerID, $pw, $rname, $bDate, $tel, $email, $platform, $plate]);
          $_SESSION["regSuccess"] = true;
          header("Location: login.php");
          exit;
        }
      }
      header("Location:" . $_SERVER['PHP_SELF']);
      exit;
    }
  }

  function isEligibleAge ($bDate){
    $bDate = new DateTime($bDate);
    $currYear = Date("Y");
    $currMonth = Date("m");
    $currDate = Date("d");
    $bYear = $bDate->format("Y");
    $bMonth = $bDate->format("m");
    $bDate = $bDate->format("d");
    $age = $currYear - $bYear;
    if ($currMonth - $bMonth < 0 || ($currMonth - $bMonth == 0 && $currDate - $bDate < 0))
      $age --;
    if ($age < 18 || $age > 69) return false;
    return true;
  }

  function isValidTel($tel) {
    $telPattern = "/^\+60 [0-9]{9,10}$/";
    if (!preg_match($telPattern, trim($tel)))
      return false;
    return true;
  }

  function isValidEmail($email){
    $emailPattern = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    if (!preg_match($emailPattern, trim($email)))
      return false;
    return true;
  }

  function isMatchingPW($pw, $pwRe){
    if($pw !== $pwRe)
      return false;
    return true;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Runner | Sign Up</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" href="css/register.css">
</head>
<body>
<h1>Runner Sign Up</h1>
<a id="return" href="<?= $returnToLoginLink ?>">Return to Login</a>
<div id="reg-box">
  <div id="progress-bar-div">
    <div id="progress-bar">
      <div class="progress-step active">1</div>
      <div class="progress-step">2</div>
      <div class="progress-step">3</div>
    </div>
  </div>
  <p>Please enter your following details</p>
  <form  id = "reg-form" action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
    <div class="rf-pg active" id="rf-pg1">
      <input type="text" id="rname" name="rname" placeholder="Name"/>
      <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="birth-date" name="birthDate" placeholder="Birth Date"/>
      <input type="tel" inputmode="numeric" maxlength="14" id="tel" name="tel" placeholder="Tel"/> 
      <input type="button" class="next-btn" id="next-btn-1" name="nextBtn1" value="Next">
    </div>
    <div class="rf-pg" id="rf-pg2">
      <select id="platform" name="platform">
        <option value="">Platform</option>
        <option value="Grab">Grab</option>
        <option value="Food Panda">Food Panda</option>
      </select>
      <input type="text" id="plate" name="plate" placeholder="Plate No."/>
      <div class="btn-row">
        <input type="button" class="prev-btn" id="prev-btn-1" value="Previous">
        <input type="button" class="next-btn" id="next-btn-2" name="nextBtn2" value="Next">
      </div>
    </div>
    <div class="rf-pg" id="rf-pg3">
      <input id="runner-id" name="runnerId" type="text" value="Your Runner ID: <?= $newRunnerID ?>" readonly>
      <input type="email" id="email" name="email" placeholder="Email"/>
      <input type="password" id="pw" name="pw" placeholder="Password"/>
      <input type="password" id="pwRe" name="pwRe" placeholder="Confirm password"/>
      <div class="btn-row">
        <input type="button" class="prev-btn" id="prev-btn-2" value="Previous">
        <input type="submit" id="submit-btn" name = "signUp" value="Sign Up">
      </div>
    </div>
  </form>
</div>
<p id="msg"><?= $msg ?></p>
<script>
  //age limit
  const birthDate = document.getElementById("birth-date");
  birthDate.max = new Date().toISOString().split("T")[0];

  //rf-pg switch
  const progressStep = document.querySelectorAll(".progress-step");
  const rfPgs = document.querySelectorAll(".rf-pg");
  const nextBtns = document.querySelectorAll(".next-btn");
  const prevBtns = document.querySelectorAll(".prev-btn");
  const regForm = document.getElementById("reg-form");
  const msg = document.getElementById("msg");

  nextBtns.forEach((nextBtn, index) => {
    nextBtn.addEventListener("click", function (){
    // check before switching to second page
    if (index == 0){
      // reject empty input
      if(regForm.rname.value.trim() === "" || regForm.birthDate.value.trim() === "" || regForm.tel.value.trim() === ""){
        if (regForm.rname.value.trim() === "") regForm.rname.focus();
        else if (regForm.birthDate.value.trim() === "") regForm.birthDate.focus();
        else if (regForm.tel.value.trim() === "") regForm.tel.focus();
        msg.textContent = "Please fill in all required fields.";
        return;
      }
      // input validation
      if(!isValidTel(regForm.tel.value)) {
        msg.textContent = "Invalid phone number!";
         regForm.tel.focus()
         return;
      }
      if(!isEligibleAge(regForm.birthDate.value)) {
        msg.textContent = "Ineligible age!";
         regForm.birthDate.focus()
         return;
      }
    }
    // reject empty input before switching to third page
    if (index == 1 && (regForm.platform.value.trim() === "" || regForm.plate.value.trim() === "")){
      if (regForm.platform.value.trim() === "") regForm.platform.focus();
      else if (regForm.plate.value.trim() === "") regForm.plate.focus();
      msg.textContent = "Please fill in all required fields.";
      return;
    }
    msg.textContent = "";
    progressStep[index].classList.remove("active");
    progressStep[index + 1].classList.add("active");
    rfPgs[index].classList.remove("active");
    rfPgs[index + 1].classList.add("active");
    })
  })
  
  prevBtns.forEach((prevBtn, index) => {
    prevBtn.addEventListener("click", function(){
      progressStep[index].classList.add("active");
      progressStep[index + 1].classList.remove("active");
      rfPgs[index].classList.add("active");
      rfPgs[index+1].classList.remove("active");
    })
  })

  // check before submitting
  regForm.addEventListener("submit", function(e){
    //reject empty input
    if(regForm.email.value.trim() === "" || regForm.pw.value.trim() === "" || regForm.pwRe.value.trim() === ""){
      if (regForm.email.value.trim() === "") regForm.email.focus();
      else if (regForm.pw.value.trim() === "") regForm.pw.focus();
      else if (regForm.pwRe.value.trim() === "") regForm.pwRe.focus();
      msg.textContent = "Please fill in all required fields.";
      e.preventDefault();
      return;
    }
    // validate email
    if(!isValidEmail(regForm.email.value)){
      e.preventDefault();
      msg.textContent = "Invalid email address!";
      regForm.email.focus()
      return;
    }
    // validate pw input
    if(!isMatchingPW(regForm.pw.value, regForm.pwRe.value)){
      e.preventDefault();
      msg.textContent = "Your retyped password doesn’t match.";
      regForm.pw.value = "";
      regForm.pwRe.value= "";
      regForm.pw.focus();
      return;
    }
  })
  
  // tel formatting
  // show +60 onclick
  const tel = document.getElementById("tel");
  const PREFIX = "+60 ";
  tel.addEventListener("focus", () => {
    if (!tel.value.startsWith(PREFIX)) tel.value = PREFIX;
    setCursorToEnd();
  });
  // prevent backspacing into "+60 "
  tel.addEventListener("keydown", (e) => {
    if (e.key === "Backspace" && tel.selectionStart <= PREFIX.length)
      e.preventDefault();
  });
  // restore prefix if removal attempted via typing/pasting
  tel.addEventListener("input", () => {
    if (!tel.value.startsWith(PREFIX))
      tel.value = PREFIX;
    setCursorToEnd();
  });
  tel.addEventListener("paste", (e) => {
    e.preventDefault();
    const pasted = (e.clipboardData || window.clipboardData).getData("text").replace(/\D/g, "");
    tel.value = PREFIX + pasted;
    setCursorToEnd();
  });
  function setCursorToEnd() {
    tel.setSelectionRange(tel.value.length, tel.value.length);
  }
  tel.addEventListener("input", () => {
    tel.value = PREFIX + tel.value.slice(PREFIX.length).replace(/\D/g, "");
  });

  //input validation
  function isValidTel(tel) {
    const telPattern = /^\+60 [0-9]{9,10}$/
    if(!telPattern.test(tel.trim()))
      return false;
    return true;
  }

  function isEligibleAge (bDate){
    bDate = new Date(bDate);
    const today = new Date();
    let age = today.getFullYear() - bDate.getFullYear();
    if (today.getMonth() - bDate.getMonth() < 0 || (today.getMonth() - bDate.getMonth() == 0 && today.getDate() - bDate.getDate() < 0))
      age --;
    if (age < 18 || age > 69) return false;
    return true;
  }
  
  function isValidEmail(email){
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.trim()))
      return false;
    return true;
  }

  function isMatchingPW(pw, pwRe){
      if(pw !== pwRe)
        return false;
      return true;
  }
</script>
</body>
</html>