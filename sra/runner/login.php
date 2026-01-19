<?php
  session_start();
  include "../../config.php";

  $msg = $_SESSION["msg"] ?? "";  
  unset($_SESSION["msg"]);
  
  if(isset($_SESSION["forgotMsg"])){
    $forgotMsg = $_SESSION["forgotMsg"];
    $tempPW = $_SESSION["tempPW"] ?? "";
 echo "<script> 
    let msg = " . json_encode($forgotMsg) . ";
    let tempPW = " . json_encode($tempPW) . ";
    if (tempPW && tempPW.trim() !== '') {
        prompt(msg, tempPW);
    } else {
        alert(msg);
    }
    </script>";
    unset($_SESSION["forgotMsg"]);
    unset($_SESSION["tempPW"]);
  }

  $regSuccess = $_SESSION["regSuccess"] ?? "";
  unset($_SESSION["regSuccess"]);

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = trim($_POST["id"]);
    $pw = trim($_POST["pw"]);

    if (empty($id)) $_SESSION["msg"] = "ID cannot be left blank";
    else if (empty($pw)) $_SESSION["msg"] = "Password cannot be left blank";
    else {
      $stmt = $pdo->prepare("SELECT * FROM runners WHERE ID = ?");
      $stmt->execute([$id]);
      $user = $stmt->fetch();
      if ($user && password_verify($pw, $user["Password"])){
        $_SESSION["user"] = [
            "id" => $user["ID"],
            "role" => "runner"
        ];
        header("Location: runnerMain.php");
        exit;
      } else $_SESSION["msg"] = "❌ Invalid credentials";
    }
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php
  $role="Runner";
  include "../loginHead.php"
?>
<body>
  <div id="banner-top">
      <img src="../../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Runner Login</h2>
    <form id="login" method="post">
        <input id="id-input" type="text" name="id" placeholder="Runner ID">
        <input id="pw-input" type="password" name="pw" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <a id="forgot-credentials" href="../forgotCredentials.php?role=runner">Forgot ID or Password</a>
  </div>
  <p id="err-msg"><?= htmlspecialchars($msg)?></p>
  <div id="delivery-platforms">
    <img src="../../images/grab-logo.webp" class = "grab" alt="grab"/>
    <img src="../../images/foodpanda-logo.webp" class = "foodpanda" alt="foodpanda"/>
  </div>
  <div id="reg-box">
    <p id="reg-text">Looking to be a rider?</p>
    <a id="reg-link" href="register.php?role=runner">Sign Up Now</a>
  </div>
  

  <script src="../sraLogin.js"></script>
</body>
</html>

<script>
  const regBox = document.getElementById("reg-box")
  const regText = document.getElementById("reg-text");
  const regLink = document.getElementById("reg-link");
  regBox.addEventListener("mouseover", function hideTextShowLink (){
    regText.classList.add("inactive");
    regLink.classList.add("active");
  })
  regBox.addEventListener("mouseout", function hideTextShowLink (){
    regText.classList.remove("inactive");
    regLink.classList.remove("active");
  })

  <?php if(isset($regSuccess) && $regSuccess): ?>
    alert("✅ Signed up successfully!");
  <?php endif; ?>
</script>