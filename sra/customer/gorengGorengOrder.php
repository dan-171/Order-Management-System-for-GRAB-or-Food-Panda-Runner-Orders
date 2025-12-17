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
        return "<input type='text' value='Sold Out' disabled style='background-color:#eee; color:#999; border:none; width:40px; font-size:10px;'>";
    } else {
        return "<input type='number' name='$id' value='0' min='0' max='10' class='qty-input' onfocus='checkLogin(this)'>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Goreng-Goreng Order</title>
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

<h2>Goreng-Goreng</h2>
<img src="menuPics/Slide11.jpeg" alt="Goreng-Goreng">

<form method="POST" action="result.php" onsubmit="return validateForm()">

<table class="bigTable">
    <tr>
        <td>
            <h3>Nasi Goreng</h3>
            
            <table class="table-9col">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Original</th><th>Quantity</th>
                        <th>Daging</th><th>Quantity</th>
                        <th>Udang</th><th>Quantity</th>
                        <th>Sotong</th><th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nasi Goreng Biasa</td>
                        <td>RM 7.50</td><td><?php echo renderInput('gqty1', $food_data); ?></td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty2', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty3', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty4', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Kampung</td>
                        <td>RM 8.00</td><td><?php echo renderInput('gqty5', $food_data); ?></td>
                        <td>RM 9.00</td><td><?php echo renderInput('gqty6', $food_data); ?></td>
                        <td>RM 11.00</td><td><?php echo renderInput('gqty7', $food_data); ?></td>
                        <td>RM 11.00</td><td><?php echo renderInput('gqty8', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Cina</td>
                        <td>RM 7.50</td><td><?php echo renderInput('gqty9', $food_data); ?></td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty10', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty11', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty12', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Ikan Masin</td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty13', $food_data); ?></td>
                        <td>RM 9.50</td><td><?php echo renderInput('gqty14', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty15', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty16', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Cili Padi</td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty17', $food_data); ?></td>
                        <td>RM 9.50</td><td><?php echo renderInput('gqty18', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty19', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty20', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Pattaya</td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty21', $food_data); ?></td>
                        <td>RM 9.50</td><td><?php echo renderInput('gqty22', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty23', $food_data); ?></td>
                        <td>RM 11.50</td><td><?php echo renderInput('gqty24', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Tom Yam</td>
                        <td>RM 9.00</td><td><?php echo renderInput('gqty25', $food_data); ?></td>
                        <td>RM 10.00</td><td><?php echo renderInput('gqty26', $food_data); ?></td>
                        <td>RM 12.00</td><td><?php echo renderInput('gqty27', $food_data); ?></td>
                        <td>RM 12.00</td><td><?php echo renderInput('gqty28', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Nasi Goreng Belacan</td>
                        <td>RM 12.00</td><td><?php echo renderInput('gqty29', $food_data); ?></td>
                        <td>RM 13.00</td><td><?php echo renderInput('gqty30', $food_data); ?></td>
                        <td>RM 15.00</td><td><?php echo renderInput('gqty31', $food_data); ?></td>
                        <td>RM 15.00</td><td><?php echo renderInput('gqty32', $food_data); ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Mee Goreng</h3>
            
            <table class="table-9col">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Original</th><th>Quantity</th>
                        <th>Daging</th><th>Quantity</th>
                        <th>Udang</th><th>Quantity</th>
                        <th>Sotong</th><th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Mee Goreng</td>
                        <td>RM 7.50</td><td><?php echo renderInput('gqty33', $food_data); ?></td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty34', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty35', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty36', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Mee Hoon Goreng Singapore</td>
                        <td>RM 7.50</td><td><?php echo renderInput('gqty37', $food_data); ?></td>
                        <td>RM 8.50</td><td><?php echo renderInput('gqty38', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty39', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('gqty40', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Char Kuey Teow</td>
                        <td>RM 8.00</td><td><?php echo renderInput('gqty41', $food_data); ?></td>
                        <td>RM 9.00</td><td><?php echo renderInput('gqty42', $food_data); ?></td>
                        <td>RM 11.00</td><td><?php echo renderInput('gqty43', $food_data); ?></td>
                        <td>RM 11.00</td><td><?php echo renderInput('gqty44', $food_data); ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<div class="buttonContainer">
    <a href="customerpage.php" class="navButton">« Back</a>
    <input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>

</form>

</body>
</html>