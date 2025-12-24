<?php
  //staff & runner acc table index
  $staffNo = 1;
  $runnerNo = 1;

  // fetch all staff
  $fetchStaffs = $pdo->prepare("SELECT * FROM staff ORDER BY CAST(SUBSTRING(ID,2) AS UNSIGNED)");
  $fetchStaffs->execute();
  $staffs = $fetchStaffs->fetchAll(PDO::FETCH_ASSOC); 

  // fetch max existing staff id, if not exists, initialize S01
  $fetchMaxStaffID = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(ID,2) AS UNSIGNED)) AS maxID FROM staff");
  $fetchMaxStaffID->execute();
  $row = $fetchMaxStaffID->fetch(PDO::FETCH_ASSOC);
  $maxID = $row['maxID'] ?? 0;
  $newStaffID = "S" . str_pad($maxID + 1, 2, "0", STR_PAD_LEFT);

  //fetch all runners
  if (isset($_SESSION['searched_runners'])){
    $runners = $_SESSION['searched_runners'];
  }
  else if ($runners === []){
    $fetchRunners = $pdo->prepare("SELECT ID, Name, Status FROM runners ORDER BY CAST(SUBSTRING(ID,2) AS UNSIGNED)");
    $fetchRunners->execute();
    $runners = $fetchRunners->fetchAll(PDO::FETCH_ASSOC); 
  }
?>

<!-- staff -->
<div id="staff-panel">
  <div class="title"><h2>Staff</h2></div>
  <div id="staff-div">
    
    <div id="staff-edit">
    <?php if(!$editing): ?>
      <h3>Create Staff Account</h3>
    <?php else: ?>
      <h3>Edit Staff Account</h3>
    <?php endif; ?>
      <form id="staff-form" method="post">
        <div id="staff-form-input-wrapper">
          <div>
            <?php if(!$editing): ?>
              <label for="staff-id">ID: </label>
              <input id="staff-id" name="staffId" type="text" value="<?= $newStaffID ?>" readonly>
            <?php else: ?>
              <label for="staff-id">ID: </label>
              <input id="staff-id" name="staffId" type="text" value="<?= $staffIdToEdit ?>" readonly>
            <?php endif; ?>
          </div>
          <div>
            <label for="staff-pw">Password: </label>
            <input id="staff-pw" name="staffPw" type="password"/>
          </div>
        </div>
        <div id="staff-edit-btns">
          <?php if(!$editing): ?>
            <input class="btn" type="submit" name="createStaffBtn" value="Create"/>
          <?php else: ?>
            <input class="btn" type="submit" name="updateStaffBtn" value="Update"/>
            <input class="btn" type="submit" name="cancelStaffEditBtn" value="Cancel"/>
          <?php endif; ?>
        </div>
      </form>
      <div id="msg"></div>
    </div>

    <div id="staff-list">
      <?php if (!$staffs): ?>
        <p>No staff account has been created yet.</p>
      <?php else: ?>
        <h3>Staff Accounts</h3>
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
                      <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <input type="hidden" name="staffIdToEdit" value="<?= $staff['ID']?>"/>
                        <input type="submit" value="✏️ Edit"/>
                      </form>
                    </div>
                    <div class="opt-delete">
                      <form class="del-acc" action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                        <input type="hidden" name="idToDel" value="<?= $staff['ID']?>"/>
                        <input type="hidden" name="type" value="staff">
                        <?php if (!($editing && $staff['ID'] === $staffIdToEdit)): ?>
                          <input type="submit" value="⛔ Delete"/>
                        <?php endif; ?>
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
</div>

<!-- runner -->
<div id="runner-panel">
  <div class="title">
    <h2>Runners</h2>
  </div>
  <div id="runner-panel-content">
    <div id="runner-list">
    <?php if (!$runners): ?>
      <p>No runner account has been created yet.</p>
    <?php else: ?>
      <div id="runner-list-header">
        <h3>Runner Accounts</h3>
        <div id="runner-search">
          <form id="runner-search-form" action="<?=  htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="get">
            <label for="rname-to-search">Search by name: </label>
            <input id="rname-to-search" name="rnameToSearch" type="text"/>
          </form>
        </div>
      </div>
      <div id="runner-table-wrapper">
        <table id="runner-table">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID</th>
              <th>Name</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($runners as $runner): ?>
              <tr>
                <td class="runner-table-no"><?= htmlspecialchars($runnerNo) ?></td>
                <td class="runner-table-id"><?= htmlspecialchars($runner["ID"]) ?></td>
                <td class="runner-table-name"><?= htmlspecialchars($runner["Name"]) ?></td>
                <td class="runner-table-status"><?= htmlspecialchars($runner["Status"]) ?></td>
                <td class="runner-table-actions">
                  <div class="opt-view">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                      <input type="hidden" name="runnerIdToView" value="<?= htmlspecialchars($runner['ID'])?>"/>
                      <input type="submit" value="📝 View More"/>
                    </form>
                  </div>
                  <div class="opt-edit">
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                      <input type="hidden" name="runnerIdToEdit" value="<?= htmlspecialchars($runner['ID'])?>"/>
                      <input type="hidden" name="runnerStatus" value="<?= htmlspecialchars($runner['Status'])?>"/>
                      <?php if($runner['Status'] === "Active" || $runner['Status'] === "Inactive"): ?>
                        <input type="submit" value="🔒 Disable"/>
                      <?php else: ?>
                        <input type="submit" value="🔓 Activate"/>
                      <?php endif; ?>
                    </form>
                  </div>
                  <div class="opt-delete">
                    <form class="del-acc" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                      <input type="hidden" name="type" value="runner">
                      <input type="hidden" name="idToDel" value="<?= htmlspecialchars($runner['ID'])?>"/>
                      <input type="submit" value="⛔ Delete"/>
                    </form>
                  </div>
                </td>
              </tr>
            <?php $runnerNo++; endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
    </div>
    <?php if ($searchError !== ""): ?>
      <div id="search-err"><?= $searchError ?></div>
    <?php endif;?>
    <?php if ($runnerToView): ?>
      <div id="runner-details-wrapper">
        <h3>Runner Details</h3>
        <div id="runner-details">
          <p><strong>ID:</strong> <?= htmlspecialchars($runnerToView["ID"]) ?></p>
          <p><strong>Name:</strong> <?= htmlspecialchars($runnerToView["Name"]) ?></p>
          <p><strong>Plate:</strong> <?= htmlspecialchars($runnerToView["Plate"]) ?></p>
          <p><strong>Platform:</strong> <?= htmlspecialchars($runnerToView["Platform"]) ?></p>
          <p><strong>Tel:</strong> <?= htmlspecialchars($runnerToView["Tel"]) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($runnerToView["Email"]) ?></p>
        </div>
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
    if(submitter.name === "createStaffBtn" || submitter.name === "updateStaffBtn"){
      if(staffForm.staffPw.value.trim() === ""){
        e.preventDefault();
        staffForm.staffPw.focus();
        msg.textContent = "❌ Password cannot be left blank";
        return; 
      }
    }
  })

  //staff update completion alert
  <?php if($msg): ?>
    alert("<?= $msg ?>");
  <?php endif; ?>

  //staff & runner delete confirmation popup
  document.querySelectorAll(".del-acc").forEach(function(delAcc){
    delAcc.addEventListener("submit", function(e){
      const idToDel = delAcc.idToDel.value;
      const type = delAcc.type.value;
      if(!confirm("⛔ Delete " + type + " account " + idToDel + "?"))
        e.preventDefault();
      return;
    })
  })

</script>

<?php unset($_SESSION['msg']); ?>