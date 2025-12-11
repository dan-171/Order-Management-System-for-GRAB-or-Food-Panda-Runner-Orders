<?php
  //staff acc table index
  $staffNo = 1;

  //add or update staff
  $editing = false;
  $staffIdToEdit = null;
  $idToDel = null;
  $msg = $_SESSION['msg'] ?? null;

  if(isset($_SESSION['staffIdToEdit'])){ //fetch id to edit to switch into edit mode
    $editing = true;
    $staffIdToEdit = $_SESSION['staffIdToEdit'];
  }

  if($_SERVER["REQUEST_METHOD"] === "POST"){
    if (isset($_POST['staffIdToEdit'])) //switch to edit staff mode
      $_SESSION["staffIdToEdit"] = $_POST["staffIdToEdit"];
    else if(isset($_POST["update-staff-btn"])){ //update staff
      if(isset($_POST['pw'])){
        $pw = password_hash(trim($_POST["pw"]), PASSWORD_DEFAULT);
        $updateStaff = $pdo->prepare("UPDATE staff SET Password = ? WHERE ID = ?");
        $updateStaff->execute([$pw, $staffIdToEdit]);
        unset($_SESSION["staffIdToEdit"]);
        $_SESSION["msg"] = "✅ Staff {$staffIdToEdit} updated successfully!";
      }
      else
        $_SESSION["msg"] = "Update failed - Password cannot be left blank!";
    }else if(isset($_POST["cancel-staff-edit-btn"])){ //cancel staff edit
      unset($_SESSION["staffIdToEdit"]);
    }else if(isset($_POST["create-staff-btn"])){ //new staff
      if(isset($_POST["pw"])){
        $id = trim($_POST["id"]);
        $pw = password_hash(trim($_POST["pw"]), PASSWORD_DEFAULT);
        $newStaff = $pdo->prepare("INSERT INTO staff(ID, Password) VALUES(?, ?)");
        $newStaff->execute([$id, $pw]);
      }
      else
        $_SESSION["msg"] = "Staff account creation failed - Password cannot be left blank!";
    }else if(isset($_POST["idToDel"])){ //del staff
      $idToDel = $_POST["idToDel"];
      $delStaff = $pdo->prepare("DELETE FROM staff WHERE ID = ?");
      $delStaff->execute([$idToDel]);
    }
    header("Location:" . $_SERVER['PHP_SELF']);
    exit;
  }

  // fetch all staff
  $fetchStaffs = $pdo->prepare("SELECT * FROM staff ORDER BY CAST(SUBSTRING(ID,2) AS UNSIGNED)");
  $fetchStaffs->execute();
  $staffs = $fetchStaffs->fetchAll(PDO::FETCH_ASSOC); 

  // fetch max existing staff id, if not exists, initialize S01
  $fetchMaxID = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(ID,2) AS UNSIGNED)) AS maxID FROM staff");
  $fetchMaxID->execute();
  $row = $fetchMaxID->fetch(PDO::FETCH_ASSOC);
  $maxID = $row['maxID'] ?? 0;
  $newStaffID = "S" . str_pad($maxID + 1, 2, "0", STR_PAD_LEFT);

?>

<!-- staff -->
<div id="staff-panel">
  <div id="staff-title"><h2>Staff</h2></div>
  <div id="create-staff-acc">
    <?php if(!$editing): ?>
      <h3>Create Staff Account</h3>
    <?php else: ?>
      <h3>Edit Staff Account</h3>
    <?php endif; ?>
    <form id="staff-form" method="post">
      <div>
        <?php if(!$editing): ?>
          <label for="staff-id">ID: <input id="staff-id" name="id" type="text" value="<?= $newStaffID ?>" readonly></label>
        <?php else: ?>
          <label for="staff-id">ID: <input id="staff-id" name="id" type="text" value="<?= $staffIdToEdit ?>" readonly></label>
        <?php endif; ?>
        <p id="auto-gen">*ID is auto-generated incrementally </p>
      </div>
        <label for="staff-pw">Password: <input id="staff-pw" name="pw" type="password"/></label>
        <?php if(!$editing): ?>
          <input class="btn" type="submit" name="create-staff-btn" value="Create"/> <div id="msg"></div>
        <?php else: ?>
          <input class="btn" type="submit" name="update-staff-btn" value="Update"/>
          <input class="btn" type="submit" name="cancel-staff-edit-btn" value="Cancel"/> <div id="msg"></div>
          <div id="msg"></div>
        <?php endif; ?>
    </form>
  </div>
  <div id="staff-list">
    <?php if (!$staffs): ?>
      <p>No staff account has been created yet.</p>
    <?php else: ?>
      <h3>Existing Staff Accounts</h3>
      <div id="staff-table-wrapper">
        <table id="staff-table">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($staffs as $staff): ?>
              <tr>
                <td class="staff-table-no"><?= $staffNo ?></td>
                <td class="staff-table-staff-id"><?= $staff['ID'] ?></td>
                <td class="staff-table-actions">
                  <div class="opt-edit">
                    <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                      <input type="hidden" name="staffIdToEdit" value="<?= $staff['ID']?>"/>
                      <input type="submit" value="✏️ Edit"/>
                    </form>
                  </div>
                  <div class="opt-delete">
                    <form class="del-staff" action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                      <input type="hidden" name="idToDel" value="<?= $staff['ID']?>"/>
                      <input type="submit" value="⛔ Delete"/>
                    </form>
                  </div>
                </td>
              </tr>
            <?php $staffNo++; endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
  //reject staff creation/update if pw is not filled
  const msg = document.getElementById("msg");
  const staffForm = document.getElementById("staff-form");
  staffForm.addEventListener("submit", function(e){
    const submitter = e.submitter;
    if(submitter.name === "create-staff-btn" || submitter.name === "update-staff-btn"){
      if(staffForm.pw.value.trim() === ""){
        e.preventDefault();
        staffForm.pw.focus();
        msg.textContent = "❌ Password cannot be left blank";
        return; 
      }
    }
  })

  //staff update completion alert
  <?php if($msg): ?>
    alert("<?= $msg ?>");
  <?php endif; ?>

  //staff delete confirmation popup
  const delStaffs = document.querySelectorAll(".del-staff").forEach(function(delStaff){
    delStaff.addEventListener("submit", function(e){
      let idToDel = delStaff.idToDel.value;
      if(!confirm("⛔ Delete staff account " + idToDel + "?"))
        e.preventDefault();
      return;
    })
  })

</script>

<?php unset($_SESSION['msg']); ?>