<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email']; 
    $phone_number = $_POST['phone_number']; 
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // CHECK IF USERNAME OR EMAIL EXISTS
        $check = $conn->prepare("SELECT username, email FROM customer WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['username'] === $username) {
                echo "<script>alert('Username already taken!');</script>";
            } else {
                echo "<script>alert('Email already registered!');</script>";
            }
        } else {
            // Insert New User
            $stmt = $conn->prepare("INSERT INTO customer (full_name, username, email, password, phone_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fullname, $username, $email, $password, $phone_number);

            if ($stmt->execute()) {
                echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
            $stmt->close();
        }
        $check->close();
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
        <label for="fullname">Full Name</label>
        <input type="text" id="fullname" name="fullname" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>

        <label for="phone_number">Phone Number</label>
        <input type="text" 
               id="phone_number" 
               name="phone_number" 
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