<?php
include 'db_connect.php';
session_start();

// ==========================================
// MERGED ROUTER LOGIC START
// ==========================================
// Only run this if coming from Step 1 (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_step1'])) {
    
    // 1. Clear previous temp order and save new data
    $_SESSION['temp_order'] = [];
    foreach ($_POST as $key => $val) {
        if ($val > 0 && strpos($key, 'sqty') === 0) {
            $_SESSION['temp_order'][$key] = $val;
        }
    }

    // 2. Check if any "Sup ZZ" items were ordered
    $hasSup = false;
    $supItems = ['sqty1', 'sqty2', 'sqty3', 'sqty4'];
    
    foreach ($supItems as $item) {
        if (isset($_POST[$item]) && $_POST[$item] > 0) {
            $hasSup = true;
            break;
        }
    }

    // 3. DECISION:
    // If NO Sup items -> Skip this page, go straight to Result
    if (!$hasSup) {
        header("Location: result.php");
        exit();
    }
    // If HAS Sup -> Do nothing, script continues below to show Add-on form
}
// ==========================================
// MERGED ROUTER LOGIC END
// ==========================================

// Fetch availability for Add-ons
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
<title>Signature Dish Add On</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Signature Dish Add-ons</h2>
<img src="menuPics/Slide3.jpeg" alt="Signature dish">

<div>
    <table class="bigTable" border="0">

        <form method="POST" action="result.php">

        <tr>
            <td>
                <h3>Add On</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Mee</td><td>RM 2.00</td><td><?php echo renderInput('sadd1', $food_data); ?></td></tr>
                    <tr><td>Mee Hoon</td><td>RM 2.00</td><td><?php echo renderInput('sadd2', $food_data); ?></td></tr>
                    <tr><td>Kuey Teow</td><td>RM 2.00</td><td><?php echo renderInput('sadd3', $food_data); ?></td></tr>
                    <tr><td>Roti Francis</td><td>RM 2.50</td><td><?php echo renderInput('sadd4', $food_data); ?></td></tr>
                    <tr><td>Roti Gardenia</td><td>RM 2.50</td><td><?php echo renderInput('sadd5', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <h3>Add On Set</h3>
                <table class="menuTable">
                    <tr><th>Items</th><th>Price</th><th>Quantity</th></tr>
                    <tr><td>Nasi Putih + Telur + Sambal + Ulaman</td><td>RM 5.00</td><td><?php echo renderInput('saddSet1', $food_data); ?></td></tr>
                </table>
            </td>
        </tr>
        
    </table>
</div>

<div class="buttonContainer">
    <a href="signatureDishOrder.php" class="navButton">« Back</a>
	<input type="submit" name="submit_step2" value="Next »" class="navButton">
</div>

</form>

</body>
</html>