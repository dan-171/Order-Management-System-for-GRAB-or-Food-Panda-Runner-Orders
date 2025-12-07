<?php
  session_start();
  $_SESSION["userType"] = "staff";
  include "../../config.php";

  $error = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      $id = $_POST["id"];
      $pw = $_POST["pw"];

      $stmt = $pdo->prepare("SELECT * FROM staff WHERE Id = ?");
      $stmt->execute([$id]);
      $user = $stmt->fetch();
      if ($user && password_verify($pw, $user["Password"])){
        $_SESSION["user"] = [
            "id" => $staff["ID"],
        ];
        header("Location: staff.php");
        exit;
      } else $error = "❌ Invalid credentials";
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../loginHead.php"?>
<body>
  <div id="banner-top">
      <img src="../../images/banner.webp" alt="banner img"/>
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