<?php
session_start();
include '../../config.php';

// Security: Kick user back to login page if unauthorized
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'runner') {
    header("Location: login.php");
    exit;
}

$currentRunnerID = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentInput = $_POST['current_pwd'];
    $newPassword = $_POST['new_pwd'];

    $stmt = $pdo->prepare("SELECT Password FROM runners WHERE ID = ?");
    $stmt->execute([$currentRunnerID]);
    $user = $stmt->fetch();

    if (password_verify($currentInput, $user['Password'])) {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE runners SET Password = ? WHERE ID = ?");
        if ($update->execute([$newHash, $currentRunnerID])) {
            echo "OK";
        } else {
            echo "Database error.";
        }
    } else {
        echo "Current password incorrect!";
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Collect data
    $name = $_POST['name'];
    $tel = $_POST['telephone'];
    $email = $_POST['email'];
    $bdate = $_POST['Bdate'];
    $plate = $_POST['plate'];
    $status = $_POST['status'];
    $platform = $_POST['platform'];

    // SQL Update
    $sql = "UPDATE runners SET Name = ?, Tel = ?, Email = ?, BirthDate = ?, Plate = ?, Status = ?, Platform = ? WHERE ID = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$name, $tel, $email, $bdate, $plate, $status, $platform, $currentRunnerID])) {
        echo "OK";
    } else {
        echo "Update failed.";
    }
    exit;
}

$fetchRunners = $pdo->prepare("SELECT ID as id, Name as name, Platform as platform, BirthDate as Bdate, Tel as telephone, Email as email, Status as status, Password as password, Plate as plate FROM runners WHERE ID = ?");
$fetchRunners->execute([$currentRunnerID]);
$runnerData = $fetchRunners->fetch(PDO::FETCH_ASSOC);

include 'runnerHead.php'; 
?>

