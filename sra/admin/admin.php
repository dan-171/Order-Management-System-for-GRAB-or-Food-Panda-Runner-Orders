<?php
  session_start();
  include '../../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/common.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/aAccount.css">
</head>
<body>
  <nav id="menu-sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li class="nav-option"><div><p>🍽️ Restaurant </p></div></li>
      <li class="nav-option"><div><p>🧑🏻‍💼 Account Management</p></div></li>
      <li class="nav-option"><div><p>🔐 Change Password</p></div></li>
      <li class="nav-option"><div><a href="../logout.php?role=admin" onclick="return confirm('Log out?')"><p>🚪 Log Out</p></a></div></li>
    </ul>
  </nav>
  <div id="main-panel">
    <div class="main-panel-content" id="restaurant-panel"><?php include "aRestaurant.php" ?></div>
    <div class="main-panel-content" id="acc-panel"><?php include "aAccount.php"?></div>
    <div class="main-panel-content" id="pw-panel"><?php include "aChangePW.php"?></div>
  </div>
</body>

<script>
  const navOptions = document.querySelectorAll(".nav-option");
  const mainPanelContents = document.querySelectorAll(".main-panel-content");

  //load restaurant panel by default
  navOptions[0].classList.add("active");
  mainPanelContents[0].classList.add("active");

  // add active class to selected sidebar menu option & show corresponding panel
  for (let i = 0; i < navOptions.length; i++){
    navOptions[i].addEventListener("click", function(){
      for(let j = 0; j < navOptions.length; j++) {
        navOptions[j].classList.remove("active");
        if (j < mainPanelContents.length)
           mainPanelContents[j].classList.remove("active");
      }
      this.classList.add("active");
      if(i!=3) mainPanelContents[i].classList.add("active");
    })
  }
</script>
</html>