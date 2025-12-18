<?php
include 'db_connect.php';
session_start();

// 1. DETERMINE LOGIN STATUS
$isLoggedIn = isset($_SESSION['username_unique']) ? true : false;

// Fetch all food availability
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
<title>Lunch Order</title>
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

    // VALIDATION FUNCTION
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
            if(input.value) {
                total += parseInt(input.value);
            }
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

<h2>Lunch Set</h2>
<img src="menuPics/Slide6.jpeg" alt="Lunch">

<form method="POST" action="result.php" onsubmit="return validateForm()">

<div>
    <table class="bigTable" border="0">
        <tr>
            <td>
                <h3>Set Nasi & Lauk</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Nasi Bawal Goreng Berlado</td><td>RM 9.00</td><td><?php echo renderInput('lqty1', $food_data); ?></td></tr>
                    <tr><td>Nasi Siakap Goreng Berlado</td><td>RM 15.00</td><td><?php echo renderInput('lqty2', $food_data); ?></td></tr>
                    <tr><td>Nasi Keli Goreng Berlado</td><td>RM 10.90</td><td><?php echo renderInput('lqty3', $food_data); ?></td></tr>
                    <tr><td>Nasi Ayam Goreng Berlado</td><td>RM 8.50</td><td><?php echo renderInput('lqty4', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Masakan Panas</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Bubur Ayam</td><td>RM 6.50</td><td><?php echo renderInput('lqty5', $food_data); ?></td></tr>
                    <tr><td>Bubur Nasi</td><td>RM 7.50</td><td><?php echo renderInput('lqty6', $food_data); ?></td></tr>
                    <tr><td>Bakso (Mee)</td><td>RM 7.50</td><td><?php echo renderInput('lqty7', $food_data); ?></td></tr>
                    <tr><td>Bakso (Mee Hoon)</td><td>RM 7.50</td><td><?php echo renderInput('lqty8', $food_data); ?></td></tr>
                    <tr><td>Laksa (Johor)</td><td>RM 8.00</td><td><?php echo renderInput('lqty9', $food_data); ?></td></tr>
                    <tr><td>Laksa (Penang)</td><td>RM 7.50</td><td><?php echo renderInput('lqty10', $food_data); ?></td></tr>
                    <tr><td>Soto (Mee)</td><td>RM 8.00</td><td><?php echo renderInput('lqty11', $food_data); ?></td></tr>
                    <tr><td>Soto (Mee Hoon)</td><td>RM 8.00</td><td><?php echo renderInput('lqty12', $food_data); ?></td></tr>
                    <tr><td>Nasi Lemak Basmathi (Telur)</td><td>RM 6.00</td><td><?php echo renderInput('lqty13', $food_data); ?></td></tr>
                    <tr><td>Nasi Lemak Basmathi (Ayam)</td><td>RM 9.00</td><td><?php echo renderInput('lqty14', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back</a>
    <input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>

</form>

</body>
</html>