<main class="container">
    <script>
        const runner = <?= json_encode($runnerData) ?>;

        window.onload = function() {
            const op = document.getElementById('ordersPanel');
            const sp = document.getElementById('summaryPanel');
            if(op) op.classList.add('hidden');
            if(sp) sp.classList.add('hidden');
            renderDetails();
        };

        let isEditing = false;
        let passwordVisible = false;

        function notify(msg, type = 'success') {
            const bar = document.getElementById('notificationBar');
            bar.innerText = msg;
            bar.style.backgroundColor = type === 'success' ? '#7bfb77ff' : '#ffb3b3ff';
            bar.classList.remove('hidden');
            setTimeout(() => { bar.classList.add('hidden'); }, 3000);
        }

        // VALIDATION FUNCTIONS
        function isValidTel(tel) {
            return /^\+60 [0-9]{9,10}$/.test(tel.trim());
        }

        function isEligibleAge(bDate) {
            const birth = new Date(bDate);
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            if (today.getMonth() < birth.getMonth() || (today.getMonth() === birth.getMonth() && today.getDate() < birth.getDate())) age--;
            return age >= 18 && age <= 69;
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
        }

        function toggleEditProfile() {
            isEditing = !isEditing;
            renderDetails();
        }

        function renderDetails() {
            const personalPanel = document.getElementById('personalDetails');
            const logoImg = runner.platform === 'Grab' ? 'grab-food-logo.png' : 'foodpanda-logo.png';
            if (isEditing) {
                const statusDisabled = runner.status === 'Disabled';
                personalPanel.innerHTML = `
                    <div class="name-logo-container">
                        <input type="text" id="editName" value="${runner.name}" class="edit-input">
                        <h2>from</h2>
                        <img src="../../images/${logoImg}" class="runner-logo">
                    </div>
                    <div class="personal">
                        <div class="form-group"><label>Runner ID:</label><div class="readonly-field">${runner.id}</div></div>
                        <!-- #region -->
                        <div class="form-group"><label>Birth Date:</label><input type="date" id="editBdate" value="${runner.Bdate}" class="edit-input"></div>
                        <div class="form-group"><label>Telephone:</label><input type="tel" id="editTelephone" value="${runner.telephone}" class="edit-input"></div>
                        <div class="form-group"><label>Email:</label><input type="email" id="editEmail" value="${runner.email}" class="edit-input"></div>
                        <div class="form-group">
                            <label>Status:</label>
                            ${statusDisabled ? `<div class="readonly-field">🔴 Disabled</div><input type="hidden" id="editStatus" value="Disabled">` : 
                            `<select id="editStatus" class="edit-select">
                                <option value="Active" ${runner.status === 'Active' ? 'selected' : ''}>🟢 Active</option>
                                <option value="Inactive" ${runner.status === 'Inactive' ? 'selected' : ''}>🟡 Inactive</option>
                            </select>`}
                        </div>
                        <div class="form-group">
                            <label>Platform:</label>
                            <select id="editPlatform" class="edit-select">
                                <option value="Grab" ${runner.platform === 'Grab' ? 'selected' : ''}>Grab Food</option>
                                <option value="Food Panda" ${runner.platform === 'Food Panda' ? 'selected' : ''}>Food Panda</option>
                            </select>
                        </div>
                        <div class="button-group">
                            <button class="save-btn" onclick="saveProfile()">💾 Save Changes</button>
                            <button class="cancel-btn" onclick="toggleEditProfile()">❌ Cancel</button>
                        </div>
                    </div>`;
            } else {
                personalPanel.innerHTML = `
                    <div class="name-logo-container">
                        <h1 class="runnerName">${runner.name} from</h1>

                        <img src="../../images/${logoImg}" class="runner-logo">
                    </div>
                    <div class="personal">
                        <div class="detail-row"><span class="detail-label">Runner ID:</span><span>${runner.id}</span></div>
                        <div class="detail-row"><span class="detail-label">Plate Number:</span><span>${runner.plate || '-'}</span></div>
                        <div class="detail-row"><span class="detail-label">Birth Date:</span><span>${formatToMMDDYYYY(runner.Bdate)}</span></div>
                        <div class="detail-row"><span class="detail-label">Telephone:</span><span>${runner.telephone}</span></div>
                        <div class="detail-row"><span class="detail-label">Email:</span><span>${runner.email}</span></div>
                        <div class="detail-row"><span class="detail-label">Status:</span>
                            <span class="${runner.status === 'Active' ? 'status-value active' : 'status-value disabled'}">
                            ${runner.status === 'Disabled' ? '🔴 ' : runner.status === 'Active' ? '🟢 ' : '🟡 '}${runner.status}</span>
                        </div>
                        <div class="button-group">
                            <button class="profileButtons" onclick="toggleEditProfile()">✏️ Edit Profile</button>
                            <button class="profileButtons" onclick="openPasswordModal()">🔑 Edit Password</button>
                            <button class="profileButtons" onclick="logout()">🚪 Log Out</button>
                        </div>
                    </div>`;
            }
        }
        function formatToMMDDYYYY(dateStr) {
          if (!dateStr || dateStr.includes('0000')) return '-';
          const d = new Date(dateStr);
          const month = String(d.getMonth() + 1).padStart(2, '0');
          const day = String(d.getDate()).padStart(2, '0');
          const year = d.getFullYear();
          return `${month}-${day}-${year
        }`;
}
        function saveProfile() {
            const name = document.getElementById('editName').value;
            const tel = document.getElementById('editTelephone').value;
            const email = document.getElementById('editEmail').value;
            const bdate = document.getElementById('editBdate').value;

            if (!isValidEmail(email)) return alert("Invalid Email format");
            if (!isValidTel(tel)) return alert("Invalid Telephone format (+60 xxxxxxxxx)");
            if (!isEligibleAge(bdate)) return alert("Age must be between 18-69");

            const formData = new FormData();
            formData.append('update_profile', '1');
            formData.append('name', name);
            formData.append('telephone', tel);
            formData.append('email', email);
            formData.append('Bdate', bdate);
            formData.append('plate', document.getElementById('editPlate').value);
            formData.append('status', document.getElementById('editStatus').value);
            formData.append('platform', document.getElementById('editPlatform').value);

            fetch('runnerProfile.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "OK") { alert('✅ Profile Updated!'); location.reload(); }
                else alert('❌ ' + data);
            });
        }

        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
            document.getElementById('personalPanel').classList.add('hidden');
        }

        function closePasswordModal() {
            document.getElementById('personalPanel').classList.remove('hidden');
            document.getElementById('passwordModal').classList.add('hidden');
        }

        function submitPasswordChange() {
            const curr = document.getElementById('currPwd').value;
            const p1 = document.getElementById('newPwd1').value;
            const p2 = document.getElementById('newPwd2').value;

            if (p1 !== p2) return alert("New passwords do not match!");

            const formData = new FormData();
            formData.append('change_password', '1');
            formData.append('current_pwd', curr);
            formData.append('new_pwd', p1);

            fetch('runnerProfile.php', { method: 'POST', body: formData })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "OK") { alert("✅ Password Updated!"); location.reload(); }
                else alert("❌ " + data);
            });
        }

        function logout() {
            if (confirm("Log out of runner panel?")) window.location.href = "../logout.php?role=runner";
        }
    </script>

    <div class="whole-panel">
        <div class="cardPanel" id="personalPanel">
            <div class="cardPanelTitle">Personal Details</div>
            <div id="personalDetails" style="color:rgba(70, 66, 211, 0.764);"></div>
          
        </div>
    </div>

    <div id="passwordModal" class="cardPanel hidden" >
        <div class="cardPanelTitle">Change Password</div>
        <div class="edit-mode">
            <div class="form-group"><label>Current Password:</label><input type="password" id="currPwd" class="edit-input"></div>
            <div class="form-group"><label>New Password:</label><input type="password" id="newPwd1" class="edit-input"></div>
            <div class="form-group"><label>Confirm New Password:</label><input type="password" id="newPwd2" class="edit-input"></div>
            <div class="button-group">
                <button class="save-btn" onclick="submitPasswordChange()">Confirm Update</button>
                <button class="cancel-btn" onclick="closePasswordModal()">Cancel</button>
            </div>
        </div>
    </div>
</main>

<footer class="notifications hidden" id="notificationBar">System Ready.</footer>
</body>
</html>