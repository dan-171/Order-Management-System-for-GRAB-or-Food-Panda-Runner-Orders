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

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

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
      <li class="nav-option" onclick="logout()"><div><p>🚪 Log Out</p></div></li>
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
  for (let i = 0; i < mainPanelContents.length; i++){
    navOptions[i].addEventListener("click", function(){
      for(let j = 0; j < mainPanelContents.length; j++) {
        navOptions[j].classList.remove("active");
        mainPanelContents[j].classList.remove("active");
      }
      this.classList.add("active");
      mainPanelContents[i].classList.add("active");
      localStorage.setItem("activeAdminPanel", i);
    })
  }

  // on page reload
  let savedIndex = localStorage.getItem("activeAdminPanel");
  let defaultIndex = savedIndex !== null ? parseInt(savedIndex) : 0;
  for(let i = 0; i < mainPanelContents.length; i++){
    navOptions[i].classList.remove("active");
    mainPanelContents[i].classList.remove("active"); 
  }
  navOptions[defaultIndex].classList.add("active");
  mainPanelContents[defaultIndex].classList.add("active");

  //logout
  function logout(){
    if (confirm("Log out of admin panel?")) {
      localStorage.removeItem("activeAdminPanel");
      window.location.href = "../logout.php?role=admin";
    }
  }
</script>
</html>