<?php
include '../../config.php';
session_start();

// check if user is logged in
if (!isset($_SESSION['username'])) {
	echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
	exit();
}

$username = $_SESSION['username'];
$message = "";

// fetch user data
$stmt = $pdo->prepare("SELECT Name, Username, Email, Address, Phone FROM members WHERE Username = ?");
$stmt->execute([$username]);
$member = $stmt->fetch();

// on submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// update member details
	if (isset($_POST['update_details'])) {
		$newAddress  = $_POST['address'];
		$newPhone    = $_POST['phone_number'];
		$newUsername = trim($_POST['username']); 
		$error = false;

		// Check if username is different from the current one
		if ($newUsername !== $username) {
			$usernameCheck = $pdo->prepare("SELECT ID FROM members WHERE Username = ?");
			$usernameCheck->execute([$newUsername]);
			$usernameExists = $usernameCheck->fetch();
			if ($usernameExxis) {
				$message = "Username already taken! Please choose another.";
				$error = true;
			}
		}

		if (!$error) {
			try {
				$update = $pdo->prepare("UPDATE members SET Username = ?, Phone = ?, Address = ? WHERE Username = ?");
				$updateSuccess = $update->execute([$newUsername, $newPhone, $newAddress, $username]);
			
				if ($updateSuccess) {
					$message = "Profile details updated!";
					$_SESSION['username'] = $newUsername;
					$username = $newUsername; 
					
					$member['Username'] = $newUsername;
					$member['Address'] = $newAddress;
					$member['Phone'] = $newPhone;
				} 
			}catch (PDOException $e) {
				$message = "Error updating profile: " . $e->getMessage();
			}
		}
	}

  // delete account
	if (isset($_POST['delete_account'])) {
		$delStmt = $pdo->prepare("DELETE FROM members WHERE Username = ?");
		$delSucess = $delStmt->execute([$username]);
		if ($delSucess) {
			session_destroy();
			echo "<script>
							alert('Account deleted successfully.');
							sessionStorage.removeItem('isLoggedIn');
							window.location.href = 'login.php';
						</script>";
			exit();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>My Profile</title>
	<link rel="stylesheet" href="style.css">
	<style>
		.profile-container {
				width: 500px; margin: 40px auto; background: white;
				padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
				text-align: left;
		}
		h2 { text-align: center; color: #3b2f2f; }
		
		.info-group { margin-bottom: 15px; }
		label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
		
		input[type="text"], input[type="email"], textarea {
				width: 100%; padding: 10px; border: 1px solid #ccc;
				border-radius: 5px; box-sizing: border-box;
		}
			
		.readonly-field { background-color: #e9e9e9; cursor: not-allowed; color: #555; }

		.action-buttons { display: flex; gap: 10px; margin-top: 20px; }
		.action-buttons input, .action-buttons a { flex: 1; text-align: center; }

		.delete-btn { background-color: #d9534f !important; width: 100%; margin-top: 20px; }
		.delete-btn:hover { background-color: #c9302c !important; }

		.alert { color: red; text-align: center; font-weight: bold; margin-bottom: 15px; }
		.success { color: green; text-align: center; font-weight: bold; margin-bottom: 15px; }
		
		.back-link { display: block; text-align: center; margin-top: 20px; color: #8b0000; text-decoration: none; font-weight: bold; }
	</style>
</head>
<body>

<div class="profile-container">
	<h2>My Profile</h2>
	
	<?php if ($message): ?>
		<p class="<?php echo strpos($message, 'updated') !== false ? 'success' : 'alert'; ?>">
			<?php echo $message; ?>
		</p>
	<?php endif; ?>

	<form method="POST" action="">
		<div class="info-group">
			<label>Full Name</label>
			<input type="text" value="<?php echo htmlspecialchars($member['Name']); ?>" class="readonly-field" readonly>
		</div>

		<div class="info-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo htmlspecialchars($member['Username']); ?>" required>
		</div>

		<div class="info-group">
			<label>Email</label>
			<input type="email" value="<?php echo htmlspecialchars($member['Email']); ?>" class="readonly-field" readonly>
		</div>

		<div class="info-group">
			<label>Phone Number</label>
			<input type="text" 
							name="phone_number" 
							value="<?php echo htmlspecialchars($member['Phone']); ?>" 
							required
							pattern="[0-9]+" 
							inputmode="numeric"
							oninput="this.value = this.value.replace(/[^0-9]/g, '')"
							title="Please enter numbers only">
		</div>

		<div class="info-group">
			<label>Address</label>
			<textarea name="address" rows="3" placeholder="Enter your delivery address"><?php echo htmlspecialchars($member['Address']); ?></textarea>
		</div>

		<div class="action-buttons">
			<input type="submit" name="update_details" value="Update Details">
			<a href="changePassword.php" class="navButton" style="margin: 15px 0;">Change Password</a>
		</div>
	</form>

	<form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
		<input type="submit" name="delete_account" value="Delete Account" class="delete-btn">
	</form>

	<a href="customerpage.php" class="back-link">« Back to Menu</a>
</div>

</body>
</html>