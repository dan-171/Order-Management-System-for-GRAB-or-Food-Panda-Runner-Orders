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
<title>Drinks Order</title>
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

<h2>Drinks Menu</h2>
<img src="menuPics/Slide12.jpeg" alt="Drinks">

<form method="POST" action="result.php" onsubmit="return validateForm()">

<table class="bigTable">
    <tr>
        <td>
            <h3>Non-Coffee</h3>
            <table class="table-5col">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Hot</th><th>Quantity</th>
                        <th>Cold</th><th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>TEH O’</td>
                        <td>RM 2.30</td><td><?php echo renderInput('dqty1', $food_data); ?></td>
                        <td>RM 2.50</td><td><?php echo renderInput('dqty2', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>TEH TARIK</td>
                        <td>RM 2.50</td><td><?php echo renderInput('dqty3', $food_data); ?></td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty4', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>TEH HALIA</td>
                        <td>RM 3.50</td><td><?php echo renderInput('dqty5', $food_data); ?></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty6', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>TEH SARBAT</td>
                        <td>RM 3.50</td><td><?php echo renderInput('dqty7', $food_data); ?></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty8', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP</td>
                        <td>RM 2.00</td><td><?php echo renderInput('dqty9', $food_data); ?></td>
                        <td>RM 2.50</td><td><?php echo renderInput('dqty10', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP SELASIH</td>
                        <td>RM 2.50</td><td><?php echo renderInput('dqty11', $food_data); ?></td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty12', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP LIMAU</td>
                        <td>RM 2.70</td><td><?php echo renderInput('dqty13', $food_data); ?></td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty14', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP LAICI</td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty15', $food_data); ?></td>
                        <td>RM 5.50</td><td><?php echo renderInput('dqty16', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP BANDUNG</td>
                        <td>-</td><td></td>
                        <td>RM 3.50</td><td><?php echo renderInput('dqty17', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP BANDUNG CINCAU</td>
                        <td>-</td><td></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty18', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>SIRAP BANDUNG SODA</td>
                        <td>-</td><td></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty19', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>LIMAU</td>
                        <td>RM 2.70</td><td><?php echo renderInput('dqty20', $food_data); ?></td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty21', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>ASAM BOY</td>
                        <td>RM 2.70</td><td><?php echo renderInput('dqty22', $food_data); ?></td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty23', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>EXTRA JOSS SUSU (ANGGUR)</td>
                        <td>-</td><td></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty24', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>EXTRA JOSS SUSU (MANGA)</td>
                        <td>-</td><td></td>
                        <td>RM 4.00</td><td><?php echo renderInput('dqty25', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>VICO</td>
                        <td>RM 3.00</td><td><?php echo renderInput('dqty26', $food_data); ?></td>
                        <td>RM 3.50</td><td><?php echo renderInput('dqty27', $food_data); ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Jus</h3>
            <table class="table-5col">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Hot</th><th>Quantity</th>
                        <th>Cold</th><th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ORANGE</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty28', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty29', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>APPLE</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty30', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty31', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>WATERMELON</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty32', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty33', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>LYCHEE</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty34', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty35', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>LEMON</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty36', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty37', $food_data); ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
	<tr>
        <td>
            <h3>Cold Dessert</h3>
            <table class="menuTable">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CIKONG</td>
                        <td>RM 6.00</td>
                        <td><?php echo renderInput('dqty38', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>AIS JELLY LIMAU</td>
                        <td>RM 6.00</td>
                        <td><?php echo renderInput('dqty39', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>CENDOL</td>
                        <td>RM 6.00</td>
                        <td><?php echo renderInput('dqty40', $food_data); ?></td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Coffee</h3>
            <table class="table-5col">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Hot</th><th>Quantity</th>
                        <th>Cold</th><th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>INDO CAFE O’</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty41', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty42', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>INDO CAFE SUSU</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty43', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty44', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>KOPI TENGGEK</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty45', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty46', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>KOPI SPECIAL</td>
                        <td>RM 4.70</td><td><?php echo renderInput('dqty47', $food_data); ?></td>
                        <td>RM 5.00</td><td><?php echo renderInput('dqty48', $food_data); ?></td>
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