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
<title>Ala-Carte Menu 2</title>
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

<h2>Ala-Carte Menu (Part 2)</h2>
<img src="menuPics/Slide9.jpeg" alt="Ala-Carte Menu2">

<form method="POST" action="alaOrder2_2.php" onsubmit="return validateForm()">
<div>
    <table class="bigTable" border="0">
        <tr>
            <td>
                <h3>Sup Ala Thai</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Sup Ayam Ala Thai</td><td>RM 8.00</td><td><?php echo renderInput('ac2qty1', $food_data); ?></td></tr>
                    <tr><td>Sup Daging Ala Thai</td><td>RM 9.00</td><td><?php echo renderInput('ac2qty2', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <h3>Tomyam</h3>
                <table class="table-5col">
                    <tr>
                        <th>Items</th>
                        <th>Seekaw</th><th>Quantity</th>
                        <th>Sideng</th><th>Quantity</th>
                    </tr>
                    <tr>
                        <td>Tom Yam Ayam</td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty3', $food_data); ?></td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty4', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Daging</td>
                        <td>RM 9.00</td><td><?php echo renderInput('ac2qty5', $food_data); ?></td>
                        <td>RM 9.00</td><td><?php echo renderInput('ac2qty6', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Ayam + Daging</td>
                        <td>RM 12.00</td><td><?php echo renderInput('ac2qty7', $food_data); ?></td>
                        <td>RM 12.00</td><td><?php echo renderInput('ac2qty8', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Seafood</td>
                        <td>RM 13.00</td><td><?php echo renderInput('ac2qty9', $food_data); ?></td>
                        <td>RM 13.00</td><td><?php echo renderInput('ac2qty10', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Campur</td>
                        <td>RM 13.00</td><td><?php echo renderInput('ac2qty11', $food_data); ?></td>
                        <td>RM 13.00</td><td><?php echo renderInput('ac2qty12', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Sayur</td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty13', $food_data); ?></td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty14', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Tom Yam Cendawan</td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty15', $food_data); ?></td>
                        <td>RM 8.00</td><td><?php echo renderInput('ac2qty16', $food_data); ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <h3>Mee Kuah</h3>
                <table class="table-7col">
                    <tr>
                        <th>Items</th>
                        <th>Mee</th><th>Qty</th>
                        <th>Bihun</th><th>Qty</th>
                        <th>K.Teow</th><th>Qty</th>
                    </tr>
                    <tr>
                        <td>Bandung</td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty17', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty18', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty19', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Hong Kong</td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty20', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty21', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty22', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Hailam</td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty23', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty24', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty25', $food_data); ?></td>
                    </tr>
                    <tr>
                        <td>Kung Fu</td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty26', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty27', $food_data); ?></td>
                        <td>RM 10.50</td><td><?php echo renderInput('ac2qty28', $food_data); ?></td>
                    </tr>
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