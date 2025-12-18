<?php
include 'db_connect.php';
session_start();

$isLoggedIn = isset($_SESSION['username_unique']) ? true : false;

$food_data = [];
$sql = "SELECT food_code, is_available FROM food";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $food_data[$row['food_code']] = $row['is_available'];
}

function renderInput($id, $data) {
    if (isset($data[$id]) && $data[$id] == 0) {
        return "<input type='text' value='Sold Out' disabled style='background-color:#ccc; color:red;'>";
    } else {
        return "<input type='number' name='$id' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Breakfast Order</title>
<link rel="stylesheet" href="style.css">

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

<h2>Breakfast Set</h2>
<img src="menuPics/Slide4.jpeg" alt="Breakfast">

<form method="POST" action="breakfastOrder2.php" onsubmit="return validateForm()">
<div>
<table class="bigTable" border="0">
    <tr>
    <td>
    <h3>Masakan Panas</h3>
    <table class="menuTable">
        <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
        <tr><td>Lontong Kuah</td><td>RM 7.50</td><td><?php echo renderInput('bqty1', $food_data); ?></td></tr>
        <tr><td>Lontong Kering (Ayam)</td><td>RM 9.00</td><td><?php echo renderInput('bqty2', $food_data); ?></td></tr>
        <tr><td>Lontong Kering (Daging)</td><td>RM 9.50</td><td><?php echo renderInput('bqty3', $food_data); ?></td></tr>
        <tr><td>Nasi Lemak Basmathi (Telur)</td><td>RM 6.00</td><td><?php echo renderInput('bqty4', $food_data); ?></td></tr>
        <tr><td>Nasi Lemak Basmathi (Ayam)</td><td>RM 9.00</td><td><?php echo renderInput('bqty5', $food_data); ?></td></tr>
        <tr><td>Nasi Lemak Rendang (Ayam)</td><td>RM 8.50</td><td><?php echo renderInput('bqty6', $food_data); ?></td></tr>
        <tr><td>Nasi Lemak Rendang (Daging)</td><td>RM 9.50</td><td><?php echo renderInput('bqty7', $food_data); ?></td></tr>
        <tr><td>Nasi Ayam Basmathi</td><td>RM 12.00</td><td><?php echo renderInput('bqty8', $food_data); ?></td></tr>
        <tr><td>Nasi Ambang</td><td>RM 9.50</td><td><?php echo renderInput('bqty9', $food_data); ?></td></tr>
        <tr><td>Bubur Nasi</td><td>RM 7.50</td><td><?php echo renderInput('bqty10', $food_data); ?></td></tr>
        <tr><td>Bubur Ayam</td><td>RM 7.00</td><td><?php echo renderInput('bqty11', $food_data); ?></td></tr>
        <tr><td>Laksa (Johor)</td><td>RM 8.00</td><td><?php echo renderInput('bqty12', $food_data); ?></td></tr>
        <tr><td>Laksa (Penang)</td><td>RM 7.50</td><td><?php echo renderInput('bqty13', $food_data); ?></td></tr>
        <tr><td>Bakso (Mee)</td><td>RM 7.50</td><td><?php echo renderInput('bqty14', $food_data); ?></td></tr>
        <tr><td>Bakso (Mee Hoon)</td><td>RM 7.50</td><td><?php echo renderInput('bqty15', $food_data); ?></td></tr>
        <tr><td>Bakso (Nasi)</td><td>RM 7.50</td><td><?php echo renderInput('bqty16', $food_data); ?></td></tr>
        <tr><td>Soto (Mee)</td><td>RM 8.00</td><td><?php echo renderInput('bqty17', $food_data); ?></td></tr>
        <tr><td>Soto (Mee Hoon)</td><td>RM 8.00</td><td><?php echo renderInput('bqty18', $food_data); ?></td></tr>
        <tr><td>Soto (Nasi)</td><td>RM 8.00</td><td><?php echo renderInput('bqty19', $food_data); ?></td></tr>
    </table>
    </td>
    </tr>

    <tr>
    <td>
    <h3>Roti Bakar</h3>
    <table class="menuTable">
        <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
        <tr><td>Roti Bakar</td><td>RM 2.50</td><td><?php echo renderInput('bqty20', $food_data); ?></td></tr>
        <tr><td>Roti Kaya</td><td>RM 3.50</td><td><?php echo renderInput('bqty21', $food_data); ?></td></tr>
        <tr><td>Roti Garlic</td><td>RM 3.50</td><td><?php echo renderInput('bqty22', $food_data); ?></td></tr>
    </table>
    </td>
    </tr>
</table>
</div>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back</a>
    <input type="submit" name="submit_step1" value="Next »" class="navButton">
</div>
</form>

</body>
</html>