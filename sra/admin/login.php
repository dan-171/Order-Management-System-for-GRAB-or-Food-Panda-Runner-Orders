<?php
  session_start();

  include "../../config.php";
  $error = $_SESSION["error"] ?? "";
  unset($_SESSION["error"]);

  if($_SERVER["REQUEST_METHOD"] == "POST"){
      $id = $_POST["id"];
      $pw = $_POST["pw"];

    if ($id && $pw){
      $stmt = $pdo->prepare("SELECT * FROM admin WHERE Id = ?");
      $stmt->execute([$id]);
      $user = $stmt->fetch();
      if ($user && password_verify($pw, $user["Password"])){
        $_SESSION["user"] = [
            "id" => $user["ID"],
        ];
        header("Location: admin.php");
        exit;
      } else {
        $_SESSION["error"] = "❌ Invalid credentials";
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php $role="Admin"; include "../loginHead.php"?>
<body>
  <div id="banner-top">
      <img src="../../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Admin Login</h2>
    <form id="login" method="post">
        <input id="id-input" type="text" name="id" placeholder="Admin ID">
        <input id="pw-input" type="password" name="pw" placeholder="Password">
        <button type="submit">Login</button>
    </form>
    <a id="reset-credentials" href="../resetCredentials.php?role=admin">Forgot ID or Password</a>
  </div>
  <p id="err-msg"><?= $error?></p>

  <script src="../sraLogin.js"></script>
</body>
</html>