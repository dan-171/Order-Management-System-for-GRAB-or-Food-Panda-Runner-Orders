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
<title>Western Food Order</title>
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

<h2>Western Food</h2>
<img src="menuPics/Slide10.jpeg" alt="Western Food">

<form method="POST" action="westernFoodOrder2.php" onsubmit="return validateForm()">
<div>
<table class="bigTable" border="0">
    <tr>
        <td>
            <h3>Fried & Grill</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Chicken Chop (Fried)</td><td>RM 18.50</td><td><?php echo renderInput('wqty1', $food_data); ?></td></tr>
                <tr><td>Chicken Chop (Grill)</td><td>RM 18.50</td><td><?php echo renderInput('wqty2', $food_data); ?></td></tr>
                <tr><td>Fish N Chips</td><td>RM 16.50</td><td><?php echo renderInput('wqty3', $food_data); ?></td></tr>
                <tr><td>Lamb Chop</td><td>RM 30.90</td><td><?php echo renderInput('wqty4', $food_data); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Spaghetti</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Aglio Olio (Seafood)</td><td>RM 17.00</td><td><?php echo renderInput('wqty5', $food_data); ?></td></tr>
                <tr><td>Aglio Olio (Beef Bacon)</td><td>RM 15.00</td><td><?php echo renderInput('wqty6', $food_data); ?></td></tr>
                <tr><td>Aglio Olio (Chicken)</td><td>RM 13.00</td><td><?php echo renderInput('wqty7', $food_data); ?></td></tr>
                <tr><td>Carbonara (Seafood)</td><td>RM 18.00</td><td><?php echo renderInput('wqty8', $food_data); ?></td></tr>
                <tr><td>Carbonara (Beef Bacon)</td><td>RM 16.00</td><td><?php echo renderInput('wqty9', $food_data); ?></td></tr>
                <tr><td>Carbonara (Chicken)</td><td>RM 14.00</td><td><?php echo renderInput('wqty10', $food_data); ?></td></tr>
                <tr><td>Bolognesse</td><td>RM 15.00</td><td><?php echo renderInput('wqty11', $food_data); ?></td></tr>
                <tr><td>Mac & Cheese</td><td>RM 14.00</td><td><?php echo renderInput('wqty12', $food_data); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Burger</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Smash Beef (Single)</td><td>RM 8.00</td><td><?php echo renderInput('wqty13', $food_data); ?></td></tr>
                <tr><td>Smash Beef (Double)</td><td>RM 10.00</td><td><?php echo renderInput('wqty14', $food_data); ?></td></tr>
                <tr><td>Crispy Chicken Burger</td><td>RM 7.50</td><td><?php echo renderInput('wqty15', $food_data); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Sides</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Fries</td><td>RM 7.50</td><td><?php echo renderInput('wqty16', $food_data); ?></td></tr>
                <tr><td>Nugget 8pcs</td><td>RM 8.00</td><td><?php echo renderInput('wqty17', $food_data); ?></td></tr>
                <tr><td>Cheesy Wedges</td><td>RM 8.50</td><td><?php echo renderInput('wqty18', $food_data); ?></td></tr>
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