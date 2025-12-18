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
        // Added class 'qty-input' for easy selection in JS
        return "<input type='number' name='$id' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Signature Dish Order</title>
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

    // NEW VALIDATION FUNCTION
    function validateForm(event) {
        var userIsLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        
        // 1. Check Login First
        if (!userIsLoggedIn) {
            alert("You must log in to make an order.");
            window.location.href = "login.php";
            return false;
        }

        // 2. Check at least one item selected
        let inputs = document.querySelectorAll('.qty-input');
        let total = 0;
        
        inputs.forEach(input => {
            if(input.value) {
                total += parseInt(input.value);
            }
        });

        if (total === 0) {
            alert("Please select at least ONE item before continuing.");
            return false; // Stop submission
        }
        
        return true; // Allow submission
    }
</script>

</head>
<body>

<h2>Signature Dish</h2>
<img src="menuPics/Slide3.jpeg" alt="Signature dish">

<form method="POST" action="signatureDishOrder2.php" onsubmit="return validateForm()">
<div>
    <table class="bigTable" border="0">
        <tr>
            <td>
                <h3>Sup ZZ</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Sup Gearbox Kambing</td><td>RM 19.00</td><td><?php echo renderInput('sqty1', $food_data); ?></td></tr>
                    <tr><td>Sup Kambing</td><td>RM 20.00</td><td><?php echo renderInput('sqty2', $food_data); ?></td></tr>
                    <tr><td>Sup Daging</td><td>RM 8.00</td><td><?php echo renderInput('sqty3', $food_data); ?></td></tr>
                    <tr><td>Sup Ayam</td><td>RM 7.00</td><td><?php echo renderInput('sqty4', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h3>Mee Rebus ZZ</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Mee Rebus Gearbox Kambing</td><td>RM 20.00</td><td><?php echo renderInput('sqty5', $food_data); ?></td></tr>
                    <tr><td>Mee Rebus Daging</td><td>RM 9.50</td><td><?php echo renderInput('sqty6', $food_data); ?></td></tr>
                    <tr><td>Mee Rebus Ayam</td><td>RM 9.00</td><td><?php echo renderInput('sqty7', $food_data); ?></td></tr>
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