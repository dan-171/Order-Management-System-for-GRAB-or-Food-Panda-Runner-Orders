<?php
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$new_pass = $_POST['new_password'];
	$confirm_pass = $_POST['confirm_password'];

	if ($new_pass !== $confirm_pass) {
		echo "<script>alert('New passwords do not match!');</script>";
	} else {
		// check if username and email match
		$check = $pdo->prepare("SELECT ID FROM members WHERE Username = ? AND Email = ?");
		$check->execute([$username, $email]);
		$match = $check->fetch();

		if ($match) {
			// update password
			$update = $pdo->prepare("UPDATE members SET Password = ? WHERE Username = ? AND Email = ?");
			$update->execute([$new_pass, $username, $email]);
			if ($update->execute())
				echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
			else
				echo "<script>alert('Error updating password.');</script>";
		}else
			echo "<script>alert('Username and Email do not match our records.');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .forgotContainer {
            width: 380px; margin: 80px auto; padding: 30px;
            background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            border-radius: 8px; text-align: center;
        }
        label { display: block; text-align: left; margin-bottom: 5px; font-weight: bold; }
        input {
            width: 100%; padding: 10px; margin-bottom: 15px;
            border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box;
        }
        .forgotButtons { display: flex; gap: 10px; }
        .forgotButtons input, .forgotButtons a { flex: 1; padding: 10px; text-align: center; }
    </style>

    <script>
    function validateEmail() {
        var emailID = document.getElementById("email").value;
        var atpos = emailID.indexOf("@");
        var dotpos = emailID.lastIndexOf(".");

        if (atpos < 1 || ( dotpos - atpos < 2 )) {
            alert("Please enter a valid email address");
            document.getElementById("email").focus();
            return false;
        }
        return true;
    }
    </script>

</head>
<body>

<div class="forgotContainer">
    <h2>Reset Password</h2>

    <form name="myForm" method="POST" action="" onsubmit="return validateEmail()">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="text" id="email" name="email" required>

        <label>New Password</label>
        <input type="password" name="new_password" required>

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" required>

        <div class="forgotButtons">
            <input type="submit" value="Change Password">
            <a href="login.php" class="navButton">Back</a>
        </div>
    </form>
</div>

</body>
</html>