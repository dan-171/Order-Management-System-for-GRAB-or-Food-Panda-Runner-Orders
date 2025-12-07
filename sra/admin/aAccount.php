<?php
  $staffNo = 1;
  //first staff id starts at S01, then S02, S03, etc.
  $fetchStaff = $pdo->prepare("SELECT * FROM staff ORDER BY CAST(SUBSTRING(ID, 2) AS UNSIGNED)");
  $fetchStaff->execute();
  $staffs = $fetchStaff->fetchALL();
  $newStaffIDDigits = $staffs ? (int)substr($staffs[sizeof($staffs) - 1][0], 1) : 0;
  $newStaffID = "S" . str_pad($newStaffIDDigits + 1, 2, "0", STR_PAD_LEFT);
?>

<div id="staff-panel">
  <div id="create-staff-acc">
    <h2>Staff</h2>
    <h3>Create Staff Account</h3>
    <form method="post">
      <div>
        <label for="staff-id">ID: <input id="staff-id" type="text" placeholder = <?= $newStaffID ?> readonly></label>
        <p>*ID is auto-generated incrementally </p>
      </div>
      <label for="staff-pw">Password: <input id="staff-pw" type="password" placeholder="Password"/></label>
      <input class="btn" type="submit" value="Create"/>
    </form>
  </div>
  <div id="staff-list">
    <?php if (!$staffs): ?>
        <tr>
          <td colspan="3">No staff account has been created yet.</td>
        </tr>  
    <?php else: ?>
      <h3>Existing Staff Accounts</h3>
      <table class="table">
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
              <td><?=  $staffNo ?></td>
              <td><?=  $staff['ID'] ?></td>
            </tr>
          <?php $staffNo++; endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>