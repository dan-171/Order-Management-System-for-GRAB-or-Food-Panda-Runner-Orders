<?php
  session_start();
  include "../../config.php";

  $error = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      $id = $_POST["id"];
      $pw = $_POST["pw"];

      $stmt = $pdo->prepare("SELECT * FROM runners WHERE Id = ?");
      $stmt->execute([$id]);
      $user = $stmt->fetch();
      if ($user && password_verify($pw, $user["Password"])){
        $_SESSION["user"] = [
            "id" => $admin["ID"],
        ];
        header("Location: runner.php");
        exit;
      } else $error = "❌ Invalid credentials";
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php $role="Runner"; include "../loginHead.php"?>
<body>
  <div id="banner-top">
      <img src="../../images/banner.webp" alt="banner img"/>
  </div>
  <div id="login-box">
    <h2>Runner Login</h2>
    <form method="post">
        <input id="id-input" type="text" name="id" placeholder="Runner ID" required>
        <input id="pw-input" type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a id="reset-credentials" href="../resetCredentials.php?role=runner">Forgot ID or Password</a>
  </div>
  <div id="delivery-platforms">
    <img src="../../images/grab-logo.webp" alt="grab"/>
    <img src="../../images/foodpanda-logo.webp" alt="grab"/>
  </div>
</body>
</html>