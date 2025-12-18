<?php
include 'db_connect.php';
session_start();

// Set Timezone to Malaysia
date_default_timezone_set("Asia/Kuala_Lumpur");

// 1. DETERMINE LOGIN STATUS
$isLoggedIn = isset($_SESSION['username_unique']) ? true : false;

// 2. DETERMINE TIME AVAILABILITY
// Available: 07:00-11:00 AND 18:00-23:00
$currentHour = (int)date('G'); // 0 - 23 format
$isTimeValid = ($currentHour >= 7 && $currentHour < 11) || ($currentHour >= 18 && $currentHour < 23);

// Fetch all food availability
$food_data = [];
$sql = "SELECT food_code, is_available FROM food";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $food_data[$row['food_code']] = $row['is_available'];
}

function renderInput($id, $data, $isTimeValid) {
    // Priority 1: Check Time
    if (!$isTimeValid) {
        return "<input type='text' value='Closed' disabled style='background-color:#eee; color:#555; text-align:center;'>";
    }
    // Priority 2: Check Stock
    if (isset($data[$id]) && $data[$id] == 0) {
        return "<input type='text' value='Sold Out' disabled style='background-color:#ccc; color:red; text-align:center;'>";
    } 
    // Available
    else {
        return "<input type='number' name='$id' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Roti Canai Order</title>
<link rel="stylesheet" href="style.css">
<style>
    .time-alert {
        background-color: #ffdddd;
        color: #d8000c;
        border: 1px solid #d8000c;
        padding: 15px;
        margin: 20px auto;
        width: 80%;
        text-align: center;
        border-radius: 5px;
        font-weight: bold;
    }
    .time-success {
        background-color: #ddffdd;
        color: #4CAF50;
        border: 1px solid #4CAF50;
        padding: 10px;
        margin: 10px auto;
        width: 80%;
        text-align: center;
        border-radius: 5px;
        font-size: 14px;
    }
</style>

<script>
    function checkLogin(inputElement) {
        var userIsLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        if (!userIsLoggedIn) {
            inputElement.blur(); 
            alert("You must log in to make an order.");
            window.location.href = "login.php";
        }
    }

    function validateForm(event) {
        var userIsLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        
        if (!userIsLoggedIn) {
            alert("You must log in to make an order.");
            window.location.href = "login.php";
            return false;
        }

        let inputs = document.querySelectorAll('.qty-input');
        let total = 0;
        
        inputs.forEach(input => {
            if(input.value) total += parseInt(input.value);
        });

        if (total === 0) {
            alert("Please select at least ONE item before continuing.");
            return false; 
        }
        
        return true; 
    }
</script>

</head>
<body>

<h2>Roti Canai</h2>
<img src="menuPics/Slide5.jpeg" alt="Roti Canai">

<?php if (!$isTimeValid): ?>
    <div class="time-alert">
        ⚠️ Roti Canai is currently UNAVAILABLE.<br>
        Available hours: 7:00 AM - 11:00 AM & 6:00 PM - 11:00 PM.
    </div>
<?php else: ?>
    <div class="time-success">
        ✅ Currently Serving (Available: 7am-11am & 6pm-11pm)
    </div>
<?php endif; ?>

<form method="POST" action="result.php" onsubmit="return validateForm()">

<div>
    <table class="bigTable" border="0">
        <tr>
            <td>
                <h3>Roti Canai Menu</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Roti Kosong</td><td>RM 1.50</td><td><?php echo renderInput('rqty1', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Kosong Bawang</td><td>RM 2.00</td><td><?php echo renderInput('rqty2', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Tampal</td><td>RM 2.80</td><td><?php echo renderInput('rqty3', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Telur</td><td>RM 2.80</td><td><?php echo renderInput('rqty4', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Telur Bawang</td><td>RM 3.50</td><td><?php echo renderInput('rqty5', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Telur Double Jantan</td><td>RM 5.50</td><td><?php echo renderInput('rqty6', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Pisang</td><td>RM 4.50</td><td><?php echo renderInput('rqty7', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Sardin</td><td>RM 6.00</td><td><?php echo renderInput('rqty8', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Bom</td><td>RM 2.50</td><td><?php echo renderInput('rqty9', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Planta</td><td>RM 3.00</td><td><?php echo renderInput('rqty10', $food_data, $isTimeValid); ?></td></tr>
                    <tr><td>Roti Sarang Burung Daging</td><td>RM 8.00</td><td><?php echo renderInput('rqty11', $food_data, $isTimeValid); ?></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back</a>
    
    <?php if ($isTimeValid): ?>
        <input type="submit" name="submit_step2" value="Next »" class="navButton">
    <?php else: ?>
        <span class="navButton" style="background-color:#ccc; cursor:not-allowed;">Next »</span>
    <?php endif; ?>
</div>

</form>

</body>
</html>