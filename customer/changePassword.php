<?php
include '../config.php';
session_start();

// Check login
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

$username = $_SESSION['username'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassInput = $_POST['current_password'];
    $newPassInput     = $_POST['new_password'];
    $confirmPassInput = $_POST['confirm_password'];

    // get current password from DB
    $stmt = $pdo->prepare("SELECT password FROM members WHERE Username = ?");
    $stmt->execute([$username]);
		$dbPassword = $stmt->fetchColumn();

    // verify and update
    if ($currentPassInput === $dbPassword) {
			if ($newPassInput === $confirmPassInput) {
				$updateStmt = $pdo->prepare("UPDATE members SET Password = ? WHERE Username = ?");
				$updateStmt->execute([$newPassInput, $username]);	
				if ($updateStmt)
					echo "<script>alert('Password changed successfully!'); window.location.href='profile.php';</script>";
				else
					$message = "Error updating password.";
			} else
					$message = "New passwords do not match.";
    } else
        $message = "Current password is incorrect.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            width: 400px; margin: 60px auto; background: white;
            padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: left;
        }
        h2 { text-align: center; color: #3b2f2f; margin-bottom: 20px; }
        
        .info-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
        
        input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid #ccc;
            border-radius: 5px; box-sizing: border-box;
        }

        /* Button Group */
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn-group input, .btn-group a {
            flex: 1;
            text-align: center;
        }

        .alert { color: red; text-align: center; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Change Password</h2>

    <?php if ($message): ?>
        <p class="alert"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="info-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>

        <div class="info-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="info-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="btn-group">
            <input type="submit" value="Confirm">
            <a href="profile.php" class="navButton">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>