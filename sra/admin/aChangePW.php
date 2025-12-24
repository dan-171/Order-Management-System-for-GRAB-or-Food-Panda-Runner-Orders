<div id="cpw-panel">
  <div class="title"><h2>Change Password</h2></div>
  <div id="change-pw-panel">
    <form id="cpw-form" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
      <label for="currPw">Current password: <input id="currPw" name="currPw" type="password"></label>
      <label for="newPw">New password: <input id="newPw" name="newPw" type="password"></label>
      <label for="newPwRe">Retype new password: <input id="newPwRe" name="newPwRe" type="password"></label>
      <input id="update-admin-btn" name="updateBtn" type="submit" value="Update"/>
    </form>
    <div id="cpw-msg"><?= htmlspecialchars($cpwMsg) ?></div>
  </div>
</div>

<script>
  const cpwForm = document.getElementById("cpw-form");
  let cpwMsg = document.getElementById("cpw-msg");
  cpwForm.addEventListener("submit", function(e){
    const currPw = cpwForm.currPw.value.trim();
    const newPw = cpwForm.newPw.value.trim();
    const newPwRe = cpwForm.newPwRe.value.trim();
    if(!currPw || !newPw || !newPwRe){ //prevent empty entry
      e.preventDefault();
      cpwMsg.textContent = "Please fill in all required fields.";
      if (!currPw) cpwForm.currPw.focus();
      else if (!newPw) cpwForm.newPw.focus();
      else cpwForm.newPwRe.focus();
      return;
    } 
    
    if(newPw !== newPwRe){ //check if new pw = new pw retyped
      e.preventDefault();
      cpwForm.currPw.value = "";
      cpwForm.newPw.value = "";
      cpwForm.newPwRe.value = "";
      cpwMsg.textContent = "Your retyped password doesn’t match.";
      cpwForm.newPw.focus();
      return;
    }
  })

  <?php if(!empty($_SESSION['cpwDone'])): ?>
    alert("✅ Password changed successfully! Returning to login page...");
    <?php unset($_SESSION['cpwDone']); ?>
    window.location.href = "login.php";
  <?php endif; ?>
</script>

