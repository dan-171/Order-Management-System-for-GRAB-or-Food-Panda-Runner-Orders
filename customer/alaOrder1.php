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
<title>Ala-Carte Menu</title>
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

<h2>Ala-Carte Menu</h2>
<img src="menuPics/Slide8.jpeg" alt="Ala-Carte Menu">

<form method="POST" action="alaOrder1_2.php" onsubmit="return validateForm()">
<div>
<table class="bigTable" border="0">
    <tr>
        <td>
            <h3>Sayur</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Kailan (Biasa)</td><td>RM 7.00</td><td><?php echo renderInput('acqty1', $food_data); ?></td></tr>
                <tr><td>Kailan (Ikan Masin)</td><td>RM 7.00</td><td><?php echo renderInput('acqty2', $food_data); ?></td></tr>
                <tr><td>Kangkung (Biasa)</td><td>RM 7.00</td><td><?php echo renderInput('acqty3', $food_data); ?></td></tr>
                <tr><td>Kangkung (Belacan)</td><td>RM 7.00</td><td><?php echo renderInput('acqty4', $food_data); ?></td></tr>
                <tr><td>Taugeh (Biasa)</td><td>RM 7.00</td><td><?php echo renderInput('acqty5', $food_data); ?></td></tr>
                <tr><td>Taugeh (Ikan Masin)</td><td>RM 7.00</td><td><?php echo renderInput('acqty6', $food_data); ?></td></tr>
                <tr><td>Sawi (Biasa)</td><td>RM 7.00</td><td><?php echo renderInput('acqty7', $food_data); ?></td></tr>
                <tr><td>Sawi (Ikan Masin)</td><td>RM 7.00</td><td><?php echo renderInput('acqty8', $food_data); ?></td></tr>
                <tr><td>Cendawan Goreng Biasa</td><td>RM 7.00</td><td><?php echo renderInput('acqty9', $food_data); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Aneka Lauk Thai</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Ayam Black Pepper</td><td>RM 7.50</td><td><?php echo renderInput('acqty10', $food_data); ?></td></tr>
                <tr><td>Daging Black Pepper</td><td>RM 8.50</td><td><?php echo renderInput('acqty11', $food_data); ?></td></tr>
                <tr><td>Sotong Black Pepper</td><td>RM 9.50</td><td><?php echo renderInput('acqty12', $food_data); ?></td></tr>
                <tr><td>Ayam Sambal</td><td>RM 7.50</td><td><?php echo renderInput('acqty13', $food_data); ?></td></tr>
                <tr><td>Daging Sambal</td><td>RM 8.50</td><td><?php echo renderInput('acqty14', $food_data); ?></td></tr>
                <tr><td>Sotong Sambal</td><td>RM 9.50</td><td><?php echo renderInput('acqty15', $food_data); ?></td></tr>
                <tr><td>Ayam Merah</td><td>RM 7.50</td><td><?php echo renderInput('acqty16', $food_data); ?></td></tr>
                <tr><td>Daging Merah</td><td>RM 8.50</td><td><?php echo renderInput('acqty17', $food_data); ?></td></tr>
                <tr><td>Sotong Merah</td><td>RM 9.50</td><td><?php echo renderInput('acqty18', $food_data); ?></td></tr>
                <tr><td>Ayam Paprik</td><td>RM 7.50</td><td><?php echo renderInput('acqty19', $food_data); ?></td></tr>
                <tr><td>Daging Paprik</td><td>RM 8.50</td><td><?php echo renderInput('acqty20', $food_data); ?></td></tr>
                <tr><td>Sotong Paprik</td><td>RM 9.50</td><td><?php echo renderInput('acqty21', $food_data); ?></td></tr>
                <tr><td>Ayam Pha Khra Phao</td><td>RM 8.00</td><td><?php echo renderInput('acqty22', $food_data); ?></td></tr>
                <tr><td>Daging Pha Khra Phao</td><td>RM 9.00</td><td><?php echo renderInput('acqty23', $food_data); ?></td></tr>
                <tr><td>Ayam Kunyit</td><td>RM 7.50</td><td><?php echo renderInput('acqty24', $food_data); ?></td></tr>
                <tr><td>Daging Kunyit</td><td>RM 9.50</td><td><?php echo renderInput('acqty25', $food_data); ?></td></tr>
                <tr><td>Sotong Kunyit</td><td>RM 9.50</td><td><?php echo renderInput('acqty26', $food_data); ?></td></tr>
                <tr><td>Udang Kunyit</td><td>RM 9.50</td><td><?php echo renderInput('acqty27', $food_data); ?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <h3>Goreng Tepung</h3>
            <table class="menuTable">
                <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                <tr><td>Sotong</td><td>RM 10.50</td><td><?php echo renderInput('acqty28', $food_data); ?></td></tr>
                <tr><td>Udang</td><td>RM 10.50</td><td><?php echo renderInput('acqty29', $food_data); ?></td></tr>
                <tr><td>Cendawan</td><td>RM 7.00</td><td><?php echo renderInput('acqty30', $food_data); ?></td></tr>
                <tr><td>Inokki</td><td>RM 7.00</td><td><?php echo renderInput('acqty31', $food_data); ?></td></tr>
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