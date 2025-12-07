<?php
  session_start();
  include "../../config.php";

  $role = "Admin";
  $error = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      $id = $_POST["id"];
      $pw = $_POST["pw"];

      $stmt = $pdo->prepare("SELECT * FROM admin WHERE Id = ?");
      $stmt->execute([$id]);
      $user = $stmt->fetch();
      if ($user && password_verify($pw, $user["Password"])){
        $_SESSION["user"] = [
            "id" => $admin["ID"],
            "role" => "admin",
        ];
        header("Location: admin.php");
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
    <h2>Admin Login</h2>
    <form method="post">
        <input id="id-input" type="text" name="id" placeholder="Admin ID" required>
        <input id="pw-input" type="password" name="pw" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a id="reset-credentials" href="../resetCredentials.php?role=admin">Forgot ID or Password</a>
  </div>
  <p id="wrong-cred-msg"><?= $error?></p>
</body>
</html>