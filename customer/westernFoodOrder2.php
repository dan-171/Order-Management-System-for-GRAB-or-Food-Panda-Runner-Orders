<?php
include 'db_connect.php';
session_start();

// ==========================================
// ROUTER LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step1'])) {
    
    // 1. Save data to temp_order
    $_SESSION['temp_order'] = [];
    foreach ($_POST as $key => $val) {
        if ($val > 0 && strpos($key, 'wqty') === 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }

    // 2. Check if "Burger" items were ordered (wqty13, wqty14, wqty15)
    $hasBurger = false;
    $burgerItems = ['wqty13', 'wqty14', 'wqty15'];
    
    foreach ($burgerItems as $item) {
        if (isset($_POST[$item]) && $_POST[$item] > 0) {
            $hasBurger = true;
            break;
        }
    }

    // 3. DECISION
    if (!$hasBurger) {
        header("Location: result.php");
        exit();
    }
}

// Fetch availability
$food_data = [];
$result = $conn->query("SELECT food_code, is_available FROM food");
while($row = $result->fetch_assoc()) { $food_data[$row['food_code']] = $row['is_available']; }

function renderInput($id, $data) {
    if (isset($data[$id]) && $data[$id] == 0) return "<input type='text' value='Sold Out' disabled>";
    return "<input type='number' name='$id' value='0' min='0' max='10'>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Western Add On</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Western Food Add On</h2>
<img src="menuPics/Slide10.jpeg" alt="Western Food">

<div>
    <table class="bigTable" border="0">
        <form method="POST" action="result.php">
        <tr>
            <td>
                <h3>Add On (For Burger)</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Fries</td><td>RM 2.00</td><td><?php echo renderInput('wadd1', $food_data); ?></td></tr>
                </table>
            </td>
        </tr> 
    </table>
</div>

<div class="buttonContainer">
    <a href="westernFoodOrder.php" class="navButton">« Back</a>
	<input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>

</form>

</body>
</html>