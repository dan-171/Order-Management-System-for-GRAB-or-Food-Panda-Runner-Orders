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
<title>Ikan Order</title>
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

<h2>Menu Ikan</h2>
<img src="menuPics/Slide7.jpeg" alt="Ikan">

<form method="POST" action="result.php" onsubmit="return validateForm()">

<div>
    <table class="bigTable" border="0">
        <tr>
            <td>
                <h3>Ikan Siakap</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Tiga Rasa</td><td>RM 40.00</td><td><?php echo renderInput('iqty1', $food_data); ?></td></tr>
                    <tr><td>Masam Manis</td><td>RM 40.00</td><td><?php echo renderInput('iqty2', $food_data); ?></td></tr>
                    <tr><td>Steam Lemon</td><td>RM 40.00</td><td><?php echo renderInput('iqty3', $food_data); ?></td></tr>
                    <tr><td>Laprik</td><td>RM 40.00</td><td><?php echo renderInput('iqty4', $food_data); ?></td></tr>
                    <tr><td>Goreng Kunyit</td><td>RM 40.00</td><td><?php echo renderInput('iqty5', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
        
        <tr>
            <td>
                <h3>Bakar-Bakar</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Siakap Bakar</td><td>RM 40.00</td><td><?php echo renderInput('iqty6', $food_data); ?></td></tr>
                    <tr><td>Caru Bakar</td><td>RM 15.00</td><td><?php echo renderInput('iqty7', $food_data); ?></td></tr>
                    <tr><td>Kerang Bakar</td><td>RM 15.00</td><td><?php echo renderInput('iqty8', $food_data); ?></td></tr>
                    <tr><td>Sotong Bakar</td><td>RM 15.00</td><td><?php echo renderInput('iqty9', $food_data); ?></td></tr>
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