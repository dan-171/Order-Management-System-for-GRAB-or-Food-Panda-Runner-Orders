<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = $_POST['name'];
	$username = $_POST['username'];
	$email = $_POST['email']; 
	$phone = $_POST['phone']; 
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];

  if ($password !== $confirm)
    echo "<script>alert('Passwords do not match!');</script>";
	else {
    $check = $pdo->prepare("SELECT Username, Email FROM members WHERE Username = ? OR Email = ?");
    $check->execute([$username, $email]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

		if ($row)
			if ($row['username'] === $username)
        echo "<script>alert('Username already taken!');</script>";
      else
       echo "<script>alert('An account has already been registered with this email address!');</script>";
		else{
			$fetchMaxMemberID = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(ID,2) AS UNSIGNED)) AS maxID FROM members");
  		$fetchMaxMemberID->execute();
  		$row = $fetchMaxMemberID->fetch(PDO::FETCH_ASSOC);
  		$maxID = $row['maxID'] ?? 0;
  		$newMemberID = "M" . str_pad($maxID + 1, 2, "0", STR_PAD_LEFT);

      $stmt = $pdo->prepare("INSERT INTO members (ID, Name, Username, Email, Password, Phone) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->execute([$newMemberID, $name, $username, $email, $password, $phone]);
			echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
		}
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Register</title>
	<link rel="stylesheet" href="style.css">
	<style>
		.registerContainer {
			width: 380px; margin: 70px auto; padding: 30px;
			background-color: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
			border-radius: 8px; text-align: center;
		}
		.registerContainer label { display: block; text-align: left; margin-bottom: 5px; font-weight: bold; }
		.registerContainer input {
			width: 100%; padding: 10px; margin-bottom: 15px;
			border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;
		}
		.registerButtons { display: flex; justify-content: space-between; gap: 10px; }
		.registerButtons input, .registerButtons a { flex: 1; padding: 10px; text-align: center; }
	</style>

	<script>
	function validateEmail() {
		var emailID = document.getElementById("email").value;
		var atpos = emailID.indexOf("@");
		var dotpos = emailID.lastIndexOf(".");

		if (atpos < 1 || ( dotpos - atpos < 2 )) {
			alert("Please enter correct email ID");
			document.getElementById("email").focus();
			return false;
		}
		return true;
	}
	</script>
</head>
<body>
<div class="registerContainer">
  <h2>Register Customer</h2>
	<form name="myForm" method="POST" action="" onsubmit="return validateEmail()">
		<label for="name">Name</label>
		<input type="text" id="name" name="name" required>

		<label for="username">Username</label>
		<input type="text" id="username" name="username" required>

		<label for="email">Email</label>
		<input type="text" id="email" name="email" required>

		<label for="phone">Phone Number</label>
		<input type="text" 
					id="phone" 
					name="phone" 
					required 
					placeholder="e.g. 0123456789" 
					pattern="[0-9]+" 
					inputmode="numeric"
					oninput="this.value = this.value.replace(/[^0-9]/g, '')"
					title="Please enter numbers only">

		<label for="password">Password</label>
		<input type="password" id="password" name="password" required>

		<label for="confirm">Confirm Password</label>
		<input type="password" id="confirm" name="confirm" required>

		<div class="registerButtons">
			<input type="submit" value="Register">
			<a href="login.php" class="navButton">Back</a>
		</div>
	</form>
</div>
</body>
</html>